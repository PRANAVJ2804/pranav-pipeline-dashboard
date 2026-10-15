<?php
define('APP_OK', true);
require __DIR__ . '/lib.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function fail($code, $msg) {
    http_response_code($code);
    echo json_encode(array('error' => $msg));
    exit;
}

try {
    session_boot();
    if (empty($_SESSION['auth'])) {
        fail(401, 'Please log in again.');
    }
    if (!user_valid()) {
        fail(401, 'Please log in again.');
    }
    $scope = user_scope();
    $action = isset($_GET['action']) ? $_GET['action'] : 'data';

    if ($action === 'refresh') {
        $sent = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string)$_SERVER['HTTP_X_CSRF_TOKEN'] : '';
        $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $want === '' || !hash_equals($want, $sent)) {
            fail(403, 'Blocked. Reload the page and try again.');
        }
        session_write_close();
        if (isset($_GET['auto']) && is_file(cache_path()) && time() - filemtime(cache_path()) < 240) {
            echo json_encode(array('ok' => true, 'skipped' => true));
            exit;
        }
        $m = refresh_cache();
        echo json_encode($scope !== null ? array('ok' => true) : array('ok' => true, 'count' => $m['count'], 'fetched_at' => $m['fetched_at']));
        exit;
    }

    if ($action === 'progress') {
        session_write_close();
        $p = cfg()['cache_dir'] . '/progress.json';
        if (isset($_GET['reset']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            @file_put_contents($p, json_encode(array('pct' => 0, 'label' => 'Starting', 'at' => time())), LOCK_EX);
        }
        $o = is_file($p) ? json_decode((string)file_get_contents($p), true) : null;
        if (!is_array($o) || time() - (int)$o['at'] > 900) {
            $o = array('pct' => 0, 'label' => '', 'at' => 0);
        }
        echo json_encode($o);
        exit;
    }

    if ($action === 'activity') {
        session_write_close();
        $items = read_activity();
        if ($scope !== null) {
            // A team login only sees its own actions.
            $me = (string)$_SESSION['user'];
            $items = array_values(array_filter($items, function ($e) use ($me) {
                return isset($e['user']) && $e['user'] === $me;
            }));
        }
        if (isset($_GET['limit'])) {
            $items = array_slice($items, 0, max(1, min(100, (int)$_GET['limit'])));
        }
        echo json_encode(array('ok' => true, 'items' => $items), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'profile_save' || $action === 'password_change') {
        $sent = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string)$_SERVER['HTTP_X_CSRF_TOKEN'] : '';
        $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $want === '' || !hash_equals($want, $sent)) {
            fail(403, 'Blocked. Reload the page and try again.');
        }
        $in = json_decode((string)file_get_contents('php://input', false, null, 0, 4000), true);
        if (!is_array($in)) {
            fail(400, 'The request was not understood.');
        }
        $me = (string)$_SESSION['user'];
        session_write_close();
        if ($action === 'profile_save') {
            $name = trim(preg_replace('/[\x00-\x1F\x7F<>]/u', '', isset($in['name']) ? (string)$in['name'] : ''));
            $name = mb_substr($name, 0, 40, 'UTF-8');
            $phone = trim(isset($in['phone']) ? (string)$in['phone'] : '');
            if (!preg_match('/^[0-9+\-\s()]{0,20}$/', $phone)) {
                fail(400, 'Phone can only have digits, spaces, + - and brackets (max 20).');
            }
            $color = isset($in['color']) ? (string)$in['color'] : '';
            if ($color !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
                fail(400, 'That colour is not valid.');
            }
            if (!write_profile($me, array('name' => $name, 'phone' => $phone, 'color' => $color))) {
                fail(500, 'Could not save. Check that the cache folder is writable.');
            }
            echo json_encode(array('ok' => true, 'profile' => array('name' => $name, 'phone' => $phone, 'color' => $color)), JSON_UNESCAPED_UNICODE);
            exit;
        }
        $cur = isset($in['current']) ? (string)$in['current'] : '';
        $new = isset($in['next']) ? (string)$in['next'] : '';
        if (!password_ok($me, $cur)) {
            usleep(1500000);
            fail(403, 'Your current password is wrong.');
        }
        if (mb_strlen($new, 'UTF-8') < 8) {
            fail(400, 'The new password needs at least 8 characters.');
        }
        if ($new === $cur) {
            fail(400, 'The new password must be different from the current one.');
        }
        if (mb_strtolower($new, 'UTF-8') === mb_strtolower($me, 'UTF-8')) {
            fail(400, 'The password cannot be your username.');
        }
        if (!write_profile($me, array('pw' => password_hash($new, PASSWORD_DEFAULT), 'pw_at' => date('c')))) {
            fail(500, 'Could not save. Check that the cache folder is writable.');
        }
        try { $_SESSION['user'] = $me; log_event('password'); } catch (Throwable $e) {}
        echo json_encode(array('ok' => true));
        exit;
    }

    if ($action === 'update') {
        $sent = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string)$_SERVER['HTTP_X_CSRF_TOKEN'] : '';
        $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $want === '' || !hash_equals($want, $sent)) {
            fail(403, 'Blocked. Reload the page and try again.');
        }
        $raw = file_get_contents('php://input', false, null, 0, 200000);
        $in = json_decode((string)$raw, true);
        if (!is_array($in) || !isset($in['id']) || (isset($in['changes']) && !is_array($in['changes']))) {
            fail(400, 'The request was not understood.');
        }
        session_write_close();
        $upd = update_deal($in['id'], isset($in['changes']) ? $in['changes'] : array(), isset($in['original']) ? $in['original'] : array(), isset($in['comment']) ? (string)$in['comment'] : '', isset($in['by']) ? (string)$in['by'] : '');
        echo json_encode(array('ok' => true, 'updated' => (object)$upd), JSON_UNESCAPED_UNICODE);
        exit;
    }

    session_write_close();
    $lastError = 'null';
    if (is_file(error_path())) {
        $e = json_decode((string)file_get_contents(error_path()), true);
        if (is_array($e)) {
            $lastError = json_encode($e);
        }
    }
    if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
        ob_start('ob_gzhandler');
    }
    if ($scope !== null) {
        // Team login: build the data from scratch with only that team's deals.
        $cache = scope_cache(load_cache(), $scope);
        $ids = array();
        foreach ($cache['rows'] as $r) {
            $ids[(string)$r['ID']] = true;
        }
        $t = array_intersect_key(read_touched(), $ids);
        echo '{"last_error":' . $lastError . ',"data":' . json_encode($cache, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_HEX_TAG) . ',"touched":' . (count($t) ? json_encode($t) : '{}') . '}';
        exit;
    }
    echo '{"last_error":' . $lastError . ',"data":';
    $fh = is_file(cache_path()) ? fopen(cache_path(), 'rb') : false;
    if ($fh) {
        fgets($fh); // skip the guard line at the top of the file
        fpassthru($fh);
        fclose($fh);
    } else {
        echo 'null';
    }
    echo ',"touched":' . (count($t = read_touched()) ? json_encode($t) : '{}') . '}';
} catch (EditConflict $e) {
    http_response_code(409);
    echo json_encode(array('error' => $e->getMessage(), 'conflicts' => (object)$e->conflicts, 'fresh' => (object)$e->fresh), JSON_UNESCAPED_UNICODE);
} catch (RefreshBusy $e) {
    fail(409, $e->getMessage());
} catch (RuntimeException $e) {
    fail(500, $e->getMessage());
} catch (Throwable $e) {
    error_log('pranav-dashboard: ' . $e->getMessage());
    fail(500, 'Unexpected error. Check the server error log.');
}

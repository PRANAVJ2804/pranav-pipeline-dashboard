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


function av_dir() {
    return cfg()['cache_dir'] . '/avatars';
}
function av_file($u) {
    return av_dir() . '/' . md5(mb_strtolower((string)$u, 'UTF-8')) . '.jpg';
}
// username => version, for people who have a photo. A team login only gets its own.
function av_map($scope, $me) {
    $out = array();
    foreach (read_profiles() as $u => $pf) {
        if (is_array($pf) && !empty($pf['av']) && is_file(av_file($u)) && ($scope === null || $u === $me)) {
            $out[(string)$u] = (int)$pf['av'];
        }
    }
    return $out;
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
        echo json_encode(array('ok' => true, 'items' => $items, 'avs' => (object)av_map($scope, (string)$_SESSION['user'])), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'reconcile') {
        if ($scope !== null) {
            fail(403, 'Only the admin can run this check.');
        }
        session_write_close();
        try {
            echo json_encode(array('ok' => true, 'r' => reconcile_check()));
        } catch (Throwable $e) {
            fail(502, 'Could not reach Bitrix24: ' . $e->getMessage());
        }
        exit;
    }

    // New-deal notice: remembers, per login, the highest deal ID that person has already been told about.
    if ($action === 'nd') {
        $me = (string)$_SESSION['user'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sent = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string)$_SERVER['HTTP_X_CSRF_TOKEN'] : '';
            $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
            if ($want === '' || !hash_equals($want, $sent)) {
                fail(403, 'Blocked. Reload the page and try again.');
            }
            $in = json_decode((string)file_get_contents('php://input', false, null, 0, 2000), true);
            $max = (is_array($in) && isset($in['max'])) ? (int)$in['max'] : 0;
            session_write_close();
            $pr = user_profile($me);
            $cur = isset($pr['nd_seen']) ? (int)$pr['nd_seen'] : 0;
            if ($max > $cur) {
                write_profile($me, array('nd_seen' => $max, 'nd_at' => date('c')));
            }
            echo json_encode(array('ok' => true));
            exit;
        }
        session_write_close();
        $pr = user_profile($me);
        echo json_encode(array('ok' => true, 'seen' => isset($pr['nd_seen']) ? (int)$pr['nd_seen'] : null));
        exit;
    }

    if ($action === 'views_save') {
        $sent = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string)$_SERVER['HTTP_X_CSRF_TOKEN'] : '';
        $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $want === '' || !hash_equals($want, $sent)) {
            fail(403, 'Blocked. Reload the page and try again.');
        }
        $in = json_decode((string)file_get_contents('php://input', false, null, 0, 20000), true);
        $me = (string)$_SESSION['user'];
        session_write_close();
        $out = array();
        foreach ((is_array($in) && isset($in['views']) && is_array($in['views'])) ? $in['views'] : array() as $v) {
            $n = trim(preg_replace('/[\x00-\x1F\x7F<>]/u', '', isset($v['n']) ? (string)$v['n'] : ''));
            $h = isset($v['h']) ? (string)$v['h'] : '';
            if ($n === '' || strpos($h, '#/') !== 0 || strlen($h) > 600) { continue; }
            $out[] = array('n' => mb_substr($n, 0, 40, 'UTF-8'), 'h' => $h);
            if (count($out) >= 20) { break; }
        }
        if (!write_profile($me, array('views' => $out))) {
            fail(500, 'Could not save. Check that the cache folder is writable.');
        }
        echo json_encode(array('ok' => true, 'views' => $out), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'track') {
        $sent = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string)$_SERVER['HTTP_X_CSRF_TOKEN'] : '';
        $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $want === '' || !hash_equals($want, $sent)) {
            fail(403, 'Blocked.');
        }
        $in = json_decode((string)file_get_contents('php://input', false, null, 0, 2000), true);
        $t = is_array($in) && isset($in['t']) ? (string)$in['t'] : '';
        if (!in_array($t, array('view', 'bot', 'bot_miss', 'search', 'edit_open', 'export'), true)) {
            fail(400, 'Unknown event.');
        }
        $d = trim(preg_replace('/[\x00-\x1F\x7F<>]/u', ' ', isset($in['d']) ? (string)$in['d'] : ''));
        session_write_close();
        log_usage($t, mb_substr($d, 0, 120, 'UTF-8'));
        echo json_encode(array('ok' => true));
        exit;
    }

    if ($action === 'usage') {
        if ($scope !== null) {
            fail(403, 'Only the admin can see usage.');
        }
        session_write_close();
        $days = 30;
        $ev = read_usage($days);
        $now = time();
        $feat = array('view' => 'Page views', 'bot' => 'Bot questions', 'bot_miss' => 'Bot could not answer', 'search' => 'Searches', 'edit_open' => 'Edit forms opened');
        $users = array();
        $tot = array();
        $pages = array();
        $miss = array();
        $daily = array();
        $touch = function ($u, $at) use (&$users) {
            if (!isset($users[$u])) {
                $users[$u] = array('user' => $u, 'last' => '', 'logins' => 0, 'edits' => 0, 'view' => 0, 'bot' => 0, 'bot_miss' => 0, 'search' => 0, 'edit_open' => 0, 'd7' => 0);
            }
            if ($at > $users[$u]['last']) { $users[$u]['last'] = $at; }
        };
        foreach ($ev as $e) {
            $u = (string)$e['u'];
            $t = (string)$e['t'];
            $ts = strtotime($e['at']);
            $touch($u, $e['at']);
            if (isset($users[$u][$t])) { $users[$u][$t]++; }
            if (!isset($tot[$t])) { $tot[$t] = array('d7' => 0, 'd30' => 0); }
            $tot[$t]['d30']++;
            if ($ts >= $now - 7 * 86400) { $tot[$t]['d7']++; $users[$u]['d7']++; }
            $day = date('Y-m-d', $ts);
            if (!isset($daily[$day])) { $daily[$day] = array(); }
            $daily[$day][$u] = 1;
            if ($t === 'view') { $k = (string)$e['d']; $pages[$k] = (isset($pages[$k]) ? $pages[$k] : 0) + 1; }
            if ($t === 'bot_miss') { $k = mb_strtolower((string)$e['d'], 'UTF-8'); $miss[$k] = (isset($miss[$k]) ? $miss[$k] : 0) + 1; }
        }
        foreach (read_activity(20000) as $e) {
            $ts = strtotime(isset($e['at']) ? $e['at'] : '');
            if (!$ts || $ts < $now - $days * 86400) { continue; }
            $u = isset($e['user']) ? (string)$e['user'] : '';
            if ($u === '') { continue; }
            $touch($u, $e['at']);
            if (isset($e['type']) && $e['type'] === 'login') { $users[$u]['logins']++; $daily[date('Y-m-d', $ts)][$u] = 1; }
            elseif (!isset($e['type']) && !empty($e['changes'])) { $users[$u]['edits']++; }
        }
        foreach (array_keys(cfg()['users']) as $u) { $touch((string)$u, ''); }
        arsort($pages);
        arsort($miss);
        ksort($daily);
        $dau = array();
        foreach ($daily as $day => $us) { $dau[] = array('day' => $day, 'n' => count($us)); }
        $ul = array_values($users);
        usort($ul, function ($a, $b) { return strcmp($b['last'], $a['last']); });
        echo json_encode(array('ok' => true, 'avs' => (object)av_map(null, ''), 'days' => $days, 'features' => $feat, 'totals' => $tot, 'users' => $ul,
            'pages' => array_slice($pages, 0, 12, true), 'misses' => array_slice($miss, 0, 15, true), 'dau' => array_slice($dau, -14)), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($action === 'avatar') {
        $u = isset($_GET['u']) ? (string)$_GET['u'] : '';
        session_write_close();
        $f = av_file($u);
        if ($u === '' || !is_file($f)) {
            http_response_code(404);
            header('Content-Type: text/plain');
            echo 'No photo.';
            exit;
        }
        header_remove('Cache-Control');
        header('Content-Type: image/jpeg');
        header('Cache-Control: private, max-age=2592000');
        header('Content-Length: ' . filesize($f));
        readfile($f);
        exit;
    }

    if ($action === 'avatar_save' || $action === 'avatar_del') {
        $sent = isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string)$_SERVER['HTTP_X_CSRF_TOKEN'] : '';
        $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $want === '' || !hash_equals($want, $sent)) {
            fail(403, 'Blocked. Reload the page and try again.');
        }
        $me = (string)$_SESSION['user'];
        $raw = $action === 'avatar_save' ? (string)file_get_contents('php://input', false, null, 0, 600001) : '';
        session_write_close();
        $f = av_file($me);
        if ($action === 'avatar_del') {
            if (is_file($f)) {
                @unlink($f);
            }
            write_profile($me, array('av' => 0));
            echo json_encode(array('ok' => true, 'av' => 0));
            exit;
        }
        if ($raw === '' || strlen($raw) > 600000) {
            fail(400, 'That photo is too large. Please pick a smaller one.');
        }
        $info = @getimagesizefromstring($raw);
        if (!$info || $info[2] !== IMAGETYPE_JPEG || $info[0] > 2000 || $info[1] > 2000) {
            fail(400, 'Please upload a JPG or PNG photo.');
        }
        // Re-encode so only pixels are kept (drops anything hidden in the file).
        if (function_exists('imagecreatefromstring') && function_exists('imagejpeg')) {
            $im = @imagecreatefromstring($raw);
            if (!$im) {
                fail(400, 'That photo could not be read.');
            }
            $w = imagesx($im);
            $h = imagesy($im);
            $side = min($w, $h);
            $dst = imagecreatetruecolor(256, 256);
            imagecopyresampled($dst, $im, 0, 0, (int)(($w - $side) / 2), (int)(($h - $side) / 2), 256, 256, $side, $side);
            ob_start();
            imagejpeg($dst, null, 88);
            $raw = (string)ob_get_clean();
            imagedestroy($im);
            imagedestroy($dst);
        }
        $d = av_dir();
        if (!is_dir($d)) {
            @mkdir($d, 0755, true);
        }
        if (@file_put_contents($f, $raw, LOCK_EX) === false) {
            fail(500, 'Could not save the photo. Check that the cache folder is writable.');
        }
        $v = time();
        write_profile($me, array('av' => $v));
        echo json_encode(array('ok' => true, 'av' => $v));
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
    try { daily_backup(); } catch (Throwable $e) {}
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

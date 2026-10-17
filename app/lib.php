<?php
// Shared code. Not a page: it refuses to run unless another file loads it.
if (!defined('APP_OK')) {
    http_response_code(403);
    exit;
}

class RefreshBusy extends RuntimeException {}

// Thrown when someone changed a deal in Bitrix24 after this page loaded it.
class EditConflict extends RuntimeException {
    public $conflicts = array();
    public $fresh = array();
}

// Fields that can be edited from the dashboard (by Bitrix24 field ID).
// Semester status fields are also matched by their name ("Semester 5 Status").
// Add more IDs in config.php under 'editable_extra' => array('UF_CRM_...').
const EDITABLE_KEYS = array(
    'STAGE_ID',
    'UF_CRM_1784702837', // Program
    'UF_CRM_1784713245', // Batch
    'UF_CRM_1784697183', // Current semester
    'UF_CRM_1784712099', // Mode of payment
    'UF_CRM_1784188741648', 'UF_CRM_1784188780998', 'UF_CRM_1784188805532',
    'UF_CRM_1784188830072', 'UF_CRM_1784188849279', 'UF_CRM_1784188886587', // Semester 1-6 status
    'UF_CRM_1782985117', // Re-registration date
    'UF_CRM_1782985171'  // Last payment date
);
// These can never be edited from the dashboard.
const NEVER_EDIT = array(
    'ID', '_NAME', '_PHONE', '_EMAIL', 'CATEGORY_ID', 'CONTACT_ID', 'DATE_CREATE', 'DATE_MODIFY',
    'UF_CRM_1784701507' // Comment History(G)
);

// Columns shown by default (if they exist in the data). Everything else is
// available from the "Columns" button.
const DEFAULT_KEYS = array(
    'ID', '_NAME', '_PHONE', '_EMAIL', 'STAGE_ID',
    'UF_CRM_1784702446', // University
    'UF_CRM_1784702837', // Program / course
    'UF_CRM_1784713245', // Batch
    'UF_CRM_1784697183', // Current semester
    'UF_CRM_1784712099', // Mode of payment
    'UF_CRM_1784188741648', 'UF_CRM_1784188780998', 'UF_CRM_1784188805532', // Sem 1-3 status
    'UF_CRM_1784188830072', 'UF_CRM_1784188849279', 'UF_CRM_1784188886587', // Sem 4-6 status
    'UF_CRM_1782985117', // Re-registration date
    'UF_CRM_1782985171'  // Last payment date
);

function cfg() {
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }
    $file = __DIR__ . '/config.php';
    if (!is_file($file)) {
        throw new RuntimeException('config.php is missing. Upload it next to index.php.');
    }
    $c = require $file;
    if (!is_array($c)) {
        throw new RuntimeException('config.php is not valid.');
    }
    foreach (array('webhook_url', 'username', 'password') as $k) {
        $v = isset($c[$k]) ? (string)$c[$k] : '';
        if ($v === '' || strpos($v, 'PASTE_') === 0) {
            throw new RuntimeException("Setup needed: open config.php and fill in '$k'.");
        }
    }
    $c['users'] = array((string)$c['username'] => (string)$c['password']);
    if (isset($c['extra_users']) && is_array($c['extra_users'])) {
        foreach ($c['extra_users'] as $un => $pw) {
            if ((string)$un !== '' && (string)$pw !== '' && strpos((string)$pw, 'PASTE_') !== 0) {
                $c['users'][(string)$un] = (string)$pw;
            }
        }
    }
    // Team logins: they only ever see (and can edit) deals whose Team Name matches.
    $c['scopes'] = array();
    if (isset($c['team_users']) && is_array($c['team_users'])) {
        foreach ($c['team_users'] as $un => $spec) {
            $pw = is_array($spec) && isset($spec['password']) ? (string)$spec['password'] : '';
            $tm = is_array($spec) && isset($spec['team']) ? trim((string)$spec['team']) : '';
            if ((string)$un === '' || $pw === '' || $tm === '' || strpos($pw, 'PASTE_') === 0 || strpos($tm, 'PASTE_') === 0 || isset($c['users'][(string)$un])) {
                continue;
            }
            $c['users'][(string)$un] = $pw;
            $c['scopes'][(string)$un] = $tm;
        }
    }
    $c['webhook_url'] = rtrim((string)$c['webhook_url'], '/') . '/';
    $c['pipeline_id'] = isset($c['pipeline_id']) ? (int)$c['pipeline_id'] : 46;
    $c['allow_edit'] = !isset($c['allow_edit']) || $c['allow_edit'] !== false;
    $c['editable_extra'] = (isset($c['editable_extra']) && is_array($c['editable_extra'])) ? $c['editable_extra'] : array();
    $c['cache_dir'] = rtrim(isset($c['cache_dir']) ? (string)$c['cache_dir'] : (__DIR__ . '/cache'), '/\\');
    $cached = $c;
    return $cached;
}

function session_boot() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $c = cfg();
    $dir = $c['cache_dir'] . '/sessions';
    if (!is_dir($dir)) {
        @mkdir($dir, 0700, true);
    }
    if (is_dir($dir) && is_writable($dir)) {
        protect_dir($c['cache_dir']);
        protect_dir($dir);
        session_save_path($dir);
    }
    ini_set('session.gc_maxlifetime', '28800');
    ini_set('session.use_strict_mode', '1');
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($path === '' || $path === '.') {
        $path = '/';
    }
    session_name('pranavdash');
    session_set_cookie_params(array(
        'lifetime' => 0,
        'path' => $path,
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ));
    session_start();
}

// ---------------------------------------------------------------
// Bitrix24
// ---------------------------------------------------------------

function bx($method, $params = array()) {
    $url = cfg()['webhook_url'] . $method . '.json';
    $body = $params ? json_encode($params) : '{}';
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 15,
        ));
        $resp = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        $data = is_string($resp) ? json_decode($resp, true) : null;
        if (is_array($data) && !isset($data['error']) && $code < 400) {
            return $data;
        }
        $limited = is_array($data) && isset($data['error']) && $data['error'] === 'QUERY_LIMIT_EXCEEDED';
        if ($limited || $resp === false || $code === 429 || $code >= 500) {
            sleep(1 + $attempt);
            continue;
        }
        $msg = 'unknown error';
        if (is_array($data)) {
            $msg = isset($data['error_description']) ? $data['error_description']
                : (isset($data['error']) ? $data['error'] : $msg);
        } elseif ($err !== '') {
            $msg = $err;
        } else {
            $msg = "HTTP $code";
        }
        throw new RuntimeException("Bitrix24 refused '$method': $msg");
    }
    throw new RuntimeException("Bitrix24 did not answer '$method' after several tries.");
}

// Progress of a running refresh, read by the progress bar in the browser.
function rf_progress($pct, $label) {
    $c = cfg();
    $f = $c['cache_dir'] . '/progress.json';
    @file_put_contents($f, json_encode(array('pct' => max(0, min(100, (int)round($pct))), 'label' => $label, 'at' => time())), LOCK_EX);
}

// Reads every page of a list method. $total receives Bitrix24's own count.
function bx_list_all($method, $params, &$total, $onPage = null) {
    $rows = array();
    $start = 0;
    $total = null;
    while (true) {
        $params['start'] = $start;
        $r = bx($method, $params);
        if (!isset($r['result']) || !is_array($r['result'])) {
            throw new RuntimeException("Bitrix24 sent an unexpected answer for '$method'.");
        }
        if ($total === null) {
            $total = isset($r['total']) ? (int)$r['total'] : 0;
        }
        foreach ($r['result'] as $row) {
            $rows[] = $row;
        }
        if ($onPage) {
            call_user_func($onPage, count($rows), $total);
        }
        if (!isset($r['next'])) {
            break;
        }
        $start = (int)$r['next'];
        usleep(150000);
    }
    return $rows;
}

// ---------------------------------------------------------------
// Cache
// ---------------------------------------------------------------

// The saved data is a .php file that starts with a line which stops the script.
// If anyone opens its address in a browser they get a blank "forbidden" page,
// even on a server that ignores .htaccess files.
const CACHE_GUARD = "<?php http_response_code(403); exit; ?>\n";

// When a deal was last saved from this dashboard (ms). Used to push updated deals to the bottom of the tables.
function touched_path() {
    return cfg()['cache_dir'] . '/touched.json';
}
function read_touched() {
    $p = touched_path();
    $j = is_file($p) ? json_decode((string)file_get_contents($p), true) : null;
    return is_array($j) ? $j : array();
}
function touch_deal($id) {
    $p = touched_path();
    $fh = @fopen($p, 'c+');
    if (!$fh) {
        return;
    }
    if (flock($fh, LOCK_EX)) {
        $j = json_decode((string)stream_get_contents($fh), true);
        if (!is_array($j)) {
            $j = array();
        }
        $j[(string)(int)$id] = (int)round(microtime(true) * 1000);
        if (count($j) > 20000) {
            asort($j);
            $j = array_slice($j, -15000, null, true);
        }
        ftruncate($fh, 0);
        rewind($fh);
        fwrite($fh, json_encode($j));
        fflush($fh);
        flock($fh, LOCK_UN);
    }
    fclose($fh);
}

// ---- who is allowed to see what ----
function user_valid() {
    $u = isset($_SESSION['user']) ? (string)$_SESSION['user'] : '';
    $c = cfg();
    return $u !== '' && isset($c['users'][$u]);
}
// ---- profiles (display name, phone, avatar colour, own password) ----
function profiles_path() {
    return cfg()['cache_dir'] . '/profiles.php';
}
function profiles_parse($raw) {
    $nl = strpos((string)$raw, "\n");
    $j = $nl === false ? null : json_decode(substr((string)$raw, $nl + 1), true);
    return is_array($j) ? $j : array();
}
function read_profiles() {
    $p = profiles_path();
    return is_file($p) ? profiles_parse(file_get_contents($p)) : array();
}
function user_profile($u) {
    $all = read_profiles();
    return isset($all[$u]) && is_array($all[$u]) ? $all[$u] : array();
}
function write_profile($user, $fields) {
    $fh = @fopen(profiles_path(), 'c+');
    if (!$fh) {
        return false;
    }
    if (!flock($fh, LOCK_EX)) {
        fclose($fh);
        return false;
    }
    $all = profiles_parse(stream_get_contents($fh));
    $cur = isset($all[$user]) && is_array($all[$user]) ? $all[$user] : array();
    $all[$user] = array_merge($cur, $fields);
    $out = CACHE_GUARD . json_encode($all, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    ftruncate($fh, 0);
    rewind($fh);
    $ok = fwrite($fh, $out) !== false;
    fflush($fh);
    flock($fh, LOCK_UN);
    fclose($fh);
    return $ok;
}
// A password changed from the dashboard replaces the one in config.php for that user.
function password_ok($u, $p) {
    $c = cfg();
    if (!isset($c['users'][$u])) {
        return false;
    }
    $pr = user_profile($u);
    if (!empty($pr['pw'])) {
        return password_verify((string)$p, (string)$pr['pw']);
    }
    return hash_equals((string)$c['users'][$u], (string)$p);
}
// null = admin (sees everything); otherwise the Team Name this login is limited to.
function user_scope() {
    $u = isset($_SESSION['user']) ? (string)$_SESSION['user'] : '';
    $c = cfg();
    return isset($c['scopes'][$u]) ? $c['scopes'][$u] : null;
}
function team_col_key($columns) {
    foreach ($columns as $col) {
        if (isset($col['role']) && $col['role'] === 'team') {
            return $col['key'];
        }
    }
    return null;
}
function same_team($a, $b) {
    return mb_strtolower(trim((string)$a), 'UTF-8') === mb_strtolower(trim((string)$b), 'UTF-8');
}
// Cuts the cache down to what a team login may see.
function scope_cache($cache, $team) {
    $tk = team_col_key($cache['columns']);
    $rows = array();
    if ($tk !== null) {
        foreach ($cache['rows'] as $r) {
            if (isset($r[$tk]) && same_team($r[$tk], $team)) {
                $rows[] = $r;
            }
        }
    }
    $cache['rows'] = $rows;
    $cache['count'] = count($rows);
    foreach ($cache['columns'] as $i => $col) {
        if ($tk !== null && $col['key'] === $tk && isset($col['options'])) {
            $cache['columns'][$i]['options'] = array_values(array_filter($col['options'], function ($o) use ($team) {
                return same_team($o['name'], $team);
            }));
        }
    }
    return $cache;
}

function cache_path() {
    return cfg()['cache_dir'] . '/data.php';
}

// Extra layer: make sure the cache folder has its own lock files.
function protect_dir($dir) {
    if (!is_file($dir . '/.htaccess')) {
        @file_put_contents(
            $dir . '/.htaccess',
            "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n"
        );
    }
    if (!is_file($dir . '/index.html')) {
        @file_put_contents($dir . '/index.html', '');
    }
}

function error_path() {
    return cfg()['cache_dir'] . '/last_error.json';
}

function fmt_value($val, $type, $map) {
    if ($val === null || $val === '') {
        return '';
    }
    if (is_array($val)) {
        $parts = array();
        foreach ($val as $v) {
            $s = fmt_value($v, $type, $map);
            if ($s !== '') {
                $parts[] = $s;
            }
        }
        return implode(', ', $parts);
    }
    if (is_bool($val)) {
        return $val ? 'Yes' : 'No';
    }
    $s = trim((string)$val);
    if ($s === '') {
        return '';
    }
    if ($map !== null) {
        return isset($map[$s]) ? $map[$s] : $s;
    }
    if ($type === 'boolean') {
        if ($s === 'Y' || $s === '1') {
            return 'Yes';
        }
        if ($s === 'N' || $s === '0') {
            return 'No';
        }
    }
    if ($type === 'date') {
        return substr($s, 0, 10);
    }
    if ($type === 'datetime') {
        return str_replace('T', ' ', substr($s, 0, 16));
    }
    return $s;
}

// What a Bitrix24 field is used for in the edit form. Matched by field ID or by the field's name,
// so it keeps working if the names carry suffixes such as (G) or (P).
const RO_ROLES = array('nextpay', 'fuview', 'cmhist');
// Exact Bitrix24 field IDs (the portal has several fields with the same name, so names are not safe to match).
function field_role($k, $label = '') {
    static $ids = array(
        'STAGE_ID' => 'stage',
        'UF_CRM_1784702446' => 'uni',          // University Name(G)
        'UF_CRM_1784702837' => 'program',      // Program Name(G)
        'UF_CRM_1784713245' => 'batch',        // Batch(G)
        'UF_CRM_1780040071743' => 'roll',      // Roll Number
        'UF_CRM_1790246879' => 'team',         // Team Name
        'UF_CRM_1790840655148' => 'docstat',   // Documents Status
        'UF_CRM_1784697183' => 'sem',          // Current Semester (G)
        'UF_CRM_1773208334206' => 'adm',       // Admission Date
        'UF_CRM_1780039753' => 'reg',          // Registration Date
        'UF_CRM_1784712099' => 'mode',         // Mode of Payment(G)
        'UF_CRM_1784699787' => 'lead',         // Lead Status
        'UF_CRM_1782985171' => 'lastpay',      // Last Payment Date (P)
        'UF_CRM_1782985117' => 'nextpay',      // Next Payment Date (P) - read only
        'UF_CRM_1784722248' => 'fuupd',        // Deal Next Follow up [Update-G]
        'UF_CRM_1784722278' => 'fuview',       // Deal Next Follow up [View-G] - read only
        'UF_CRM_1784701507' => 'cmhist',       // Comment History(G) - read only
        'UF_CRM_1773218485649' => 'refhas',    // Has Reference
        'UF_CRM_1782968155' => 'refadm',       // Reference admission
        'UF_CRM_1784188741648' => 'semstat1', 'UF_CRM_1784188780998' => 'semstat2', 'UF_CRM_1784188805532' => 'semstat3',
        'UF_CRM_1784188830072' => 'semstat4', 'UF_CRM_1784188849279' => 'semstat5', 'UF_CRM_1784188886587' => 'semstat6',
    );
    return isset($ids[$k]) ? $ids[$k] : '';
}

function is_editable_field($k, $label, $def) {
    $c = cfg();
    $role = field_role($k, $label);
    if (!$c['allow_edit'] || in_array($k, array('ID', '_NAME', '_PHONE', '_EMAIL', 'CATEGORY_ID', 'CONTACT_ID', 'DATE_CREATE', 'DATE_MODIFY'), true) || in_array($role, RO_ROLES, true)) {
        return false;
    }
    if ($k === 'STAGE_ID') {
        return true;
    }
    if (!empty($def['isReadOnly']) || !empty($def['isMultiple'])) {
        return false;
    }
    $type = isset($def['type']) ? $def['type'] : '';
    if (!in_array($type, array('enumeration', 'string', 'date', 'datetime', 'boolean', 'integer', 'double'), true)) {
        return false;
    }
    return $role !== ''
        || in_array($k, EDITABLE_KEYS, true)
        || in_array($k, $c['editable_extra'], true)
        || (bool)preg_match('/^sem(ester)?\s*\d+\s*status$/i', $label);
}

function field_label($key, $def) {
    foreach (array('listLabel', 'formLabel', 'filterLabel', 'title') as $k) {
        if (!empty($def[$k]) && is_string($def[$k])) {
            return $def[$k];
        }
    }
    return $key;
}

function multi_values($list) {
    $out = array();
    if (is_array($list)) {
        foreach ($list as $item) {
            if (is_array($item) && isset($item['VALUE']) && trim((string)$item['VALUE']) !== '') {
                $out[] = trim((string)$item['VALUE']);
            }
        }
    }
    return implode(', ', $out);
}

// Pulls the whole pipeline from Bitrix24 and saves it to cache/data.json.
// If anything fails, the old file is left untouched.
function refresh_cache() {
    $c = cfg();
    $dir = $c['cache_dir'];
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        throw new RuntimeException('Cannot create the cache folder. Check folder permissions.');
    }
    if (!is_writable($dir)) {
        throw new RuntimeException('The cache folder is not writable. Set its permission to 755.');
    }
    protect_dir($dir);
    $lock = fopen($dir . '/refresh.lock', 'c');
    if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
        throw new RefreshBusy('A refresh is already running. Wait a minute and try again.');
    }

    try {
        @set_time_limit(600);
        $pid = $c['pipeline_id'];

        // Field names and dropdown labels
        rf_progress(2, 'Connecting to Bitrix24');
        $fr = bx('crm.deal.fields');
        $defs = (isset($fr['result']) && is_array($fr['result'])) ? $fr['result'] : array();
        $maps = array();
        $optLists = array();
        foreach ($defs as $k => $d) {
            if (isset($d['type']) && $d['type'] === 'enumeration' && !empty($d['items']) && is_array($d['items'])) {
                $m = array();
                $ol = array();
                foreach ($d['items'] as $it) {
                    if (isset($it['ID'])) {
                        $nm = isset($it['VALUE']) ? (string)$it['VALUE'] : (string)$it['ID'];
                        $m[(string)$it['ID']] = $nm;
                        $ol[] = array('id' => (string)$it['ID'], 'name' => $nm);
                    }
                }
                $maps[$k] = $m;
                $optLists[$k] = $ol;
            }
        }

        // Stage names for this pipeline
        $entity = $pid > 0 ? ('DEAL_STAGE_' . $pid) : 'DEAL_STAGE';
        $sr = bx('crm.status.list', array('filter' => array('ENTITY_ID' => $entity)));
        $stageRows = (isset($sr['result']) && is_array($sr['result'])) ? $sr['result'] : array();
        usort($stageRows, function ($a, $b) {
            return (int)(isset($a['SORT']) ? $a['SORT'] : 0) - (int)(isset($b['SORT']) ? $b['SORT'] : 0);
        });
        $stageNames = array();
        $stageList = array();
        $stageOptions = array();
        foreach ($stageRows as $s) {
            if (isset($s['STATUS_ID'], $s['NAME'])) {
                $stageNames[(string)$s['STATUS_ID']] = (string)$s['NAME'];
                $stageList[] = (string)$s['NAME'];
                $stageOptions[] = array('id' => (string)$s['STATUS_ID'], 'name' => (string)$s['NAME']);
            }
        }

        rf_progress(6, 'Reading deals');
        // Deals
        $total = 0;
        $raw = bx_list_all('crm.deal.list', array(
            'filter' => array('CATEGORY_ID' => $pid),
            'select' => array('*', 'UF_*'),
            'order' => array('ID' => 'ASC'),
        ), $total, function ($n, $t) {
            rf_progress(6 + ($t > 0 ? min(1, $n / $t) : 0) * 64, 'Reading deals: ' . $n . ($t > 0 ? ' of ' . $t : ''));
        });
        $byId = array();
        foreach ($raw as $d) {
            if (isset($d['ID'])) {
                $byId[(string)$d['ID']] = $d;
            }
        }
        $deals = array_values($byId);
        if (count($deals) === 0) {
            throw new RuntimeException("Bitrix24 returned no deals for pipeline $pid. Check the pipeline number and the webhook's CRM permission. The previous data was kept.");
        }
        if ($total > 0 && count($deals) < $total - 5) {
            throw new RuntimeException('Bitrix24 returned only ' . count($deals) . " of $total deals. The previous data was kept.");
        }

        // Contacts (name, phone, email) for these deals only
        $idSet = array();
        foreach ($deals as $d) {
            $cid = isset($d['CONTACT_ID']) ? (string)$d['CONTACT_ID'] : '';
            if ($cid !== '' && $cid !== '0') {
                $idSet[$cid] = true;
            }
        }
        $contacts = array();
        $chunks = array_chunk(array_keys($idSet), 50);
        $ci = 0;
        foreach ($chunks as $chunk) {
            rf_progress(70 + ($ci / max(1, count($chunks))) * 22, 'Reading student contacts');
            $ci++;
            $t = 0;
            $list = bx_list_all('crm.contact.list', array(
                'filter' => array('ID' => $chunk),
                'select' => array('ID', 'NAME', 'LAST_NAME', 'PHONE', 'EMAIL'),
            ), $t);
            foreach ($list as $ct) {
                if (!isset($ct['ID'])) {
                    continue;
                }
                $contacts[(string)$ct['ID']] = array(
                    'name' => trim((isset($ct['NAME']) ? $ct['NAME'] : '') . ' ' . (isset($ct['LAST_NAME']) ? $ct['LAST_NAME'] : '')),
                    'phone' => multi_values(isset($ct['PHONE']) ? $ct['PHONE'] : null),
                    'email' => multi_values(isset($ct['EMAIL']) ? $ct['EMAIL'] : null),
                );
            }
        }

        rf_progress(93, 'Preparing the data');
        // Build rows (only non-empty values are stored)
        $rows = array();
        $present = array();
        foreach ($deals as $d) {
            $row = array();
            foreach ($d as $k => $v) {
                if ($k === 'STAGE_ID') {
                    $sid = (string)$v;
                    $s = isset($stageNames[$sid]) ? $stageNames[$sid] : $sid;
                } else {
                    $type = isset($defs[$k]['type']) ? $defs[$k]['type'] : '';
                    $map = isset($maps[$k]) ? $maps[$k] : null;
                    $s = fmt_value($v, $type, $map);
                }
                if ($s !== '') {
                    $row[$k] = $s;
                }
            }
            $cid = isset($d['CONTACT_ID']) ? (string)$d['CONTACT_ID'] : '';
            $ct = isset($contacts[$cid]) ? $contacts[$cid] : null;
            $name = ($ct && $ct['name'] !== '') ? $ct['name'] : (isset($d['TITLE']) ? trim((string)$d['TITLE']) : '');
            if ($name !== '') {
                $row['_NAME'] = $name;
            }
            if ($ct && $ct['phone'] !== '') {
                $row['_PHONE'] = $ct['phone'];
            }
            if ($ct && $ct['email'] !== '') {
                $row['_EMAIL'] = $ct['email'];
            }
            foreach ($row as $k => $_) {
                $present[$k] = true;
            }
            $rows[] = $row;
        }

        // Fields the edit form needs must exist as columns even if no deal has a value yet
        foreach ($defs as $dk => $dd) {
            if (!isset($present[$dk]) && strpos($dk, 'UF_CRM') === 0 && field_role($dk, field_label($dk, $dd)) !== '') {
                $present[$dk] = true;
            }
        }
        // Column list: useful ones first, then everything else that has data
        $keys = array();
        foreach (array('ID', '_NAME', '_PHONE', '_EMAIL', 'STAGE_ID') as $k) {
            if (isset($present[$k])) {
                $keys[] = $k;
            }
        }
        foreach (array_keys($present) as $k) {
            if (!in_array($k, $keys, true)) {
                $keys[] = $k;
            }
        }
        $fixedLabels = array('_NAME' => 'Student name', '_PHONE' => 'Phone', '_EMAIL' => 'Email', 'STAGE_ID' => 'Stage');
        $columns = array();
        $defaultCount = 0;
        foreach ($keys as $k) {
            $isDefault = in_array($k, DEFAULT_KEYS, true);
            if ($isDefault) {
                $defaultCount++;
            }
            $label = isset($fixedLabels[$k]) ? $fixedLabels[$k] : field_label($k, isset($defs[$k]) ? $defs[$k] : array());
            $col = array(
                'key' => $k,
                'label' => $label,
                'type' => isset($defs[$k]['type']) ? (string)$defs[$k]['type'] : '',
                'default' => $isDefault,
            );
            if ($k === 'STAGE_ID') {
                $col['options'] = $stageOptions;
            } elseif (!empty($optLists[$k])) {
                $col['options'] = $optLists[$k];
            }
            $col['editable'] = is_editable_field($k, $label, isset($defs[$k]) ? $defs[$k] : array());
            $role = field_role($k, $label);
            if ($role !== '') {
                $col['role'] = $role;
            }
            if ($k === '_NAME' || $k === '_PHONE' || $k === '_EMAIL') {
                $col['editable'] = (bool)$c['allow_edit'];
                $col['contact'] = true;
            }
            $columns[] = $col;
        }
        if ($defaultCount < 6) {
            foreach ($columns as $i => $col) {
                $columns[$i]['default'] = $i < 12;
            }
        }
        foreach ($rows as $r) {
            if (isset($r['STAGE_ID']) && !in_array($r['STAGE_ID'], $stageList, true)) {
                $stageList[] = $r['STAGE_ID'];
            }
        }

        $fetchedAt = date('c');
        // JSON_HEX_TAG turns < and > into < / > so the data can never contain "<?php"
        $json = json_encode(array(
            'fetched_at' => $fetchedAt,
            'pipeline_id' => $pid,
            'count' => count($rows),
            'stages' => $stageList,
            'columns' => $columns,
            'rows' => $rows,
        ), JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_HEX_TAG);
        if ($json === false) {
            throw new RuntimeException('Could not prepare the data for saving.');
        }
        $path = cache_path();
        $tmp = $dir . '/data.tmp.php';
        if (file_put_contents($tmp, CACHE_GUARD . $json, LOCK_EX) === false || !rename($tmp, $path)) {
            throw new RuntimeException('Could not save the data. Check that the cache folder is writable.');
        }
        @unlink(error_path());
        rf_progress(100, 'Done');
        return array('count' => count($rows), 'fetched_at' => $fetchedAt);
    } catch (RefreshBusy $e) {
        throw $e;
    } catch (Exception $e) {
        @file_put_contents(error_path(), json_encode(array('message' => $e->getMessage(), 'at' => date('c'))));
        throw $e;
    } finally {
        flock($lock, LOCK_UN);
        fclose($lock);
    }
}


// ---------------------------------------------------------------
// Editing a deal from the dashboard
// ---------------------------------------------------------------

function load_cache() {
    $p = cache_path();
    if (!is_file($p)) {
        throw new RuntimeException('No data yet. Press "Refresh data" first.');
    }
    $raw = (string)file_get_contents($p);
    $nl = strpos($raw, "\n");
    $j = json_decode($nl === false ? '' : substr($raw, $nl + 1), true);
    if (!is_array($j) || empty($j['columns'])) {
        throw new RuntimeException('The saved data is not readable. Press "Refresh data".');
    }
    return $j;
}

function col_meta($cache) {
    $m = array();
    foreach ($cache['columns'] as $col) {
        $m[$col['key']] = $col;
    }
    return $m;
}

// Same wording the refresh uses, so a live value can be compared with what the page shows.
function display_of($key, $val, $meta) {
    $col = isset($meta[$key]) ? $meta[$key] : array();
    $type = isset($col['type']) ? $col['type'] : '';
    if ($key === 'STAGE_ID') {
        foreach (isset($col['options']) ? $col['options'] : array() as $o) {
            if ((string)$o['id'] === (string)$val) {
                return (string)$o['name'];
            }
        }
        return (string)$val;
    }
    $map = null;
    if ($type === 'enumeration' && !empty($col['options'])) {
        $map = array();
        foreach ($col['options'] as $o) {
            $map[(string)$o['id']] = (string)$o['name'];
        }
    }
    return fmt_value($val, $type, $map);
}

function to_bitrix_value($k, $v, $col) {
    if (!is_scalar($v) && $v !== null) {
        throw new RuntimeException("Bad value for '$k'.");
    }
    $v = trim((string)$v);
    if (strlen($v) > 2000) {
        throw new RuntimeException("The value for '$k' is too long.");
    }
    $ids = array();
    foreach (isset($col['options']) ? $col['options'] : array() as $o) {
        $ids[] = (string)$o['id'];
    }
    $type = isset($col['type']) ? $col['type'] : '';
    if ($k === 'STAGE_ID') {
        if (!in_array($v, $ids, true)) {
            throw new RuntimeException('That stage does not exist in this pipeline.');
        }
        return $v;
    }
    switch ($type) {
        case 'enumeration':
            if ($v !== '' && !in_array($v, $ids, true)) {
                throw new RuntimeException("'$v' is not a valid choice for this field.");
            }
            return $v;
        case 'boolean':
            if ($v === '') { return ''; }
            if ($v === 'Yes') { return '1'; }
            if ($v === 'No') { return '0'; }
            throw new RuntimeException("Choose Yes or No for '$k'.");
        case 'date':
            if ($v === '') { return ''; }
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $v, $m) || !checkdate((int)$m[2], (int)$m[3], (int)$m[1])) {
                throw new RuntimeException("'$v' is not a valid date.");
            }
            return $v;
        case 'datetime':
            if ($v === '') { return ''; }
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})[T ](\d{2}):(\d{2})/', $v, $m) || !checkdate((int)$m[2], (int)$m[3], (int)$m[1]) || (int)$m[4] > 23 || (int)$m[5] > 59) {
                throw new RuntimeException("'$v' is not a valid date and time.");
            }
            return $m[1] . '-' . $m[2] . '-' . $m[3] . ' ' . $m[4] . ':' . $m[5] . ':00';
        case 'integer':
            if ($v !== '' && !preg_match('/^-?\d+$/', $v)) {
                throw new RuntimeException("'$v' is not a whole number.");
            }
            return $v;
        case 'double':
            if ($v !== '' && !preg_match('/^-?\d+(\.\d+)?$/', $v)) {
                throw new RuntimeException("'$v' is not a number.");
            }
            return $v;
        case 'string':
            return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $v);
    }
    throw new RuntimeException("The field '$k' cannot be edited.");
}

function fresh_values($deal, $meta) {
    $out = array();
    foreach ($meta as $k => $col) {
        if (array_key_exists($k, $deal) && substr($k, 0, 1) !== '_') {
            $out[$k] = display_of($k, $deal[$k], $meta);
        }
    }
    return $out;
}

// Keep the saved copy in step, so the change also shows after a page reload.
function patch_cache($id, $values) {
    $dir = cfg()['cache_dir'];
    $lock = fopen($dir . '/refresh.lock', 'c');
    if (!$lock) {
        return false;
    }
    $got = false;
    for ($i = 0; $i < 8; $i++) {
        if (flock($lock, LOCK_EX | LOCK_NB)) { $got = true; break; }
        usleep(500000);
    }
    if (!$got) {
        fclose($lock);
        return false;
    }
    try {
        $cache = load_cache();
        $done = false;
        foreach ($cache['rows'] as $i => $row) {
            if (isset($row['ID']) && (string)$row['ID'] === (string)$id) {
                foreach ($values as $k => $v) {
                    if ($v === '') { unset($row[$k]); } else { $row[$k] = $v; }
                }
                $cache['rows'][$i] = $row;
                $done = true;
                break;
            }
        }
        if (!$done) {
            return false;
        }
        $json = json_encode($cache, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_HEX_TAG);
        $tmp = $dir . '/data.tmp.php';
        if ($json === false || file_put_contents($tmp, CACHE_GUARD . $json, LOCK_EX) === false || !rename($tmp, cache_path())) {
            return false;
        }
        return true;
    } catch (Exception $e) {
        return false;
    } finally {
        flock($lock, LOCK_UN);
        fclose($lock);
    }
}

function log_edit($id, $changes, $original, $meta, $comment = '', $by = '') {
    $file = cfg()['cache_dir'] . '/edit_log.php';
    $line = '';
    if (!is_file($file)) {
        $line = CACHE_GUARD;
    }
    $entry = array('at' => date('c'), 'user' => (isset($_SESSION['user']) && $_SESSION['user'] !== '') ? $_SESSION['user'] : cfg()['username'], 'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '', 'deal' => (string)$id, 'changes' => array());
    if ($comment !== '') {
        $entry['comment'] = $comment;
        $entry['by'] = $by;
    }
    foreach ($changes as $k => $v) {
        $entry['changes'][$k] = array(
            'field' => isset($meta[$k]['label']) ? $meta[$k]['label'] : $k,
            'old' => isset($original[$k]) ? (string)$original[$k] : '',
            'new' => display_of($k, $v, $meta),
        );
    }
    $line .= json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . "\n";
    @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
}

// Sign-in / sign-out events go in the same log.
function log_event($type) {
    $file = cfg()['cache_dir'] . '/edit_log.php';
    $line = is_file($file) ? '' : CACHE_GUARD;
    $entry = array('at' => date('c'), 'type' => $type, 'user' => (isset($_SESSION['user']) && $_SESSION['user'] !== '') ? $_SESSION['user'] : '', 'ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
    @file_put_contents($file, $line . json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . "\n", FILE_APPEND | LOCK_EX);
}

// Once a day (first visit), copy the small data files that cannot be rebuilt from Bitrix24: profiles/passwords, activity log, usage log.
// Kept for 14 days inside the protected cache folder.
function daily_backup() {
    $dir = cfg()['cache_dir'] . '/backups';
    $today = date('Y-m-d');
    $mark = $dir . '/.last';
    if (is_file($mark) && trim((string)@file_get_contents($mark)) === $today) {
        return;
    }
    if (!is_dir($dir) && !@mkdir($dir, 0700, true)) {
        return;
    }
    $to = $dir . '/' . $today;
    if (!is_dir($to) && !@mkdir($to, 0700, true)) {
        return;
    }
    foreach (array('profiles.php', 'edit_log.php', 'usage.php', 'touched.php', 'touched.json') as $f) {
        $src = cfg()['cache_dir'] . '/' . $f;
        if (is_file($src)) {
            @copy($src, $to . '/' . $f);
        }
    }
    @file_put_contents($mark, $today);
    foreach ((array)glob($dir . '/20??-??-??', GLOB_ONLYDIR) as $d) {
        if (basename($d) < date('Y-m-d', time() - 14 * 86400)) {
            foreach ((array)glob($d . '/*') as $f) { @unlink($f); }
            @rmdir($d);
        }
    }
}

// Compare the saved data with Bitrix24 right now (3 light calls).
function reconcile_check() {
    $c = cfg();
    $cache = load_cache();
    $maxId = 0;
    foreach ($cache['rows'] as $r) {
        if ((int)$r['ID'] > $maxId) { $maxId = (int)$r['ID']; }
    }
    $f = array('CATEGORY_ID' => $c['pipeline_id']);
    $t = bx('crm.deal.list', array('filter' => $f, 'select' => array('ID'), 'order' => array('ID' => 'DESC')));
    $f['>DATE_MODIFY'] = $cache['fetched_at'];
    $m = bx('crm.deal.list', array('filter' => $f, 'select' => array('ID')));
    $live = isset($t['total']) ? (int)$t['total'] : 0;
    $liveMax = (isset($t['result'][0]['ID'])) ? (int)$t['result'][0]['ID'] : 0;
    return array('cached' => count($cache['rows']), 'live' => $live, 'cached_max' => $maxId, 'live_max' => $liveMax,
        'changed_since' => isset($m['total']) ? (int)$m['total'] : 0, 'fetched_at' => $cache['fetched_at'],
        'ok' => count($cache['rows']) === $live && $maxId === $liveMax);
}

// Usage tracking: which features people actually use (page views, bot, search, edit forms).
function log_usage($type, $detail) {
    $file = cfg()['cache_dir'] . '/usage.php';
    if (is_file($file) && @filesize($file) > 3000000) {
        @rename($file, cfg()['cache_dir'] . '/usage_old.php');
    }
    $line = is_file($file) ? '' : CACHE_GUARD;
    $entry = array('at' => date('c'), 't' => $type, 'u' => (isset($_SESSION['user']) ? (string)$_SESSION['user'] : ''), 'd' => $detail);
    @file_put_contents($file, $line . json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . "\n", FILE_APPEND | LOCK_EX);
}
function read_usage($days) {
    $out = array();
    $cut = time() - $days * 86400;
    foreach (array('usage_old.php', 'usage.php') as $f) {
        $file = cfg()['cache_dir'] . '/' . $f;
        if (!is_file($file)) { continue; }
        $fh = @fopen($file, 'r');
        if (!$fh) { continue; }
        while (($ln = fgets($fh)) !== false) {
            if ($ln === '' || $ln[0] !== '{') { continue; }
            $j = json_decode($ln, true);
            if (is_array($j) && isset($j['at']) && strtotime($j['at']) >= $cut) { $out[] = $j; }
        }
        fclose($fh);
    }
    return $out;
}

// Latest entries of the activity log, newest first.
function read_activity($limit = 5000) {
    $file = cfg()['cache_dir'] . '/edit_log.php';
    if (!is_file($file)) {
        return array();
    }
    $out = array();
    foreach (array_reverse((array)file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) as $ln) {
        if ($ln === '' || $ln[0] !== '{') {
            continue;
        }
        $j = json_decode($ln, true);
        if (is_array($j)) {
            $out[] = $j;
            if (count($out) >= $limit) {
                break;
            }
        }
    }
    return $out;
}

// ---- student contact (name, phone, email) ----
function contact_snapshot($cid) {
    $out = array('name' => '', 'phone' => '', 'email' => '', 'phones' => array(), 'emails' => array(), 'first' => '', 'last' => '');
    if ($cid <= 0) {
        return $out;
    }
    $r = bx('crm.contact.get', array('id' => $cid));
    $ct = (isset($r['result']) && is_array($r['result'])) ? $r['result'] : array();
    $out['first'] = isset($ct['NAME']) ? (string)$ct['NAME'] : '';
    $out['last'] = isset($ct['LAST_NAME']) ? (string)$ct['LAST_NAME'] : '';
    $out['name'] = trim($out['first'] . ' ' . $out['last']);
    foreach (array('PHONE' => 'phones', 'EMAIL' => 'emails') as $f => $key) {
        if (isset($ct[$f]) && is_array($ct[$f])) {
            foreach ($ct[$f] as $it) {
                if (is_array($it) && isset($it['VALUE']) && trim((string)$it['VALUE']) !== '') {
                    $out[$key][] = array('ID' => isset($it['ID']) ? $it['ID'] : null, 'VALUE' => trim((string)$it['VALUE']));
                }
            }
        }
    }
    $out['phone'] = implode(', ', array_map(function ($x) { return $x['VALUE']; }, $out['phones']));
    $out['email'] = implode(', ', array_map(function ($x) { return $x['VALUE']; }, $out['emails']));
    return $out;
}

// Turns "a, b" into Bitrix24's list. Existing entries keep their place (and ID); extra ones are added; missing ones are removed.
function contact_multi($existing, $csv, $type) {
    $vals = array();
    foreach (explode(',', (string)$csv) as $p) {
        $p = trim($p);
        if ($p !== '') {
            $vals[] = $p;
        }
    }
    $out = array();
    $i = 0;
    foreach ($existing as $e) {
        $out[] = array('ID' => $e['ID'], 'VALUE' => isset($vals[$i]) ? $vals[$i] : '');
        $i++;
    }
    for (; $i < count($vals); $i++) {
        $out[] = array('VALUE' => $vals[$i], 'VALUE_TYPE' => 'WORK');
    }
    return $out;
}

function check_contact_value($k, $v) {
    $v = trim((string)$v);
    if ($k === '_NAME') {
        if ($v === '' || strlen($v) > 200) {
            throw new RuntimeException('The name cannot be empty or longer than 200 letters.');
        }
        return preg_replace('/[\x00-\x1F\x7F]/', ' ', $v);
    }
    foreach (explode(',', $v) as $p) {
        $p = trim($p);
        if ($p === '') {
            continue;
        }
        if ($k === '_PHONE' && !preg_match('/^[0-9+\-() .]{5,25}$/', $p)) {
            throw new RuntimeException("'$p' is not a valid phone number. Use digits, +, spaces or -. Separate several numbers with a comma.");
        }
        if ($k === '_EMAIL' && !filter_var($p, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("'$p' is not a valid email address. Separate several emails with a comma.");
        }
    }
    return $v;
}

// $changes: field => new value (option ID for dropdowns and stages).
// $original: field => the value the page was showing when the person opened the form.
function update_deal($id, $changes, $original, $newComment = '', $commentBy = '') {
    $c = cfg();
    if (!$c['allow_edit']) {
        throw new RuntimeException('Editing is turned off in config.php.');
    }
    if (!preg_match('/^\d+$/', (string)$id)) {
        throw new RuntimeException('Bad deal number.');
    }
    if (!is_array($changes)) {
        $changes = array();
    }
    // New comment: cleaned here, then added on top of the live history further down.
    $newComment = trim(preg_replace('/\s+/u', ' ', preg_replace('/[\x00-\x1F\x7F]/', ' ', (string)$newComment)));
    $newComment = trim(str_replace('|', '/', $newComment));
    if (function_exists('mb_substr')) {
        $newComment = mb_substr($newComment, 0, 1000, 'UTF-8');
    }
    $commentBy = trim(preg_replace('/[\x00-\x1F\x7F|]+/', ' ', (string)$commentBy));
    if (function_exists('mb_substr')) {
        $commentBy = mb_substr($commentBy, 0, 60, 'UTF-8');
    }
    $byGiven = $commentBy !== '';
    if ($commentBy === '') {
        $commentBy = (isset($_SESSION['user']) && $_SESSION['user'] !== '') ? (string)$_SESSION['user'] : (string)$c['username'];
    }
    // Name, phone and email belong to the student's contact, not to the deal.
    $contactCh = array();
    foreach (array('_NAME', '_PHONE', '_EMAIL') as $ck) {
        if (array_key_exists($ck, $changes)) {
            $contactCh[$ck] = check_contact_value($ck, $changes[$ck]);
            unset($changes[$ck]);
        }
    }
    if (!count($changes) && !count($contactCh) && $newComment === '') {
        throw new RuntimeException('Nothing to save.');
    }
    if (!is_array($original)) {
        $original = array();
    }
    $meta = col_meta(load_cache());
    // Every save must carry a comment, the person's name and a new next follow-up date.
    $fuKey = null;
    $hasCm = false;
    foreach ($meta as $mk => $mc) {
        if (isset($mc['role']) && $mc['role'] === 'fuupd') {
            $fuKey = $mk;
        }
        if ($mk === 'UF_CRM_1784701507' || (isset($mc['label']) && preg_match('/^comment history\s*\(g\)/i', $mc['label']))) {
            $hasCm = true;
        }
    }
    // Fixing only the student's name, phone or email is not a follow-up, so it needs no comment or date.
    $contactOnly = !count($changes) && count($contactCh) && $newComment === '';
    $missing = array();
    if ($contactOnly) {
        $hasCm = false;
        $fuKey = null;
    }
    if ($hasCm && $newComment === '') {
        $missing[] = 'a comment';
    }
    if ($hasCm && !$byGiven) {
        $missing[] = 'your name';
    }
    if ($fuKey !== null && (!array_key_exists($fuKey, $changes) || trim((string)$changes[$fuKey]) === '')) {
        $missing[] = 'a new Next Follow-up date';
    }
    if ($missing) {
        throw new RuntimeException('Nothing was saved. Please add ' . implode(', ', $missing) . '.');
    }
    $fields = array();
    $cmKey = null;
    if ($newComment !== '') {
        foreach ($meta as $mk => $mc) {
            if ($mk === 'UF_CRM_1784701507' || (isset($mc['label']) && preg_match('/^comment history\s*\(g\)/i', $mc['label']))) {
                $cmKey = $mk;
                break;
            }
        }
        if ($cmKey === null) {
            throw new RuntimeException('The comment history field was not found in your data. Press Refresh data once and try again.');
        }
    }
    foreach ($changes as $k => $v) {
        if (!isset($meta[$k]) || empty($meta[$k]['editable'])) {
            throw new RuntimeException("The field '$k' cannot be edited. If this is new, press Refresh data once.");
        }
        $fields[$k] = to_bitrix_value($k, $v, $meta[$k]);
    }

    // Read the live deal first: refuse if someone changed these fields in the meantime.
    $r = bx('crm.deal.get', array('id' => (int)$id));
    $deal = (isset($r['result']) && is_array($r['result'])) ? $r['result'] : null;
    if (!$deal) {
        throw new RuntimeException('That deal was not found in Bitrix24.');
    }
    if ((int)(isset($deal['CATEGORY_ID']) ? $deal['CATEGORY_ID'] : -1) !== (int)$c['pipeline_id']) {
        throw new RuntimeException('That deal is not in this pipeline, so it was not changed.');
    }
    $scope = user_scope();
    if ($scope !== null) {
        $tk = team_col_key(array_values($meta));
        $liveTeam = $tk !== null && isset($deal[$tk]) ? display_of($tk, $deal[$tk], $meta) : '';
        if ($tk === null || !same_team($liveTeam, $scope)) {
            throw new RuntimeException('That deal does not belong to your team, so it was not changed.');
        }
        if ($tk !== null && array_key_exists($tk, $changes)) {
            throw new RuntimeException('Your login cannot change the Team Name.');
        }
    }
    $conf = array();
    foreach ($fields as $k => $_) {
        $live = display_of($k, isset($deal[$k]) ? $deal[$k] : '', $meta);
        $seen = isset($original[$k]) ? (string)$original[$k] : '';
        if ($live !== $seen) {
            $conf[$k] = array('label' => $meta[$k]['label'], 'live' => $live);
        }
    }
    $cid = (int)(isset($deal['CONTACT_ID']) ? $deal['CONTACT_ID'] : 0);
    $ctLive = null;
    $createContact = false;
    if ($contactCh) {
        if ($cid <= 0 && (isset($contactCh['_PHONE']) || isset($contactCh['_EMAIL']))) {
            // No student contact on this deal yet: one is created and linked when saving.
            // Refuse if the same phone/email already belongs to a contact, so nobody is linked by mistake.
            foreach (array('PHONE' => '_PHONE', 'EMAIL' => '_EMAIL') as $ft => $ck) {
                if (!isset($contactCh[$ck])) {
                    continue;
                }
                foreach (explode(',', $contactCh[$ck]) as $one) {
                    $one = trim($one);
                    if ($one === '') {
                        continue;
                    }
                    $dup = bx('crm.contact.list', array('filter' => array($ft => $one), 'select' => array('ID', 'NAME', 'LAST_NAME')));
                    if (isset($dup['result'][0]['ID'])) {
                        $d0 = $dup['result'][0];
                        throw new RuntimeException("This deal has no student contact, and '$one' already belongs to contact #" . $d0['ID'] . ' (' . trim((isset($d0['NAME']) ? $d0['NAME'] : '') . ' ' . (isset($d0['LAST_NAME']) ? $d0['LAST_NAME'] : '')) . '). Open the deal in Bitrix24 and link that contact, then edit here. Nothing was saved.');
                    }
                }
            }
            $createContact = true;
        }
        $ctLive = $cid > 0 ? contact_snapshot($cid) : contact_snapshot(0);
        $liveName = $cid > 0 && $ctLive['name'] !== '' ? $ctLive['name'] : trim(isset($deal['TITLE']) ? (string)$deal['TITLE'] : '');
        $liveVals = array('_NAME' => $liveName, '_PHONE' => $ctLive['phone'], '_EMAIL' => $ctLive['email']);
        foreach ($contactCh as $ck => $_) {
            $seen = isset($original[$ck]) ? (string)$original[$ck] : '';
            if ($liveVals[$ck] !== $seen) {
                $conf[$ck] = array('label' => isset($meta[$ck]['label']) ? $meta[$ck]['label'] : $ck, 'live' => $liveVals[$ck]);
            }
        }
    }
    if ($conf) {
        $e = new EditConflict('Someone changed this deal in Bitrix24 after you opened it. Nothing was saved.');
        $e->conflicts = $conf;
        $e->fresh = fresh_values($deal, $meta);
        if ($ctLive !== null) {
            $e->fresh['_NAME'] = $liveName;
            $e->fresh['_PHONE'] = $ctLive['phone'];
            $e->fresh['_EMAIL'] = $ctLive['email'];
        }
        throw $e;
    }

    if ($cmKey !== null) {
        // Newest first, same layout as the existing history: text | dd/mm/yyyy hh:mm:ss am | name
        $old = isset($deal[$cmKey]) && !is_array($deal[$cmKey]) ? (string)$deal[$cmKey] : '';
        $stamp = (new DateTime('now', new DateTimeZone('Asia/Kolkata')))->format('d/m/Y h:i:s a');
        $fields[$cmKey] = $newComment . ' | ' . $stamp . ' | ' . $commentBy . "\n" . $old;
        $original[$cmKey] = $old;
    }
    $logFields = $fields;
    if ($contactCh) {
        if ($cid > 0) {
            $cf = array();
            if (isset($contactCh['_NAME'])) {
                $parts = preg_split('/\s+/', trim($contactCh['_NAME']));
                $cf['LAST_NAME'] = count($parts) > 1 ? array_pop($parts) : '';
                $cf['NAME'] = implode(' ', $parts);
            }
            if (isset($contactCh['_PHONE'])) {
                $cf['PHONE'] = contact_multi($ctLive['phones'], $contactCh['_PHONE'], 'PHONE');
            }
            if (isset($contactCh['_EMAIL'])) {
                $cf['EMAIL'] = contact_multi($ctLive['emails'], $contactCh['_EMAIL'], 'EMAIL');
            }
            $contactPending = $cf;
        } else {
            if (isset($contactCh['_NAME'])) {
                $fields['TITLE'] = $contactCh['_NAME'];
            }
            $contactPending = null;
        }
        foreach ($contactCh as $ck => $cv) {
            $logFields[$ck] = $cv;
            $original[$ck] = isset($original[$ck]) ? $original[$ck] : '';
        }
    } else {
        $contactPending = null;
    }
    if ($createContact) {
        $nm = isset($contactCh['_NAME']) ? $contactCh['_NAME'] : (isset($deal['TITLE']) ? trim((string)$deal['TITLE']) : '');
        $parts = preg_split('/\s+/', trim($nm));
        $cf = array('LAST_NAME' => count($parts) > 1 ? array_pop($parts) : '', 'NAME' => implode(' ', $parts), 'OPENED' => 'Y');
        if (isset($deal['ASSIGNED_BY_ID'])) {
            $cf['ASSIGNED_BY_ID'] = $deal['ASSIGNED_BY_ID'];
        }
        if (isset($contactCh['_PHONE'])) {
            $cf['PHONE'] = contact_multi(array(), $contactCh['_PHONE'], 'PHONE');
        }
        if (isset($contactCh['_EMAIL'])) {
            $cf['EMAIL'] = contact_multi(array(), $contactCh['_EMAIL'], 'EMAIL');
        }
        $made = bx('crm.contact.add', array('fields' => $cf, 'params' => array('REGISTER_SONET_EVENT' => 'N')));
        $newCid = (int)(isset($made['result']) ? $made['result'] : 0);
        if ($newCid <= 0) {
            throw new RuntimeException('Bitrix24 did not create the student contact. Nothing was saved.');
        }
        bx('crm.deal.contact.add', array('id' => (int)$id, 'fields' => array('CONTACT_ID' => $newCid, 'IS_PRIMARY' => 'Y')));
        $cid = $newCid;
        $contactPending = null;
    }
    if (count($fields)) {
        bx('crm.deal.update', array('id' => (int)$id, 'fields' => $fields, 'params' => array('REGISTER_SONET_EVENT' => 'N')));
    }
    if ($contactPending) {
        bx('crm.contact.update', array('id' => $cid, 'fields' => $contactPending, 'params' => array('REGISTER_SONET_EVENT' => 'N')));
    }
    $r2 = bx('crm.deal.get', array('id' => (int)$id));
    $after = (isset($r2['result']) && is_array($r2['result'])) ? $r2['result'] : $deal;
    $values = fresh_values($after, $meta);
    if ($contactCh) {
        $ctNow = contact_snapshot($cid);
        $values['_NAME'] = ($cid > 0 && $ctNow['name'] !== '') ? $ctNow['name'] : trim(isset($after['TITLE']) ? (string)$after['TITLE'] : '');
        $values['_PHONE'] = $ctNow['phone'];
        $values['_EMAIL'] = $ctNow['email'];
    }
    patch_cache($id, $values);
    log_edit($id, $logFields, $original, $meta, $newComment, $commentBy);
    if (!$contactOnly) {
        touch_deal($id);
    }
    return $values;
}

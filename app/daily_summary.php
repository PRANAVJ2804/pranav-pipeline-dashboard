<?php
// Daily e-mail summary + Bitrix24 cross-check. Run from cPanel > Cron Jobs, e.g. every day at 8 AM:
//   php /home/USER/public_html/pranav-dashboard/daily_summary.php
// Needs these (optional) lines in config.php:
//   'summary_admin' => 'you@example.com',                       // admin copy + data-mismatch alert
//   'summary_to'    => array('Team A' => 'teamlead@example.com'), // one e-mail (or array of e-mails) per Team Name
// Only facts the server can count exactly are sent: deals per stage, deals created yesterday, edits made yesterday.
// Rules that live in the dashboard (overdue follow-up, payment pending) are NOT repeated here, so the e-mail never disagrees with the screen.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit;
}
define('APP_OK', true);
require __DIR__ . '/lib.php';

$c = cfg();
$admin = isset($c['summary_admin']) ? (string)$c['summary_admin'] : '';
$to = isset($c['summary_to']) && is_array($c['summary_to']) ? $c['summary_to'] : array();
if ($admin === '' && !$to) {
    fwrite(STDERR, "Nothing to do: add 'summary_admin' and/or 'summary_to' to config.php.\n");
    exit(0);
}
$h = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
$base = isset($c['dashboard_url']) ? rtrim((string)$c['dashboard_url'], '/') . '/' : '';
$yday = date('Y-m-d', time() - 86400);

try {
    $cache = load_cache();
} catch (Throwable $e) {
    fwrite(STDERR, 'FAILED: ' . $e->getMessage() . "\n");
    exit(1);
}
$tk = team_col_key($cache['columns']);
$dk = null;
foreach ($cache['columns'] as $col) {
    if ($col['key'] === 'DATE_CREATE' || preg_match('/^created/i', (string)$col['label'])) { $dk = $col['key']; break; }
}
$editsBy = array();
foreach (read_activity(20000) as $e) {
    if (isset($e['type']) || empty($e['changes']) || substr((string)$e['at'], 0, 10) !== $yday) { continue; }
    $u = isset($e['user']) ? (string)$e['user'] : '';
    $editsBy[$u] = (isset($editsBy[$u]) ? $editsBy[$u] : 0) + 1;
}
$teamOf = isset($c['scopes']) ? $c['scopes'] : array();

function team_block($rows, $dk, $editsBy, $team, $teamOf, $h) {
    $stages = array();
    $new = 0;
    foreach ($rows as $r) {
        $s = trim((string)$r['STAGE_ID']);
        $s = $s === '' ? '(no stage)' : $s;
        $stages[$s] = (isset($stages[$s]) ? $stages[$s] : 0) + 1;
        if ($dk && substr((string)(isset($r[$dk]) ? $r[$dk] : ''), 0, 10) === date('Y-m-d', time() - 86400)) { $new++; }
    }
    arsort($stages);
    $ed = 0;
    foreach ($editsBy as $u => $n) {
        if ($team === null || (isset($teamOf[$u]) && same_team($teamOf[$u], $team))) { $ed += $n; }
    }
    $o = '<p><b>' . count($rows) . '</b> deals · <b>' . $new . '</b> created yesterday · <b>' . $ed . '</b> edits saved yesterday</p><table cellpadding="5" style="border-collapse:collapse;font-size:13px">';
    foreach (array_slice($stages, 0, 12, true) as $s => $n) {
        $o .= '<tr><td style="border-bottom:1px solid #eee">' . $h($s) . '</td><td style="border-bottom:1px solid #eee;text-align:right"><b>' . $n . '</b></td></tr>';
    }
    return $o . '</table>';
}
function send_mail($to, $subject, $html) {
    $hdr = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
    return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $html, $hdr);
}
$foot = $base !== '' ? '<p><a href="' . $h($base) . '">Open the dashboard</a> for follow-ups, payments and documents.</p>' : '<p>Open the dashboard for follow-ups, payments and documents.</p>';

// Team leads
$sent = 0;
foreach ($to as $team => $addr) {
    $rows = array();
    foreach ($cache['rows'] as $r) {
        if ($tk !== null && isset($r[$tk]) && same_team($r[$tk], $team)) { $rows[] = $r; }
    }
    $html = '<div style="font-family:Arial,sans-serif"><h3>' . $h($team) . ' · ' . $h($yday) . '</h3>' . team_block($rows, $dk, $editsBy, $team, $teamOf, $h) . $foot . '</div>';
    foreach ((array)$addr as $a) {
        if (send_mail($a, 'Pranav Pipeline · ' . $team . ' · daily summary', $html)) { $sent++; }
    }
}

// Admin copy: everything, the Bitrix24 cross-check, and (Mondays) the questions the bot could not answer.
if ($admin !== '') {
    $html = '<div style="font-family:Arial,sans-serif"><h3>All teams · ' . $h($yday) . '</h3>' . team_block($cache['rows'], $dk, $editsBy, null, $teamOf, $h);
    $subj = 'Pranav Pipeline · daily summary';
    try {
        $r = reconcile_check();
        if ($r['ok']) {
            $html .= '<p style="color:#0b8a4f">✔ Matches Bitrix24: ' . $r['live'] . ' deals (' . $r['changed_since'] . ' changed since the last refresh).</p>';
        } else {
            $subj = '⚠ ' . $subj . ' · numbers do not match Bitrix24';
            $html .= '<p style="color:#e11d48"><b>⚠ The dashboard shows ' . $r['cached'] . ' deals but Bitrix24 has ' . $r['live'] . ' (newest ID ' . $r['cached_max'] . ' vs ' . $r['live_max'] . ').</b> Press “Refresh data”. If it keeps happening, tell your developer.</p>';
        }
    } catch (Throwable $e) {
        $subj = '⚠ ' . $subj . ' · could not reach Bitrix24';
        $html .= '<p style="color:#e11d48"><b>⚠ Could not check Bitrix24:</b> ' . $h($e->getMessage()) . '</p>';
    }
    if (date('N') === '1') {
        $miss = array();
        foreach (read_usage(7) as $e) {
            if ($e['t'] === 'bot_miss') { $k = mb_strtolower((string)$e['d'], 'UTF-8'); $miss[$k] = (isset($miss[$k]) ? $miss[$k] : 0) + 1; }
        }
        arsort($miss);
        $html .= '<h4>Questions the bot could not answer this week</h4>' . ($miss ? '<ul>' : '<p>None.</p>');
        foreach (array_slice($miss, 0, 15, true) as $k => $n) { $html .= '<li>' . $h($k) . ' (' . $n . '×)</li>'; }
        $html .= $miss ? '</ul>' : '';
    }
    if (send_mail($admin, $subj, $html . $foot . '</div>')) { $sent++; }
}
echo 'Sent ' . $sent . " e-mail(s).\n";

<?php
// Optional: run this from cPanel > Cron Jobs to refresh the data automatically.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit;
}
define('APP_OK', true);
require __DIR__ . '/lib.php';

try {
    $m = refresh_cache();
    echo 'OK: ' . $m['count'] . ' deals saved at ' . $m['fetched_at'] . "\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'FAILED: ' . $e->getMessage() . "\n");
    exit(1);
}

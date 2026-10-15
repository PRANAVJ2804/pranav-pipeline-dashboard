#!/usr/bin/env bash
# Local dev server with a fake Bitrix24.  Usage: tools/dev.sh   ->  http://127.0.0.1:8080  (admin / S3cret-Pass-123)
set -e
cd "$(dirname "$0")/.."
rm -rf .dev && mkdir -p .dev && cp -r app/. .dev/ && cp tools/test-config.php .dev/config.php
python3 tools/mock_bitrix.py >/tmp/mock_bitrix.log 2>&1 &
MOCK=$!
trap 'kill $MOCK 2>/dev/null' EXIT
echo "Mock Bitrix24 on :9000, dashboard on http://127.0.0.1:8080  (login: admin / S3cret-Pass-123)"
cd .dev && php -S 127.0.0.1:8080

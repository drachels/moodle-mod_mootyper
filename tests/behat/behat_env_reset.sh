#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../../../.." && pwd)"
cd "$ROOT_DIR"

php admin/tool/behat/cli/util.php --disable || true
php admin/tool/behat/cli/util.php --enable
php admin/tool/behat/cli/init.php

echo "Behat environment reset complete."

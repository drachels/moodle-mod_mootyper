#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../../../.." && pwd)"
cd "$ROOT_DIR"

BEHAT_CFG_DEFAULT="/var/moodledata/behatmoodledatadev/behatrun/behat/behat.yml"
BEHAT_CFG_IN="${BEHAT_CONFIG:-$BEHAT_CFG_DEFAULT}"
BEHAT_CFG_PATCHED="/tmp/mootyper-behat-gecko.yml"

if [[ ! -f "$BEHAT_CFG_IN" ]]; then
    echo "Behat config not found: $BEHAT_CFG_IN" >&2
    echo "Run: mod/mootyper/tests/behat/behat_env_reset.sh" >&2
    exit 1
fi

# Moodle's generated config uses /wd/hub. geckodriver expects root path.
sed "s#http://127.0.0.1:4444/wd/hub#http://127.0.0.1:4444#" "$BEHAT_CFG_IN" > "$BEHAT_CFG_PATCHED"

"$ROOT_DIR/mod/mootyper/tests/behat/webdriver_gecko.sh" start

cleanup() {
    if [[ "${KEEP_WEBDRIVER:-0}" != "1" ]]; then
        "$ROOT_DIR/mod/mootyper/tests/behat/webdriver_gecko.sh" stop >/dev/null 2>&1 || true
    fi
}
trap cleanup EXIT

if [[ $# -eq 0 ]]; then
    set -- mod/mootyper/tests/behat/continue_gate.feature
fi

vendor/bin/behat --config "$BEHAT_CFG_PATCHED" "$@"

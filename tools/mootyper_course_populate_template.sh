#!/usr/bin/env bash
set -euo pipefail

# Tiny reusable wrapper for mootyper_XX_course_populate.php.
# Edit only the run_course lines at the bottom for each target course.

SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
TOOL_PHP="${SCRIPT_DIR}/mootyper_XX_course_populate.php"

PHP_BIN="${PHP_BIN:-php83}"
MOODLEROOT="${MOODLEROOT:-/var/www/moodledev/public}"
CREATE="${CREATE:-0}"
CATEGORYID="${CATEGORYID:-0}"
DRY_RUN="${DRY_RUN:-1}"

run_course() {
    local donorcourseid="$1"
    local targetcourseid="$2"
    local targetlanguage="$3"
    local layoutname="$4"
    local destinationfullname="$5"
    local destinationshortname="$6"

    local cmd=(
        "$PHP_BIN" "$TOOL_PHP"
        "--moodleroot=${MOODLEROOT}"
        "--donorcourseid=${donorcourseid}"
        "--targetcourseid=${targetcourseid}"
        "--destinationfullname=${destinationfullname}"
        "--destinationshortname=${destinationshortname}"
        "--targetlanguage=${targetlanguage}"
        "--layoutname=${layoutname}"
    )

    if [[ "$CREATE" == "1" ]]; then
        cmd+=("--create=1" "--categoryid=${CATEGORYID}")
    else
        cmd+=("--create=0")
    fi

    if [[ "$DRY_RUN" == "1" ]]; then
        printf 'DRY_RUN: '
        printf '%q ' "${cmd[@]}"
        printf '\n'
    else
        "${cmd[@]}"
    fi
}

# -----------------------------------------------------------------------------
# TEMPLATE LINES: duplicate/edit these for each new target course build.
# Format:
# run_course DONOR TARGET LANG LAYOUT "DEST_FULLNAME" "DEST_SHORTNAME"
# -----------------------------------------------------------------------------

run_course 1086 1094 hi "Hindi(HIV5)" "Hindi Demo" "Hindi-demo"
# run_course 1086 1095 fr "French(BelgianFRBV5)" "Belgium(French) Demo" "Belgium(French)-demo"
# run_course 1086 1096 nl "Belgium(DutchV5)" "Belgium(Dutch) Demo" "Belgium(Dutch)-demo"

# Usage:
# 1) Preview only (default):
#    ./mootyper_course_populate_template.sh
# 2) Execute:
#    DRY_RUN=0 ./mootyper_course_populate_template.sh
# 3) Create a new course instead of updating an existing target:
#    DRY_RUN=0 CREATE=1 CATEGORYID=1 ./mootyper_course_populate_template.sh

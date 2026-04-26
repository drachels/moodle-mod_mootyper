#!/usr/bin/env bash
set -euo pipefail

# Queue many MooTyper course population runs from a simple CSV file.
# CSV columns:
# donorcourseid,targetcourseid,targetlanguage,layoutname,destinationfullname,destinationshortname,create,categoryid
# Notes:
# - create and categoryid are optional per row.
# - destinationfullname and destinationshortname should not contain commas.

SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
TOOL_PHP="${SCRIPT_DIR}/mootyper_XX_course_populate.php"

PHP_BIN="${PHP_BIN:-php83}"
MOODLEROOT="${MOODLEROOT:-/var/www/moodledev/public}"
CSV_FILE="${CSV_FILE:-${SCRIPT_DIR}/mootyper_course_queue.csv}"
DRY_RUN="${DRY_RUN:-1}"
DEFAULT_CREATE="${CREATE:-0}"
DEFAULT_CATEGORYID="${CATEGORYID:-0}"

trim() {
    local value="$1"
    value="${value#"${value%%[![:space:]]*}"}"
    value="${value%"${value##*[![:space:]]}"}"
    printf '%s' "$value"
}

run_row() {
    local donorcourseid="$1"
    local targetcourseid="$2"
    local targetlanguage="$3"
    local layoutname="$4"
    local destinationfullname="$5"
    local destinationshortname="$6"
    local create="$7"
    local categoryid="$8"

    local cmd=(
        "$PHP_BIN" "$TOOL_PHP"
        "--moodleroot=${MOODLEROOT}"
        "--donorcourseid=${donorcourseid}"
        "--targetcourseid=${targetcourseid}"
        "--destinationfullname=${destinationfullname}"
        "--destinationshortname=${destinationshortname}"
        "--targetlanguage=${targetlanguage}"
        "--layoutname=${layoutname}"
        "--create=${create}"
    )

    if [[ "$create" == "1" ]]; then
        cmd+=("--categoryid=${categoryid}")
    fi

    if [[ "$DRY_RUN" == "1" ]]; then
        printf 'DRY_RUN: '
        printf '%q ' "${cmd[@]}"
        printf '\n'
    else
        "${cmd[@]}"
    fi
}

if [[ ! -f "$CSV_FILE" ]]; then
    echo "ERROR: CSV file not found: $CSV_FILE" >&2
    exit 1
fi

lineno=0
while IFS=',' read -r donorcourseid targetcourseid targetlanguage layoutname destinationfullname destinationshortname create categoryid; do
    lineno=$((lineno + 1))

    donorcourseid="$(trim "${donorcourseid:-}")"
    targetcourseid="$(trim "${targetcourseid:-}")"
    targetlanguage="$(trim "${targetlanguage:-}")"
    layoutname="$(trim "${layoutname:-}")"
    destinationfullname="$(trim "${destinationfullname:-}")"
    destinationshortname="$(trim "${destinationshortname:-}")"
    create="$(trim "${create:-}")"
    categoryid="$(trim "${categoryid:-}")"

    if [[ -z "$donorcourseid" ]]; then
        continue
    fi
    if [[ "$donorcourseid" == \#* ]]; then
        continue
    fi
    if [[ "$donorcourseid" == "donorcourseid" ]]; then
        continue
    fi

    if [[ -z "$targetcourseid" || -z "$targetlanguage" || -z "$layoutname" || -z "$destinationfullname" || -z "$destinationshortname" ]]; then
        echo "ERROR: CSV line $lineno is missing required fields" >&2
        exit 1
    fi

    if [[ -z "$create" ]]; then
        create="$DEFAULT_CREATE"
    fi
    if [[ -z "$categoryid" ]]; then
        categoryid="$DEFAULT_CATEGORYID"
    fi

    if [[ "$create" != "0" && "$create" != "1" ]]; then
        echo "ERROR: CSV line $lineno has invalid create value: $create (expected 0 or 1)" >&2
        exit 1
    fi

    if [[ "$create" == "1" && "$categoryid" == "0" ]]; then
        echo "ERROR: CSV line $lineno uses create=1 but categoryid is 0" >&2
        exit 1
    fi

    run_row "$donorcourseid" "$targetcourseid" "$targetlanguage" "$layoutname" "$destinationfullname" "$destinationshortname" "$create" "$categoryid"
done < "$CSV_FILE"

# Usage examples:
# 1) Preview queue from default CSV path:
#    ./mootyper_course_populate_from_csv.sh
# 2) Execute queue:
#    DRY_RUN=0 ./mootyper_course_populate_from_csv.sh
# 3) Use a different CSV file:
#    CSV_FILE=/path/to/my_queue.csv DRY_RUN=0 ./mootyper_course_populate_from_csv.sh

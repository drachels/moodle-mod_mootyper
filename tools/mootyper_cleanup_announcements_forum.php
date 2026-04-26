<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Keep one Announcements forum in section 0 and remove duplicate ones.
 *
 * @package    mod_mootyper
 * @copyright  2026 onwards AL Rachels
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);
// phpcs:disable moodle.Files.MoodleInternal

/**
 * Get CLI --key=value argument.
 *
 * @param array $argv args.
 * @param string $key key.
 * @param string|null $default default.
 * @return string|null
 */
function arg_value(array $argv, string $key, ?string $default = null): ?string {
    foreach ($argv as $arg) {
        if (strpos($arg, '--' . $key . '=') === 0) {
            return substr($arg, strlen($key) + 3);
        }
    }
    return $default;
}

/**
 * Fail fast for CLI.
 *
 * @param string $message error text.
 * @param int $code exit code.
 */
function fail(string $message, int $code = 1): void {
    fwrite(STDERR, "ERROR: {$message}\n");
    exit($code);
}

$moodleroot = arg_value($argv, 'moodleroot');
$courseid = (int)(arg_value($argv, 'courseid', '0'));
$apply = (int)(arg_value($argv, 'apply', '0')) === 1;

if (!$moodleroot || !is_file($moodleroot . '/config.php')) {
    fail('Missing/invalid --moodleroot');
}
if ($courseid <= 0) {
    fail('Missing/invalid --courseid');
}

require($moodleroot . '/config.php');
require_once($CFG->dirroot . '/course/lib.php');

global $DB, $USER;
$USER = get_admin();
if (empty($USER) || empty($USER->id)) {
    fail('Admin user not available in CLI context');
}

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$modinfo = get_fast_modinfo($courseid);
$sectionzero = $modinfo->get_section_info(0);

$sequence = [];
if (!empty($sectionzero->sequence)) {
    $sequence = array_map('intval', explode(',', $sectionzero->sequence));
}

$announcementforums = [];
foreach ($sequence as $cmid) {
    if ($cmid <= 0) {
        continue;
    }
    $cm = $modinfo->get_cm($cmid);
    if ($cm->modname !== 'forum' || (int)$cm->sectionnum !== 0) {
        continue;
    }
    if (strtolower(trim($cm->name)) === 'announcements') {
        $announcementforums[] = $cm;
    }
}

if (count($announcementforums) <= 1) {
    echo "NOOP course={$courseid} fullname={$course->fullname} announcements=" . count($announcementforums) . "\n";
    exit(0);
}

$keep = array_shift($announcementforums);
$deleted = 0;
foreach ($announcementforums as $cm) {
    if ($apply) {
        course_delete_module((int)$cm->id);
    }
    $deleted++;
}

$mode = $apply ? 'APPLY' : 'DRYRUN';
echo "{$mode} course={$courseid} fullname={$course->fullname} keepcmid={$keep->id} deleted={$deleted}\n";

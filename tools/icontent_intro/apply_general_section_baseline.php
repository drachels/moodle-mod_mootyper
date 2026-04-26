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
 * CLI tool to apply the general section baseline to one or more courses.
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);
// phpcs:disable moodle.Files.MoodleInternal

/**
 * Write an error message to STDERR and exit.
 *
 * @param string $message Error description.
 * @param int    $code    Exit code (default 1).
 */
function fail(string $message, int $code = 1): void {
    fwrite(STDERR, "ERROR: {$message}\n");
    exit($code);
}

/**
 * Parse CLI --key=value arguments into an associative array.
 *
 * @param array $argv Command-line arguments from $argv.
 * @return array Parsed key/value pairs.
 */
function parse_args(array $argv): array {
    $args = [];
    foreach ($argv as $arg) {
        if (substr($arg, 0, 2) !== '--') {
            continue;
        }
        $parts = explode('=', substr($arg, 2), 2);
        $key = $parts[0];
        $value = $parts[1] ?? '1';
        $args[$key] = $value;
    }
    return $args;
}

/**
 * Parse a comma-separated string of positive integers into an array.
 *
 * @param string $raw Comma-separated integer list.
 * @return array Array of unique positive integers.
 */
function parse_csv_ints(string $raw): array {
    $items = array_filter(array_map('trim', explode(',', $raw)), static function (string $v): bool {
        return $v !== '';
    });

    $ids = [];
    foreach ($items as $item) {
        if (!ctype_digit($item) || (int)$item <= 0) {
            fail("Invalid integer in CSV list: {$item}");
        }
        $ids[] = (int)$item;
    }
    return array_values(array_unique($ids));
}

/**
 * Clone a course module into a target course using Moodle backup/restore.
 *
 * @param int $sourcecmid     Course module ID to clone.
 * @param int $targetcourseid Destination course ID.
 * @param int $userid         User ID to perform the backup/restore as.
 * @return int New course module ID in the target course.
 */
function clone_activity_to_course(int $sourcecmid, int $targetcourseid, int $userid): int {
    global $CFG;

    require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
    require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
    require_once($CFG->libdir . '/filelib.php');

    $sourcecontext = context_module::instance($sourcecmid);

    $bc = new backup_controller(
        backup::TYPE_1ACTIVITY,
        $sourcecmid,
        backup::FORMAT_MOODLE,
        backup::INTERACTIVE_NO,
        backup::MODE_IMPORT,
        $userid
    );

    $backupid = $bc->get_backupid();
    $backupbasepath = $bc->get_plan()->get_basepath();
    $bc->execute_plan();
    $bc->destroy();

    $rc = new restore_controller(
        $backupid,
        $targetcourseid,
        backup::INTERACTIVE_NO,
        backup::MODE_IMPORT,
        $userid,
        backup::TARGET_CURRENT_ADDING
    );

    $plan = $rc->get_plan();
    $groupsetting = $plan->get_setting('groups');
    if ($groupsetting && empty($groupsetting->get_value())) {
        $groupsetting->set_value(true);
    }

    if (!$rc->execute_precheck()) {
        $precheckresults = $rc->get_precheck_results();
        $rc->destroy();
        if (empty($CFG->keeptempdirectoriesonbackup)) {
            fulldelete($backupbasepath);
        }
        fail('Restore precheck failed for source cmid ' . $sourcecmid . ': ' . json_encode($precheckresults));
    }

    $rc->execute_plan();

    $newcmid = 0;
    foreach ($rc->get_plan()->get_tasks() as $task) {
        if (is_subclass_of($task, 'restore_activity_task') && $task->get_old_contextid() == $sourcecontext->id) {
            $newcmid = (int)$task->get_moduleid();
            break;
        }
    }

    $rc->destroy();
    if (empty($CFG->keeptempdirectoriesonbackup)) {
        fulldelete($backupbasepath);
    }

    if ($newcmid <= 0) {
        fail('Could not resolve new course module id after restore for source cmid ' . $sourcecmid);
    }

    return $newcmid;
}

$args = parse_args($argv);

$moodleroot = $args['moodleroot'] ?? '';
$targetcsv = $args['targetcourseids'] ?? '';
$rootcategoryid = isset($args['rootcategoryid']) ? (int)$args['rootcategoryid'] : 0;
$sourcecourseid = isset($args['sourcecourseid']) ? (int)$args['sourcecourseid'] : 1081;
$sectionnum = isset($args['section']) ? (int)$args['section'] : 0;
$introcontains = $args['introcontains'] ?? 'Guided Introduction';
$methodname = $args['methodname'] ?? 'Method of carrying out the exercises';
$pagename = $args['pagename'] ?? 'Initial position of the fingers on the keyboard';
$dryrun = !empty($args['dry-run']) && $args['dry-run'] !== '0';

if ($moodleroot === '') {
    fail('Missing --moodleroot=/path/to/moodle');
}
if (!is_file($moodleroot . '/config.php')) {
    fail('Could not find config.php in --moodleroot path');
}
if ($targetcsv === '' && $rootcategoryid <= 0) {
    fail('Missing target scope: provide --targetcourseids=<id1,id2,...> and/or --rootcategoryid=<int>');
}
if ($sourcecourseid <= 0) {
    fail('Invalid --sourcecourseid=<int>');
}
if ($sectionnum < 0) {
    fail('Invalid --section=<int> (must be >= 0)');
}

$targetcourseids = [];
if ($targetcsv !== '') {
    $targetcourseids = parse_csv_ints($targetcsv);
}

require_once($moodleroot . '/config.php');
require_once($CFG->dirroot . '/course/lib.php');

global $DB, $USER;
$USER = get_admin();
if (empty($USER) || empty($USER->id)) {
    fail('Could not resolve admin user in CLI context');
}

if ($rootcategoryid > 0) {
    $rootcat = \core_course_category::get($rootcategoryid, IGNORE_MISSING, true);
    if (!$rootcat) {
        fail('Could not find category ID: ' . $rootcategoryid);
    }
    $categoryids = array_merge([$rootcategoryid], $rootcat->get_all_children_ids());
    [$catsql, $catparams] = $DB->get_in_or_equal($categoryids, SQL_PARAMS_NAMED);
    $courseidsfromcat = $DB->get_fieldset_sql(
        "SELECT id
           FROM {course}
          WHERE category {$catsql}
       ORDER BY id",
        $catparams
    );
    $targetcourseids = array_merge($targetcourseids, array_map('intval', $courseidsfromcat));
}

$targetcourseids = array_values(array_unique(array_filter(array_map('intval', $targetcourseids), static function (int $id): bool {
    return $id > 0;
})));

if (empty($targetcourseids)) {
    fail('No target courses resolved from provided scope');
}

$sourcecourse = $DB->get_record('course', ['id' => $sourcecourseid], '*', MUST_EXIST);
$sourcesection = $DB->get_record('course_sections', ['course' => $sourcecourseid, 'section' => $sectionnum], '*', MUST_EXIST);

$sourceitems = $DB->get_records_sql(
    "SELECT cm.id AS cmid,
            m.name AS modname,
            COALESCE(ic.name, p.name) AS itemname
       FROM {course_modules} cm
       JOIN {modules} m ON m.id = cm.module
  LEFT JOIN {icontent} ic ON ic.id = cm.instance AND m.name = 'icontent'
  LEFT JOIN {page} p ON p.id = cm.instance AND m.name = 'page'
      WHERE cm.section = :sectionid
        AND (
             (m.name = 'icontent' AND (ic.name = :methodname OR ic.name = :methodcopy))
             OR
             (m.name = 'page' AND (p.name = :pagename OR p.name = :pagecopy))
        )",
    [
        'sectionid' => $sourcesection->id,
        'methodname' => $methodname,
        'methodcopy' => $methodname . ' (copy)',
        'pagename' => $pagename,
        'pagecopy' => $pagename . ' (copy)',
    ]
);

$sourcecmidmethod = 0;
$sourcecmidpage = 0;
foreach ($sourceitems as $si) {
    if ($si->modname === 'icontent' && $sourcecmidmethod === 0) {
        $sourcecmidmethod = (int)$si->cmid;
    }
    if ($si->modname === 'page' && $sourcecmidpage === 0) {
        $sourcecmidpage = (int)$si->cmid;
    }
}

if ($sourcecmidmethod === 0) {
    fail("Could not find source iContent item '{$methodname}' in source course section");
}
if ($sourcecmidpage === 0) {
    fail("Could not find source page item '{$pagename}' in source course section");
}

$results = [];

foreach ($targetcourseids as $targetid) {
    $DB->get_record('course', ['id' => $targetid], 'id', MUST_EXIST);
    $targetsection = $DB->get_record('course_sections', ['course' => $targetid, 'section' => $sectionnum], '*', MUST_EXIST);

    $rows = $DB->get_records_sql(
        "SELECT CONCAT(cs.id, '-', cm.id) AS rid,
                cm.id AS cmid,
                m.name AS modname,
                FIND_IN_SET(cm.id, cs.sequence) AS seqpos,
                COALESCE(ic.name, p.name, f.name) AS itemname
           FROM {course_sections} cs
           JOIN {course_modules} cm ON FIND_IN_SET(cm.id, cs.sequence) > 0
           JOIN {modules} m ON m.id = cm.module
      LEFT JOIN {icontent} ic ON ic.id = cm.instance AND m.name = 'icontent'
      LEFT JOIN {page} p ON p.id = cm.instance AND m.name = 'page'
      LEFT JOIN {forum} f ON f.id = cm.instance AND m.name = 'forum'
          WHERE cs.course = :courseid
            AND cs.section = :sectionnum
          ORDER BY seqpos",
        ['courseid' => $targetid, 'sectionnum' => $sectionnum]
    );

    $introcmid = 0;
    $introfallbackcmid = 0;
    $announcementcmid = 0;
    $firstsectioncmid = 0;
    $methodcmid = 0;
    $pagecmid = 0;

    foreach ($rows as $row) {
        if ($firstsectioncmid === 0) {
            $firstsectioncmid = (int)$row->cmid;
        }

        if ($row->modname === 'forum' && $announcementcmid === 0) {
            $announcementcmid = (int)$row->cmid;
        }

        if ($row->modname === 'icontent'
            && ($row->itemname === $methodname || $row->itemname === $methodname . ' (copy)')
            && $methodcmid === 0) {
            $methodcmid = (int)$row->cmid;
            continue;
        }

        if ($row->modname === 'page'
            && ($row->itemname === $pagename || $row->itemname === $pagename . ' (copy)')
            && $pagecmid === 0) {
            $pagecmid = (int)$row->cmid;
            continue;
        }

        if ($row->modname === 'icontent') {
            if ($introcontains !== '' && $introcmid === 0 && stripos((string)$row->itemname, $introcontains) !== false) {
                $introcmid = (int)$row->cmid;
                continue;
            }
            if ($introfallbackcmid === 0) {
                $introfallbackcmid = (int)$row->cmid;
            }
        }
    }

    if ($introcmid === 0) {
        if ($introfallbackcmid > 0) {
            $introcmid = $introfallbackcmid;
        } else if ($announcementcmid > 0) {
            $introcmid = $announcementcmid;
        } else if ($firstsectioncmid > 0) {
            $introcmid = $firstsectioncmid;
        }
    }

    $created = [];

    if ($methodcmid === 0) {
        if ($dryrun) {
            $created[] = 'method(copy planned)';
        } else {
            $methodcmid = clone_activity_to_course($sourcecmidmethod, $targetid, (int)$USER->id);
            $created[] = "method(cmid={$methodcmid})";

            $cmrow = get_coursemodule_from_id('icontent', $methodcmid, $targetid, false, MUST_EXIST);
            $item = $DB->get_record('icontent', ['id' => $cmrow->instance], '*', MUST_EXIST);
            if ($item->name !== $methodname) {
                $item->name = $methodname;
                $DB->update_record('icontent', $item);
            }
        }
    }

    if ($pagecmid === 0) {
        if ($dryrun) {
            $created[] = 'page(copy planned)';
        } else {
            $pagecmid = clone_activity_to_course($sourcecmidpage, $targetid, (int)$USER->id);
            $created[] = "page(cmid={$pagecmid})";

            $cmrow = get_coursemodule_from_id('page', $pagecmid, $targetid, false, MUST_EXIST);
            $item = $DB->get_record('page', ['id' => $cmrow->instance], '*', MUST_EXIST);
            if ($item->name !== $pagename) {
                $item->name = $pagename;
                $DB->update_record('page', $item);
            }
        }
    }

    if ($dryrun && ($methodcmid === 0 || $pagecmid === 0)) {
        $results[] = [
            'courseid' => $targetid,
            'introcmid' => $introcmid,
            'methodcmid' => $methodcmid,
            'pagecmid' => $pagecmid,
            'created' => $created,
            'reordered' => false,
            'dryrun' => true,
        ];
        continue;
    }

    if ($methodcmid === 0 || $pagecmid === 0) {
        fail("Could not resolve target cmids for course {$targetid}");
    }

    $targetsection = $DB->get_record('course_sections', ['course' => $targetid, 'section' => $sectionnum], '*', MUST_EXIST);
    $sequence = array_values(array_filter(array_map('intval', explode(',', (string)$targetsection->sequence))));

    $beforemod = null;
    $alreadyordered = false;
    $introindex = null;
    if ($introcmid > 0) {
        $introindex = array_search($introcmid, $sequence, true);
        if ($introindex === false) {
            fail("Intro cmid {$introcmid} not found in section sequence for course {$targetid}");
        }

        $alreadyordered = (
            isset($sequence[$introindex + 1], $sequence[$introindex + 2])
            && (int)$sequence[$introindex + 1] === (int)$methodcmid
            && (int)$sequence[$introindex + 2] === (int)$pagecmid
        );

        for ($i = $introindex + 1; $i < count($sequence); $i++) {
            $candidate = $sequence[$i];
            if ($candidate !== $methodcmid && $candidate !== $pagecmid) {
                $beforemod = $candidate;
                break;
            }
        }
    }

    $reordered = false;
    if (!$dryrun) {
        if (!$alreadyordered) {
            if ($beforemod !== null) {
                $pagecm = get_coursemodule_from_id('page', $pagecmid, $targetid, false, MUST_EXIST);
                moveto_module($pagecm, $targetsection, $beforemod);
            }

            $methodcm = get_coursemodule_from_id('icontent', $methodcmid, $targetid, false, MUST_EXIST);
            moveto_module($methodcm, $targetsection, $pagecmid);

            rebuild_course_cache($targetid, true);
            $reordered = true;
        }
    }

    $results[] = [
        'courseid' => $targetid,
        'introcmid' => $introcmid,
        'methodcmid' => $methodcmid,
        'pagecmid' => $pagecmid,
        'created' => $created,
        'reordered' => $reordered,
        'dryrun' => $dryrun,
    ];
}

foreach ($results as $r) {
    $created = empty($r['created']) ? 'none' : implode(',', $r['created']);
    $mode = $r['dryrun'] ? 'DRY_RUN' : 'APPLIED';
    echo "{$mode} course={$r['courseid']} intro={$r['introcmid']}" .
        " method={$r['methodcmid']} page={$r['pagecmid']}" .
        " created={$created} reordered=" . ($r['reordered'] ? '1' : '0') . "\n";
}

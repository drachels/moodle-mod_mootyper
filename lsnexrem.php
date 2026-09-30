<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file is used to remove exercises and lessons.
 *
 * Called from exercises.php when clicking on Remove all from 'xxxx' or
 * one of the remove icon/link for an individual exercise.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\exercise_deleted;
use mod_mootyper\event\lesson_deleted;
use mod_mootyper\local\lessons;

// Changed to this newer format 20190301.
require(__DIR__ . '/../../config.php');

global $DB;

$id = required_param('id', PARAM_INT); // Course_module ID.
$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);
// If re is set we remove an exercise.
// If rl is set we remove a lesson and all its exercises.
$exerciseid = optional_param('re', 0, PARAM_INT);
$lessonid = optional_param('rl', 0, PARAM_INT);
// Added cmid so can exit back to MooTyper activity we came from.
$cmid = optional_param('cmid', '0', PARAM_INT); // Course Module ID.

require_login($course, true, $cm);
require_sesskey();

/** @var context $context */
$context = context_module::instance($cm->id);
if (!($context instanceof context_module)) {
    throw new moodle_exception('invalidaccess', 'mootyper', '', null);
}
require_capability('mod/mootyper:aftersetup', $context);
$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id=' . $id;

if (!$lessonid && !$exerciseid) {
    throw new moodle_exception('invalidaccess', 'mootyper', '', null);
}

// 0 Find the lesson being used.
// 1 Find all the MooTypers using this lessons ID first.
// 2 If more than one is found, list the MooTyper activities which will no longer have a valid lesson.
// 3 Halt and notify the admin/teacher regarding 2. DO NOT allow process to proceed.
// 4 If none or only one MooTyper is using the lesson, find all the exercises used by this lesson ID.
// 5 Use results from 4 to find all the grades used by the exercises from this lesson ID.
// 6 Delete all the grades found in 5.
// 7 Delete all the exercises used by this lesson ID.
// 8 Delete the lesson.


// 20241227 Get the lesson ID if available. STEP 0
$lessonpo = optional_param('lesson', '', PARAM_INT);
// Get all the exercises in this lesson so we can fix the snumbers if we need too. STEP 4.
$exes = lessons::get_exercises_by_lesson($lessonpo);

// 20241231 Block of code to delete a lesson and all of the exercises in it.
if ($lessonid) {
    $lessonid = (int)$lessonid;
    if ((int)$mootyper->lesson !== $lessonid) {
        throw new moodle_exception('invalidaccess', 'mootyper', '', null);
    }
    $lessonrecord = $DB->get_record('mootyper_lessons', ['id' => $lessonid], '*', IGNORE_MISSING);

    // Handle stale repeat requests where the lesson has already been deleted.
    if (!$lessonrecord) {
        $fallbacklesson = $DB->get_record_sql(
            "SELECT id, lessonname
               FROM {mootyper_lessons}
           ORDER BY LOWER(lessonname) ASC",
            null,
            IGNORE_MULTIPLE
        );

        $webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id=' . $id;
        if ($fallbacklesson) {
            $webdir .= '&lesson=' . $fallbacklesson->id;
        }
        redirect($webdir);
    }

    require_once($CFG->dirroot . '/mod/mootyper/lib.php');
    require_once($CFG->dirroot . '/rating/lib.php');

    // Determine which activities currently reference this lesson.
    $mootypers = $DB->get_records('mootyper', ['lesson' => $lessonid]);
    $mootyperscount = count($mootypers);

    // Protect non-admin users from breaking multiple activities at once.
    if (($mootyperscount > 1) && !is_siteadmin()) {
        throw new moodle_exception('mootyperlessonerror', 'mootyper', '', null);
    }

    // If any activity points to this lesson, re-point to another lesson first.
    $fallbacklesson = null;
    if ($mootyperscount > 0) {
        $sql = "SELECT id, lessonname FROM {mootyper_lessons}
                  WHERE id <> :lessonid
               ORDER BY LOWER(lessonname) ASC";
        $fallbacklesson = $DB->get_record_sql($sql, ['lessonid' => $lessonid], IGNORE_MULTIPLE);
        if (!$fallbacklesson) {
            throw new moodle_exception('mootyperlessonerror', 'mootyper', '', null);
        }

        foreach ($mootypers as $mootyper) {
            $mootyper->lesson = $fallbacklesson->id;
            $DB->update_record('mootyper', $mootyper);
        }
    }

    // Delete the physical lesson file only when the stored name is a safe filename in the lesson directory.
    $lessondir = realpath($CFG->dirroot . '/mod/mootyper/lessons');
    $lessonname = (string)$lessonrecord->lessonname;
    if (
        $lessondir !== false
        && $lessonname !== ''
        && basename($lessonname) === $lessonname
        && strpbrk($lessonname, "/\\") === false
        && preg_match('/[\x00-\x1F\x7F]/', $lessonname) === 0
    ) {
        $lessonfilepath = $lessondir . DIRECTORY_SEPARATOR . $lessonname . '.txt';
        $resolvedlessonfilepath = realpath($lessonfilepath);
        if (
            $resolvedlessonfilepath !== false
            && dirname($resolvedlessonfilepath) === $lessondir
            && is_file($resolvedlessonfilepath)
            && !unlink($resolvedlessonfilepath)
        ) {
            throw new moodle_exception('errorcannotdeletefile', 'moodle', '', $resolvedlessonfilepath);
        }
    }

    $transaction = $DB->start_delegated_transaction();
    $ratingmanager = new rating_manager();
    $affectedgrades = [];

    // Delete checks/attempts/grades that belong to exercises in this lesson.
    $exercises = $DB->get_records('mootyper_exercises', ['lesson' => $lessonid]);
    foreach ($exercises as $exercise) {
        $grades = $DB->get_records('mootyper_grades', ['exercise' => $exercise->id]);
        foreach ($grades as $grade) {
            $affectedgrades[$grade->mootyper][$grade->userid] = true;

            // Remove ratings attached to this grade row.
            $delopt = new stdClass();
            $delopt->contextid = $context->id;
            $delopt->component = 'mod_mootyper';
            $delopt->ratingarea = 'exercises';
            $delopt->itemid = $grade->id;
            $ratingmanager->delete_ratings($delopt);

            if (!empty($grade->attemptid)) {
                $DB->delete_records('mootyper_checks', ['attemptid' => $grade->attemptid]);
                $DB->delete_records('mootyper_attempts', ['id' => $grade->attemptid]);
            }
        }
        $DB->delete_records('mootyper_grades', ['exercise' => $exercise->id]);
    }

    // Delete exercises and then the lesson itself.
    $DB->delete_records('mootyper_exercises', ['lesson' => $lessonid]);
    $DB->delete_records('mootyper_lessons', ['id' => $lessonid]);

    // Refresh gradebook entries for users impacted by deleted lesson grades.
    foreach ($affectedgrades as $mootyperid => $users) {
        $mootyper = $DB->get_record('mootyper', ['id' => $mootyperid], '*', IGNORE_MISSING);
        if (!$mootyper) {
            continue;
        }
        foreach (array_keys($users) as $userid) {
            mootyper_update_grades($mootyper, (int)$userid);
        }
    }

    $transaction->allow_commit();

    // Trigger module lesson_deleted event.
    $params = [
        'objectid' => $lessonid,
        'context' => $context,
        'other' => [
            'lesson' => $lessonrecord->lessonname,
        ],
    ];
    $event = lesson_deleted::create($params);
    $event->trigger();

    // Return to exercises with a valid lesson selected when possible.
    $returnlessonid = $fallbacklesson ? $fallbacklesson->id : 0;
    $webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id=' . $id;
    if ($returnlessonid > 0) {
        $webdir .= '&lesson=' . $returnlessonid;
    }
    header('Location: ' . $webdir);
    exit;
}

// 20250828 Below here works and Will delete the selected exercise.
if ($exerciseid) {
    $exerciseid = (int)$exerciseid;
    $targetexercise = $DB->get_record('mootyper_exercises', ['id' => $exerciseid], 'id,lesson', MUST_EXIST);
    if ((int)$targetexercise->lesson !== (int)$mootyper->lesson) {
        throw new moodle_exception('invalidaccess', 'mootyper', '', null);
    }
    // 20241229 Get all the mootyper_grades that have this exercise listed no matter if it is passing or not.
    $orphanedgrades = $DB->get_records('mootyper_grades', ['exercise' => $exerciseid]);

    // 20241229 Delete the exercise selected for deletion.
    $DB->delete_records('mootyper_exercises', (['id' => $exerciseid]));
    // 20241229 Delete any mootyper_grades.
    $DB->delete_records('mootyper_grades', (['exercise' => $exerciseid]));


    // Initialize a counter to use for snumber replacements.
    $count = 0;
    // Get all the exercises left in this lesson so we can fix the snumbers if we need too.
    $exes = lessons::get_exercises_by_lesson($lessonpo);
    // 20241229 Adjust the snumber for each of the remaining exercises.
    foreach ($exes as $exe) {
        $count++;
        // 20241229 Replace the current snumber of this exercise with the current count.
        $exe['snumber'] = $count + 0;
        // 20240101 Also use the snumber to update the Exercise names.
        $exercisename = $exe['exercisename'];
        // 20250101 Break exercisename at the first space found and keep the remainder.
        $exercisename = (explode(" ", $exercisename, 2));
        // 20250101 Update the Exercise name with snumber+remainder.
        $exe['exercisename'] = str_replace('\n', "&#10;", $count . ' ' . $exercisename[1]);
        $DB->update_record('mootyper_exercises', $exe);
    }
    $exes = lessons::get_exercises_by_lesson($lessonpo);
    // 20241229 Had to move these into the if check to go back to the proper location.
    $webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id=' . $id . '&lesson=' . $lessonpo;
    header('Location: ' . $webdir);
}


header('Location: ' . $webdir);

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

$id = optional_param('id', 0, PARAM_INT); // Course_module ID.
$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
// If re is set we remove an exercise.
// if rl is set we remove a lesson and all its exercises.
$exerciseid = optional_param('re', '', PARAM_TEXT);
$lessonid = optional_param('rl', '', PARAM_TEXT);
// Added cmid so can exit back to MooTyper activity we came from.
$cmid = optional_param('cmid', '0', PARAM_INT); // Course Module ID.

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

// 20240617 COde to delete a lesson and all of the exercises in it.
// Needs more work to force grade removal first.
// Needs to check mootyper_grades for the mootyper in use.
// if ($lessonid) {
// $DB->delete_records('mootyper_lessons', ['id' => $lessonid]);
// $DB->delete_records('mootyper_exercises', ['lesson' => $lessonid]);
// }

if ($exerciseid) {
    $lessonpo = optional_param('lesson', '', PARAM_INT);
    // Get all the other exercises in this lesson so we can fix the snumbers.
    $exes = lessons::get_exercises_by_lesson($lessonpo);
    // Initialize a counter to use for snumber replacements.
    $count = 0;
    // Adjust the snumber for each of the remaining exercises.
    foreach ($exes as $exe) {
        $count++;
        // Replace the current snumber of this exercise with the current count.
        $exe['snumber'] = $count + 0;

        $orphanedgrades = $DB->get_records('mootyper_grades', ['exercise' => $exe['id'] = $exerciseid]);

        foreach ($orphanedgrades as $orphanedgrade) {
            $mootyper = $DB->get_record('mootyper', ['id' => $orphanedgrade->mootyper]);
            $attempt = $DB->get_records('mootyper_attempts',
                [
                    'id' => $orphanedgrade->attemptid,
                    'mootyperid' => $orphanedgrade->mootyper,
                ]
            );
            $checks = $DB->get_record('mootyper_checks', ['id' => $attemptid]);
        }
        // At this point we need to look for orphaned mdl_mootyper_grades that refer to the exercise we just deleted!
        // If we find any, we need to delete them!
    }
}

$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
header('Location: '.$webdir);

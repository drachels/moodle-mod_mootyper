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

if ($exerciseid) {
    $lessonpo = optional_param('lesson', '', PARAM_INT);
    $DB->delete_records('mootyper_exercises', ['id' => $exerciseid]);
    // Trigger module exercise_deleted event.
    $params = [
        'objectid' => $course->id,
        'context' => $context,
        'other' => [
            'lesson' => $lessonpo,
            'exercise' => $exerciseid,
        ],
    ];
    $event = exercise_deleted::create($params);
    $event->trigger();
} else if ($lessonid) {
    $DB->delete_records('mootyper_exercises', ['lesson' => $lessonid]);
    $DB->delete_records('mootyper_lessons', ['id' => $lessonid]);
    $lessonpo = 0;
    // Trigger module lesson_deleted event.
    $params = [
        'objectid' => $course->id,
        'context' => $context,
        'other' => $lessonid,
    ];
    $event = lesson_deleted::create($params);
    $event->trigger();
}
// 20200224 Variable $cid not needed after changes to id. Delete after more testing.
// Out of sequence lesson delete seems to have made follow on exercise appear as part
// of wrong lesson!
// Lesson contained three exercises. I deleted number 2, and then number 3 appeared as part
// of another lesson, lesson aaaa(121).
// Later - seems to work okay now.
//$cid = optional_param('id', 0, PARAM_INT);
$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
header('Location: '.$webdir);

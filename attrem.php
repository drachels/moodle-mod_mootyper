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
 * This file is used to remove the results of a student attempt.
 *
 * This sub-module is called from gview.php, (View All Grades),
 * or from owngrades.php, (View my grades).
 * Currently it does include a, Confirm... check before it removes.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\owngrades_deleted;
use mod_mootyper\event\grade_deleted;

require(__DIR__ . '/../../config.php');

global $DB;

// NOTE!!!! I think completion stuff is not needed here as the lib.php mootyper_update_grades
// function always gets invoked after a grade delete.

$mid = optional_param('m_id', 0, PARAM_INT);  // MooTyper id (mdl_mootyper).
$cid = optional_param('c_id', 0, PARAM_INT);  // Course module id (mdl_course_modules).
$context = optional_param('context', 0, PARAM_INT);  // MooTyper id (mdl_mootyper).
$gradeid = optional_param('g', 0, PARAM_INT);
$mtmode = optional_param('mtmode', 0, PARAM_INT);

$mootyper = $DB->get_record('mootyper', ['id' => $mid], '*', MUST_EXIST);
$course = $mootyper->course;
$cm = get_coursemodule_from_instance('mootyper', $mootyper->id, $course, false, MUST_EXIST);

$context = context_module::instance($cm->id);
require_login($course, true, $cm);


if (isset($gradeid)) {
    $dbgrade = $DB->get_record('mootyper_grades', ['id' => $gradeid]);
    // Changed from attempt_id to attemptid 20180129.
    $DB->delete_records('mootyper_attempts', ['id' => $dbgrade->attemptid]);
    $DB->delete_records('mootyper_grades', ['id' => $dbgrade->id]);

    // 20200808 Delete ratings too.
    require_once($CFG->dirroot.'/rating/lib.php');
    $delopt = new stdClass;
    $delopt->contextid = $context->id;
    $delopt->component = 'mod_mootyper';
    $delopt->ratingarea = 'exercises';
    $delopt->itemid = $dbgrade->id;
    $rm = new rating_manager();
    $rm->delete_ratings($delopt);

    // 20230517 Added to recalculate grades when admin, teacher, or user deletes one of the entries.
    mootyper_update_grades($mootyper, $dbgrade->userid);
}

// Return to the View my grades or View all grades page.
if ($mtmode == 2) {
    // 20230517 Added to recalculate grades when user deletes one of their entries.
    // mootyper_update_grades($mootyper, $dbgrade->userid);

    // Trigger owngrades_deleted event for mode 2 only if on the, View own grades, page.
    $params = [
        'objectid' => $mootyper->id,
        'context' => $context,
        'other' => [
            'exercise' => $dbgrade->exercise,
            'mode' => $mootyper->isexam,
        ],
    ];
    $event = owngrades_deleted::create($params);
    $event->trigger();
    $webdir = $CFG->wwwroot . '/mod/mootyper/owngrades.php?id='.$cid.'&n='.$mid;
} else {
    // Trigger grade_deleted event for mode 0, 1, or 2 if on the, View all grades, page.
    $params = [
        'objectid' => $mootyper->id,
        'context' => $context,
        'other' => [
            'exercise' => $dbgrade->exercise,
            'mode' => $mootyper->isexam,
        ],
        'relateduserid' => $dbgrade->userid,
    ];
    $event = grade_deleted::create($params);
    $event->trigger();
    $webdir = $CFG->wwwroot . '/mod/mootyper/gview.php?id='.$cid.'&n='.$mid;
}
header('Location: '.$webdir);

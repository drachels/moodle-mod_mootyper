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
 * This file adds grade and performance info to mdl_mootyper_grades after an exercise.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\exercise_completed;
use mod_mootyper\event\exam_completed;
use mod_mootyper\event\lesson_completed;
use mod_mootyper\local\results;

// Changed to this format 20190301.
require(__DIR__ . '/../../config.php');
// 20200808 Added for integration with Moodle rating/grades.
require_once(__DIR__ . '/lib.php');

global $CFG, $DB;
    require_once($CFG->libdir.'/completionlib.php');

$cmid = optional_param('cmid', 0, PARAM_INT); // Course_module ID.
$lsnname = optional_param('lsnname', '', PARAM_RAW); // MooTyper lesson name.
$exercisename = optional_param('exercisename', 0, PARAM_INT); // MooTyper exercise name (It is just a number.).
$mtmode = optional_param('mtmode', 0, PARAM_INT); // MooTyper activity mode. 0 = Lesson, 1 = Exam, 2 = Practice.
$count = optional_param('count', 0, PARAM_INT); // Number of exercises in this lesson.

if ($cmid) {
    $cm = get_coursemodule_from_id('mootyper', $cmid, 0, false, MUST_EXIST);
    $courseid = $cm->course;
    $context = context_module::instance($cm->id);
}

require_login(0, true, null, false);

// Set pass flag to control background color when viewing grades.
// Check to see if accuracy was good enough to pass.
if (optional_param('rpAccInput', '', PARAM_FLOAT) >= optional_param('rpGoal', '', PARAM_FLOAT)) {
    $passfield = 1;
} else {
    $passfield = 0;
}

// Check to see if wpm rate was good enough to pass.
if (($passfield == 1) && (optional_param('rpWpmInput', '', PARAM_FLOAT) >= optional_param('rpWPM', '', PARAM_FLOAT))) {
    $passfield = 1;
} else {
    $passfield = 0;
}

// Need to add some code here to generate the $record->grade entry based on whether the grade is
// based on both precision and wpm, just precision, or just wpm.
$record = new stdClass();
$record->mootyper = optional_param('rpSityperId', '', PARAM_INT);
$record->userid = optional_param('rpUser', '', PARAM_INT);
// 20200915 Changed from float to int.
$record->grade = optional_param('rpAccInput', '', PARAM_INT);
$record->mistakes = optional_param('rpMistakesInput', '', PARAM_INT);
$record->timeinseconds = optional_param('rpTimeInput', '', PARAM_INT);
$record->hitsperminute = optional_param('rpSpeedInput', '', PARAM_FLOAT);
$record->fullhits = optional_param('rpFullHits', '', PARAM_INT);
$record->precisionfield = optional_param('rpAccInput', '', PARAM_FLOAT);
$record->timetaken = time();
$record->exercise = optional_param('rpExercise', '', PARAM_INT);
$record->pass = $passfield;
$record->attemptid = optional_param('rpAttId', '', PARAM_INT);
$record->wpm = (max(0, optional_param('rpWpmInput', '', PARAM_FLOAT)));
$record->mistakedetails = optional_param('rpMistakeDetailsInput', '', PARAM_CLEAN);
// 20200111 Check to see if there were no mistakes made and change undefined to nomistakes string.
if (stripos($record->mistakedetails, "undefined") !== false) {
    $record->mistakedetails = get_string('nomistakes', 'mootyper');
}

// 20200808 Added code for using MooTyper exercise grades as Moodle Ratings.
$mootyper = $DB->get_record('mootyper', ['id' => $record->mootyper], '*', MUST_EXIST);

// 20230102 Update $record->grade and $record->mistakedetails as needed to get the correct grade or rating.
if (($mootyper->requiredgoal == 0) && ($mootyper->requiredwpm > 0)) {
    // Results for WPM only.
    // This gives incorrect results as it does not take into account the scale value!
    $record->grade = (min($mootyper->scale, ($mootyper->scale * ((max(0, optional_param('rpWpmInput', '', PARAM_FLOAT)))
                     / $mootyper->requiredwpm))));
} else if (($mootyper->requiredgoal > 0) && ($mootyper->requiredwpm > 0)) {
    // Results for both goal and wpm.
    $halfscale = $mootyper->scale / 2;
    $record->grade = (min(100, ($halfscale * (optional_param('rpAccInput', '', PARAM_FLOAT) / 100))
                     + min($halfscale, ($halfscale * ((max(0, optional_param('rpWpmInput', '', PARAM_FLOAT)))
                     / $mootyper->requiredwpm)))));
} else if (($mootyper->requiredgoal > 0) && ($mootyper->requiredwpm == 0)) {
    // Results for goal only.
    $record->grade = (min(100, ($mootyper->scale * (optional_param('rpAccInput', '', PARAM_FLOAT) / 100))));


} else if (($mootyper->requiredgoal == 0) && ($mootyper->requiredwpm == 0)) {
    // Results for no goal and no wpm.
    $record->grade = null;
}
// 20230103 Set decimal to two places.
$record->grade = number_format($record->grade, 2);
// 20230103 Add goal and wpm info to the mistake details.
$record->mistakedetails .= get_string('reqgoalwpm', 'mootyper',
                           ['goal' => $mootyper->requiredgoal,
                           'wpm' => $mootyper->requiredwpm,
                           'currentresult' => $record->grade, ]);

$DB->insert_record('mootyper_grades', $record, false);

// 20200808 Need id of the record we just inserted.
$rec = results::get_grade_entry($mootyper->id, $record->userid, $record->exercise, $record->timetaken);

// 20200808 Make grade entry depending on whether grade or rating.
if ($mootyper->assessed) {
    // 20200808 Need code to place the exercise grade into the rating table.
    $ratingoptions = new stdClass();
    $ratingoptions->contextid = \context_module::instance($cm->id)->id;
    $ratingoptions->component = 'mod_mootyper';
    $ratingoptions->ratingarea = 'exercises';
    $ratingoptions->itemid = $rec->id;
    $ratingoptions->scaleid = $mootyper->scale;
    $ratingoptions->rating = number_format($record->grade, 0);
    $ratingoptions->userid = $record->userid;
    $ratingoptions->timecreated = $record->timetaken;
    $ratingoptions->timemodified = $record->timetaken;
    // 20200808 Place latest exercise grade into the mdl_rating table.
    $DB->insert_record('rating', $ratingoptions, false);
    // 20200808 Update entry in Moodle Grades.
    mootyper_update_grades($mootyper, $record->userid);

} else {
    // Otherwise, place a whole grade into the mdl_grade_items table.
    mootyper_update_grades($mootyper);
}

// 20191129 Added trigger for exercise_completed event.
// 20191201 Added modification to also trigger exam_completed event.
$params = [
    'objectid' => $cmid,
    'context' => $context,
    'other' => [
        'exercise' => $record->exercise,
        'lessonname' => $lsnname,
        'activity' => $cm->name,
    ],
];
// If exam or just an exercise is completed, log the appropriate event.
if ($mtmode === 1) {
    $event = exam_completed::create($params);
    // 20230910 If an exam is completed, so is completionexercises and and lesson.
    // Hmm, setting these to 1 here might be an error. Might be that I just need
    // to initiate completion for the current user.
    $mootyper->completionexercise <= 1;
    $mootyper->completionlesson <= 1;
    // ...$mootyper->completionprecision <= 1;.
    // ...$mootyper->completionwpm <= 1;.
    // ...$mootyper->completionpass <= 1;.

} else {
    $event = exercise_completed::create($params);
}
$event->trigger();



// Added 20191203 If all the exercises in a lesson are complete, trigger lesson_completed event, too.
// 20240119 If all the exercises in a lesson are complete for this user, trigger completion
// for completionexercise, completionlesson, completionprecision, completionwpm, and completionmootypergrade.
if (!($mtmode === 1) && ($exercisename === $count)) {
    $params = [
        'objectid' => $cmid,
        'context' => $context,
        'other' => [
            'exercise' => $record->exercise,
            'lessonname' => $lsnname,
            'activity' => $cm->name,
        ],
    ];

    // 20240120 Added as it was needed by $completion.
    $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

    // 20240119 Added new completion code.
    $completion = new completion_info($course);
    if ($completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC &&
        ($mootyper->completionexercise
            || $mootyper->completionlesson
            || $mootyper->completionprecision
            || $mootyper->completionwpm
            || $mootyper->completionmootypergrade
        )) {
        $completion->update_state($cm, COMPLETION_COMPLETE, $mootyper->id);
    }


    // 20230910 If all the exercises are completed, so is completionexercise and and completionlesson.
    $mootyper->completionexercise <= $count;
    $mootyper->completionlesson <= 1;
    $event = lesson_completed::create($params);
    $event->trigger();
}

$webdir = $CFG->wwwroot . '/mod/mootyper/view.php?n='.$record->mootyper;
echo '<script type="text/javascript">window.location="'.$webdir.'";</script>';

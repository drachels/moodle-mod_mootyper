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

global $CFG, $DB, $USER;
    require_once($CFG->libdir . '/completionlib.php');

$cmid = required_param('cmid', PARAM_INT); // Course_module ID.
$lsnname = optional_param('lsnname', '', PARAM_RAW); // MooTyper lesson name.
$exercisename = optional_param('exercisename', 0, PARAM_INT); // MooTyper exercise name (It is just a number.).
$mtmode = optional_param('mtmode', 0, PARAM_INT); // MooTyper activity mode. 0 = Lesson, 1 = Exam, 2 = Practice.
$count = optional_param('count', 0, PARAM_INT); // Number of exercises in this lesson.

$cm = get_coursemodule_from_id('mootyper', $cmid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);
$courseid = $course->id;
/** @var context $context */
$context = context_module::instance($cm->id);
if (!($context instanceof context_module)) {
    throw new moodle_exception('invalidaccess', 'mootyper', '', null);
}

require_login($course, true, $cm);
require_sesskey();
require_capability('mod/mootyper:view', $context);

$record = new stdClass();
$record->mootyper = (int)$mootyper->id;
$record->userid = $USER->id;
$record->timetaken = time();
$record->exercise = optional_param('rpExercise', '', PARAM_INT);
$record->pass = 0;
$record->attemptid = optional_param('rpAttId', '', PARAM_INT);
$record->mistakedetails = optional_param('rpMistakeDetailsInput', '', PARAM_CLEAN);
// 20200111 Check to see if there were no mistakes made and change undefined to nomistakes string.
if (stripos($record->mistakedetails, "undefined") !== false) {
    $record->mistakedetails = get_string('nomistakes', 'mootyper');
}

// Require a valid, finalized attempt before accepting and storing grade data.
if (empty($record->attemptid)) {
    $returnurl = new moodle_url('/mod/mootyper/view.php', ['n' => $record->mootyper]);
    redirect($returnurl, get_string('attemptsubmitblockedmissing', 'mootyper'));
}

$attempt = $DB->get_record('mootyper_attempts', [
    'id' => $record->attemptid,
    'mootyperid' => $record->mootyper,
    'userid' => $record->userid,
]);

if (!$attempt) {
    $returnurl = new moodle_url('/mod/mootyper/view.php', ['n' => $record->mootyper]);
    redirect($returnurl, get_string('attemptsubmitblockedmissing', 'mootyper'));
}

if (!empty($attempt->inprogress)) {
    $returnurl = new moodle_url('/mod/mootyper/view.php', ['n' => $record->mootyper]);
    redirect($returnurl, get_string('attemptsubmitblockedinprogress', 'mootyper'));
}

if ($DB->record_exists('mootyper_grades', ['attemptid' => $attempt->id])) {
    throw new moodle_exception('invalidaccess', 'mootyper', '', null);
}

if ((string)$mootyper->isexam === '1') {
    if ((int)$record->exercise !== (int)$mootyper->exercise) {
        throw new moodle_exception('invalidaccess', 'mootyper', '', null);
    }
    $exercise = get_exercise_record($mootyper->exercise);
} else {
    $exercise = $DB->get_record('mootyper_exercises', [
        'id' => $record->exercise,
        'lesson' => $mootyper->lesson,
    ]);
    if (!$exercise) {
        throw new moodle_exception('invalidaccess', 'mootyper', '', null);
    }
}

$checks = $DB->get_records('mootyper_checks', ['attemptid' => $attempt->id], 'checktime DESC, id DESC', '*', 0, 1);
$latestcheck = reset($checks);
if (!$latestcheck) {
    throw new moodle_exception('invalidaccess', 'mootyper', '', null);
}

$record->mistakes = max(0, (int)$latestcheck->mistakes);
$record->fullhits = max($record->mistakes, (int)$latestcheck->hits);
$correcthits = max(0, $record->fullhits - $record->mistakes);
$record->precisionfield = $record->fullhits > 0
    ? ($correcthits * 100 / $record->fullhits)
    : 0;
$elapsedserver = max(1, (int)$latestcheck->checktime - (int)$attempt->timetaken);
$record->timeinseconds = $elapsedserver;
$speedhits = !empty($mootyper->continuoustype) ? $correcthits : $record->fullhits;
$record->hitsperminute = ($speedhits * 60) / $elapsedserver;
$record->wpm = max(0, ($record->hitsperminute / 5) - ($record->mistakes / ($elapsedserver / 60)));

if (
    $record->precisionfield >= (int)$mootyper->requiredgoal
    && $record->wpm >= (int)$mootyper->requiredwpm
) {
    $record->pass = 1;
}

// Enforce time limit on the server to prevent client-side timer bypass.
if (!empty($mootyper->timelimit)) {
    $timelimitseconds = (int)$mootyper->timelimit * 60;
    $graceseconds = 5;
    if ($elapsedserver > ($timelimitseconds + $graceseconds)) {
        $returnurl = new moodle_url('/mod/mootyper/view.php', ['n' => $record->mootyper]);
        $message = get_string('timesubmissionrejected', 'mootyper', [
            'limit' => $timelimitseconds,
            'elapsed' => $elapsedserver,
        ]);
        redirect($returnurl, $message);
    }

    // Keep persisted elapsed time aligned with the configured hard limit.
    $record->timeinseconds = min($elapsedserver, $timelimitseconds);
}

// Calculate the stored grade from server-recorded attempt counters.
if (($mootyper->requiredgoal == 0) && ($mootyper->requiredwpm > 0)) {
    $record->grade = min($mootyper->scale, $mootyper->scale * ($record->wpm / $mootyper->requiredwpm));
} else if (($mootyper->requiredgoal > 0) && ($mootyper->requiredwpm > 0)) {
    // Results for both goal and wpm.
    $halfscale = $mootyper->scale / 2;
    $record->grade = min(100, ($halfscale * ($record->precisionfield / 100))
                     + min($halfscale, ($halfscale * ($record->wpm / $mootyper->requiredwpm))));
} else if (($mootyper->requiredgoal > 0) && ($mootyper->requiredwpm == 0)) {
    // Results for goal only.
    $record->grade = min(100, $mootyper->scale * ($record->precisionfield / 100));
} else if (($mootyper->requiredgoal == 0) && ($mootyper->requiredwpm == 0)) {
    // Results for no goal and no wpm.
    $record->grade = null;
}
// 20230103 Set decimal to two places. 20240902 change from number_format to sprintf due to deprecation.
$record->grade = sprintf('%0.2f', $record->grade);
// 20230103 Add goal and wpm info to the mistake details.
$record->mistakedetails .= get_string(
    'reqgoalwpm',
    'mootyper',
    ['goal' => $mootyper->requiredgoal,
                           'wpm' => $mootyper->requiredwpm,
    'currentresult' => $record->grade,
    ]
);

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
    // 20240902 Added, $record->userid.
    mootyper_update_grades($mootyper, $record->userid);
}
$DB->delete_records('mootyper_checks', ['attemptid' => $attempt->id]);

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
    if (
        $completion->is_enabled($cm) == COMPLETION_TRACKING_AUTOMATIC &&
        ($mootyper->completionexercise ||
            $mootyper->completionlesson ||
            $mootyper->completionprecision ||
            $mootyper->completionwpm ||
            $mootyper->completionmootypergrade
        )
    ) {
        $completion->update_state($cm, COMPLETION_COMPLETE, $mootyper->id);
    }


    // 20230910 If all the exercises are completed, so is completionexercise and and completionlesson.
    $mootyper->completionexercise <= $count;
    $mootyper->completionlesson <= 1;
    $event = lesson_completed::create($params);
    $event->trigger();
}

$webdir = $CFG->wwwroot . '/mod/mootyper/view.php?n=' . $record->mootyper;
echo '<script type="text/javascript">window.location="' . $webdir . '";</script>';

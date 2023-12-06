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
 * This file handles mootyper exercises.
 *
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\course_exercises_viewed;
use mod_mootyper\event\invalid_access_attempt;
use mod_mootyper\local\lessons;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $DB, $OUTPUT, $PAGE, $USER;

// 20200224 Switched $id to Course_module ID vice course ID.
$id = optional_param('id', 0, PARAM_INT); // Course module ID.
// Changed cmid to course id.
$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

require_login($course, true);
$context = context_module::instance($cm->id);

// 20200706 Added to prevent student direct URL access attempts.
if (!(has_capability('mod/mootyper:aftersetup', $context))) {
    // Trigger invalid_access_attempt with redirect to course page.
    $params = [
        'objectid' => $id,
        'context' => $context,
        'other' => [
            'file' => 'exercises.php',
        ],
    ];
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('../../course/view.php?id='.$course->id, get_string('invalidaccessexp', 'mootyper'));
}

$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance] , '*', MUST_EXIST);
$lessonpo = optional_param('lesson', 0, PARAM_INT);

// Trigger module exercise_viewed event.
$params = [
    'objectid' => $course->id,
    'context' => $context,
    'other' => $lessonpo,
];
$event = course_exercises_viewed::create($params);
$event->trigger();

// Print the page header.
$PAGE->set_url('/mod/mootyper/exercises.php', ['id' => $id]);
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));
$PAGE->set_pagelayout('standard');

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();

// 20200625 Changed from using site default color to current Mootyper
// keyboard background color.
$color3 = $mootyper->keybdbgc;

echo '<div align="center" style="font-size:1em;
     font-weight:bold;background: '.$color3.';
     border:2px solid black;
     -webkit-border-radius:16px;
     -moz-border-radius:16px;border-radius:16px;">';

$lessons = lessons::get_mootyperlessons($USER->id, $id);

if ($lessonpo == 0 && count($lessons) > 0) {
    $lessonpo = $lessons[0]['id'];
}

// Create and show a drop down selector for the lesson name to show.
echo '<form method="post">';
echo '<br>'.get_string('excategory', 'mootyper').': <select onchange="this.form.submit()" name="lesson">';

$selectedlessonindex = 0;

for ($ij = 0; $ij < count($lessons); $ij++) {
    if ($lessons[$ij]['id'] == $lessonpo) {
        echo '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        $selectedlessonindex = $ij;
    } else {
        echo '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
    }
}

echo '</select>';

// Preload not editable by me message for the current user.
$jlink = get_string('noteditablebyme', 'mootyper');
if (lessons::is_editable_by_me($USER->id, $id, $lessonpo)) {
    $deleteurl = $CFG->wwwroot . '/mod/mootyper/erem.php?id='.$id.'&rl='.$lessons[$selectedlessonindex]['id'];
    $exporturl = $CFG->wwwroot . '/mod/mootyper/lsnexport.php?id='.$course->id.'&lsn='.$lessons[$selectedlessonindex]['id'];
    echo '<br>';
    echo '</form><br>';
    // Build a link with course id and lsn options to use when exporting the current Lesson.
    $jlink = '<a onclick="return confirm(\''.get_string('exportconfirm', 'mootyper')
        .$lessons[$selectedlessonindex]['lessonname'].'\')" href="lsnexport.php?id='
        .$course->id.'&lsn='.$lessons[$selectedlessonindex]['id']
        .'"><img src="pix/download_all.svg" alt='
        .get_string('export', 'mootyper').'> '
        .$lessons[$selectedlessonindex]['lessonname'].'';

    // Build a link to let teachers add a new exercise to the Lesson currently being viewed.
    $jlnk3 = $CFG->wwwroot . '/mod/mootyper/eins.php?id='.$id.'&lesson='.$lessonpo;

    // 20200628 Following variable is temporary for development.
    $vis = $DB->get_record("mootyper_lessons", ['id' => $lessonpo]);
    // 20220125 Added words instead of numbers, to the button.
    $visible = get_string('vaccess'.$vis->visible, 'mootyper');
    $editable = get_string('eaccess'.$vis->editable, 'mootyper');

    // 20200614 Added a button for, Add a new exercise to the Lesson currently being viewed.
    // 20220125 Modified the info on the buttons, words instead of numbers.
    echo ' <a onclick="return confirm(\''.get_string('eaddnewex', 'mootyper').$lessonpo.
        '\')" href="'.$jlnk3.'" class="btn btn-secondary" style="border-radius: 8px">'
        .get_string('eaddnewex', 'mootyper').$lessonpo
        .', '.get_string('authorid', 'mootyper').': '.$vis->authorid
        .', '.get_string('visibility', 'mootyper').': '.$visible
        .', '.get_string('editable', 'mootyper').': '.$editable.'</a>';
} else {
    echo '</form><br>';
}

// Create border and alignment styles for use as needed.
$style1 = 'style="border-color: #000000; border-style: solid; border-width: 3px; text-align: center;"';
$style2 = 'style="border-color: #000000; border-style: solid; border-width: 3px; text-align: left;"';
// Print header row for Lesson table currently being viewed.
echo '<table><tr><td '.$style1.'>'.get_string('ename', 'mootyper').'</td>
                 <td '.$style1.'>'.$lessons[$selectedlessonindex]['lessonname'].'</td>
                 <td '.$style1.'>'.$jlink.'</td></tr>';

// Print table row for each of the exercises in the lesson currently being viewed.
$exercises = $DB->get_records("mootyper_exercises", ['lesson' => $lessonpo]);
// 20230110 PostgreSQL gets sloppy with the order, but this seems to fix it.
sort($exercises);
foreach ($exercises as $ex) {
    // 20210326 Shorten displayed exercisename as well as text to type.
    $strtocut = $ex->texttotype;
    $strtocut = str_replace('\n', '<br>', $strtocut);
    if (strlen($strtocut) > 65) {
        $strtocut = substr($strtocut, 0, 65).'...';
    }
    $exnametocut = $ex->exercisename;
    $exnametocut = str_replace('\n', '<br>', $exnametocut);
    if (strlen($exnametocut) > 20) {
        $exnametocut = substr($exnametocut, 0, 20).'...';
    }
    // If user can edit, create a delete link to the current exercise.
    $jlink1 = '<a onclick="return confirm(\''.get_string('deleteexconfirm', 'mootyper')
              .$lessons[$selectedlessonindex]['lessonname']
              .'\')" href="erem.php?id='.$id
              .'&re='.$ex->id
              .'&lesson='.$lessonpo.'"><img src="pix/delete.png" alt="'
              .get_string('delete', 'mootyper').'"></a>';

    // If user can edit, create an edit link to the current exercise.
    // Use activity ID so we can exit back to the MooTyper activity we came from.
    $jlink2 = '<a href="eedit.php?id='.$id
              .'&ex='.$ex->id
              .'&lesson='.$mootyper->lesson
              .'"><img src="pix/edit.png" alt='
              .get_string('eeditlabel', 'mootyper').'></a>';

    // 20210326 Shorten displayed exercisename as well as text to type.
    echo '<tr><td '.$style2.'>'.$exnametocut.'</td><td '.$style2.'>'.$strtocut.'</td>';

    // If the user can edit or delete this lesson and its exercises, then add edit and delete tools.
    if (lessons::is_editable_by_me($USER->id, $id, $lessonpo)) {
        echo '<td '.$style1.'>'.$jlink2.' | '.$jlink1.'</td>';
    } else {
        // If the user can not edit or delete, show an empty space.
        echo '<td '.$style2.'></td>';
    }
    echo '</tr>';
}
echo '</table>';

$url = $CFG->wwwroot . '/mod/mootyper/view.php?id='.$id;
$deleteurl = $CFG->wwwroot.'/mod/mootyper/erem.php?id='.$id.'&rl='.$lessons[$selectedlessonindex]['id'];
$exporturl = $CFG->wwwroot.'/mod/mootyper/lsnexport.php?id='.$course->id.'&lsn='.$lessons[$selectedlessonindex]['id'];

// 20200414 Added a, Return, button. 20200428 added round corners.
echo '<br><a href="'.$url.'" class="btn btn-primary" style="border-radius: 8px">'
    .get_string('returnto', 'mootyper', $mootyper->name).'</a>';

// 20200614 Added an, Add new lesson with exercise, button.
$jlnk2 = $CFG->wwwroot.'/mod/mootyper/eins.php?id='.$id.'&course='.$course->id;
echo ' <a onclick="return confirm(\''.get_string('eaddnew', 'mootyper').
    '\')" href="'.$jlnk2.'" class="btn btn-secondary" style="border-radius: 8px">'
    .get_string('eaddnew', 'mootyper').'</a>';

// 20200613 Added an, Export, lesson button.
echo ' <a onclick="return confirm(\''.get_string('exportconfirm', 'mootyper').$lessons[$selectedlessonindex]['lessonname'].
    '\')"  href="'.$exporturl.'" class="btn btn-info" style="border-radius: 8px">'
    .get_string('export', 'mootyper').' - '.$lessons[$selectedlessonindex]['lessonname'].'</a>';

if (lessons::is_editable_by_me($USER->id, $id, $lessonpo)) {
    // 20200613 Added a, Delete all from, this lesson button.
    echo ' <a onclick="return confirm(\''.get_string('deletelsnconfirm', 'mootyper').$lessons[$selectedlessonindex]['lessonname'].
        '\')" href="'.$deleteurl.'" class="btn btn-danger" style="border-radius: 8px">'
        .get_string('deleteall', 'mootyper').' - '. $lessons[$selectedlessonindex]['lessonname'].'</a>'.'</form>';
} else {
    echo '</form>';
}

echo '</div>';

echo $OUTPUT->footer();

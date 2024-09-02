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
 * This is is used to add a new lesson/category.
 *
 * Settings for category name, visibility and who can edit the exercise, are included.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 **/

use mod_mootyper\event\exercise_added;
use mod_mootyper\event\invalid_access_attempt;
use mod_mootyper\local\lessons;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $USER, $DB;

// 20200224 Switched $id to Course_module ID vice course ID.
$id = optional_param('id', 0, PARAM_INT); // Course ID.
$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
// If lessonid is available, put in lsnnamepos, otherwise leave it blank.
$lsnnamepo = optional_param('lesson', '', PARAM_TEXT);
// If lessonid is available, put it in lsnnamepos, otherwise enter a -1.
// Flag for new lesson name is -1.
$lessonpo = optional_param('lesson', -1, PARAM_INT);

require_login($course, true);

$context = context_module::instance($cm->id);

$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);

// 20200706 Added to prevent student direct URL access attempts.
if (!(has_capability('mod/mootyper:aftersetup', $context))) {
    // Trigger invalid_access_attempt with redirect to course page.
    $params = [
        'objectid' => $id,
        'context' => $context,
        'other' => [
            'file' => 'eins.php',
        ],
    ];
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('../../course/view.php?id='.$course->id, get_string('invalidaccessexp', 'mootyper'));
}

// Check to see if Confirm button is clicked and returning 'Confirm' to trigger insert record.
$param1 = optional_param('button', '', PARAM_TEXT);

// DB insert.
if (isset($param1) && get_string('fconfirm', 'mootyper') == $param1 ) {

    $texttotypeepo = optional_param('texttotype', '', PARAM_RAW);

    // If new lesson, add lesson details to mdl_mootyper_lessons table.
    if ($lessonpo == -1) {
        $lsnnamepo = optional_param('lessonname', '', PARAM_TEXT);
        $lsnrecord = new stdClass();
        $lsnrecord->lessonname = $lsnnamepo;
        $lsnrecord->visible = optional_param('visible', '', PARAM_TEXT);
        $lsnrecord->editable = optional_param('editable', '', PARAM_TEXT);
        $lsnrecord->authorid = $USER->id;
        $lsnrecord->courseid = $course->id;
        $lessonid = $DB->insert_record('mootyper_lessons', $lsnrecord, true);
    } else {
        $lessonid = $lessonpo;
    }
    // Get next sequence number for this exercise.

    $snum = lessons::get_new_snumber($lessonid);

    // Add exercise to mdl_mootyper_exercises table.
    // 20210325 Added a new exercise_name to snum.
    $exercisenamepo = optional_param('exercise_name', '', PARAM_TEXT);
    $erecord = new stdClass();
    // 20210325 Add new exercise name to the auto sname and add to the table.
    $erecord->exercisename = "".$snum.' '.$exercisenamepo;
    $erecord->snumber = $snum;
    $erecord->lesson = $lessonid;
    $erecord->texttotype = str_replace("\r\n", '\n', $texttotypeepo);

    $DB->insert_record('mootyper_exercises', $erecord, false);
    $webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonid;

    // Get the lesson name to use in the exercise_added event.
    if ($lsnnamepo) {
        $lesson = $lsnnamepo;
    } else {
        $lesson = $lessonpo;
    }

    echo '<script type="text/javascript">window.location="'.$webdir.'";</script>';

    // Trigger module exercise_added event.
    $params = [
        'objectid' => $course->id,
        'context' => $context,
        'other' => [
            'lesson' => $lesson,
            'exercisename' => $erecord->exercisename,
        ],
    ];
    $event = exercise_added::create($params);
    $event->trigger();

}
// Get all the default configuration settings for MooTyper.
$moocfg = get_config('mod_mootyper');

// Check to see if configuration for MooTyper defaulteditalign is set.
if (isset($moocfg->defaulteditalign)) {
    // Current MooTyper edittalign is set so use it.
    $editalign = optional_param('editalign', $moocfg->defaulteditalign, PARAM_INT);
    $align = $editalign;
} else {
    // Current MooTyper edittalign is NOT set so set it to left.
    $editalign = optional_param('editalign', 0, PARAM_INT);
    $align = $editalign;
}

// Print the page header.
$PAGE->set_url('/mod/mootyper/eins.php', ['id' => $course->id]);
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));
$PAGE->set_pagelayout('standard');
// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();

// Get all MooTyper lessons.
$lessonsg = lessons::get_typerlessons();
if (has_capability('mod/mootyper:editall', context_course::instance($course->id))) {
    // If manager or admin get all lessons.
    $lessons = $lessonsg;
} else {
    $lessons = [];
    // If a teacher, only get ones they can edit.
    foreach ($lessonsg as $lsng) {
        if (lessons::is_editable_by_me($USER->id, $id, $lsng['id'])) {
            $lessons[] = $lsng;
        }
    }
}

// 20200625 Get the current MooTyper keyboard background default color for our page background here.
$color3 = $mootyper->keybdbgc;
// Add colored background with border.
echo '<div align="center" style="font-size:1em;
    font-weight:bold;background: '.$color3.';
    border:2px solid black;
    -webkit-border-radius:16px;
    -moz-border-radius:16px;border-radius:16px;">'.'<br>';

// Start page form and add lesson name selector.
echo '<form method="POST">';
// Add the label, Exercise will be a part of lesson.
echo get_string('fnewexercise', 'mootyper').'&nbsp;';
echo '<select onchange="this.form.submit()" name="lesson">';
// Add, create new lesson, as first selection on the lesson name selector list.
echo '<option value="-1">'.get_string('fnewlesson', 'mootyper').'</option>';
// Check the selection list of lesson names for match to lessonpo.
for ($ij = 0; $ij < count($lessons); $ij++) {
    if ($lessons[$ij]['id'] == $lessonpo) {
        // If selection matches lessonpo (id), show it in the text box.
        echo '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
    } else {
        // Add the rest of the lesson names to the list.
        echo '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
    }
}
echo '</select>';

// 20210325 Prevent error before exercise name is actually created.
if ($snum = null) {
    $num = ' ';
}

// Add lesson name, visibility, and editing options if this is a new lesson.
if ($lessonpo == -1) {

    // Set up place to enter new lesson name.
    echo '<br><br>'.get_string('lsnname', 'mootyper').': <input type="text" name="lessonname" id="lessonname">
        <span style="color:red;" id="namemsg"></span>';

    // 20210325 Prevent error before exercise name is actually created.
    if ($snum = null) {
        $num = ' ';
    }
    // 20210325 Set up place to enter new exercise name.
    echo '<br><br>'.get_string('exercise_name', 'mootyper').': '
        .$snum.' <input type="text" name="exercise_name" id="exercise_name"></span>';

    // Set up visibility selector options for this new lesson.
    echo '<br><br>'.get_string('visibility', 'mootyper').': <select name="visible">';
    echo '<option value="2">'.get_string('vaccess2', 'mootyper').'</option>';
    echo '<option value="1">'.get_string('vaccess1', 'mootyper').'</option>';
    if (is_siteadmin()) {
        echo '<option value="0">'.get_string('vaccess0', 'mootyper').'</option>';
    }
    echo '</select>';

    // Set up editing selector options for this new lesson.
    echo '<br><br>'.get_string('editable', 'mootyper').': <select name="editable">';
    echo '<option value="2">'.get_string('eaccess2', 'mootyper').'</option>';
    echo '<option value="1">'.get_string('eaccess1', 'mootyper').'</option>';
    if (is_siteadmin()) {
        echo '<option value="0">'.get_string('eaccess0', 'mootyper').'</option>';
    }
    echo '</select>';
} else {
    // 20210325 Set up place to enter new exercise name.
    echo '<br><br>'.get_string('exercise_name', 'mootyper').': '
        .$snum.' <input type="text" name="exercise_name" id="exercise_name"></span>';
}

// Get our alignment strings and add a selector for text alignment.
$aligns = [get_string('defaulttextalign_left', 'mod_mootyper'),
            get_string('defaulttextalign_center', 'mod_mootyper'),
            get_string('defaulttextalign_right', 'mod_mootyper'), ];
echo '<br><br><span id="editalign" class="">'.get_string('defaulttextalign', 'mootyper').': ';
echo '<select onchange="this.form.submit()" name="editalign">';

// This will loop through ALL three alignments and show current alignment setting.
foreach ($aligns as $akey => $aval) {
    // The first if is executed ONLY when, when defaulttextalign matches one of the alignments
    // and it will then show that alignment in the selector.
    if ($akey == $editalign) {
        echo '<option value="'.$akey.'" selected="true">'.$aval.'</option>';
        $align = $aval;
    } else {
        // This part of the if is reached the most and its when an alignment
        // is is not the one selected.
        echo '<option value="'.$akey.'">'.$aval.'</option>';
    }
}

echo '</select></span>'.get_string('defaulttextalign_warning', 'mootyper');

?>

<script type="text/javascript">
function isLetter(str) {
    // var pattern = /[a-z,ก-๛,а-я,א-ת,ㄱ-ㅣ,äáàâãčćçëéèêđïîíöôóõüúùûµšžº¡ñ]/i;
    var pattern = /[!-ﻼ]/i;
    return str.length === 1 && str.match(pattern);
}
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

var ok = true;

function clClick() {
    var exercise_text = document.getElementById("texttotype").value;
    var allowed_chars = ['\\', '~', '!', '@', '#', '$', '%', '^', '&', '(', ')',
                         '*', '_', '+', ':', ';', '"', '{', '}', '>', '<', '?', '\'',
                         '-', '/', '=', '.', ',', ' ', '|', '¡', '`', 'ç', 'ñ', 'º',
                         '¿', 'ª', '·', '\n', '\r', '\r\n', '\n\r', ']', '[', '¬',
                         '´', '`', '§', '°', '€', '¦', '¢', '£', '₢', '¹', '²', '³',
                         '¨', 'Ё', '№', 'ё', 'ë', 'ù', 'µ', 'ï','÷', '×', 'ł', 'Ł', 'ß',
                         '¤', '«', '»', '₪', '־', 'װ', 'ױ', 'ײ', 'ˇ', '½'];
    var shown_text = "";
    ok = true;
    for(var i=0; i<exercise_text.length; i++) {
        if(!isLetter(exercise_text[i]) && !isNumber(exercise_text[i]) && allowed_chars.indexOf(exercise_text[i]) == -1) {
            shown_text += '<span style="color: red;">'+exercise_text[i]+'</span>';
            ok = false;
        }
        else
            shown_text += exercise_text[i];
    }
    if(!ok) {
        document.getElementById('text_holder_span').innerHTML = shown_text;
        return false;
    }
    if(document.getElementById("lessonname").value == "") {
        document.getElementById("namemsg").innerHTML = '<?php echo get_string('reqfield', 'mootyper'); // phpcs:ignore?>';
        return false;
    }
    else
        return true;
}
</script>
<?php // phpcs:ignore
// Create a link back to where we came from in case we want to cancel.
if ($lessonpo == -1) {
    // 20200414 On cancel, go back to the MT activity if invoked from there.
    $url = $CFG->wwwroot . '/mod/mootyper/view.php?id='.$id;
} else {
    // Use this link for new lesson with exercise.
    $url = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
}

// Add a text area for adding the text of the exercise.
echo '<br><span id="text_holder_span" class=""></span><br>'.get_string('fexercise', 'mootyper').':<br>'
    .'<textarea rows="4" cols="60" name="texttotype" id="texttotype" style="text-align:'.$align.'"></textarea>';

// Add a confirm and cancel button.
echo '<br><br><input class="btn btn-primary" style="border-radius: 8px"
     name="button" onClick="return clClick()" type="submit" value="'
    .get_string('fconfirm', 'mootyper').'"> <a href="'
    .$url.'" class="btn btn-secondary"  style="border-radius: 8px">'
    .get_string('cancel', 'mootyper').'</a>'.'</form>';

echo '</div>';

echo $OUTPUT->footer();

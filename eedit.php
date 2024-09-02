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
 * This file is used to edit exercise content. Called from exercises.php.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_mootyper\event\exercise_edited;
use mod_mootyper\event\invalid_access_attempt;

// Changed to this format 20190301.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $DB, $OUTPUT, $PAGE, $USER;

$id = optional_param('id', 0, PARAM_INT); // Course ID.
$ex = optional_param('ex', 0, PARAM_INT); // Id of exercise to edit.
$lsnnamepo = '';
$lessonpo = '';

$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

// Looks like these two variables are exact duplicates. Maybe need to combine?
$exercise = $DB->get_record('mootyper_exercises', ['id' => $ex], '*', MUST_EXIST);
$rcrd = $DB->get_record('mootyper_exercises', ['id' => $ex], '*', MUST_EXIST);

// Get the id of the lesson name from the current exercise, and then use it to get the lesson name.
$lesson = $DB->get_record('mootyper_exercises', ['id' => $ex], 'lesson', MUST_EXIST);
$lessonname = $DB->get_record('mootyper_lessons', ['id' => $lesson->lesson], 'lessonname', MUST_EXIST);
$actuallesson = $DB->get_record('mootyper_lessons', ['id' => $lesson->lesson]);

// This context->id will be used for the path to file storage.
$context = context_module::instance($cm->id);
$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance] , '*', MUST_EXIST);

require_login($course, true, $cm);

// 20200706 Added to prevent student direct URL access attempts.
if (!(has_capability('mod/mootyper:aftersetup', $context))) {
    // Trigger invalid_access_attempt with redirect to course page.
    $params = [
        'objectid' => $id,
        'context' => $context,
        'other' => [
            'file' => 'eedit.php',
        ],
    ];
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('../../course/view.php?id='.$course->id, get_string('invalidaccessexp', 'mootyper'));
}

// Check to see if Confirm button is clicked and returning 'Confirm' to trigger update record.
$param1 = optional_param('button', '', PARAM_TEXT);

if (isset($param1) && get_string('fconfirm', 'mootyper') == $param1 ) {
    // 20210325 Added as part of capability to edit lesson and exercise names.
    $newlessonname = optional_param('lesson_name', '', PARAM_RAW);
    $newexercisename = optional_param('exercise_name', '', PARAM_RAW);
    $newtext = optional_param('texttotype', '', PARAM_RAW);

    // Future development.
    $newdictation = optional_param('dictationdata', '', PARAM_RAW);

    $rcrd = $DB->get_record('mootyper_exercises', ['id' => $ex], '*', MUST_EXIST);

    $updr = new stdClass();
    $updr->id = $rcrd->id;
    $updr->texttotype = str_replace("\r\n", '\n', $newtext);
    // 20210327 Added as part of new capability to edit lesson and exercise names.
    $updr->exercisename = str_replace("\r\n", '\n', $newexercisename);
    $lessonname->id = $rcrd->lesson;
    $lessonname->lessonname = str_replace("\r\n", '\n', $newlessonname);

    $updr->lesson = $rcrd->lesson;
    $updr->snumber = $rcrd->snumber;
    $DB->update_record('mootyper_exercises', $updr);
    // 20210327 Added as part of new capability to edit lesson and exercise names.
    $DB->update_record('mootyper_lessons', $lessonname);

    // Trigger module exercise_edited event.
    $params = [
        'objectid' => $course->id,
        'context' => $context,
        'other' => [
            'lesson' => $updr->lesson,
            'exercise' => $updr->exercisename,
        ],
    ];
    $event = exercise_edited::create($params);
    $event->trigger();

    $webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$rcrd->lesson;
    echo '<script type="text/javascript">window.location="'.$webdir.'";</script>';

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

$PAGE->set_url('/mod/mootyper/eedit.php', ['id' => $course->id, 'ex' => $ex]);
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));
$PAGE->set_cacheable(false);
echo $OUTPUT->header();
$exercisetoedit = $DB->get_record('mootyper_exercises',
    ['id' => $ex], 'id, texttotype, exercisename, lesson, snumber', MUST_EXIST);

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
        if((exercise_text[i] != '\n' && exercise_text[i] != '\r\n'
                                     && exercise_text[i] != '\n\r'
                                     && exercise_text[i] != '\r')
                                     && !isLetter(exercise_text[i])
                                     && !isNumber(exercise_text[i])
                                     && allowed_chars.indexOf(exercise_text[i]) == -1) {
            shown_text += '<span style="color: red;">'+exercise_text[i]+'</span>';
            ok = false;
            /*var text = (i-3)+'-'+exercise_text[i-3]+"\n";
            text += (i-2)+'-'+exercise_text[i-2]+"\n";
            text += (i-1)+'-'+exercise_text[i-1]+"\n";
            text += i+'-'+exercise_text[i]+"\n";
            text += (i+1)+'-'+exercise_text[i+1]+"\n";
            text += (i+2)+'-'+exercise_text[i+2];
            alert(text);*/
        }
        else
            shown_text += exercise_text[i];
    }
    if(!ok) {
        document.getElementById('text_holder_span').innerHTML = shown_text;
        return false;
    }
    else 
        return true;
}

</script>
<?php // phpcs:ignore
// 20200625 Get the current MooTyper keyboard background default color for our page background here.
$color3 = $mootyper->keybdbgc;
echo '<div align="center" style="font-size:1em;
     font-weight:bold;background: '.$color3.';
     border:2px solid black;
     -webkit-border-radius:16px;
     -moz-border-radius:16px;border-radius:16px;">';

echo '<form method="POST">';

// 20210327 Add a text area for editing the name of the lesson.
echo '<label>'.get_string('lsnname', 'mootyper')
    .' = <input type="text" name="lesson_name" value="'
    .str_replace('\n', "&#10;", $lessonname->lessonname)
    .'"</label><br />';

// 20210327 Add a text area for editing the name of the exercise.
echo '<label>'.get_string('exercise_name', 'mootyper')
    .' = <input type="text" name="exercise_name" value="'
    .str_replace('\n', "&#10;", $exercisetoedit->exercisename)
    .'"</label><br />';

// Get our alignment strings and add a selector for text alignment.
$aligns = [
    get_string('defaulttextalign_left', 'mod_mootyper'),
    get_string('defaulttextalign_center', 'mod_mootyper'),
    get_string('defaulttextalign_right', 'mod_mootyper'),
];

echo '<span id="editalign" class="">'.get_string('defaulttextalign', 'mootyper').': ';
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

echo '</select></span>';
// Create a link back to where we came from in case we want to cancel.
$url = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$rcrd->lesson;

// Add a text area for editing the text of the exercise.
echo '<span id="text_holder_span" class=""></span><br>'
    .get_string('fexercise', 'mootyper')
    .':<br>'
    .'<textarea name="texttotype" id="texttotype" rows="3" cols="60" style="text-align:'
    .$align.'">'
    .str_replace('\n', "&#10;", $exercisetoedit->texttotype)
    .'</textarea>';

$editor = editors_get_preferred_editor(FORMAT_HTML);
$attobuttons = 'files = recordrtc'. PHP_EOL .'list = unorderedlist, orderedlist'. PHP_EOL .'other = html, htmlplus';

$editor->use_editor('exercisetoedit',
    [
        'context' => $context,
        'enable_filemanagement' => true,
        'autosave' => true,
        'atto:toolbar' => $attobuttons,
    ],
    [
         'return_types' => "FILE_EXTERNAL",
    ]
);

// 20220723 Add some details so we know more about this lesson and it's exercises.
$lessonauthor = $DB->get_record("user", ['id' => $actuallesson->authorid]);
$coursename = $DB->get_record("course", ['id' => $actuallesson->courseid]);
// Add a number 0, 1, or 2 to get the correct string to use.
$visible = get_string('vaccess'.$actuallesson->visible, 'mootyper');
$editable = get_string('eaccess'.$actuallesson->editable, 'mootyper');
echo '<br>'.get_string('authorid', 'mootyper').': '.$actuallesson->authorid.', '
           .$lessonauthor->firstname.' '.$lessonauthor->lastname.'<br>';
echo get_string('visibility', 'mootyper').': '.$visible.', ';
echo get_string('editable', 'mootyper').': '.$editable.'<br>';
// 20220729 Added check for missing courseid and and name that would indicate the lesson came with MooTyper.
if ((!$actuallesson->courseid) || (!$coursename->fullname)) {
    echo get_string('lessonincluded', 'mootyper');

} else {
    echo get_string('createdin', 'mootyper').$actuallesson->courseid.', '.$coursename->fullname;
}

echo '</select></span><br>';

// Add a confirm and cancel button.
echo '<input class="btn btn-primary"
    style="border-radius: 8px"
    name="button"
    onClick="return clClick()"
    type="submit" value="'
    .get_string('fconfirm', 'mootyper')
    .'"> <a href="'
    .$url
    .'" class="btn btn-secondary"  style="border-radius: 8px">'
    .get_string('cancel', 'mootyper')
    .'</a>'.'</form>';

echo '</div>';

echo $OUTPUT->footer();

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
 * Shows the setup of a particular instance of mootyper.
 *
 * You can set whether this instance is a lesson or exam,
 * select the exercise category, required precision, as
 * well as which keyboard to show and use.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use \mod_mootyper\event\invalid_access_attempt;
use \mod_mootyper\local\keyboards;
use \mod_mootyper\local\lessons;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or.
$n = optional_param('n', 0, PARAM_INT);  // Mootyper instance ID - it should be named as the first character of the module.

if ($id) {
    $cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $mootyper = $DB->get_record('mootyper', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $mootyper = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $mootyper->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
} else {
    throw new moodle_exception(get_string('mootypererror', 'mootyper'));
}
require_login($course, true, $cm);
$context = context_module::instance($cm->id);

// 20200706 Added to prevent student direct URL access attempts.
if (!(has_capability('mod/mootyper:aftersetup', $context))) {
    // Trigger invalid_access_attempt with redirect to course page.
    $params = array(
        'objectid' => $id,
        'context' => $context,
        'other' => array(
            'file' => 'mod_setup.php'
        )
    );
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('../../course/view.php?id='.$course->id, get_string('invalidaccessexp', 'mootyper'));
}

// Get the default config for MooTyper.
$moocfg = get_config('mod_mootyper');
// Enable-disable flag.
$epo = optional_param('e', 0, PARAM_INT);
// Get settings for current mootyper activity.
// During initial actvity setup, there is no mode set, so get the site default.
$modepo = optional_param('mode', $moocfg->isexam, PARAM_INT);
$lessonpo = optional_param('lesson', $mootyper->lesson, PARAM_INT);
$exercisepo = optional_param('exercise', $mootyper->exercise, PARAM_INT);
$textalign = optional_param('textalign', $mootyper->textalign, PARAM_INT);
$showkeyboardpo = optional_param('showkeyboard', "off", PARAM_CLEAN);
$continuoustypepo = optional_param('continuoustype', "off", PARAM_CLEAN);
$countmistypedspacespo = optional_param('countmistypedspaces', "off", PARAM_CLEAN);
$countmistakespo = optional_param('countmistakes', "off", PARAM_CLEAN);
$statscolor = optional_param('statscolor', $mootyper->statsbgc, PARAM_CLEAN);
$keytoptextc = optional_param('keytoptextc', $mootyper->keytoptextc, PARAM_CLEAN);
$keytopcolor = optional_param('keytopcolor', $mootyper->keytopbgc, PARAM_CLEAN);
$backgroundcolor = optional_param('backgroundcolor', $mootyper->keybdbgc, PARAM_CLEAN);
$cursorcolor = optional_param('cursorcolor', $mootyper->cursorcolor, PARAM_CLEAN);
$textbgc = optional_param('textbgc', $mootyper->textbgc, PARAM_CLEAN);
$texterrorcolor = optional_param('texterrorcolor', $mootyper->texterrorcolor, PARAM_CLEAN);

// Check to see if current MooTyper mode is already set.
if (!($mootyper->isexam == null)) {
    // Current MooTyper mode is set, so use it.
    $modepo = optional_param('mode', $mootyper->isexam, PARAM_INT);
}

// Check to see if a timelimit is set.
if ($mootyper->timelimit == null || is_null($mootyper->timelimit)) {
    // Current MooTyper timelimit is empty so set it to the site default.
    $dftlimit = $moocfg->defaulttimelimit;
} else {
    // Otherwise use current MooTyper timelimit.
    $dftlimit = $mootyper->timelimit;
}
$timelimitpo = optional_param('timelimit', $dftlimit, PARAM_INT); // Display with default or current setting.

// Check to see if current MooTyper precision goal is empty.
if ($mootyper->requiredgoal == null || is_null($mootyper->requiredgoal)) {
    // Current MooTyper precision goal is empty so set it to the site default.
    $dfgoal = $moocfg->defaultprecision;
} else {
    // Otherwise use current MooTyper precision requiredgoal.
    $dfgoal = $mootyper->requiredgoal;
}
$goalpo = optional_param('requiredgoal', $dfgoal, PARAM_INT); // Display with default or current setting.

// Check to see if current MooTyper requiredwpm is empty.
if ($mootyper->requiredwpm == null || is_null($mootyper->requiredwpm)) {
    // Current MooTyper requiredwpm is empty so set it to the site default.
    $dfwpm = $moocfg->defaultwpm;
} else {
    // Otherwise use current MooTyper requiredwpm.
    $dfwpm = $mootyper->requiredwpm;
}
$wpmpo = optional_param('requiredwpm', $dfwpm, PARAM_INT); // Display with default or current setting.

// Check to see if current MooTyper activity textalign is empty.
if ($mootyper->textalign == null || is_null($mootyper->textalign)) {
    // Current MooTyper textalign is empty so set it to the site default.
    $dftextalign = $moocfg->defaulttextalign;
} else {
    // Otherwise use current MooTyper textalign.
    $dftextalign = $mootyper->textalign;
}
$textalignpo = optional_param('textalign', $dftextalign, PARAM_INT); // Display with default or current setting.

// Check to see if current MooTyper continuoustype is empty.
if ($mootyper->continuoustype == null || is_null($mootyper->continuoustype)) {
    $dfct = "off";
} else if ($mootyper->continuoustype) {
    // Otherwise use current MooTyper continuoustype.
    $dfct = "on";
} else {
    $dfct = "off";
}
$continuoustypepo = optional_param('continuoustype', $dfct, PARAM_CLEAN); // Display with default or current setting.

// Check to see if the current MooTyper countmistypedspaces is empty.
if ($mootyper->countmistypedspaces == null || is_null($mootyper->countmistypedspaces)) {
    // Current MooTyper continuoustype is empty so set it to the site default.
    $dfms = "off";
} else if ($mootyper->countmistypedspaces) {
    // Otherwise use current MooTyper countmistypedspaces.
    $dfms = "on";
} else {
    $dfms = "off";
}
$countmistypedspacespo = optional_param('countmistypedspaces', $dfms, PARAM_CLEAN); // Display with default or current setting.

// Check to see if the current MooTyper countmistakes is empty.
if ($mootyper->countmistakes == null || is_null($mootyper->countmistakes)) {
    // Current MooTyper continuoustype is empty so set it to the site default.
    $dfcm = "on";
} else if ($mootyper->countmistakes) {
    // Otherwise use current MooTyper countmistakes.
    $dfcm = "on";
} else {
    $dfcm = "off";
}
$countmistakespo = optional_param('countmistakes', $dfcm, PARAM_CLEAN); // Display with default or current setting.

// Check to see if current MooTyper showkeyboard is empty.
if ($mootyper->showkeyboard == null || is_null($mootyper->showkeyboard)) {
    $dfkb = "off";
} else if ($mootyper->showkeyboard) {
    // Otherwise use current MooTyper showkeyboard.
    $dfkb = "on";
} else {
    $dfkb = "off";
}
$showkeyboardpo = optional_param('showkeyboard', $dfkb, PARAM_CLEAN);

// Check to see current MooTyper layout is empty.
$mootyperconfig = get_config('mod_mootyper');
if ($mootyper->layout == null || is_null($mootyper->layout)) {
    // Current MooTyper layout is empty so set it to the site default.
    if (isset($mootyperconfig->defaultlayout_filenamewithoutfiletype) &&
            keyboards::is_layout_installed("$mootyperconfig->defaultlayout_filenamewithoutfiletype")) {
        //$dfly = keyboards::get_layout_id($mootyperconfig->defaultlayout_filenamewithoutfiletype);
        $dfly = $mootyperconfig->defaultlayout_filenamewithoutfiletype;
    } else {
        $dfly = $moocfg->defaultlayout;
    }
} else {
    // Otherwise use current MooTyper layout.
    $dfly = $mootyper->layout;
}
$layoutpo = optional_param('layout', $dfly, PARAM_INT); // Display with default or current setting.

// Check to see if current MooTyper statsbgc is empty.
if ($mootyper->statsbgc == null || is_null($mootyper->statsbgc)) {
    // Current MooTyper statsbgc is empty so set it to the sites statscolor default.
    $dfstatscolor = $moocfg->statscolor;
} else {
    $dfstatscolor = $mootyper->statsbgc;
}
$statscolorpo = optional_param('statsbgc', $dfstatscolor, PARAM_CLEAN); // Display with default or current setting.

// Check to see if current MooTyper keytoptextc is empty.
if ($mootyper->keytoptextc == null || is_null($mootyper->keytoptextc)) {
    // Current MooTyper keytoptextc is empty so set it to the sites normalkeytoptextc default.
    $dfkeytoptextc = $moocfg->normalkeytoptextc;
} else {
    $dfkeytoptextc = $mootyper->keytoptextc;
}
$keytoptextcpo = optional_param('keytoptextc', $dfkeytoptextc, PARAM_CLEAN); // Display with default or current setting.

// Check to see if current MooTyper keytopbgc is empty.
if ($mootyper->keytopbgc == null || is_null($mootyper->keytopbgc)) {
    // Current MooTyper keytopbgc is empty so set it to the sites normalkeytops default.
    $dfkeytopcolor = $moocfg->normalkeytops;
} else {
    $dfkeytopcolor = $mootyper->keytopbgc;
}
$keytopcolorpo = optional_param('keytopbgc', $dfkeytopcolor, PARAM_CLEAN); // Display with default or current setting.

// Check to see if current MooTyper keybdbgc is empty.
if ($mootyper->keybdbgc == null || is_null($mootyper->keybdbgc)) {
    // Current MooTyper keybdbgc is empty so set it to the sites keyboardbgc default.
    $dfbackgroundcolor = $moocfg->keyboardbgc;
} else {
    $dfbackgroundcolor = $mootyper->keybdbgc;
}
$backgroundcolorpo = optional_param('keybdbgc', $dfbackgroundcolor, PARAM_CLEAN); // Display with default or current setting.

// Check to see if current MooTyper cursorcolor is empty.
if ($mootyper->cursorcolor == null || is_null($mootyper->cursorcolor)) {
    // Current MooTyper cursorcolor is empty so set it to the sites cursorcolor default.
    $dfcursorcolor = $moocfg->cursorcolor;
} else {
    $dfcursorcolor = $mootyper->cursorcolor;
}
$cursorcolorpo = optional_param('cursorcolor', $dfcursorcolor, PARAM_CLEAN); // Display with default or current setting.

// Check to see if current MooTyper textbgc is empty.
if ($mootyper->textbgc == null || is_null($mootyper->textbgc)) {
    // Current MooTyper textbgc is empty so set it to the sites textbgc default.
    $dftextbgc = $moocfg->textbgc;
} else {
    $dftextbgc = $mootyper->textbgc;
}
$textbgcpo = optional_param('textbgc', $dftextbgc, PARAM_CLEAN); // Display with default or current setting.

// Check to see if current MooTyper text error color is empty.
if ($mootyper->texterrorcolor == null || is_null($mootyper->texterrorcolor)) {
    // Current MooTyper texterrorcolor is empty so set it to the sites texterrorcolor default.
    $dftexterrorcolor = $moocfg->texterrorcolor;
} else {
    $dftexterrorcolor = $mootyper->texterrorcolor;
}
$texterrorcolorpo = optional_param('texterrorcolor', $dftexterrorcolor, PARAM_CLEAN); // Display with default or current setting.

// Check to see if Confirm button is clicked and returning 'Confirm' to trigger insert record.
$param1 = optional_param('button', '', PARAM_TEXT);
if (isset($param1) && get_string('fconfirm', 'mootyper') == $param1) {
    $modepo = optional_param('mode', null, PARAM_INT);
    $lessonpo = optional_param('lesson', null, PARAM_INT);
    $timelimitpo = optional_param('timelimit', null, PARAM_INT);
    $goalpo = optional_param('requiredgoal', null, PARAM_INT);
    $wpmpo = optional_param('requiredwpm', null, PARAM_INT);
    $textalignpo = optional_param('textalign', $dftextalign, PARAM_INT); // Display with default or current setting.
    $continuoustypepo = optional_param('continuoustype', null, PARAM_CLEAN);
    $countmistypedspacespo = optional_param('countmistypedspaces', null, PARAM_CLEAN);
    $countmistakespo = optional_param('countmistakes', null, PARAM_CLEAN);
    $showkeyboardpo = optional_param('showkeyboard', null, PARAM_CLEAN);
    $layoutpo = optional_param('layout', 0, PARAM_INT);
    $statscolorpo = optional_param('statsbgc', $dfstatscolor, PARAM_CLEAN);
    $keytoptextcpo = optional_param('keytoptextc', $dfkeytoptextc, PARAM_CLEAN);
    $keytopcolorpo = optional_param('keytopbgc', $dfkeytopcolor, PARAM_CLEAN);
    $backgroundcolorpo = optional_param('keybdbgc', $dfbackgroundcolor, PARAM_CLEAN);
    $cursorcolorpo = optional_param('cursorcolor', $dfcursorcolor, PARAM_CLEAN);
    $textbgcpo = optional_param('textbgc', $dftextbgc, PARAM_CLEAN);
    $texterrorcolorpo = optional_param('texterrorcolor', $dftexterrorcolor, PARAM_CLEAN);

    global $DB, $CFG;
    // Update all the settings for this MooTyper instance when Confirm is clicked.
    $mootyper = $DB->get_record('mootyper', array('id' => $n), '*', MUST_EXIST);
    $mootyper->lesson = $lessonpo;
    $mootyper->isexam = $modepo;
    if ($modepo == 1) {
        $exercisepo = optional_param('exercise', null, PARAM_INT);
        $mootyper->exercise = $exercisepo;
    }
    $mootyper->timelimit = $timelimitpo;
    $mootyper->requiredgoal = $goalpo;
    $mootyper->requiredwpm = $wpmpo;
    $mootyper->textalign = $textalignpo;
    $mootyper->continuoustype = $continuoustypepo == 'on';
    $mootyper->countmistypedspaces = $countmistypedspacespo == 'on';
    $mootyper->countmistakes = $countmistakespo == 'on';
    $mootyper->showkeyboard = $showkeyboardpo == 'on';
    $mootyper->layout = $layoutpo;
    $mootyper->statsbgc = $statscolorpo;
    $mootyper->keytoptextc = $keytoptextcpo;
    $mootyper->keytopbgc = $keytopcolorpo;
    $mootyper->keybdbgc = $backgroundcolorpo;
    $mootyper->cursorcolor = $cursorcolorpo;
    $mootyper->textbgc = $textbgcpo;
    $mootyper->texterrorcolor = $texterrorcolorpo;
    $DB->update_record('mootyper', $mootyper);
    header('Location: '.$CFG->wwwroot.'/mod/mootyper/view.php?n='.$n);
}

// Print the page header.
$PAGE->set_url('/mod/mootyper/mod_setup.php', array('id' => $cm->id));
$PAGE->set_title(format_string($mootyper->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);
$PAGE->set_cacheable(false);

echo $OUTPUT->header();
echo $OUTPUT->heading($mootyper->name);
$htmlout = '';
$htmlout .= '<div align="center" style="font-size:1em;
    font-weight:bold;background: '.$backgroundcolorpo.';
    border:2px solid black;
    -webkit-border-radius:16px;
    -moz-border-radius:16px;
    border-radius:16px;">';
$htmlout .= '<script type="text/javascript">
function removeAtts() {
    document.getElementById("lesson").disabled = false;
    document.getElementById("mode").disabled = false;
    document.getElementById("exercise").disabled = false;
}
</script>';
$htmlout .= '<form id="setupform" onsubmit="removeAtts();" name="setupform" method="POST">';

// 20200801 Admin can change Mode and Lesson name at any time. All others just during first setup.
if (is_siteadmin()) {
    $disselect = '';
} else {
    $disselect = $epo == 1 ? ' disabled="disabled"' : '';
}

$htmlout .= '<table><tr><td>'
    .get_string('fmode', 'mootyper').'</td><td><select'
    .$disselect.' onchange="this.form.submit()" name="mode" id="mode">';

// 20160322 Modified to use only improved function get_mootyperlessons.
if (has_capability('mod/mootyper:aftersetup', context_module::instance($cm->id))) {
    $lessons = lessons::get_mootyperlessons($USER->id, $course->id);
}

// Start building htmlout for this page based on exam or lesson exercise. Mode = 0 is Lesson.
if ($modepo == 0 || is_null($modepo)) { // If mode is 0, this is a lesson?
    $htmlout .= '<option selected="true" value="0">'.get_string('sflesson', 'mootyper').'</option>
        <option value="1">'.get_string('isexamtext', 'mootyper').'</option>
        <option value="2">'.get_string('practice', 'mootyper').'</option>';
    $htmlout .= '</select></td></tr><tr><td>';
    $htmlout .= get_string('excategory', 'mootyper')
        .'</td><td><select'.$disselect
        .' onchange="this.form.submit()" id="lesson" name="lesson">';

    for ($ij = 0; $ij < count($lessons); $ij++) {
        if ($lessons[$ij]['id'] == $lessonpo) {
            $htmlout .= '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        } else {
            $htmlout .= '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        }
    }
} else if ($modepo == 1) { // Or, if mode is 1, this is an exam?
    $htmlout .= '<option value="0">'.get_string('sflesson', 'mootyper').'</option>
        <option value="1" selected="true">'.get_string('isexamtext', 'mootyper').'</option>
        <option value="2">'.get_string('practice', 'mootyper').'</option>';
    $htmlout .= '</select></td></tr><tr><td>';
    $htmlout .= get_string('flesson', 'mootyper').'</td><td><select'
        .$disselect.' onchange="this.form.submit()" id="lesson" name="lesson">';
    for ($ij = 0; $ij < count($lessons); $ij++) {
        if ($lessons[$ij]['id'] == $lessonpo) {
            $htmlout .= '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        } else {
            $htmlout .= '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        }
    }
    $htmlout .= '</select></td></tr>';
    $exercises = lessons::get_exercises_by_lesson($lessonpo);
    $htmlout .= '<tr><td>'.get_string('fexercise', 'mootyper').'</td><td><select'.$disselect.' name="exercise" id="exercise">';
    for ($ik = 0; $ik < count($exercises); $ik++) {
        if ($exercises[$ik]['id'] == $exercisepo) {
            $htmlout .= '<option selected="true" value="'.$exercises[$ik]['id'].'">'.$exercises[$ik]['exercisename'].'</option>';
        } else {
            $htmlout .= '<option value="'.$exercises[$ik]['id'].'">'.$exercises[$ik]['exercisename'].'</option>';
        }
    }
} else if ($modepo == 2) { // If mode is 2, this is a practice lesson?
    $htmlout .= '<option selected="true" value="0">'.get_string('sflesson', 'mootyper').'</option>
        <option value="1">'.get_string('isexamtext', 'mootyper').'</option>
        <option value="2" selected="true">'.get_string('practice', 'mootyper').'</option>';
    $htmlout .= '</select></td></tr><tr><td>';
    $htmlout .= get_string('excategory', 'mootyper').'</td><td><select'
        .$disselect.' onchange="this.form.submit()" id="lesson" name="lesson">';
    for ($ij = 0; $ij < count($lessons); $ij++) {
        if ($lessons[$ij]['id'] == $lessonpo) {
            $htmlout .= '<option selected="true" value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        } else {
            $htmlout .= '<option value="'.$lessons[$ij]['id'].'">'.$lessons[$ij]['lessonname'].'</option>';
        }
    }
}
// Add the time limit.
$htmlout .= '</select></td></tr><tr><td>'
    .get_string('timelimit', 'mootyper').'</td><td><input value="'
    .$timelimitpo.'" style="width: 35px;" type="text" name="timelimit"> '
    .get_string('minutes').' </td></tr>';
// Add the required precision percentage.
$htmlout .= '</select></td></tr><tr><td>'
    .get_string('requiredgoal', 'mootyper').'</td><td><input value="'
    .$goalpo.'" style="width: 35px;" type="text" name="requiredgoal"> % </td></tr>';
// Add the required speed in words per minute.
$htmlout .= '</select></td></tr><tr><td>'
    .get_string('requiredwpm', 'mootyper').'</td><td><input value="'
    .$wpmpo.'" style="width: 35px;" type="text" name="requiredwpm"></td></tr>';

// Add a selector for text alignment.
$aligns = array(get_string('defaulttextalign_left', 'mod_mootyper'),
              get_string('defaulttextalign_center', 'mod_mootyper'),
              get_string('defaulttextalign_right', 'mod_mootyper'));
$defaulttextalign = $moocfg->defaulttextalign;

$htmlout .= '<tr><td>'.get_string('defaulttextalign', 'mootyper').'</td><td><select name="textalign">';
// Get the ID and name of each alignment in the DB.
foreach ($aligns as $akey => $aval) {
    // The first if is executed ONLY when, Text alignment, is
    // clicked to change alignment.
    if ($akey == $defaulttextalign) {
        $htmlout .= '<option value="'.$akey.'" selected="true">'.$aval.'</option>';
    } else if ($akey == $textalignpo) {
        // This part of the if is reached when going to setup with an
        // alignment already selected and it is the one already in use.
        $htmlout .= '<option value="'.$akey.'" selected="true">'.$aval.'</option>';
    } else {
        // This part of the if is reached the most and its when an alignment
        // is already selected but it is not the one being selected.
        $htmlout .= '<option value="'.$akey.'">'.$aval.'</option>';
    }
}
$htmlout .= '</select>';

// Need to keep the next line as it is helping get rid of _POST in line 267.
$tempchkkb = optional_param('showkeyboard', 0, PARAM_BOOL);

// Add the check box to enable continuous typing.
$htmlout .= '<tr><td>'.get_string('continuoustype', 'mootyper').'</td><td>';
$continuoustypechecked = $continuoustypepo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$continuoustypechecked.' " name="continuoustype">';

// Add the check box to enable counting mistyped spaces.
$htmlout .= '<tr><td>'.get_string('countmistypedspaces', 'mootyper').'</td><td>';
$countmistypedspaceschecked = $countmistypedspacespo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$countmistypedspaceschecked.' " name="countmistypedspaces">';

// Add the check box to enable counting multiple keystrokes for one error.
$htmlout .= '<tr><td>'.get_string('countmistakes', 'mootyper').'</td><td>';
$countmistakeschecked = $countmistakespo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$countmistakeschecked.' " name="countmistakes">';

// Add the check box for show keyboard.
$htmlout .= '<tr><td>'.get_string('showkeyboard', 'mootyper').'</td><td>';
$showkeyboardchecked = $showkeyboardpo == 'on' ? ' checked="checked"' : '';
$htmlout .= '<input type="checkbox"'.$showkeyboardchecked.' " name="showkeyboard">';

// Add the dropdown slector for keyboard layouts.
$layouts = keyboards::get_keyboard_layouts_db();
$deflayout = $moocfg->defaultlayout;
$htmlout .= '<tr><td>'.get_string('layout', 'mootyper').'</td><td><select name="layout">';
// Get the ID and name of each keyboard layout in the DB.
foreach ($layouts as $lkey => $lval) {
    // The first if is executed ONLY when Showkeyboard is
    // clicked to turn it on or off. It seems to have the
    // the job of selecting our default layout when turned ON.
    if (($tempchkkb) && ($lkey == $deflayout)) {
        $htmlout .= '<option value="'.$lkey.'" selected="true">'.$lval.'</option>';
    } else if ($lkey == $layoutpo) {
        // This part of the if is reached when going to setup with a
        // keyboard layout already selected and it is the one already in use.
        $htmlout .= '<option value="'.$lkey.'" selected="true">'.$lval.'</option>';
    } else {
        // This part of the if is reached the most and its when a keyboard layout
        // is already selected but it is not the one being checked.
        $htmlout .= '<option value="'.$lkey.'">'.$lval.'</option>';
    }
}

// Add input box for statistics background color.
$htmlout .= '</td></tr><tr><td>'.get_string('statsbgc', 'mootyper').'</td><td>';
$htmlout .= '<input value="'.$statscolorpo.'" style="width: 135px;" type="text" name="statsbgc"></td></tr>';

// Add input box for normal keytoptextc color.
$htmlout .= '</td></tr><tr><td>'.get_string('keytoptextc', 'mootyper').'</td><td>';
$htmlout .= '<input value="'.$keytoptextcpo.'" style="width: 135px;" type="text" name="keytoptextc"></td></tr>';

// Add input box for normal keytop color.
$htmlout .= '</td></tr><tr><td>'.get_string('keytopbgc', 'mootyper').'</td><td>';
$htmlout .= '<input value="'.$keytopcolorpo.'" style="width: 135px;" type="text" name="keytopbgc"></td></tr>';

// Add input box for keyboard background color.
$htmlout .= '</td></tr><tr><td>'.get_string('keybdbgc', 'mootyper').'</td><td>';
$htmlout .= '<input value="'.$backgroundcolorpo.'" style="width: 135px;" type="text" name="keybdbgc"></td></tr>';

// Add input box for cursorcolor.
$htmlout .= '</td></tr><tr><td>'.get_string('cursorcolor', 'mootyper').'</td><td>';
$htmlout .= '<input value="'.$cursorcolorpo.'" style="width: 135px;" type="text" name="cursorcolor"></td></tr>';

// Add input box for textbgc.
$htmlout .= '</td></tr><tr><td>'.get_string('textbgc', 'mootyper').'</td><td>';
$htmlout .= '<input value="'.$textbgcpo.'" style="width: 135px;" type="text" name="textbgc"></td></tr>';

// Add input box for texterrorcolor.
$htmlout .= '</td></tr><tr><td>'.get_string('texterrorcolor', 'mootyper').'</td><td>';
$htmlout .= '<input value="'.$texterrorcolorpo.'" style="width: 135px;" type="text" name="texterrorcolor"></td></tr>';

// Finish adding html to our page.
$htmlout .= '</select>';
$htmlout .= '</td></tr>';
$htmlout .= '</table>';
// Change to BS4 style button to fix issue #77 on github.
$htmlout .= '<br><input type="submit" name="button" class="btn btn-primary" style="border-radius: 8px" value="'
    .get_string('fconfirm', 'mootyper').'">';
// Create return URL for use with Cancel button, 12/26/19.
$url = $CFG->wwwroot . '/mod/mootyper/view.php?id='.$cm->id;
$htmlout .= ' <a href="'.$url.'" class="btn btn-secondary" style="border-radius: 8px">'.get_string('cancel', 'mootyper').'</a>';

$htmlout .= '</form>';
$htmlout .= '<br>';

// Finally show the complete page.
echo $htmlout;
// Finish the page by adding a footer.
echo $OUTPUT->footer();

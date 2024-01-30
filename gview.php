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
 * This file displays all the grades of a particular MooTyper Lesson.
 *
 * It is also possible to remove the results of any individual attempt.
 * 20200624 The grades that are visible now depend on the group settings.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_mootyper\event\viewed_all_grades;
use mod_mootyper\event\invalid_access_attempt;
use mod_mootyper\local\lessons;
use mod_mootyper\local\results;

// Changed to this newer format 20190301.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or.
$n = optional_param('n', 0, PARAM_INT);  // Mootyper instance ID - it should be named as the first character of the module.
$se = optional_param('exercise', 0, PARAM_INT);
$md = optional_param('jmode', 0, PARAM_INT);
$us = optional_param('juser', 0, PARAM_INT);
$orderby = optional_param('orderby', -1, PARAM_INT);
$des = optional_param('desc', -1, PARAM_INT);

if ($md == 1) {
    $us = 0;
} else if ($md == 0 || $md == 2) {
    $se = 0;
}
if ($id) {
    $cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);
    $n = $mootyper->id;
} else if ($n) {
    $mootyper = $DB->get_record('mootyper', ['id' => $n], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $mootyper->course], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('mootyper', $mootyper->id, $course->id, false, MUST_EXIST);
    $id = $cm->id; // Since we had ID of 0, we really need Course module ID for cvsexport, so set it.
} else {
    throw new moodle_exception(get_string('mootypererror', 'mootyper'));
}

$lsnname = $DB->get_record('mootyper_lessons', ['id' => $mootyper->lesson], '*', MUST_EXIST);
$mtmode = $mootyper->isexam;
require_login($course, true, $cm);
$context = context_module::instance($cm->id);

// 20200706 Added to prevent student direct URL access attempts.
if (!has_capability('mod/mootyper:viewgrades', context_module::instance($cm->id))) {
    // Trigger invalid_access_attempt with redirect to course page.
    $params = [
        'objectid' => $id,
        'context' => $context,
        'other' => [
            'file' => 'gview.php',
        ],
    ];
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('view.php?id='.$id, get_string('invalidaccessexp', 'mootyper'));
} else {
    // Trigger view all grades event.
    $params = [
            'objectid' => $mootyper->id,
            'context' => $context,
            'other' => [
                'lessonid' => $mootyper->lesson,
                'lessonname' => $lsnname->lessonname,
            ],
        ];
    $event = viewed_all_grades::create($params);
    $event->trigger();
    // The following needs to retrieve keybdbgc for setting this background.
    $color3 = $mootyper->keybdbgc;

    $PAGE->set_url('/mod/mootyper/gview.php', ['id' => $cm->id]);
    $PAGE->set_title(format_string($mootyper->name));
    $PAGE->set_heading(format_string($course->fullname));
    $PAGE->set_context($context);
    $PAGE->set_cacheable(false);
    echo $OUTPUT->header();
    echo '<link rel="stylesheet" type="text/css" href="styles.css">';
    echo $OUTPUT->heading($mootyper->name);
    $temp = '<span class="reportlink"><a href="index.php?id='
        .$course->id.'">'
        .get_string('viewallmootypers', 'mootyper')
        .'</a></span>';
    echo $temp;
    // 20200620 Changed $htmlout's to echo's.
    echo '<div align="center" style="font-size:1em;
        font-weight:bold;background: '.$color3.';
        border:2px solid black;
        -webkit-border-radius:16px;
        -moz-border-radius:16px;
        border-radius:16px;">';

    // Set a heading for the grades table, based on the mode and the lesson/category name.
    switch ($mtmode) {
        case 0:
            echo get_string('fmode', 'mootyper')." = ".get_string('flesson', 'mootyper');
            break;
        case 1:
            echo get_string('fmode', 'mootyper')." = ".get_string('isexamtext', 'mootyper');
            break;
        case 2:
            echo get_string('fmode', 'mootyper')." = ".get_string('practice', 'mootyper');
            break;
        default:
            echo get_string('error', 'moodle');
    }
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('lsnname', 'mootyper')
        ." = ".$lsnname->lessonname;
    echo '<br>'.get_string('timelimit', 'mootyper')
        .' = '.$mootyper->timelimit.':00 '.get_string('min');
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('requiredgoal', 'mootyper')
        .' = '.$mootyper->requiredgoal.'%';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;'.get_string('requiredwpm', 'mootyper')
        .' = '.$mootyper->requiredwpm;
    echo '<br>';
    echo '<table><tr><td>';

    // Print group information (A drop down box will be displayed if the user
    // is a member of more than one group, or has access to all groups).
    echo groups_print_activity_menu($cm, $CFG->wwwroot.'/mod/mootyper/gview.php?id='.$cm->id);
    // 20200620 Check to see if groups are being used here.
    $groupmode = groups_get_activity_groupmode($cm);
    $currentgroup = groups_get_activity_group($cm, true);
    if ($currentgroup) {
        $groups = $currentgroup;
    } else {
        // 20220805 Changed from '' to 0 for PostreSQL.
        $groups = 0;
    }
    echo '</td>';

    echo '<form method="post">';
    // 20190308 Changed the code for mode 1 to use the same code as mode 0 and mode 2.
    echo '<td>'.get_string('gviewmode', 'mootyper').'   ';
    // 20200620 Added class="custom-select singleselect" to look like group selector.
    echo '<select onchange="this.form.submit()" class="custom-select singleselect" name="jmode">
                <option value="0">'.get_string('byuser', 'mootyper').'</option>';
    if ($md == 1) {
        echo '<option value="1" selected="true">'.get_string('bymootyper', 'mootyper').'</option>';
    } else {
        echo '<option value="1">'.get_string('bymootyper', 'mootyper').'</option>';
    }

    echo '</select></td>';

    if ($md == 0 || $md == 1 || $mtmode == 2) {
        $usrs = get_users_of_one_instance($mootyper->id);
        echo '<td>'.get_string('student', 'mootyper').'   </td><td>';
        // 20200620 Added class="custom-select singleselect" to look like group selector.
        echo '<select name="juser" onchange="this.form.submit()" class="custom-select singleselect">';
        echo '<option value="0">'.get_string('allstring', 'mootyper').'</option>';
        // 20200621 Filter Student selector to only show group members when set to visible groups or separate groups.
        if (($usrs != false) && ($groupmode != false)) {
            foreach ($usrs as $x) {
                if (($us == $x->id) && (groups_is_member($groups, $x->id))) {
                    echo '<option value="'.$x->id.'" selected="true">'.$x->firstname.' '.$x->lastname.'</option>';
                } else if (groups_is_member($groups, $x->id)) {
                    echo '<option value="'.$x->id.'">'.$x->firstname.' '.$x->lastname.'</option>';
                }
            }
        }
        // 20200621 Filter Student selector to show everyone when set to no groups.
        if (($usrs != false) && ($groupmode == false)) {
            foreach ($usrs as $x) {
                if ($us == $x->id) {
                    echo '<option value="'.$x->id.'" selected="true">'.$x->firstname.' '.$x->lastname.'</option>';
                } else {
                    echo '<option value="'.$x->id.'">'.$x->firstname.' '.$x->lastname.'</option>';
                }
            }
        }
        echo '</select>';
        echo '</td>';
    }
    // Show the Exercise selector. To work, the View must be set to, by exercise.
    if ($md == 0 || $md == 1 || $mtmode == 2) {
        $exes = lessons::get_exercises_by_lesson($mootyper->lesson);
        echo '<td>'.get_string('fexercise', 'mootyper').'  </td><td>';
        // 20200620 Added class="custom-select singleselect" to look like group selector.
        echo '<select name="exercise" onchange="this.form.submit()" class="custom-select singleselect">';
        echo '<option value="0">'.get_string('allstring', 'mootyper').'</option>';
        foreach ($exes as $x) {
            if ($se == $x['id']) {
                echo '<option value="'.$x['id'].'" selected="true">'.$x['exercisename'].'</option>';
            } else {
                echo '<option value="'.$x['id'].'">'.$x['exercisename'].'</option>';
            }
        }
        echo '</select>';
        echo '</td></tr></table></form>';
    }

    if ($des == -1) {
        $des = 0;
    }
    $grds = get_typer_grades_adv($mootyper->id, $se, $us, $orderby, $des);
    if ($grds != false) {
        if ($des == -1 || $des == 1) {
            $lnkadd = "&desc=0";
        } else {
            $lnkadd = "&desc=1";
        }
        $arrtextadds = [];
        $arrtextadds[2] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[4] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[5] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[6] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[7] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[8] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[9] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[10] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[11] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[12] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[13] = '<span class="arrow-s" style="font-size:1em;"></span>';
        $arrtextadds[$orderby] = $des == -1 || $des == 1 ? '<span class="arrow-s" style="font-size:1em;">
                                 </span>' : '<span class="arrow-n" style="font-size:1em;"></span>';

        echo '<table style="border-style: solid;"><tr>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=2'.$lnkadd.'">'
            .get_string('student', 'mootyper').'</a>'.$arrtextadds[2].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=10'.$lnkadd.'">'
            .get_string('fexercise', 'mootyper').'</a>'.$arrtextadds[10].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=4'.$lnkadd.'">'
            .get_string('vmistakes', 'mootyper').'</a>'.$arrtextadds[4].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=5'.$lnkadd.'">'
            .get_string('timeinseconds', 'mootyper').'</a>'.$arrtextadds[5].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=6'.$lnkadd.'">'
            .get_string('hitsperminute', 'mootyper').'</a>'.$arrtextadds[6].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=7'.$lnkadd.'">'
            .get_string('fullhits', 'mootyper').'</a>'.$arrtextadds[7].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=8'.$lnkadd.'">'
            .get_string('precision', 'mootyper').'</a>'.$arrtextadds[8].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=9'.$lnkadd.'">'
            .get_string('wpm', 'mootyper').'</a>'.$arrtextadds[9].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=12'.$lnkadd.'">'
            .get_string('gradenoun').'</a>'.$arrtextadds[12].'</td>
            <td><a href="?id='.$id.'&n='.$n.'&orderby=13'.$lnkadd.'">'
            .get_string('timetaken', 'mootyper').'</a>'.$arrtextadds[13].'</td>
            <td>'.get_string('delete', 'mootyper').'</td></tr>';

        $labels = null; // 20200624 Set to use as a flag for graphing.
        $grds2 = []; // 20200730 Array to hold data for current group members.
        foreach ($grds as $gr) {
            if ((! $groups) || (groups_is_member($groups, $gr->u_id))) {
                if ($gr->suspicion) {
                    $exclamation = '<span style="color: '.(get_config('mod_mootyper', 'suspicion')).';"><b>!!!!!</b></span>';
                } else {
                    $exclamation = '';
                }
                if ($gr->pass) {
                    $stil = 'background-color: '.(get_config('mod_mootyper', 'passbgc')).';';
                } else {
                    $stil = 'background-color: '.(get_config('mod_mootyper', 'failbgc')).';';
                }
                // Keep grades for group members so stats will be just for the group selected.
                $grds2[] = $gr;
                $fcol = $gr->exercisename;

                // 20240110 Added new string for orphaned grades that need to be deleted.
                if ($fcol == '') {
                    $fcol = get_string('deletegrade', 'mootyper');
                }

                $fcol = get_string('exercise_abreviation', 'mootyper').'-'.$fcol;  // This gets the exercise number.
                $removelnk = '<a onclick="return confirm(\''
                    .get_string('deletegradeconfirm', 'mootyper')
                    .$gr->firstname.' '
                    .$gr->lastname.' '
                    .$fcol.'.'
                    .'\')" href="'.$CFG->wwwroot.'/mod/mootyper/attrem.php?c_id='
                    .optional_param('id', 0, PARAM_INT)
                    .'&m_id='.$n
                    .'&g='.$gr->id
                    .'">'.get_string('delete', 'mootyper').'</a>';
                $namelnk = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.$gr->u_id
                    .'&amp;course='.$course->id
                    .'">'.$gr->firstname.' '
                    .$gr->lastname.'</a>';

                // 20191230 Combine new mistakedetails with mistakes count.
                $strtocut = $gr->mistakes.': '.$gr->mistakedetails;
                // 20231216 Added next line for exerciseid to find orphaned grades, and use it four lines later.
                $se = $gr->exercise;
                // 20210327 Added alignment to Exercise, Mistakes and Elapsed time columns.
                echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                             <td>'.$exclamation.' '.$namelnk.'</td>
                             <td align="left">ID '.$se.'<br>'.$fcol.'</td>
                             <td align="left">'.$strtocut.'</td>
                             <td align="right">'.format_time($gr->timeinseconds).'</td>
                             <td>'.format_float($gr->hitsperminute).'</td>
                             <td>'.$gr->fullhits.'</td>
                             <td>'.format_float($gr->precisionfield).'%</td>
                             <td>'.format_float($gr->wpm).'</td>
                             <td>'.format_float($gr->grade).'</td>
                             <td>'.date(get_config('mod_mootyper', 'dateformat'), $gr->timetaken).'</td>
                             <td>'.$removelnk.'&nbsp;</td></tr>';

                // Get information to draw the chart for all exercises in this lesson.
                $labels[] = $gr->firstname.' '.$gr->lastname.' '.$fcol;  // This gets the exercise number.
                $serieshitsperminute[] = $gr->hitsperminute; // Get the hits per minute value.
                $seriesprecision[] = $gr->precisionfield;  // Get the precision percentage value.
                $serieswpm[] = $gr->wpm; // Get the corrected words per minute rate.
                $seriesgrade[] = $gr->grade; // Get the grade value.
            }
        }

        // 20200704 Added code to include mean date of completion and mean wpm.
        // 20200727 Changed from avg to mean and added code for additional statistics.
        if ($grds2 != false) {
            $mean = results::get_grades_mean($grds2);
            $median = results::get_grades_median($grds2);
            $mode = results::get_grades_mode($grds2);
            $range = results::get_grades_range($grds2);
            $agcount = results::get_grades_agcount($grds2);
            $agmax = results::get_grades_agmax($grds2);
            $agmin = results::get_grades_agmin($grds2);
            $agsum = results::get_grades_agsum($grds2);

            $stil = 'background-color: '.(get_config('mod_mootyper', 'textbgc')).';';
            // 20200727 Print blank table row.
            echo '<tr align="center" style="border-top-style: solid;">
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td></tr>';

            // 20200727 Print means.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('mean', 'mootyper').': </strong></td><td></td>
                <td align ="left">'.$mean['mistakes'].'</td>
                <td>'.format_time($mean['timeinseconds']).'</td>
                <td>'.format_float($mean['hitsperminute']).'</td>
                <td>'.$mean['fullhits'].'</td>
                <td>'.format_float($mean['precisionfield']).'%</td>
                <td>'.format_float($mean['wpm']).'</td>
                <td>'.format_float($mean['grade']).'</td>
                <td>'.date(get_config('mod_mootyper', 'dateformat'), $mean['timetaken']).'</td>
                <td></td>
                </tr>';

            // 20200727 Print medians.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('median', 'mootyper').': </strong></td><td></td>
                <td align ="left">'.$median['mistakes'].'</td>
                <td>'.format_time($median['timeinseconds']).'</td>
                <td>'.format_float($median['hitsperminute']).'</td>
                <td>'.$median['fullhits'].'</td>
                <td>'.format_float($median['precisionfield']).'%</td>
                <td>'.format_float($median['wpm']).'</td>
                <td>'.format_float($median['grade']).'</td>
                <td>'.date(get_config('mod_mootyper', 'dateformat'), (round($median['timetaken']))).'</td>
                <td></td>
                </tr>';

            // 20200727 Print modes.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('mode', 'mootyper').': </strong></td><td></td>
                <td align ="left">'.$mode['mistakes'].'</td>
                <td>'.$mode['timeinseconds'].'</td>
                <td>'.$mode['hitsperminute'].'</td>
                <td>'.$mode['fullhits'].'</td>
                <td>'.$mode['precisionfield'].'</td>
                <td>'.$mode['wpm'].'</td>
                <td>'.$mode['grade'].'</td>
                <td>'.$mode['timetaken'].'</td>
                <td></td>
                </tr>';

            // 20200727 Print ranges.
            echo '<tr align="center" style="border-top-style: solid;'.$stil.'">
                <td><strong>'.get_string('range', 'mootyper').': </strong></td><td></td>
                <td align ="left">'.$range['mistakes'].'</td>
                <td>'.format_time($range['timeinseconds']).'</td>
                <td>'.format_float($range['hitsperminute']).'</td>
                <td>'.$range['fullhits'].'</td>
                <td>'.format_float($range['precisionfield']).'%</td>
                <td>'.format_float($range['wpm']).'</td>
                <td>'.format_float($range['grade']).'</td>
                <td>'.$range['timetaken'].'</td>
                <td></td>
                </tr>';

            // 202230116 If assessed, print rating aggregate statistics.
            if ($mootyper->assessed) {
                // 202316 Add a section title.
                echo '<tr align="left" style="border-top-style: solid;">
                    <td colspan="9">'.get_string('rating', 'rating').' '.get_string('aggregatetype', 'rating').'</td></tr>';

                // 202230116 Print rating aggregateavg.
                echo '<tr align="left" style="border-top-style: solid;'.$stil.'">
                    <td colspan="2"><strong>'.get_string('aggregateavg', 'rating').': </strong></td>
                    <td align ="left" style="opacity: 0.5;">'.$mean['mistakes'].'</td>
                    <td style="opacity: 0.5;">'.format_time($mean['timeinseconds']).'</td>
                    <td style="opacity: 0.5;">'.format_float($mean['hitsperminute']).'</td>
                    <td style="opacity: 0.5;">'.$mean['fullhits'].'</td>
                    <td style="opacity: 0.5;">'.format_float($mean['precisionfield']).'%</td>
                    <td style="opacity: 0.5;">'.format_float($mean['wpm']).'</td>
                    <td>'.format_float($mean['grade']).'</td>
                    <td>'.get_string('agavg', 'mootyper').'</td>
                    <td style="opacity: 0.5;">'.date(get_config('mod_mootyper', 'dateformat'), $mean['timetaken']).'</td>
                    </tr>';

                // 202230116 Print rating aggregatecount. Hits per minute, Precision, Completed, and WPM are meaningless as a count.
                echo '<tr align="left" style="border-top-style: solid;'.$stil.'">
                    <td colspan="2"><strong>'.get_string('aggregatecount', 'rating').': </strong></td>
                    <td align ="left" style="opacity: 0.5;">'.$agcount['mistakes'].'</td>
                    <td style="opacity: 0.5;">'.format_time($agcount['timeinseconds']).'</td>
                    <td style="opacity: 0.5;"> -- </td>
                    <td style="opacity: 0.5;">'.$agcount['fullhits'].'</td>
                    <td style="opacity: 0.5;"> -- </td>
                    <td style="opacity: 0.5;"> -- </td>
                    <td>'.format_float($agcount['grade']).'</td>
                    <td>'.get_string('agcount', 'mootyper').'</td>
                    <td style="opacity: 0.5;"> -- </td>
                    </tr>';

                // 202230116 Print rating aggregatemax.
                echo '<tr align="left" style="border-top-style: solid;'.$stil.'">
                    <td colspan="2"><strong>'.get_string('aggregatemax', 'rating').': </strong></td>
                    <td align ="left" style="opacity: 0.5;">'.$agmax['mistakes'].'</td>
                    <td style="opacity: 0.5;">'.format_time($agmax['timeinseconds']).'</td>
                    <td style="opacity: 0.5;">'.format_float($agmax['hitsperminute']).'</td>
                    <td style="opacity: 0.5;">'.$agmax['fullhits'].'</td>
                    <td style="opacity: 0.5;">'.format_float($agmax['precisionfield']).'%</td>
                    <td style="opacity: 0.5;">'.format_float($agmax['wpm']).'</td>
                    <td>'.format_float($agmax['grade']).'</td>
                    <td>'.get_string('agmax', 'mootyper').'</td>
                    <td style="opacity: 0.5;">'.date(get_config('mod_mootyper', 'dateformat'), $agmax['timetaken']).'</td>
                    </tr>';

                // 202230116 Print rating aggregatemin.
                echo '<tr align="left" style="border-top-style: solid;'.$stil.'">
                    <td colspan="2"><strong>'.get_string('aggregatemin', 'rating').': </strong></td>
                    <td align ="left" style="opacity: 0.5;">'.$agmin['mistakes'].'</td>
                    <td style="opacity: 0.5;">'.format_time($agmin['timeinseconds']).'</td>
                    <td style="opacity: 0.5;">'.format_float($agmin['hitsperminute']).'</td>
                    <td style="opacity: 0.5;">'.$agmin['fullhits'].'</td>
                    <td style="opacity: 0.5;">'.format_float($agmin['precisionfield']).'%</td>
                    <td style="opacity: 0.5;">'.format_float($agmin['wpm']).'</td>
                    <td>'.format_float($agmin['grade']).'</td>
                    <td>'.get_string('agmin', 'mootyper').'</td>
                    <td style="opacity: 0.5;">'.date(get_config('mod_mootyper', 'dateformat'), $agmin['timetaken']).'</td>
                    </tr>';

                // 202230116 Print rating aggregatesum. Precision, Completed, and WPM are meaningless as a sum.
                echo '<tr align="left" style="border-top-style: solid;'.$stil.'">
                    <td colspan="2"><strong>'.get_string('aggregatesum', 'rating').': </strong></td>
                    <td align ="left" style="opacity: 0.5;">'.$agsum['mistakes'].'</td>
                    <td style="opacity: 0.5;">'.format_time($agsum['timeinseconds']).'</td>
                    <td style="opacity: 0.5;">'.format_float($agsum['hitsperminute']).'</td>
                    <td style="opacity: 0.5;">'.$agsum['fullhits'].'</td>
                    <td style="opacity: 0.5;"> -- </td>
                    <td style="opacity: 0.5;"> -- </td>
                    <td>'.format_float($agsum['grade']).'</td>
                    <td>'.get_string('agsum', 'mootyper').'</td>
                    <td style="opacity: 0.5;"> -- </td>
                    </tr>';
            }
            echo '</table>';
        }
    } else {
        echo get_string('nogrades', 'mootyper');
        $labels = null;
    }
}

echo '</table><br>';

// 20200413 Added a return button. 20200428 Added round corners.
echo '<a href="'.$CFG->wwwroot . '/mod/mootyper/view.php?id='.$id
    .'"class="btn btn-primary" style="border-radius: 8px">'
    .get_string('returnto', 'mootyper', $mootyper->name)
    .'</a>';

// 20200103 Changed to button. 20200428 Added round corners.
// Create link for export and pass mode, lesson name, and required goal to csvexport file.
echo ' <a href="'.$CFG->wwwroot.'/mod/mootyper/csvexport.php?mootyperid='.$mootyper->id
    .'&id='.$id
    .'&coursename='.$course->fullname
    .'&mtname='.$mootyper->name
    .'&isexam='.$mootyper->isexam
    .'&lsnname='.$lsnname->lessonname
    .'&timelimit='.$mootyper->timelimit
    .'&requiredgoal='.$mootyper->requiredgoal
    .'&requiredwpm='.$mootyper->requiredwpm
    .'&scale='.$mootyper->scale
    .'"class="btn btn-secondary" style="border-radius: 8px">'.get_string('csvexport', 'mootyper')
    .'</a>';

echo '</form>';
echo '</div><br>';

// 20230517 Added selectors for Hits per minute, Precision, WPM, and Grade display selectors.
echo '<form method="post">';
$oldhpmpreference = get_user_preferences('mootyper_hpmpreference_'.$mootyper->id, 2);
$hpmpreference = optional_param('hpmpreference', $oldhpmpreference, PARAM_INT);

$oldprecisionpreference = get_user_preferences('mootyper_precisionpreference_'.$mootyper->id, 2);
$precisionpreference = optional_param('precisionpreference', $oldprecisionpreference, PARAM_INT);

$oldwpmpreference = get_user_preferences('mootyper_wpmpreference_'.$mootyper->id, 2);
$wpmpreference = optional_param('wpmpreference', $oldwpmpreference, PARAM_INT);

$oldgradepreference = get_user_preferences('mootyper_gradepreference_'.$mootyper->id, 2);
$gradepreference = optional_param('gradepreference', $oldgradepreference, PARAM_INT);

// 20230517 Added selector for hits per minute view. Default is ON.
if ($hpmpreference != $oldhpmpreference) {
    set_user_preference('mootyper_hpmpreference_'.$mootyper->id, $hpmpreference);
}

$listoptions = [
    1 => get_string('hpmhide', 'mootyper'),
    2 => get_string('hpmshow', 'mootyper'),
];

// This creates the dropdown list for how many entries to show on the page.
$selection = html_writer::select(
    $listoptions,
    'hpmpreference',
    $hpmpreference,
    false,
    [
        'id' => 'pref_hpm',
        'class' => 'custom-select',
    ]
);
echo get_string('rhitspermin', 'mootyper').': <select onchange="this.form.submit()" name="hpmpreference">';
echo '<option selected="true" value="'.$selection.'</option>';
echo '</select>';

// 20230517 Added selector for precision view. Default is ON.
if ($precisionpreference != $oldprecisionpreference) {
    set_user_preference('mootyper_precisionpreference_'.$mootyper->id, $precisionpreference);
}

$listoptions = [
    1 => get_string('precisionhide', 'mootyper'),
    2 => get_string('precisionshow', 'mootyper'),
];

// This creates the dropdown list for how many entries to show on the page.
$selection = html_writer::select(
    $listoptions,
    'precisionpreference',
    $precisionpreference,
    false,
    [
        'id' => 'pref_precision',
        'class' => 'custom-select',
    ]
);
echo ' | '.get_string('precision', 'mootyper').': <select onchange="this.form.submit()" name="precisionpreference">';
echo '<option selected="true" value="'.$selection.'</option>';
echo '</select>';

// 20230517 Added selector for wpm view. Default is ON.
if ($wpmpreference != $oldwpmpreference) {
    set_user_preference('mootyper_wpmpreference_'.$mootyper->id, $wpmpreference);
}

$listoptions = [
    1 => get_string('wpmhide', 'mootyper'),
    2 => get_string('wpmshow', 'mootyper'),
];

// This creates the dropdown list for how many entries to show on the page.
$selection = html_writer::select(
    $listoptions,
    'wpmpreference',
    $wpmpreference,
    false,
    [
        'id' => 'pref_wpm',
        'class' => 'custom-select',
    ]
);
echo ' | '.get_string('wpm', 'mootyper').': <select onchange="this.form.submit()" name="wpmpreference">';
echo '<option selected="true" value="'.$selection.'</option>';
echo '</select>';

// 20230517 Added selector for grade view. Default is ON.
if ($gradepreference != $oldgradepreference) {
    set_user_preference('mootyper_gradepreference_'.$mootyper->id, $gradepreference);
}

$listoptions = [
    1 => get_string('gradehide', 'mootyper'),
    2 => get_string('gradeshow', 'mootyper'),
];

// This creates the dropdown list for how many entries to show on the page.
$selection = html_writer::select(
    $listoptions,
    'gradepreference',
    $gradepreference,
    false,
    [
        'id' => 'pref_grade',
        'class' => 'custom-select',
    ]
);
echo ' | '.get_string('grade_mootyper_title', 'mootyper').': <select onchange="this.form.submit()" name="gradepreference">';
echo '<option selected="true" value="'.$selection.'</option>';
echo '</select>';

echo '</form>';
// Process the chart. Skip if there are NOT any grades, DON'T draw the chart.
// AND skip unless Moodle is greater than 3.1.
// AND skip if none of the series are visible, don't draw the chart.
// AND skip if none of the users in the selected group have made any attempts.
if ((($grds != false) && ($CFG->branch > 31) && (isset($serieshitsperminute)))
    && (($hpmpreference == 2) || ($precisionpreference == 2) || ($wpmpreference == 2) || ($gradepreference == 2))) {
    // Create the info the api needs passed to it for each series I want to chart.
    $serie1 = new core\chart_series(get_string('hitsperminute', 'mootyper'), $serieshitsperminute);
    $serie2 = new core\chart_series(get_string('precision', 'mootyper'), $seriesprecision);
    $serie3 = new core\chart_series(get_string('wpm', 'mootyper'), $serieswpm);
    $serie4 = new core\chart_series(get_string('gradenoun'), $seriesgrade);

    $chart = new core\chart_bar();  // Tell the api I want a bar chart.
    $chart->set_horizontal(true); // Calling set_horizontal() passing true as parameter will display horizontal bar charts.
    $chart->set_title(get_string('charttitlemyowngrades', 'mootyper')); // Tell the api what I want for a chart title.
    // 20230517 Only add a chart series if it is not hidden.
    if ($hpmpreference == 2) {
        $chart->add_series($serie1);  // Pass the hits per minute data to the api.
    }
    if ($precisionpreference == 2) {
        $chart->add_series($serie2);  // Pass the precision data to the api.
    }
    if ($wpmpreference == 2) {
        $chart->add_series($serie3);  // Pass the words per minute data to the api.
    }
    if ($gradepreference == 2) {
        $chart->add_series($serie4);  // Pass the grade data to the api.
    }
        $chart->set_labels($labels);  // Pass the exercise number data to the api.
    $chart->get_xaxis(0, true)->set_label(get_string('xaxislabel', 'mootyper'));  // Pass a label to add to the x-axis.
    $chart->get_yaxis(0, true)->set_label(get_string('fexercise', 'mootyper')); // Pass the label to add to the y-axis.
    echo $OUTPUT->render($chart); // Draw the chart on the output page.
}
echo $OUTPUT->footer();

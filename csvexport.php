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
 * This file is used to export exercise attempts in csv format.
 *
 * Called from gview.php (View All Grades).
 * Changed the code 03/10/2019 to work with removing ...lib.php function get_typergradesfull.
 * Adds the mode, lesson name, timelimit, required precision, required wpm to row one of the csv output file.
 * Also changed to use the lesson name, with whitespace removed, as the filename.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use \mod_mootyper\event\export_viewallgrades_to_csv;

// Changed to this newer format 03/10/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

require_login(0, true, null, false);

/**
 * The function for exporting results data from this MooTyper.
 *
 * @param array $array All the grade data for this MooTyper
 * @param string $filename
 * @param string $delimiter
 * @return array, false if none.
 */
function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    $mootyperid = optional_param('mootyperid', 0, PARAM_INT); // Get the id for this MooTyper.
    $id = optional_param('id', 0, PARAM_INT); // Get the course module id for this MooTyper.
    $coursename = optional_param('coursename', '', PARAM_RAW); // Get the course name for this MooTyper.
    $mtname = optional_param('mtname', '', PARAM_TEXT); // Get the activity name for this MooTyper.
    $misexam = optional_param('isexam', 0, PARAM_INT); // Get the mode for this MooTyper.
    $lsnname = optional_param('lsnname', '', PARAM_RAW); // Get the lesson name for this MooTyper.
    $timelimit = optional_param('timelimit', 0, PARAM_INT); // Get the timelimit for this MooTyper.
    $requiredgoal = optional_param('requiredgoal', 0, PARAM_INT); // Get the required precision goal for this MooTyper.
    $requiredwpm = optional_param('requiredwpm', 0, PARAM_INT); // Get the required precision goal for this MooTyper.
    $scale = optional_param('scale', 0, PARAM_INT); // Get the scale for this MooTyper.

    $cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
    $context = context_module::instance($cm->id);

    // Start building a row 1 entry of the course name, activity name, mode, lesson name, required precision, and required WPM.
    $coursename = get_string('course')." = ".$coursename;
    $mtname = get_string('activity')." = ".$mtname;

    // Continue building a row 1 entry grades csv output, based on the mode.
    switch ($misexam) {
        case 0:
            $mtmode = get_string('fmode', 'mootyper')." = ".get_string('flesson', 'mootyper');
            break;
        case 1:
            $mtmode = get_string('fmode', 'mootyper')." = ".get_string('isexamtext', 'mootyper');
            break;
        case 2:
            $mtmode = get_string('fmode', 'mootyper')." = ".get_string('practice', 'mootyper');
            break;
        default:
            $mtmode = get_string('error', 'moodle');
    }

    // Create a spreadsheet csv filename based on the lesson name.
    $filename = get_string('flesson', 'mootyper')."_".$lsnname.'_'.gmdate("Ymd_Hi").'GMT.csv';

    // Get the lesson name, required precision, and required WPM for the csv spreadsheet row 1 entry.
    $lsnname = get_string('flesson', 'mootyper')." = ".$lsnname;
    $timelimit = get_string('timelimit', 'mootyper')." = ".$timelimit.":00 ".get_string('minutes');
    $requiredgoal = get_string('requiredgoal', 'mootyper').' = '.$requiredgoal.'%';
    $requiredwpm = get_string('requiredwpm', 'mootyper').' = '.$requiredwpm;
    $scale = get_string('gradenoun').' = '.$scale;

    // Trigger export_viewallgrades_to_csv event.
    $params = array(
        'objectid' => $id,
        'context' => $context,
        'other' => array(
            'coursename' => $coursename,
            'mtname' => $mtname,
            'lesson' => $lsnname,
            'filename' => $filename
        )
    );
    $event = export_viewallgrades_to_csv::create($params);
    $event->trigger();

    // Remove all whitespace from the filename. This will remove tabs too.
    $filename = preg_replace('/\s+/', '', $filename);

    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="'.$filename.'";');
    header("Pragma: no-cache");
    header("Expires: 0");
    $f = fopen('php://output', 'w');

    $details = array($coursename,
                     $mtname,
                     $mtmode,
                     $lsnname,
                     $timelimit,
                     $requiredgoal,
                     $requiredwpm,
                     $scale);

    $headings = array(get_string('student', 'mootyper'),
                      get_string('fexercise', 'mootyper'),
                      get_string('vmistakes', 'mootyper'),
                      get_string('timeinseconds', 'mootyper'),
                      get_string('hitsperminute', 'mootyper'),
                      get_string('fullhits', 'mootyper'),
                      get_string('precision', 'mootyper'),
                      get_string('wpm', 'mootyper'),
                      get_string('gradenoun'),
                      get_string('timetaken', 'mootyper'));
    fputcsv($f, $details, $delimiter);
    fputcsv($f, $headings, $delimiter);
    foreach ($array as $gr) {
        $fields = array($gr->firstname.' '.$gr->lastname,
                        $gr->exercisename,
                        $gr->mistakes.': '.$gr->mistakedetails,
                        format_time($gr->timeinseconds),
                        format_float($gr->hitsperminute),
                        $gr->fullhits,
                        format_float($gr->precisionfield).'%',
                        $gr->wpm,
                        $gr->grade,
                        date(get_config('mod_mootyper', 'dateformat'), $gr->timetaken));
        fputcsv($f, $fields, $delimiter);
    }
    fclose($f);
}

$mid = optional_param('mootyperid', 0, PARAM_INT);
// Fourth item determines sort order of the data.
// 2 is lastname. 10 is exercise name, ($mid, 0, 0, 10, 0).
// The function get_typer_grades_adv needs further work on sorting.
$grds = get_typer_grades_adv($mid, 0, 0, 2, 0);

// Add suspicion mark to first name for each suspicious entry.
foreach ($grds as $gr) {
    if ($gr->suspicion) {
        $gr->firstname = '!!!!! '.$gr->firstname;
    }
}

array_to_csv_download($grds, get_string('gradesfilename', 'mootyper'));

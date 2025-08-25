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
use mod_mootyper\local\lessons;

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

$debug;
$dcount = 0;
/*
$debug['AAA CP-'.$dcount.' In lsnexrem.php printing the course module id $id'] = $id;
$dcount++;
$debug['AAA CP-'.$dcount.' In lsnexrem.php printing the course module info $cm'] = $cm;
$dcount++;
$debug['AAA CP-'.$dcount.' In lsnexrem.php printing $course'] = $course;
$dcount++;
$debug['AAA CP-'.$dcount.' In lsnexrem.php printing $exerciseid'] = $exerciseid;
$dcount++;
$debug['AAA CP-'.$dcount.' In lsnexrem.php printing $lessonid'] = $lessonid;
$dcount++;
$debug['AAA CP-'.$dcount.' In lsnexrem.php printing $cmid'] = $cmid;
*/


// 0 Find the lesson being used.
// 1 Find all the MooTypers using this lessons ID first.
// 2 If more than one is found, list the MooTyper activities which will no longer have a valid lesson.
// 3 Halt and notify the admin/teacher regarding 2.
// 4 Use results from 1 to find all the exercises used by this lesson ID.
// 5 Use results from 2 to find all the grades used by the exercises from this lesson ID.
// 6 Delete all the grades found in 3.
// 7 Delete all the exercises used by this lesson ID.
// 8 Delete the lesson.


// 20241227 Get the lesson ID if available. STEP 0
$lessonpo = optional_param('lesson', '', PARAM_INT);
// Get all the exercises in this lesson so we can fix the snumbers if we need too. STEP 4
$exes = lessons::get_exercises_by_lesson($lessonpo);

$dcount++;
$debug['BBB CP-'.$dcount.' In lsnexrem.php printing $lessonpo'] = $lessonpo;
$dcount++;
$debug['BBB CP-'.$dcount.' In lsnexrem.php printing the lesson exercises $exes'] = $exes;

// 20241231 Block of code to delete a lesson and all of the exercises in it.
if ($lessonid) {
    // 20241231 Get the lesson and count to see if there is an erro of more than one.
    $lessons = $DB->get_records('mootyper_lessons', ['id' => $lessonid]);
    $lessonscount = $DB->count_records('mootyper_lessons', ['id' => $lessonid]);
    if ($lessonscount == 1) {
        
        $dcount++;
        $debug['AAA CP-'.$dcount.' The button for, delete a lesson and all it\'s exercises, was clicked and so printing it\'s ID, $lessonpo'] = $lessonpo;

        foreach ($lessons as $lesson) {
            $dcount++;
            $debug['BBB CP-'.$dcount.' In the foreach loop after the button for, delete a lesson and all it\'s exercises, was clicked and so printing its ID and name, from $lesson'] = 'The lesson ID is - '.$lesson->id.', and the lesson name is - '.$lesson->lessonname;
        }
    } else {
        // 20241231 You CANNOT have more than one lesson with this lesson ID.
        throw new moodle_exception(get_string('mootyperlessonerror', 'mootyper'));
    }
    $dcount++;
    $debug['CCC CP-'.$dcount.' In delete a lesson and here are the exercises we want to delete $exes'] = $exes;



    // 20241231 Count and find all the MooTypers using this lesson ID. STEP 1
    $mootyperscount = $DB->count_records('mootyper', ['lesson' => $lessonpo]);
    $mootypers = $DB->get_records('mootyper', ['lesson' => $lessonpo]);
    // 20241231 List the MooTyper activities. STEP 2 and 3. Allow only admin if more than one.
	// For teachers, should also check if editable by the teacher, or deny delete ability! NO NEED FOR THIS!!! Already done by view and exercises files.
    // Note that teacher2, non-editing teacher does NOT even see the option to access Expor/Edit exercises.
    if (($mootyperscount > 1) && (is_siteadmin())) {
        // 20250101 Need an admin capability check here as deleting this lesson will break multiple MooTyper activities.
        // In other words, let an admin proceed, but stop a teacher and give them a warning message.
        $dcount++;
        $debug['DDD CP-'.$dcount.' In delete a lesson and counting the number of $mootypers using the lesson: '] = $mootyperscount;
        $dcount++;
        $debug['DDD CP-'.$dcount.' In delete a lesson and due to multiple MooTyper activities using this lesson ID-'.$lessonpo.', you should NOT delete the lesson.'] = '!!!Don\'t do it, Ethyl!!!';
        //  $mootypers = $DB->get_records('mootyper', ['lesson' => $lessonpo]);
        foreach ($mootypers as $mootyper) {
            $dcount++;
            $debug['EEE CP-'.$dcount.' In delete a lesson and here is info for one of the MooTyper activities using this lesson ID: '.$mootyper->lesson] = 'The mootyper mode is - '.$mootyper->isexam.', and it is in course - '.$mootyper->course.', and it is named - '.$mootyper->name.', and the lessionid is - '.$mootyper->lesson;
        }
        //die;
    } else if (($mootyperscount > 1) && !(is_siteadmin())){
        $dcount++;
        $debug['FFF CP-'.$dcount.' In delete a lesson and NOT an admin and counting the number of $mootypers using the lesson: '] = $mootyperscount;
        // 20241231 There is only one MooTyper using this lesson ID so it is safe to delete it and associated exercises and grades. STEP 4 STEP 5 STEP 6 STEP 7 STEP 8
        // If user is a teacher, or admin let them proceed.
        foreach ($mootypers as $mootyper) {
            $dcount++;
            $debug['GGG CP-'.$dcount.' In delete a lesson and there is only one MooTyper using this lesson ID'] = 'mootyperid-'.$mootyper->id.', course-'.$mootyper->course.', name-'.$mootyper->name.', lessionid-'.$mootyper->lesson;
        }
        // 20240101 Will need to see about doing a Moodle grade update here.
        // STEP 8
    } else {
        // 20241231 There is only one MooTyper using this lesson ID so it is safe to delete it and associated exercises and grades. STEP 4 STEP 5 STEP 6 STEP 7 STEP 8
        // If user is a teacher, or admin let them proceed.
        foreach ($mootypers as $mootyper) {
            $dcount++;
            $debug['HHH CP-'.$dcount.' In delete a lesson and there is only one MooTyper using this lesson ID'] = 'mootyperid-'.$mootyper->id.', course-'.$mootyper->course.', name-'.$mootyper->name.', lessionid-'.$mootyper->lesson;
        }
        // 20240101 Will need to see about doing a Moodle grade update here.
        // STEP 8
    }



    // This will break every OTHER MooTyper activty that uses this lesson ID.
    // $DB->delete_records('mootyper_lessons', ['id' => $lessonid]);
    // $DB->delete_records('mootyper_exercises', ['lesson' => $lessonid]);

    // 20241229 Delete any mootyper_grades. This will need to be modified, I think!
    // Cannot use $exerciseid or we wind up in the second part of this file.
    // WIll need to use a variation such as $exerid.
    // $DB->delete_records('mootyper_grades', (['exercise' => $exerciseid]));
    // $DB->delete_records('mootyper_grades', (['exercise' => $exerid]));



    // $DB->delete_records('mootyper_grades', ['mootyper' => $mootyper->id]);
    // Can just invoke the function in lib.php at line 535.

    // Need a foreach loop here to get all the grades for this combination of lessson and exercises, so they can be deleted, too.
    foreach ($exes as $exe) {
        //$orphanedgrades = $DB->get_records('mootyper_grades', ['exercise' => $exe['id'] = $exerciseid]);
        // 20241227 Get orphaned grades for ones created when completing the lesson with this ID, $lessonid.
        $orphanedgrades = $DB->get_records('mootyper_grades', ['exercise' => $exe['id']]);
        $dcount++;
        $debug['III CP-'.$dcount.' In delete a lesson and here are the grades we need to delete $orphanedgrades'] = $orphanedgrades;

        foreach ($orphanedgrades as $orphanedgrade) {
            $mootyper = $DB->get_record('mootyper', ['id' => $orphanedgrade->mootyper]);
            $attempt = $DB->get_records('mootyper_attempts', ['id' => $orphanedgrade->attemptid, 'mootyperid' => $orphanedgrade->mootyper, ]);
			if ($attempt) {
                $checks = $DB->get_record('mootyper_checks', ['id' => $orphanedgrade->attemptid]);
                // 20241223 If there are any checks in the mdl_mootyper_checks table, delete them.
                if ($checks) {
                    $DB->delete_records('mootyper_checks', ['id' => $orphanedgrade->attemptid]);
                    $dcount++;
				    $debug['JJJ CP-'.$dcount.' In the if $attempt printing $checks that NEED to be deleted!'] = $checks;
                    // 20241223 If there are not any checks then do nothing for now. Later this else needs to be deleted since it has nothing to do.
                } else {
                    $dcount++;
				    $debug['KKK CP-'.$dcount.' In the else $attempt printing $checks and there are NOT ANY checks to delete.'] = $checks;
                }

                // 20241226 Delete the attempt.
                // $DB->delete_records('mootyper_attempts', ['id' => $orphanedgrade->attemptid, 'mootyperid' => $orphanedgrade->mootyper]);

            }
        }

    }
    $webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
    //$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;
}

///////////////////////////////Below here works and Will delete the selected exercise./////////////////////////////////////////////////////////////////////////////////////
if ($exerciseid) {
    // 20241229 Get all the mootyper_grades that have this exercise listed no matter if it is passing or not.
    $orphanedgrades = $DB->get_records('mootyper_grades', ['exercise' => $exerciseid]);
    // 20241229 Delete the exercise selected for deletion.
    $DB->delete_records('mootyper_exercises', (['id' => $exerciseid]));
    // 20241229 Delete any mootyper_grades.
    $dcount++;
	$debug['KKK CP-'.$dcount.' In the area where an exercise is being deleted with grades printing $orphanedgrades that NEED to be deleted!'] = $orphanedgrades;
    $DB->delete_records('mootyper_grades', (['exercise' => $exerciseid]));

    // Initialize a counter to use for snumber replacements.
    $count = 0;
    // Get all the exercises left in this lesson so we can fix the snumbers if we need too.
    $exes = lessons::get_exercises_by_lesson($lessonpo);
    // 20241229 Adjust the snumber for each of the remaining exercises.
    foreach ($exes as $exe) {
        $count++;
        // 20241229 Replace the current snumber of this exercise with the current count.
        $exe['snumber'] = $count + 0;
        // 20240101 Also use the snumber to update the Exercise names.
        $exercisename = $exe['exercisename'];
        // 20250101 Break exercisename at the first space found and keep the remainder.
        $exercisename = (explode(" ", $exercisename, 2));
        // 20250101 Update the Exercise name with snumber+remainder.
        $exe['exercisename'] = str_replace('\n', "&#10;", $count.' '.$exercisename[1]);

        $DB->update_record('mootyper_exercises', $exe);
    }
    $exes = lessons::get_exercises_by_lesson($lessonpo);
print_object($debug);
//die;

    // 20241229 Had to move these into the if check to go back to the proper location.
	$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
    header('Location: '.$webdir);
}

print_object($debug);
die;

//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;
//header('Location: '.$webdir);

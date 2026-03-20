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
// If rl is set we remove a lesson and all its exercises.
$exerciseid = optional_param('re', '', PARAM_TEXT);
$lessonid = optional_param('rl', '', PARAM_TEXT);
// Added cmid so can exit back to MooTyper activity we came from.
$cmid = optional_param('cmid', '0', PARAM_INT); // Course Module ID.

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

// 0 Find the lesson being used.
// 1 Find all the MooTypers using this lessons ID first.
// 2 If more than one is found, list the MooTyper activities which will no longer have a valid lesson.
// 3 Halt and notify the admin/teacher regarding 2. DO NOT allow process to proceed.
// 4 If none or only one MooTyper is using the lesson, find all the exercises used by this lesson ID.
// 5 Use results from 4 to find all the grades used by the exercises from this lesson ID.
// 6 Delete all the grades found in 5.
// 7 Delete all the exercises used by this lesson ID.
// 8 Delete the lesson.


// 20241227 Get the lesson ID if available. STEP 0
$lessonpo = optional_param('lesson', '', PARAM_INT);
// Get all the exercises in this lesson so we can fix the snumbers if we need too. STEP 4
$exes = lessons::get_exercises_by_lesson($lessonpo);

// 20241231 Block of code to delete a lesson and all of the exercises in it.
if ($lessonid) {
    // 20241231 Get the lesson and count to see if there is an error of more than one.
    $lessons = $DB->get_records('mootyper_lessons', ['id' => $lessonid]);



    $lessonscount = $DB->count_records('mootyper_lessons', ['id' => $lessonid]);

    if ($lessonscount == 1) {
        foreach ($lessons as $lesson) {
            // Go ahead and remove the lesson.



			// After removal, get a different lesson to show on the exercises.php page.
            // Use get_record_sql() to execute a custom query.
            //$sql = "SELECT MIN(id) AS lowestid FROM {mootyper_lessons}";
            //$sql = "SELECT id AS lowestid, lessonname FROM {mootyper_lessons} WHERE (id = 464)";
            //$sql = "SELECT MAX(id) AS lowestid, lessonname FROM {mootyper_lessons}";
            //$sql = "SELECT MIN(lessonname) AS lowestid FROM {mootyper_lessons}";
            $sql = "SELECT * FROM {mootyper_lessons} ORDER BY LOWER(lessonname) ASC LIMIT 1";


            $lowestidobject = $DB->get_records_sql($sql);
            print_object($lowestidobject);
foreach ($lowestidobject as $rcdid) {
    echo "ID: ".$rcdid->id."\n";
// Got to come up with $course when reloading the exercises.php page.
$course =  $rcdid->courseid;
}
            //print_object($lowestidobject);



            //var_dump($cm);
            //var_dump($cmid);
            //var_dump($lessonslowestid);
            //die;
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$lessonid.'&lesson='.$rcdid->id;
$webdir = $CFG->wwwroot.'/mod/mootyper/view.php?id='.$cm->id;




        }
    } else {
        // 20241231 You CANNOT have more than one lesson with this lesson ID.
        // This is because we do not want to BREAK an activity somewhere else in our Moodle site.
        throw new moodle_exception(get_string('mootyperlessonerror', 'mootyper'));
    }
    // 20241231 Count and find all the MooTypers using this lesson ID. STEP 1
    $mootyperscount = $DB->count_records('mootyper', ['lesson' => $lessonpo]);


    $mootypers = $DB->get_records('mootyper', ['lesson' => $lessonpo]);


    // 20241231 List the MooTyper activities. STEP 2 and 3. Allow only admin if more than one.
	// For teachers, should also check if editable by the teacher, or deny delete ability! NO NEED FOR THIS!!! Already done by view and exercises files.
    // Note that teacher2, non-editing teacher does NOT even see the option to access Expor/Edit exercises.
    if (($mootyperscount >= 1) && (is_siteadmin())) {
        // 20250101 Need an admin capability check here as deleting this lesson will break multiple MooTyper activities.
        // In other words, let an admin proceed, but stop a teacher and give them a warning message.
        //  $mootypers = $DB->get_records('mootyper', ['lesson' => $lessonpo]);
        foreach ($mootypers as $mootyper) {
            // This will break every OTHER MooTyper activty that uses this lesson ID.
            // 20250909 Need to change tactics and try to use the delete code used by exercise.php page.
            // 20241229 Delete any mootyper_grades. This will need to be modified, I think!
            // Cannot use $exerciseid or we wind up in the second part of this file.
            // WIll need to use a variation such as $exerid.




            // foreach ($exercise as $exerid) {
            foreach ($exes as $exerid) {
                // $DB->delete_records('mootyper_grades', (['exercise' => $exerciseid]));

                // $DB->delete_records('mootyper_grades', (['exercise' => $exerid]));

                // $DB->delete_records('mootyper_grades', ['mootyper' => $mootyper->id]);
                // Can just invoke the function in lib.php at line 535.
            }
            // $DB->delete_records('mootyper_lessons', ['id' => $lessonid]);
            // $DB->delete_records('mootyper_exercises', ['lesson' => $lessonid]);
            // $DB->delete_records('mootyper_grades', ['id' => $mootyper->id, 'exercise' => $mootyper->exercise]);
            // $DB->delete_records('mootyper', ['id' => $mootyper_grades->mootyper]);

        }

    } else if (($mootyperscount > 1) && !(is_siteadmin())){
        // 20241231 There is only one MooTyper using this lesson ID so it is safe to delete it and associated exercises and grades. STEP 4 STEP 5 STEP 6 STEP 7 STEP 8
        // If user is a teacher, or admin let them proceed.
        foreach ($mootypers as $mootyper) {

        }
        // 20240101 Will need to see about doing a Moodle grade update here.
        // STEP 8
    } else {
        // 20241231 There is only one MooTyper using this lesson ID so it is safe to delete it and associated exercises and grades. STEP 4 STEP 5 STEP 6 STEP 7 STEP 8
        // If user is a teacher, or admin let them proceed.
        foreach ($mootypers as $mootyper) {

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
        foreach ($orphanedgrades as $orphanedgrade) {
            $mootyper = $DB->get_record('mootyper', ['id' => $orphanedgrade->mootyper]);
            $attempt = $DB->get_records('mootyper_attempts', ['id' => $orphanedgrade->attemptid, 'mootyperid' => $orphanedgrade->mootyper, ]);
			if ($attempt) {
                $checks = $DB->get_record('mootyper_checks', ['id' => $orphanedgrade->attemptid]);
                // 20241223 If there are any checks in the mdl_mootyper_checks table, delete them.
                if ($checks) {
                    $DB->delete_records('mootyper_checks', ['id' => $orphanedgrade->attemptid]);
                    // 20241223 If there are not any checks then do nothing for now. Later this else needs to be deleted since it has nothing to do.
                } else {

                }

                // 20241226 Delete the attempt.
                // $DB->delete_records('mootyper_attempts', ['id' => $orphanedgrade->attemptid, 'mootyperid' => $orphanedgrade->mootyper]);
            }
        }
    }
    //$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
    //$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;
    //$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$exe->id.'&lesson='.$lessonpo;
    //$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;

}

// 20250928 Here we need to find out the lowest lesson ID number, the mootyper ID number, and use them to go to exercises.php page.
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$lessonid;

// 20250928 THIS IS THE ONE I NEED! Use after the delete.
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$lessonid.'&lesson='.$lowestidobject->lowestid;
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$lessonid.'&lesson='.$lowestidobject->id;
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$lessonid.'&lesson='.$rcdid->id;


//$webdir = $CFG->wwwroot . '/course/view.php?id='.$cm->course;

//$webdir = $CFG->wwwroot.'/course/view.php?id='.$cmid;
//$webdir = $CFG->wwwroot.'/mod/mootyper/view.php?id='.$cmid->id;


// 20250928 This one works and takes me back to the current MooTyper.
//$webdir = $CFG->wwwroot.'/mod/mootyper/view.php?id='.$cm->id;

//$url = '<a href="'.$CFG->wwwroot.'/mod/mootyper/view.php?id='.$cmid.'</a>';
//echo 'test';
//echo $url;

///////////////////////////////20250828 Below here works and Will delete the selected exercise./////////////////////////////
if ($exerciseid) {
    // 20241229 Get all the mootyper_grades that have this exercise listed no matter if it is passing or not.
    $orphanedgrades = $DB->get_records('mootyper_grades', ['exercise' => $exerciseid]);

    // Have these here because I am trying to get Moodle to update the grades using whatever results,
    // if any, are still available for the student(s). Since my first attemp of dumping the
    // $orphanedgrades only listed one, I think I need to start using another student to see what's dumped.
    // YES! with two students having completed the exercise, the orphanedgrades list included multiple students.

    //$orderby = optional_param('orderby', -1, PARAM_INT);
    //$grds = get_typergradesuser('mootyper_grades', ['exercise' => $exerciseid], $USER->id, $orderby, 0);



    // 20241229 Delete the exercise selected for deletion.
    $DB->delete_records('mootyper_exercises', (['id' => $exerciseid]));
    // 20241229 Delete any mootyper_grades.
    $DB->delete_records('mootyper_grades', (['exercise' => $exerciseid]));


/*
    // Developing events for deleting exercises, lessons, and grades.
    // Trigger mootyper_grades, mootyper_exercises event for mode 0, 1, or 2 on the, lsnexrem.php, page.
    $params = [
        'objectid' => $mootyper->id,
        'context' => $context,
        'other' => [
            'exercise' => $exerciseid,
            'mode' => $mootyper->isexam,
        ],
        'relateduserid' => $dbgrade->userid,
    ];
    $event = grade_deleted::create($params);
    $event->trigger();
*/

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
    // 20241229 Had to move these into the if check to go back to the proper location.
	$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
    header('Location: '.$webdir);

}


//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$lessonid;
//$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;
header('Location: '.$webdir);


// 20241229 Had to move these into the if check to go back to the proper location.
$webdir = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id.'&lesson='.$lessonpo;
//header('Location: '.$webdir);

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
 * Library of interface functions and constants for module mootyper
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the mootyper specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die();

/** Example constant.
 * define('NEWMODULE_ULTIMATE_ANSWER', 42);
 */

 // Moodle core API.

/**
 * Returns the information on whether the module supports a feature.
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function mootyper_supports($feature) {
    switch ($feature) {
        case FEATURE_GROUPS;
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_COMPLETION_HAS_RULES:
            return false;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;

        default:
            return null;
    }
}

/**
 * Get users for this MooTyper.
 *
 * @param int $mootyperid
 * @return array, false if none.
 */
function get_users_of_one_instance($mootyperid) {
    global $DB, $CFG;
    $params = array();
    $toreturn = array();
    $gradestblname = $CFG->prefix."mootyper_grades";
    $userstblname = $CFG->prefix."user";
    $sql = "SELECT DISTINCT ".$userstblname.".firstname, ".$userstblname.".lastname, ".$userstblname.".id".
    " FROM ".$gradestblname.
    " LEFT JOIN ".$userstblname." ON ".$gradestblname.".userid = ".$userstblname.".id".
    " WHERE (mootyper=".$mootyperid.")";
    if ($grades = $DB->get_records_sql($sql, $params)) {
        return $grades;
    }
    return false;
}

/**
 * Get grades for users for this MooTyper.
 *
 * @param int $mootyperid
 * @param int $exerciseid
 * @param int $userid
 * @param int $orderby
 * @param int $desc
 * @return array, false if none.
 */
function get_typer_grades_adv($mootyperid, $exerciseid, $userid=0, $orderby=-1, $desc=false) {
    global $DB, $CFG;
    $params = array();
    $toreturn = array();
    $gradestblname = $CFG->prefix."mootyper_grades";
    $userstblname = $CFG->prefix."user";
    $exertblname = $CFG->prefix."mootyper_exercises";
    $atttblname = $CFG->prefix."mootyper_attempts";
    $sql = "SELECT ".$gradestblname.".id, "
                    .$userstblname.".firstname, "
                    .$userstblname.".lastname, "
                    .$userstblname.".id as u_id, "
                    .$gradestblname.".pass, "
                    .$gradestblname.".mistakes, "
                    .$gradestblname.".timeinseconds, "
                    .$gradestblname.".hitsperminute, "
                    .$atttblname.".suspicion, "
                    .$gradestblname.".fullhits, "
                    .$gradestblname.".precisionfield, "
                    .$gradestblname.".timetaken, "
                    .$exertblname.".exercisename, "
                    .$gradestblname.".wpm".
    " FROM ".$gradestblname.
    " LEFT JOIN ".$userstblname." ON ".$gradestblname.".userid = ".$userstblname.".id".
    " LEFT JOIN ".$exertblname." ON ".$gradestblname.".exercise = ".$exertblname.".id".
    " LEFT JOIN ".$atttblname." ON ".$atttblname.".id = ".$gradestblname.".attemptid".
    " WHERE (mootyper=".$mootyperid.") AND (exercise=".$exerciseid." OR ".$exerciseid."=0) AND".
    " (".$gradestblname.".userid=".$userid." OR ".$userid."=0)";
    if ($orderby == 0 || $orderby == -1) {
        $oby = " ORDER BY ".$gradestblname.".id";
    } else if ($orderby == 1) {
        $oby = " ORDER BY ".$userstblname.".firstname";
    } else if ($orderby == 2) {
        $oby = " ORDER BY ".$userstblname.".lastname";
    } else if ($orderby == 3) {
        $oby = " ORDER BY ".$atttblname.".suspicion";
    } else if ($orderby == 4) {
        $oby = " ORDER BY ".$gradestblname.".mistakes";
    } else if ($orderby == 5) {
        $oby = " ORDER BY ".$gradestblname.".timeinseconds";
    } else if ($orderby == 6) {
        $oby = " ORDER BY ".$gradestblname.".hitsperminute";
    } else if ($orderby == 7) {
        $oby = " ORDER BY ".$gradestblname.".fullhits";
    } else if ($orderby == 8) {
        $oby = " ORDER BY ".$gradestblname.".precisionfield";
    } else if ($orderby == 9) {
        $oby = " ORDER BY ".$gradestblname.".timetaken";
    } else if ($orderby == 10) {
        $oby = " ORDER BY ".$exertblname.".exercisename";
    } else if ($orderby == 11) {
        $oby = " ORDER BY ".$gradestblname.".pass";
    } else if ($orderby == 12) {
        $oby = " ORDER BY ".$gradestblname.".wpm";
    } else {
        $oby = "";
    }
    $sql .= $oby;
    if ($desc) {
        $sql .= " DESC";
    }
    if ($grades = $DB->get_records_sql($sql, $params)) {
        return $grades;
    }
    return false;
}

/**
 * Get averages for users for this MooTyper.
 *
 * @param int $grads
 * @return array.
 */
function get_grades_average($grads) {
    $povprecje = array();
    $cnt = count($grads);
    $povprecje['mistakes'] = 0;
    $povprecje['timeinseconds'] = 0;
    $povprecje['hitsperminute'] = 0;
    $povprecje['precision'] = 0;
    foreach ($grads as $grade) {
        $povprecje['mistakes'] = $povprecje['mistakes'] + $grade->mistakes;
        $povprecje['timeinseconds']  = $povprecje['timeinseconds'] + $grade->timeinseconds;
    }
    if ($cnt != 0) {
        $povprecje['mistakes'] = $povprecje['mistakes'] / $cnt;
        $povprecje['timeinseconds'] = $povprecje['timeinseconds'] / $cnt;
    }
    return $povprecje;
}

/**
 * Get grades for all users.
 *
 * @param int $sid
 * @param int $orderby
 * @param int $desc
 * @return array, false if null.
 */
function get_typergradesfull($sid, $orderby=-1, $desc=false) {
    global $DB, $CFG;
    $params = array();
    $toreturn = array();
    $gradestblname = $CFG->prefix."mootyper_grades";
    $userstblname = $CFG->prefix."user";
    $exertblname = $CFG->prefix."mootyper_exercises";
    $atttblname = $CFG->prefix."mootyper_attempts";
    $sql = "SELECT ".$gradestblname.".id, "
                    .$userstblname.".firstname, "
                    .$userstblname.".lastname, "
                    .$userstblname.".id as u_id, "
                    .$atttblname.".suspicion, "
                    .$gradestblname.".mistakes, "
                    .$gradestblname.".timeinseconds, "
                    .$gradestblname.".hitsperminute, "
                    .$gradestblname.".fullhits, "
                    .$gradestblname.".precisionfield, "
                    .$gradestblname.".timetaken, "
                    .$exertblname.".exercisename, "
                    .$gradestblname.".wpm".
    " FROM ".$gradestblname.
    " LEFT JOIN ".$userstblname." ON ".$gradestblname.".userid = ".$userstblname.".id".
    " LEFT JOIN ".$exertblname." ON ".$gradestblname.".exercise = ".$exertblname.".id".
    " LEFT JOIN ".$atttblname." ON ".$atttblname.".id = ".$gradestblname.".attemptid".
    " WHERE mootyper=".$sid;
    if ($orderby == 0 || $orderby == -1) {
        $oby = " ORDER BY ".$gradestblname.".id";
    } else if ($orderby == 1) {
        $oby = " ORDER BY ".$userstblname.".firstname";
    } else if ($orderby == 2) {
        $oby = " ORDER BY ".$userstblname.".lastname";
    } else if ($orderby == 3) {
        $oby = " ORDER BY ".$atttblname.".suspicion";
    } else if ($orderby == 4) {
        $oby = " ORDER BY ".$gradestblname.".mistakes";
    } else if ($orderby == 5) {
        $oby = " ORDER BY ".$gradestblname.".timeinseconds";
    } else if ($orderby == 6) {
        $oby = " ORDER BY ".$gradestblname.".hitsperminute";
    } else if ($orderby == 7) {
        $oby = " ORDER BY ".$gradestblname.".fullhits";
    } else if ($orderby == 8) {
        $oby = " ORDER BY ".$gradestblname.".precisionfield";
    } else if ($orderby == 9) {
        $oby = " ORDER BY ".$gradestblname.".timetaken";
    } else if ($orderby == 10) {
        $oby = " ORDER BY ".$exertblname.".exercisename";
    } else if ($orderby == 12) {
        $oby = " ORDER BY ".$gradestblname.".wpm";
    } else {
        $oby = "";
    }
    $sql .= $oby;
    if ($desc) {
        $sql .= " DESC";
    }
    if ($grades = $DB->get_records_sql($sql, $params)) {
        return $grades;
    }
    return false;
}

/**
 * Get grades for one user.
 *
 * @param int $sid
 * @param int $uid
 * @param int $orderby
 * @param int $desc
 * @return array, false if null.
 */
function get_typergradesuser($sid, $uid, $orderby=-1, $desc=false) {
    global $DB, $CFG;
    $params = array();
    $toreturn = array();
    $gradestblname = $CFG->prefix."mootyper_grades";
    $userstblname = $CFG->prefix."user";
    $exertblname = $CFG->prefix."mootyper_exercises";
    $atttblname = $CFG->prefix."mootyper_attempts";
    $sql = "SELECT ".$gradestblname.".id, "
                    .$userstblname.".firstname, "
                    .$userstblname.".lastname, "
                    .$atttblname.".suspicion, "
                    .$gradestblname.".mistakes, "
                    .$gradestblname.".timeinseconds, "
                    .$gradestblname.".hitsperminute, "
                    .$gradestblname.".fullhits, "
                    .$gradestblname.".precisionfield, "
                    .$gradestblname.".pass, "
                    .$gradestblname.".timetaken, "
                    .$exertblname.".exercisename, "
                    .$gradestblname.".wpm".
    " FROM ".$gradestblname.
    " LEFT JOIN ".$userstblname." ON ".$gradestblname.".userid = ".$userstblname.".id".
    " LEFT JOIN ".$exertblname." ON ".$gradestblname.".exercise = ".$exertblname.".id".
    " LEFT JOIN ".$atttblname." ON ".$atttblname.".id = ".$gradestblname.".attemptid".
    " WHERE mootyper=".$sid." AND ".$gradestblname.".userid=".$uid;
    if ($orderby == 0 || $orderby == -1) {
        $oby = " ORDER BY ".$gradestblname.".id";
    } else if ($orderby == 1) {
        $oby = " ORDER BY ".$userstblname.".firstname";
    } else if ($orderby == 2) {
        $oby = " ORDER BY ".$userstblname.".lastname";
    } else if ($orderby == 3) {
        $oby = " ORDER BY ".$atttblname.".suspicion";
    } else if ($orderby == 4) {
        $oby = " ORDER BY ".$gradestblname.".mistakes";
    } else if ($orderby == 5) {
        $oby = " ORDER BY ".$gradestblname.".timeinseconds";
    } else if ($orderby == 6) {
        $oby = " ORDER BY ".$gradestblname.".hitsperminute";
    } else if ($orderby == 7) {
        $oby = " ORDER BY ".$gradestblname.".fullhits";
    } else if ($orderby == 8) {
        $oby = " ORDER BY ".$gradestblname.".precisionfield";
    } else if ($orderby == 9) {
        $oby = " ORDER BY ".$gradestblname.".timetaken";
    } else if ($orderby == 10) {
        $oby = " ORDER BY ".$exertblname.".exercisename";
    } else if ($orderby == 12) {
        $oby = " ORDER BY ".$gradestblname.".wpm";
    } else {
        $oby = "";
    }
    $sql .= $oby;
    if ($desc) {
        $sql .= " DESC";
    }
    if ($grades = $DB->get_records_sql($sql, $params)) {
        return $grades;
    }
    return false;
}

/**
 * Saves a new instance of the mootyper into the database.
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $mootyper An object from the form in mod_form.php
 * @param mod_mootyper_mod_form $mform
 * @return int The id of the newly inserted mootyper record.
 */
function mootyper_add_instance(stdClass $mootyper, mod_mootyper_mod_form $mform = null) {
    global $DB;
    $mootyper->timecreated = time();
    return $DB->insert_record('mootyper', $mootyper);
}

/**
 * Gets an instance of an exercise from the database.
 *
 * @param object $eid An exercise id.
 * @return int The id of the exercise.
 */
function get_exercise_record($eid) {
    global $DB;
    return $DB->get_record('mootyper_exercises', array('id' => $eid));
}

/**
 * Checks to see if exam is already done.
 *
 * @param object $mootyper
 * @param int $userid
 * @return boolean.
 */
function exam_already_done($mootyper, $userid) {
    global $DB;
    $table = 'mootyper_grades';
    $select = 'userid='.$userid.' AND mootyper='.$mootyper->id; // Is put into the where clause.
    $result = $DB->get_records_select($table, $select);
    if (!is_null($result) && count($result) > 0) {
        return true;
    }
    return false;
}

/**
 * Get next exercise to do.
 *
 * @param object $mootyperid
 * @param int $lessonid
 * @param int $userid
 * @return $lessonid
 */
function get_exercise_from_mootyper($mootyperid, $lessonid, $userid) {
    global $DB;
    $table = 'mootyper_grades';
    $select = 'userid='.$userid.' AND mootyper='.$mootyperid.' AND pass=1'; // Is put into the where clause.
    $result = $DB->get_records_select($table, $select);
    if (!is_null($result) && count($result) > 0) {
        $max = 0;
        foreach ($result as $grd) {
            $exrec = $DB->get_record('mootyper_exercises', array('id' => $grd->exercise));
            $zapst = $exrec->snumber;
            if ($zapst > $max) {
                $max = $zapst;
            }
        }
        return $DB->get_record('mootyper_exercises', array('snumber' => ($max + 1), 'lesson' => $lessonid));
    } else {
        return $DB->get_record('mootyper_exercises', array('snumber' => 1, 'lesson' => $lessonid));
    }
}

/**
 * Get a record.
 *
 * @param int $sid
 * @return $sid
 */
function jget_mootyper_record($sid) {
    global $DB;
    return $DB->get_record('mootyper', array('id' => $sid));
}
/**
 * Updates an instance of the mootyper in the database.
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $mootyper An object from the form in mod_form.php
 * @param mod_mootyper_mod_form $mform
 * @return boolean Success/Fail
 */
function mootyper_update_instance(stdClass $mootyper, mod_mootyper_mod_form $mform = null) {
    global $DB;
    $mootyper->timemodified = time();
    $mootyper->id = $mootyper->instance;
    return $DB->update_record('mootyper', $mootyper);
}

/**
 * Removes an instance of the mootyper from the database.
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function mootyper_delete_instance($id) {
    global $DB;
    $mootyper = $DB->get_record('mootyper', array('id' => $id), '*', MUST_EXIST);
    mootyper_delete_all_grades($mootyper);
    if (! $mootyper = $DB->get_record('mootyper', array('id' => $id))) {
        return false;
    }
    mootyper_delete_all_checks($id);
    $DB->delete_records('mootyper_attempts', array('mootyperid' => $id));
    $DB->delete_records('mootyper', array('id' => $mootyper->id));
    return true;
}

/**
 * Removes all checks from the database.
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $mid Id of the module instance
 */
function mootyper_delete_all_checks($mid) {
    global $DB;
    $rcs = $DB->get_records('mootyper_attempts', array('mootyperid' => $mid));
    foreach ($rcs as $at) {
        $DB->delete_records('mootyper_checks', array('attemptid' => $at->id));
    }
}

/**
 * Removes all grades from the database.
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $mootyper Id of the module instance
 */
function mootyper_delete_all_grades($mootyper) {
    global $DB;
    $DB->delete_records('mootyper_grades', array('mootyper' => $mootyper->id));
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module.
 * Used for user activity reports.
 * @param int $course
 * @param int $user
 * @param int $mod
 * @param int $mootyper
 * $return->time = The time they did it.
 * $return->info = A short text description.
 *
 * @return stdClass|null
 */
function mootyper_user_outline($course, $user, $mod, $mootyper) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = 'This is an outline.';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $mootyper the module instance record
 * @return void, is supposed to echp directly
 */
function mootyper_user_complete($course, $user, $mod, $mootyper) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in mootyper activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @param int $course
 * @param int $viewfullnames
 * @param int $timestart
 * @return boolean
 */
function mootyper_print_recent_activity($course, $viewfullnames, $timestart) {
    global $CFG, $USER, $DB, $OUTPUT;

    $dbparams = array($timestart, $course->id, 'mootyper');
    $namefields = user_picture::fields('u', null, 'userid');
    $sql = "SELECT mtg.id, mtg.mootyper, mtg.timetaken, cm.id AS cmid, $namefields
         FROM {mootyper_grades} mtg
              JOIN {mootyper} mt         ON mt.id = mtg.mootyper
              JOIN {course_modules} cm ON cm.instance = mt.id
              JOIN {modules} md        ON md.id = cm.module
              JOIN {user} u            ON u.id = mtg.userid
         WHERE mtg.timetaken > ? AND
               mt.course = ? AND
               md.name = ?
         ORDER BY mtg.timetaken ASC
    ";

    $newentries = $DB->get_records_sql($sql, $dbparams);

    $modinfo = get_fast_modinfo($course);
    $show    = array();
    $grader  = array();
    $showrecententries = get_config('mod_mootyper', 'showrecentactivity');

    foreach ($newentries as $anentry) {

        if (!array_key_exists($anentry->cmid, $modinfo->get_cms())) {
            continue;
        }
        $cm = $modinfo->get_cm($anentry->cmid);

        if (!$cm->uservisible) {
            continue;
        }
        if ($anentry->userid == $USER->id) {
            $show[] = $anentry;
            continue;
        }
        $context = context_module::instance($anentry->cmid);

        // The act of submitting of entries may be considered private -
        // only graders will see it if specified.
        if (empty($showrecententries)) {
            if (!array_key_exists($cm->id, $grader)) {
                $grader[$cm->id] = has_capability('moodle/grade:viewall', $context);
            }
            if (!$grader[$cm->id]) {
                continue;
            }
        }

        $groupmode = groups_get_activity_groupmode($cm, $course);

        if ($groupmode == SEPARATEGROUPS &&
                !has_capability('moodle/site:accessallgroups',  $context)) {
            if (isguestuser()) {
                // Shortcut - guest user does not belong into any group.
                continue;
            }

            // This will be slow - show only users that share group with me in this cm.
            if (!$modinfo->get_groups($cm->groupingid)) {
                continue;
            }
            $usersgroups = groups_get_all_groups($course->id, $anentry->userid, $cm->groupingid);
            if (is_array($usersgroups)) {
                $usersgroups = array_keys($usersgroups);
                $intersect = array_intersect($usersgroups, $modinfo->get_groups($cm->groupingid));
                if (empty($intersect)) {
                    continue;
                }
            }
        }
        $show[] = $anentry;
    }

    if (empty($show)) {
        return false;
    }

    echo $OUTPUT->heading(get_string('modulenameplural', 'mootyper').':', 3);

    foreach ($show as $submission) {
        $cm = $modinfo->get_cm($submission->cmid);
        $context = context_module::instance($submission->cmid);
        if (has_capability('mod/mootyper:viewgrades', $context)) {
            $link = $CFG->wwwroot.'/mod/mootyper/gview.php?id='.$cm->id.'&n='.$submission->mootyper;
        } else {
            $link = $CFG->wwwroot.'/mod/mootyper/owngrades.php?id='.$cm->id.'&n='.$submission->mootyper;
        }
        $name = $cm->name;
        print_recent_activity_note($submission->timetaken,
                                   $submission,
                                   $name,
                                   $link,
                                   false,
                                   $viewfullnames);
    }
    return true;
}

/**
 * Prepares the recent activity data.
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link mootyper_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function mootyper_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {mootyper_get_recent_mod_activity()}.
 * @param int $activity
 * @param int $courseid
 * @param int $detail
 * @param int $modnames
 * @param int $viewfullnames
 * @return void
 */
function mootyper_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron.
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function.
 **/
function mootyper_cron () {
    return true;
}

/**
 * Returns an array of users who are participanting in this mootyper.
 *
 * Must return an array of users who are participants for a given instance
 * of mootyper. Must include every user involved in the instance,
 * independient of his role (student, teacher, admin...). The returned
 * objects must contain at least id property.
 * See other modules as example.
 *
 * @param int $mootyperid ID of an instance of this module
 * @return boolean|array false if no participants, array of objects otherwise
 */
function mootyper_get_participants($mootyperid) {
    return false;
}

/**
 * Returns all other caps used in the module.
 *
 * @return array
 */
function mootyper_get_extra_capabilities() {
    return array();
}

// Gradebook API.

/**
 * Is a given scale used by the instance of mootyper?
 *
 * This function returns if a scale is being used by one mootyper
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $mootyperid ID of an instance of this module
 * @param int $scaleid
 * @return bool true if the scale is used by the given mootyper instance
 */
function mootyper_scale_used($mootyperid, $scaleid) {
    return false;
}

/**
 * Checks if scale is being used by any instance of mootyper.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid
 * @return boolean true if the scale is used by any mootyper instance
 */
function mootyper_scale_used_anywhere($scaleid) {
    return false;
}

/**
 * Creates or updates grade item for the given mootyper instance.
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $mootyper instance object with extra cmidnumber and modname property
 * @return void
 */
function mootyper_grade_item_update(stdClass $mootyper) {
    global $CFG;
    if (!function_exists('grade_update')) { // Workaround for buggy PHP versions.
        require_once($CFG->libdir.'/gradelib.php');
    }

    $params = array();
    $params['itemname'] = clean_param($mootyper->name, PARAM_NOTAGS);
    $params['gradetype'] = GRADE_TYPE_VALUE;
    $params['grademax']  = $mootyper->grade;
    $params['grademin']  = 0;

    if (!$mootyper->assessed or $mootyper->scale == 0) {
        $params['gradetype'] = GRADE_TYPE_NONE;
    } else if ($mootyper->scale > 0) {
        $params['gradetype'] = GRADE_TYPE_VALUE;
        $params['grademax']  = $mootyper->scale;
        $params['grademin']  = 0;
    } else if ($mootyper->scale < 0) {
        $params['gradetype'] = GRADE_TYPE_SCALE;
        $params['scaleid']   = -$mootyper->scale;
    }

    if ($grades === 'reset') {
        $params['reset'] = true;
        $grades = null;
    }

    // return grade_update('mod/mootyper', $mootyper->course, 'mod', 'mootyper', $mootyper->id, 0, null, $params);
    return grade_update('mod/mootyper', $mootyper->course, 'mod', 'mootyper', $mootyper->id, 0, $grades, $params);
}

/**
 * Update mootyper grades in the gradebook.
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php.
 *
 * @param stdClass $mootyper instance object with extra cmidnumber and modname property.
 * @param int $userid update grade of specific user only, 0 means all participants.
 * @return void
 */
/** function mootyper_update_grades(stdClass $mootyper, $userid = 0) {
 *    global $CFG, $DB;
 *    require_once($CFG->libdir.'/gradelib.php');
 *
 *    $grades = array(); // Populate array of grade objects indexed by userid.
 *
 *    grade_update('mod/mootyper', $mootyper->course, 'mod', 'mootyper', $mootyper->id, 0, $grades);
 *    }
 */

// New version to replace the above.
function mootyper_update_grades($mootyper, $userid=0, $nullifnone=true) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    if (!$mootyper->assessed) {
        mootyper_grade_item_update($mootyper);

    } else if ($grades = mootyper_get_user_grades($mootyper, $userid)) {
        mootyper_grade_item_update($mootyper, $grades);

    } else if ($userid and $nullifnone) {
        $grade = new stdClass();
        $grade->userid   = $userid;
        $grade->rawgrade = null;
        mootyper_grade_item_update($mootyper, $grade);

    } else {
        mootyper_grade_item_update($mootyper);
    }
}


/**
 * Called by course/reset.php
 *
 * @param stdClass $mform
 */
function mootyper_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'mootyperheader', get_string('modulenameplural', 'mootyper'));
    $mform->addElement('checkbox', 'reset_mootyper', get_string('resetmootyperall', 'mootyper'));
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 *
 *
 * @param stdClass $data
 * @return array
 */
function mootyper_reset_userdata($data) {
    global $DB;

    $status = array();
    if (!empty($data->reset_mootyper)) {
        $instances = $DB->get_records('mootyper', array('course' => $data->courseid));
        foreach ($instances as $instance) {
            if (reset_mootyper_instance($instance->id)) {
                $status[] = array('component' => get_string('modulenameplural', 'mootyper')
                , 'item' => get_string('resetmootyperall', 'mootyper')
                .': '.$instance->name, 'error' => false);
            }
        }
    }

    return $status;
}

/**
 * Clear all attempts and grades.
 *
 * This function will remove all attempts and grades from the specified
 * mootyper and clean up any related data.
 * @param int $mootyperid
 * @return boolean Success/Failure
 */
function reset_mootyper_instance($mootyperid) {
    global $DB;
    $attempts = $DB->get_records('mootyper_attempts', array('mootyperid' => $mootyperid));
    foreach ($attempts as $attempt) {
        if (! $DB->delete_records('mootyper_attempts', array('id' => $mootyperid))) {
            return false;
        }
    }

    if (! $DB->delete_records('mootyper_grades', array('mootyper' => $mootyperid))) {
        return false;
    }

    if (! $DB->delete_records('mootyper_attempts', array('mootyperid' => $mootyperid))) {
        return false;
    }

    return true;
}

// File API.

/**
 * Returns the lists of all browsable file areas within the given module context.
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function mootyper_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * Serves the files from the mootyper file areas
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return void this should never return to the caller
 */
function mootyper_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

// Navigation API.

/**
 * Extends the global navigation tree by adding mootyper nodes if there is a relevant content.
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the mootyper module instance.
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
/**
 * function mootyper_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
 *
 * }
 */

/**
 * Extends the settings navigation with the mootyper settings.
 *
 * This function is called when the context for the page is a mootyper module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $navref {@link navigation_node}
 */
function mootyper_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $navref) {
    global $PAGE, $DB;

    $cm = $PAGE->cm;
    if (!$cm) {
        return;
    }

    $context = $cm->context;
    $course = $PAGE->course;

    if (!$course) {
        return;
    }

    // Link to the Add new exercise / category page.
    if (has_capability('mod/mootyper:aftersetup', $cm->context)) {
        $link = new moodle_url('eins.php', array('id' => $course->id));
        $linkname = get_string('eaddnew', 'mootyper');
        $icon = new pix_icon('icon', '', 'mootyper', array('class' => 'icon'));
        $node = $navref->add($linkname, $link, navigation_node::TYPE_SETTING, null, null, $icon);
    }

    // Link to Import new exercise / category.
    if (has_capability('mod/mootyper:aftersetup', $cm->context)) {
        $link = new moodle_url('lsnimport.php', array('id' => $course->id));
        $linkname = get_string('lsnimport', 'mootyper');
        $icon = new pix_icon('icon', '', 'mootyper', array('class' => 'icon'));
        $node = $navref->add($linkname, $link, navigation_node::TYPE_SETTING, null, null, $icon);
    }

    // Link to exercise management page.
    if (has_capability('mod/mootyper:aftersetup', $cm->context)) {
        $link = new moodle_url('exercises.php', array('id' => $course->id));
        $linkname = get_string('editexercises', 'mootyper');
        $icon = new pix_icon('icon', '', 'mootyper', array('class' => 'icon'));
        $node = $navref->add($linkname, $link, navigation_node::TYPE_SETTING, null, null, $icon);
    }
}

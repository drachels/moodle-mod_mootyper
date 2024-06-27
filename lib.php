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
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
use mod_mootyper\local\lessons;

defined('MOODLE_INTERNAL') || die(); // phpcs:ignore

define('MOOTYPER_EVENT_TYPE_OPEN', 'open');
define('MOOTYPER_EVENT_TYPE_CLOSE', 'close');

/** Example constant.
 * define('NEWMODULE_ULTIMATE_ANSWER', 42);
 */

 // Moodle core API.

/**
 * Returns the information on whether the module supports a feature.
 *
 * @uses FEATURE_MOD_PURPOSE:
 * @uses FEATURE_BACKUP_MOODLE2
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_COMPLETION_HAS_RULES
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_RATE
 * @uses FEATURE_SHOW_DESCRIPTION
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function mootyper_supports($feature) {
    global $CFG;
    if ((int)$CFG->branch > 311) {
        if ($feature === FEATURE_MOD_PURPOSE) {
            return MOD_PURPOSE_COLLABORATION;
        }
    }
    switch ($feature) {
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_COMPLETION_HAS_RULES:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_GROUPS;
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_GROUPMEMBERSONLY:
            return true;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_RATE:
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
    $params = [];
    $toreturn = [];
    $gradestblname = $CFG->prefix."mootyper_grades";
    $userstblname = $CFG->prefix."user";
    $sql = "SELECT DISTINCT ".$userstblname.".id, "
                             .$userstblname.".firstname, "
                             .$userstblname.".lastname".
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
    $params = [];
    $toreturn = [];
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
                    .$gradestblname.".exercise, "
                    .$exertblname.".exercisename, "
                    .$gradestblname.".wpm,"
                    .$gradestblname.".grade,"
                    .$gradestblname.".mistakedetails".
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
    } else if ($orderby == 13) {
        $oby = " ORDER BY ".$gradestblname.".grade";
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
    $params = [];
    $toreturn = [];
    $gradestblname = $CFG->prefix."mootyper_grades";
    $userstblname = $CFG->prefix."user";
    $exertblname = $CFG->prefix."mootyper_exercises";
    $atttblname = $CFG->prefix."mootyper_attempts";
    // 20231216 Added $gradestblname.".exercise a couple of lines down from here, for testing.
    $sql = "SELECT ".$gradestblname.".id, "
                    .$gradestblname.".exercise, "
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
                    .$gradestblname.".wpm,"
                    .$gradestblname.".grade,"
                    .$gradestblname.".mistakedetails".
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
    } else if ($orderby == 13) {
        $oby = " ORDER BY ".$gradestblname.".grade";
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
function mootyper_add_instance($mootyper, $mform = null) {
    global $CFG, $DB;

    $mootyper->timecreated = time();

    if (empty($mootyper->assessed)) {
        $mootyper->assessed = 0;
    }

    if (empty($mootyper->ratingtime) || empty($mootyper->assessed)) {
        $mootyper->assesstimestart = 0;
        $mootyper->assesstimefinish = 0;
    }

    // Changed to add instance now instead of in the return, 02/15/19.
    $mootyper->id = $DB->insert_record('mootyper', $mootyper);

    // You may have to add extra stuff in here.
    // Added next line for behat test 20190211.
    $cmid = $mootyper->coursemodule;

    mootyper_update_calendar($mootyper, $cmid);
    mootyper_grade_item_update($mootyper);

    $completiontimeexpected = !empty($mootyper->completionexpected) ? $mootyper->completionexpected : null;
    \core_completion\api::update_completion_date_event($mootyper->coursemodule, 'mootyper', $mootyper->id, $completiontimeexpected);

    return $mootyper->id;
}

/**
 * Gets an instance of an exercise from the database.
 *
 * @param object $eid An exercise id.
 * @return int The id of the exercise.
 */
function get_exercise_record($eid) {
    global $DB;
    return $DB->get_record('mootyper_exercises', ['id' => $eid]);
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
 * Called from view.php file, around line 218.
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
    // Process result if it is not empty.
    if (!is_null($result) && count($result) > 0) {
        $max = 0;
        foreach ($result as $grd) {
            $exrec = $DB->get_record('mootyper_exercises', ['id' => $grd->exercise]);
            $zapst = $exrec->snumber;
            if ($zapst > $max) {
                $max = $zapst;
            }
        }
        // Return with the lesson ID and the next exercise snumber.
        return $DB->get_record('mootyper_exercises', ['snumber' => ($max + 1), 'lesson' => $lessonid]);
    } else {
        // Return with the lesson ID and the first exercise snumber.
        return $DB->get_record('mootyper_exercises', ['snumber' => 1, 'lesson' => $lessonid]);
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
    return $DB->get_record('mootyper', ['id' => $sid]);
}

/**
 * Updates an instance of the mootyper in the database.
 *
 * 20200808 Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $mootyper An object from the form in mod_form.php
 * @param mod_mootyper_mod_form $mform
 * @return boolean Success/Fail
 */
function mootyper_update_instance($mootyper, $mform) {
    global $CFG, $DB, $OUTPUT, $USER;

    $mootyper->timemodified = time();
    $mootyper->id = $mootyper->instance;

    if (empty($mootyper->assessed)) {
        $mootyper->assessed = 0;
    }

    if (empty($mootyper->assessed)) {
        $mootyper->assesstimestart = 0;
        $mootyper->assesstimefinish = 0;
    }

    if (empty($mootyper->timeopen)) {
        $mootyper->timeopen = 0;
    }
    if (empty($mootyper->timeclose)) {
        $mootyper->timeclose = 0;
    }

    $cmid = $mootyper->coursemodule;
    $cmidnumber = $mootyper->cmidnumber;
    $courseid = $mootyper->course;
    $mootyper->id = $mootyper->instance;
    $context = context_module::instance($cmid);
    $mootyper->timemodified = time();
    $mootyper->id = $mootyper->instance;

    $oldmootyper = $DB->get_record('mootyper', ['id' => $mootyper->id]);

    // MDL-3942 - if the aggregation type or scale (i.e. max grade) changes then
    // recalculate the grades for the entire mootyper if  scale changes - do we
    // need to recheck the ratings, if ratings higher than scale how do we want
    // to respond? For count and sum aggregation types the grade we check to make
    // sure they do not exceed the scale (i.e. max score) when calculating the grade.
    $updategrades = false;

    if ($oldmootyper->assessed <> $mootyper->assessed) {
        // Whether this mootyper is rated.
        $updategrades = true;
    }

    if ($oldmootyper->scale <> $mootyper->scale) {
        // The scale currently in use.
        $updategrades = true;
    }

    // 20200907 Skip grading options for Moodle less than v3.8.
    if ($CFG->branch > 37) {
        // 20230117 Fixes whole grades MTs created prior to adding Moodle grading to MooTyper.
        if (empty($oldmootyper->scale) && $oldmootyper->grade_mootyper > 0) {
            $mootyper->scale = $mootyper->grade_mootyper;
            // The whole mootyper grading.
            $updategrades = true;
        }
        if (empty($oldmootyper->grade_mootyper) || $oldmootyper->grade_mootyper <> $mootyper->grade_mootyper) {
            // The whole mootyper grading.
            $updategrades = true;
        }

        if ($updategrades) {
            mootyper_update_grades($mootyper); // Recalculate grades for the mootyper.
        }
    }

    // You may have to add extra stuff in here.
    mootyper_update_calendar($mootyper, $cmid);
    mootyper_grade_item_update($mootyper);

    $completiontimeexpected = !empty($mootyper->completionexpected) ? $mootyper->completionexpected : null;
    \core_completion\api::update_completion_date_event($mootyper->coursemodule, 'mootyper', $mootyper->id, $completiontimeexpected);

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

    $mootyper = $DB->get_record('mootyper', ['id' => $id], '*', MUST_EXIST);
    mootyper_delete_all_grades($mootyper);
    if (! $mootyper = $DB->get_record('mootyper', ['id' => $id])) {
        return false;
    }
    mootyper_delete_all_checks($id);
    $DB->delete_records('mootyper_attempts', ['mootyperid' => $id]);
    $DB->delete_records('mootyper', ['id' => $mootyper->id]);
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

    $rcs = $DB->get_records('mootyper_attempts', ['mootyperid' => $mid]);
    foreach ($rcs as $at) {
        $DB->delete_records('mootyper_checks', ['attemptid' => $at->id]);
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
    $DB->delete_records('mootyper_grades', ['mootyper' => $mootyper->id]);
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

    $dbparams = [$timestart, $course->id, 'mootyper'];
    // 20212611 Added Moodle branch check.
    if ($CFG->branch < 311) {
        $namefields = user_picture::fields('u', null, 'userid');
    } else {
        $userfieldsapi = \core_user\fields::for_userpic();
        $namefields = $userfieldsapi->get_sql('u', false, '', 'userid', false)->selects;;
    }
    $sql = "SELECT mtg.id, mtg.mootyper, mtg.timetaken, cm.id AS cmid, $namefields
         FROM {mootyper_grades} mtg
              JOIN {mootyper} mt       ON mt.id = mtg.mootyper
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
    $show = [];
    $grader = [];
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
 * mootyper_print_recent_mod_activity().
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
    return [];
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

    $rec = $DB->get_record("mootyper", ["id" => $mootyperid, "grade" => -$scaleid]);

    if (!empty($rec) && !empty($scaleid)) {
        $return = true;
    }

    return $return;
}

/**
 * Checks if scale is being used by any instance of mootyper.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid
 * @return boolean true if the scale is used by any mootyper instance
 */
function mootyper_scale_used_anywhere(int $scaleid): bool {
    global $DB;

    if (empty($scaleid)) {
        return false;
    }

    return $DB->record_exists('mootyper', ['scale' => $scaleid * -1]);
}

/**
 * Creates or updates grade item for the given mootyper instance.
 *
 * 20200808 Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @category grade
 * @uses GRADE_TYPE_NONE
 * @uses GRADE_TYPE_VALUE
 * @uses GRADE_TYPE_SCALE
 * @param stdClass $mootyper Instance object with extra cmidnumber and modname property
 * @param mixed $ratings
 * @param mixed $mootypergrades
 * @return int 0 if ok
 */
function mootyper_grade_item_update($mootyper, $ratings = null, $mootypergrades = null): void {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');
    // Update the rating.
    $item = [
        'itemname' => get_string('gradeitemnameforrating', 'mootyper', $mootyper),
        'idnumber' => $mootyper->cmidnumber,
    ];

    if (!$mootyper->assessed || $mootyper->scale == 0) {
        $item['gradetype'] = GRADE_TYPE_NONE;
    } else if ($mootyper->scale > 0) {
        $item['gradetype'] = GRADE_TYPE_VALUE;
        $item['grademax'] = $mootyper->scale;
        $item['grademin'] = 0;
    } else if ($mootyper->scale < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid'] = -$mootyper->scale;
    }
    if ($ratings === 'reset') {
        $item['reset'] = true;
        $ratings = null;
    }
    // Itemnumber 0 is the rating.
    grade_update('mod/mootyper', $mootyper->course, 'mod', 'mootyper', $mootyper->id, 0, $ratings, $item);

    // 20200907 Skip mootyper grading options for Moodle less than v3.8.
    if ($CFG->branch > 37) {
        // Whole mootyper grade.
        $item = [
            'itemname' => get_string('gradeitemnameforwholemootyper', 'mootyper', $mootyper),
            // Note: We do not need to store the idnumber here.
        ];

        if (empty($mootyper->grade_mootyper)) {
            $item['gradetype'] = GRADE_TYPE_NONE;
        } else if ($mootyper->grade_mootyper > 0) {
            $item['gradetype'] = GRADE_TYPE_VALUE;
            $item['grademax'] = $mootyper->grade_mootyper;
            $item['grademin'] = 0;
        } else if ($mootyper->grade_mootyper < 0) {
            $item['gradetype'] = GRADE_TYPE_SCALE;
            $item['scaleid'] = $mootyper->grade_mootyper * -1;
        }

        if ($mootypergrades === 'reset') {
            $item['reset'] = true;
            $mootypergrades = null;
        }
        // Itemnumber 1 is the whole mootyper grade.
        grade_update('mod/mootyper', $mootyper->course, 'mod', 'mootyper', $mootyper->id, 1, $mootypergrades, $item);
    }
}

/**
 * Update mootyper grades in the gradebook.
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php.
 * New version to replace the one above.
 * @param stdClass $mootyper instance object with extra cmidnumber and modname property.
 * @param int $userid update grade of specific user only, 0 means all participants.
 * @return void
 */
function mootyper_update_grades($mootyper, $userid=0): void {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    $cm = get_coursemodule_from_instance('mootyper', $mootyper->id);
    $mootyper->cmidnumber = $cm->idnumber;
    $ratings = null;

    if ($mootyper->assessed) {
        // I think this is the Whole MooTyper grade stuff.
        require_once($CFG->dirroot.'/rating/lib.php');
        $rm = new rating_manager();
        $ratings = $rm->get_user_grades((object) [
            'component' => 'mod_mootyper',
            'ratingarea' => 'exercises',
            'contextid' => \context_module::instance($cm->id)->id,

            'modulename' => 'mootyper',
            'moduleid  ' => $mootyper->id,
            'userid' => $userid,
            'aggregationmethod' => $mootyper->assessed,
            'scaleid' => $mootyper->scale,
            'itemtable' => 'mootyper_grades',
            'itemtableusercolumn' => 'userid',
        ]);
    }

    $mootypergrades = null;
    if (($mootyper->requiredgoal) || ($mootyper->requiredwpm)) {
        $sql = "SELECT g.userid,
                       0 as datesubmitted,
                       g.grade as rawgrade,
                       g.timetaken as dategraded,
                       g.mistakedetails
                  FROM {mootyper} m
                  JOIN {mootyper_grades} g ON g.mootyper = m.id
                 WHERE m.id = :mootyperid";

        $params = [
            'mootyperid' => $mootyper->id,
        ];

        if ($userid) {
            $sql .= " AND g.userid = :userid";
            $params['userid'] = $userid;
        }
        $mootypergrades = [];
        if ($grades = $DB->get_recordset_sql($sql, $params)) {
            foreach ($grades as $userid => $grade) {
                if ($grade->rawgrade != -1) {
                    $grade->feedback = $grade->mistakedetails;
                    $mootypergrades[$userid] = $grade;
                    $course = $DB->get_record('course', ['id' => $mootyper->course]);
                    // 20240122 I think this needs to be $course.
                    $ci = new completion_info($course);
                    if ($cm->completion == COMPLETION_TRACKING_AUTOMATIC) {
                        $ci->update_state($cm, COMPLETION_UNKNOWN, $grade->userid);
                    }

                }
            }
            $grades->close();
        }
    }
    mootyper_grade_item_update($mootyper, $ratings, $mootypergrades);
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

    $status = [];
    if (!empty($data->reset_mootyper)) {
        $instances = $DB->get_records('mootyper', ['course' => $data->courseid]);
        foreach ($instances as $instance) {
            if (reset_mootyper_instance($instance->id)) {
                $status[] = ['component' => get_string('modulenameplural', 'mootyper')
                , 'item' => get_string('resetmootyperall', 'mootyper')
                .': '.$instance->name, 'error' => false, ];
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

    $attempts = $DB->get_records('mootyper_attempts', ['mootyperid' => $mootyperid]);

    foreach ($attempts as $attempt) {
        // 20230708 Added to delete any checks for this attempt.
        $DB->delete_records('mootyper_checks', ['attemptid' => $attempt->id]);
        if (!$DB->delete_records('mootyper_attempts', ['id' => $mootyperid])) {
            return false;
        }
    }
    if (!$DB->delete_records('mootyper_grades', ['mootyper' => $mootyperid])) {
        return false;
    }
    if (!$DB->delete_records('mootyper_attempts', ['mootyperid' => $mootyperid])) {
        return false;
    }
    return true;
}

// File API.

/**
 * Returns the lists of all browsable file areas within the given module context.
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by file_browser::get_file_info_context_module().
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function mootyper_get_file_areas($course, $cm, $context) {
    return [];
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


/**
 * Update the calendar entries for this mootyper activity.
 *
 * @param stdClass $mootyper the row from the database table mootyper.
 * @param int $cmid The coursemodule id
 * @return bool
 */
function mootyper_update_calendar(stdClass $mootyper, $cmid) {
    global $DB, $CFG;

    if ($CFG->branch > 30) { // If Moodle less than version 3.1 skip this.
        require_once($CFG->dirroot.'/calendar/lib.php');

        // Get CMID if not sent as part of $mootyper.
        if (!isset($mootyper->coursemodule)) {
            $cm = get_coursemodule_from_instance('mootyper', $mootyper->id, $mootyper->course);
            $mootyper->coursemodule = $cm->id;
        }

        // Mootyper start calendar events.
        $event = new stdClass();
        $event->eventtype = MOOTYPER_EVENT_TYPE_OPEN;
        // The MOOTYPER_EVENT_TYPE_OPEN event should only be an action event if no close time is specified.
        $event->type = empty($mootyper->timeclose) ? CALENDAR_EVENT_TYPE_ACTION : CALENDAR_EVENT_TYPE_STANDARD;
        if ($event->id = $DB->get_field('event', 'id',
            ['modulename' => 'mootyper', 'instance' => $mootyper->id, 'eventtype' => $event->eventtype])) {
            if ((!empty($mootyper->timeopen)) && ($mootyper->timeopen > 0)) {
                // Calendar event exists so update it.
                $event->name = get_string('calendarstart', 'mootyper', $mootyper->name);
                $event->description = format_module_intro('mootyper', $mootyper, $cmid);
                $event->timestart = $mootyper->timeopen;
                $event->timesort = $mootyper->timeopen;
                $event->visible = instance_is_visible('mootyper', $mootyper);
                $event->timeduration = 0;

                $calendarevent = calendar_event::load($event->id);
                $calendarevent->update($event, false);
            } else {
                // Calendar event is no longer needed.
                $calendarevent = calendar_event::load($event->id);
                $calendarevent->delete();
            }
        } else {
            // Event doesn't exist so create one.
            if ((!empty($mootyper->timeopen)) && ($mootyper->timeopen > 0)) {
                $event->name = get_string('calendarstart', 'mootyper', $mootyper->name);
                $event->description = format_module_intro('mootyper', $mootyper, $cmid);
                $event->courseid = $mootyper->course;
                $event->groupid = 0;
                $event->userid = 0;
                $event->modulename = 'mootyper';
                $event->instance = $mootyper->id;
                $event->timestart = $mootyper->timeopen;
                $event->timesort = $mootyper->timeopen;
                $event->visible = instance_is_visible('mootyper', $mootyper);
                $event->timeduration = 0;

                calendar_event::create($event, false);
            }
        }

        // Mootyper end calendar events.
        $event = new stdClass();
        $event->type = CALENDAR_EVENT_TYPE_ACTION;
        $event->eventtype = MOOTYPER_EVENT_TYPE_CLOSE;
        if ($event->id = $DB->get_field('event', 'id',
            ['modulename' => 'mootyper', 'instance' => $mootyper->id, 'eventtype' => $event->eventtype])) {
            if ((!empty($mootyper->timeclose)) && ($mootyper->timeclose > 0)) {
                // Calendar event exists so update it.
                $event->name = get_string('calendarend', 'mootyper', $mootyper->name);
                $event->description = format_module_intro('mootyper', $mootyper, $cmid);
                $event->timestart = $mootyper->timeclose;
                $event->timesort = $mootyper->timeclose;
                $event->visible = instance_is_visible('mootyper', $mootyper);
                $event->timeduration = 0;

                $calendarevent = calendar_event::load($event->id);
                $calendarevent->update($event, false);
            } else {
                // Calendar event is on longer needed.
                $calendarevent = calendar_event::load($event->id);
                $calendarevent->delete();
            }
        } else {
            // Event doesn't exist so create one.
            if ((!empty($mootyper->timeclose)) && ($mootyper->timeclose > 0)) {
                $event->name = get_string('calendarend', 'mootyper', $mootyper->name);
                $event->description = format_module_intro('mootyper', $mootyper, $cmid);
                $event->courseid = $mootyper->course;
                $event->groupid = 0;
                $event->userid = 0;
                $event->modulename = 'mootyper';
                $event->instance = $mootyper->id;
                $event->timestart = $mootyper->timeclose;
                $event->timesort = $mootyper->timeclose;
                $event->visible = instance_is_visible('mootyper', $mootyper);
                $event->timeduration = 0;

                calendar_event::create($event, false);
            }
        }

        return true;
    }
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
 * @param settings_navigation $settingsnav
 * @param navigation_node $navref
 */
function mootyper_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $navref) {
    global $PAGE, $DB, $USER;

    $cm = $PAGE->cm;
    if (!$cm) {
        return;
    }

    $context = $cm->context;
    $course = $PAGE->course;

    if (!$course) {
        return;
    }

    // Link to the Add new lessons w/exercises page. Visible to any teacher.
    if (has_capability('mod/mootyper:aftersetup', $cm->context)) {
        $link = new moodle_url('/mod/mootyper/eins.php', ['id' => $cm->id]);
        $linkname = get_string('eaddnew', 'mootyper');
        $icon = new pix_icon('icon', '', 'mootyper', ['class' => 'icon']);
        $node = $navref->add($linkname, $link, navigation_node::TYPE_SETTING, null, null, $icon);
    }

    // Link to lessons w/exercises management page. Visible only if can edit.
    if (has_capability('mod/mootyper:aftersetup', $cm->context)) {
        // 02/24/2020 Change to be like other modules code base.
        $mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);
        $lesson = $DB->get_record('mootyper_lessons', ['id' => $mootyper->lesson], '*');
        // 20200627 Modified to show link only if user can edit the current lesson.
        if ($lesson) {
            if (lessons::is_editable_by_me($USER->id, $mootyper->id, $lesson->id)) {
                $link = new moodle_url('/mod/mootyper/exercises.php', ['id' => $cm->id, 'lesson' => $mootyper->lesson]);
                $linkname = get_string('editexercises', 'mootyper');
                $icon = new pix_icon('icon', '', 'mootyper', ['class' => 'icon']);
                $node = $navref->add($linkname, $link, navigation_node::TYPE_SETTING, null, null, $icon);
            }
        }
    }

    // Link to Import new lessons w/exercises and new keyboard layouts. Visible to admin only.
    if (is_siteadmin()) {
        $link = new moodle_url('/mod/mootyper/lsnimport.php', ['id' => $cm->id]);
        $linkname = get_string('lsnimport', 'mootyper');
        $icon = new pix_icon('icon', '', 'mootyper', ['class' => 'icon']);
        $node = $navref->add($linkname, $link, navigation_node::TYPE_SETTING, null, null, $icon);
    }

    // 20201028 Link to remove keyboard layouts. Visible to siteadmin only.
    if (is_siteadmin()) {
        $link = new moodle_url('/mod/mootyper/layouts.php', ['id' => $cm->id]);
        $linkname = get_string('loheading', 'mootyper');
        $icon = new pix_icon('icon', '', 'mootyper', ['class' => 'icon']);
        $node = $navref->add($linkname, $link, navigation_node::TYPE_SETTING, null, null, $icon);
    }
}

/**
 * Add a get_coursemodule_info function in case any mootyper wants to add 'extra' information
 * for the course (see resource).
 *
 * Given a course_module stdClass, this function returns any "extra" information that may be needed
 * when printing this activity in a course listing.  See get_array_of_activities() in course/lib.php.
 *
 * @param stdClass $coursemodule The coursemodule stdClass (record).
 * @return cached_cm_info A stdClass of information that the courses
 *                        will know about (most noticeably, an icon).
 */
function mootyper_get_coursemodule_info($coursemodule) {
    global $DB;

    $dbparams = ['id' => $coursemodule->instance];
    $fields = 'id,
              name,
              intro,
              introformat,
              completionexercise,
              completionlesson,
              completionprecision,
              completionwpm,
              completionmootypergrade,
              timeopen,
              timeclose,
              assesstimestart,
              assesstimefinish';
    if (!$mootyper = $DB->get_record('mootyper', $dbparams, $fields)) {
        return false;
    }

    $result = new cached_cm_info();
    $result->name = $mootyper->name;

    if ($coursemodule->showdescription) {
        // Convert intro to html. Do not filter cached version, filters run at display time.
        $result->content = format_module_intro('mootyper', $mootyper, $coursemodule->id, false);
    }

    // Populate the custom completion rules as key => value pairs, but only if the completion mode is 'automatic'.
    if ($coursemodule->completion == COMPLETION_TRACKING_AUTOMATIC) {
        $result->customdata['customcompletionrules']['completionexercise'] = $mootyper->completionexercise;
        $result->customdata['customcompletionrules']['completionlesson'] = $mootyper->completionlesson;
        $result->customdata['customcompletionrules']['completionprecision'] = $mootyper->completionprecision;
        $result->customdata['customcompletionrules']['completionwpm'] = $mootyper->completionwpm;
        $result->customdata['customcompletionrules']['completionmootypergrade'] = $mootyper->completionmootypergrade;
    }

    // Populate some other values that can be used in calendar or on dashboard.
    if ($mootyper->timeopen) {
        $result->customdata['timeopen'] = $mootyper->timeopen;
    }
    if ($mootyper->timeclose) {
        $result->customdata['timeclose'] = $mootyper->timeclose;
    }
    if ($mootyper->assesstimestart) {
        $result->customdata['assesstimestart'] = $mootyper->assesstimestart;
    }
    if ($mootyper->assesstimefinish) {
        $result->customdata['assesstimefinish'] = $mootyper->assesstimefinish;
    }
    return $result;
}

/**
 * Callback which returns human-readable strings describing the active completion custom rules for the module instance.
 *
 * @param cm_info|stdClass $cm stdClass with fields ->completion and ->customdata['customcompletionrules']
 * @return array $descriptions the array of descriptions for the custom rules.
 */
function mod_mootyper_get_completion_active_rule_descriptions($cm) {
    // Values will be present in cm_info, and we assume these are up to date.
    if (empty($cm->customdata['customcompletionrules'])
        || $cm->completion != COMPLETION_TRACKING_AUTOMATIC) {
        return [];
    }

    $descriptions = [];
    foreach ($cm->customdata['customcompletionrules'] as $key => $val) {
        switch ($key) {
            case 'completionexercise':
                if (!empty($val)) {
                    $descriptions[] = get_string('completionexercisedesc', 'mootyper', $val);
                }
                break;
            case 'completionlesson':
                if (!empty($val)) {
                    $descriptions[] = get_string('completionlessondesc', 'mootyper', $val);
                }
                break;
            case 'completionprecision':
                if (!empty($val)) {
                    $descriptions[] = get_string('completionprecisiondesc', 'mootyper', $val);
                }
                break;
            case 'completionwpm':
                if (!empty($val)) {
                    $descriptions[] = get_string('completionwpmdesc', 'mootyper', $val);
                }
                break;
            case 'completionmootypergrade':
                if (!empty($val)) {
                    $descriptions[] = get_string('completionmootypergradedesc', 'mootyper', $val);
                }
                break;
            default:
                break;
        }
    }
    return $descriptions;
}

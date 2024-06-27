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
 * Lesson and Exercises utilities for MooTyper.
 *
 * 3/20/2020 Moved these functions from locallib.php to here.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_mootyper\local;
use mod_mootyper\local\lessons;
defined('MOODLE_INTERNAL') || die(); // phpcs:ignore
/**
 * Utility class for counting keyboards and so on.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lessons {

    /**
     * 20160322 Changed call from mod_setup so this is no longer used by exercises.php.
     * Currently this is only used by eins.php which does not need the extra parameters
     * brought in like exercise.php does.
     *
     * Get the current lesson.
     *
     * @return string
     */
    public static function get_typerlessons() {
        global $CFG, $DB;
        $params = [];
        $lstoreturn = [];
        $sql = "SELECT id, lessonname
               FROM ".$CFG->prefix."mootyper_lessons
               ORDER BY lessonname";
        if ($lessons = $DB->get_records_sql($sql, $params)) {
            foreach ($lessons as $ex) {
                $lss = [];
                $lss['id'] = $ex->id;
                $lss['lessonname'] = $ex->lessonname;
                $lstoreturn[] = $lss;
            }
        }
        return $lstoreturn;
    }

    /**
     * Improved get_typerlessons() function.
     * Modified 20160322 to improve reliability of correctly listing edit/remove capability.
     *
     * If correct user and in a course, get list of lessons.
     * @param int $u
     * @param int $c
     * @return string
     */
    public static function get_mootyperlessons($u, $c) {
        global $CFG, $DB;
        $params = [];
        $lstoreturn = []; // DETERMINE IF USER IS INSIDE A COURSE???
        // 20191124 Changed SQL for Postgre compatibility based on issue #34.
        $sql = "SELECT id, lessonname
            FROM ".$CFG->prefix."mootyper_lessons
            WHERE ((visible = 2 AND authorid = ".$u.") OR
                (visible = 1 AND ".self::is_user_enrolled($u, $c)." = 1) OR
                (visible = 0 AND ".self::is_user_enrolled($u, $c)." = 1) OR
                (".self::can_view_edit_all($u, $c)." = 1))
            ORDER BY lessonname";

        if ($lessons = $DB->get_records_sql($sql, $params)) {
            foreach ($lessons as $ex) {
                $lss = [];
                $lss['id'] = $ex->id;
                $lss['lessonname'] = $ex->lessonname;
                $lstoreturn[] = $lss;
            }
        }
        return $lstoreturn;
    }

    /**
     * Check if admin or other user.
     * 22 Mar 16 Changed so that ONLY someone who is a site admin can modify sample lessons.
     * Old method allowed everyone to modify everything.
     * @param int $usr
     * @param int $c
     * @return boolean
     */
    public static function can_view_edit_all($usr, $c) {
        if (is_siteadmin($usr)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 20191211 Modified to add missing compare of current
     * course to courseid listed in the lesson. TRK1-315.
     *
     * Check if current user can edit.
     *
     * @param int $usr
     * @param int $id
     * @param int $lsn
     * @return boolean
     */
    public static function is_editable_by_me($usr, $id, $lsn) {
        global $DB;
        $lesson = $DB->get_record('mootyper_lessons', ['id' => $lsn]);
        if (is_null($lesson->courseid)) {
            $crs = 0;
        } else {
            $crs = $lesson->courseid;
        }

        if (($lesson->editable == 0)
            // 20200625 Fix for ticket MooTyper_548. When editable is 1, changed second $id to $crs.
            || (($lesson->editable == 1) && (self::is_user_enrolled($usr, $id)) && ($crs == $lesson->courseid))
            || (($lesson->editable == 2) && ($lesson->authorid == $usr))
            || (self::can_view_edit_all($usr, $crs))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get current exercise name for current lesson.
     *
     * @return string
     */
    public static function get_typerexercises() {
        global $USER, $CFG, $DB;
        $params = [];
        $exestoreturn = [];
        $sql = "SELECT id, exercisename
                FROM ".$CFG->prefix."mootyper_exercises";
        if ($exercises = $DB->get_records_sql($sql, $params)) {
            foreach ($exercises as $ex) {
                $exestoreturn[$ex->id] = $ex->exercisename;
            }
        }
        return $exestoreturn;
    }

    /**
     * Get current exercise for current lesson.
     *
     * @param int $less
     * @return string
     */
    public static function get_exercises_by_lesson($less) {
        global $DB;
        $params = ['less' => $less];
        $lessonpo = [];
        $sql = "SELECT * FROM {mootyper_exercises} WHERE lesson = :less";
        if ($exercises = $DB->get_records_sql($sql, $params)) {
            foreach ($exercises as $ex) {
                $exestoreturn = [];
                $exestoreturn['id'] = $ex->id;
                $exestoreturn['exercisename'] = $ex->exercisename;
                $exestoreturn['snumber'] = $ex->snumber;
                $lessonpo[] = $exestoreturn;
            }
        }
        return $lessonpo;
    }

    /**
     * Get keystroke count for this lesson.
     *
     * @param int $lsnid
     * @return int
     */
    public static function get_new_snumber($lsnid) {
        $exes = self::get_exercises_by_lesson($lsnid);
        if (count($exes) == 0) {
            return 1;
        }
        $max = $exes[0]['snumber'];
        for ($i = 0; $i < count($exes); $i++) {
            if ($exes[$i]['snumber'] > $max) {
                $max = $exes[$i]['snumber'];
            }
        }
        return $max + 1;
    }

    /**
     * Get info for this lesson.
     *
     * @param int $lsn
     * @return array
     */
    public static function get_typerexercisesfull($lsn = 0) {
        global $USER, $CFG, $DB;
        $params = [];
        $toreturn = [];
        $sql = "SELECT * FROM ".$CFG->prefix."mootyper_exercises WHERE lesson=".$lsn." OR 0=".$lsn;
        if ($exercises = $DB->get_records_sql($sql, $params)) {
            foreach ($exercises as $ex) {
                $exestoreturn = [];
                $exestoreturn['id'] = $ex->id;
                $exestoreturn['exercisename'] = $ex->exercisename;
                $exestoreturn['texttotype'] = $ex->texttotype;
                $exestoreturn['snumber'] = $ex->snumber;
                $exestoreturn['dictationdata'] = $ex->dictationdata;
                $exestoreturn['dictationdataformat'] = $ex->dictationdataformat;
                $toreturn[] = $exestoreturn;
            }
        }
        return $toreturn;
    }

    /** 160322 Modified Where clause. Previously, it was comparing a
     * course number to modifierid which was never going to match
     * except in the very rare case of being in course 2 in all of my Moodles.
     *
     * Check to see if user is enrolled in current course.
     * @param int $usr
     * @param int $crs
     * @return string
     */
    public static function is_user_enrolled($usr, $crs) {
        global $DB, $CFG;

        $params = [];
        $params[] = $usr;
        $sql2 = "SELECT * FROM ".$CFG->prefix."user_enrolments
                 WHERE userid = ?";
        $enrolls = $DB->get_records_sql($sql2, $params);
        $rt = count($enrolls) > 0 ? 1 : 0;

        return $rt;
    }

}

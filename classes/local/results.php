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
 * Keyboard utilities for MooTyper.
 *
 * 3/19/2020 Moved these functions from locallib.php to here.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_mootyper\local;
defined('MOODLE_INTERNAL') || die(); // @codingStandardsIgnoreLine

/**
 * Utility class for MooTyper results.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class results {


    /**
     * Get the last keystroke and check if correct.
     * @param int $mid
     */
    public static function get_last_check($mid) {
        global $USER, $DB, $CFG;
        $sql = "SELECT * FROM ".$CFG->prefix."mootyper_checks".
               " JOIN ".$CFG->prefix."mootyper_attempts ON "
                       .$CFG->prefix."mootyper_attempts.id = "
                       .$CFG->prefix."mootyper_checks.attemptid".
               " WHERE ".$CFG->prefix."mootyper_attempts.mootyperid = "
                        .$mid." AND "
                        .$CFG->prefix."mootyper_attempts.userid = "
                        .$USER->id.
               " AND ".$CFG->prefix."mootyper_attempts.inprogress = 1".
               " ORDER BY ".$CFG->prefix."mootyper_checks.checktime DESC LIMIT 1";
        if ($rec = $DB->get_record_sql($sql, [])) {
            return $rec;
        } else {
            return null;
        }
    }

    /**
     * Check for suspicous results.
     * @param int $checks
     * @param int $starttime
     * @return boolean
     */
    public static function suspicion($checks, $starttime) {
        for ($i = 1; $i < count($checks); $i++) {
            // 20220724 Translate says udarci = blows. English might be better to use, strokes1 and 2, or check1 and check2?
            $udarci1 = $checks[$i]['mistakes'] + $checks[$i]['hits'];
            $udarci2 = $checks[($i - 1)]['mistakes'] + $checks[($i - 1)]['hits'];
            // 20220724 If current hit count is suddenly 60 strokes higher than last check, mark as suspicious.
            if ($udarci2 > ($udarci1 + 60)) {
                return true;
            }
            // 20220724 If it has been more than ten minutes since the last check, mark as suspicious.
            if ($checks[($i - 1)]['checktime'] > ($starttime + 300)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculate averages (mean).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_avg($grades) {
        // 20200704 Added code to include average date of completion and average wpm.
        $avg = [];
        $avg['mistakes'] = 0;
        $avg['timeinseconds'] = 0;
        $avg['hitsperminute'] = 0;
        $avg['fullhits'] = 0;
        $avg['precisionfield'] = 0;
        $avg['timetaken'] = 0;
        $avg['wpm'] = 0;
        $avg['grade'] = 0;
        foreach ($grades as $g) {
            $avg['mistakes'] += $g->mistakes;
            $avg['timeinseconds'] += $g->timeinseconds;
            $avg['hitsperminute'] += $g->hitsperminute;
            $avg['fullhits'] += $g->fullhits;
            $avg['precisionfield'] += $g->precisionfield;
            $avg['timetaken'] += $g->timetaken;
            $avg['wpm'] += $g->wpm;
            $avg['grade'] += $g->grade;
        }
        $c = count($grades);
        $avg['mistakes'] = $avg['mistakes'] / $c;
        $avg['timeinseconds'] = $avg['timeinseconds'] / $c;
        $avg['hitsperminute'] = $avg['hitsperminute'] / $c;
        $avg['fullhits'] = $avg['fullhits'] / $c;
        $avg['precisionfield'] = $avg['precisionfield'] / $c;
        $avg['timetaken'] = $avg['timetaken'] / $c;
        $avg['wpm'] = $avg['wpm'] / $c;
        $avg['grade'] = $avg['grade'] / $c;
        // Due to limited display space, round off the results.
        $avg['mistakes'] = round($avg['mistakes'], 0);
        $avg['timeinseconds'] = round($avg['timeinseconds'], 0);
        $avg['hitsperminute'] = round($avg['hitsperminute'], 2);
        $avg['fullhits'] = round($avg['fullhits'], 0);
        $avg['precisionfield'] = round($avg['precisionfield'], 2);
        $avg['timetaken'] = round($avg['timetaken'], 0);
        $avg['wpm'] = round($avg['wpm'], 2);
        $avg['grade'] = round($avg['grade'], 2);
        return $avg;
    }

    /**
     * Calculate mean (average).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_mean($grades) {
        $mean = [];
        $mean['mistakes'] = 0;
        $mean['timeinseconds'] = 0;
        $mean['hitsperminute'] = 0;
        $mean['fullhits'] = 0;
        $mean['precisionfield'] = 0;
        $mean['timetaken'] = 0;
        $mean['wpm'] = 0;
        $mean['grade'] = 0;
        foreach ($grades as $g) {
            $mean['mistakes'] += $g->mistakes;
            $mean['timeinseconds'] += $g->timeinseconds;
            $mean['hitsperminute'] += $g->hitsperminute;
            $mean['fullhits'] += $g->fullhits;
            $mean['precisionfield'] += $g->precisionfield;
            $mean['timetaken'] += $g->timetaken;
            $mean['wpm'] += $g->wpm;
            $mean['grade'] += $g->grade;
        }
        $c = count($grades);
        $mean['mistakes'] = $mean['mistakes'] / $c;
        $mean['timeinseconds'] = $mean['timeinseconds'] / $c;
        $mean['hitsperminute'] = $mean['hitsperminute'] / $c;
        $mean['fullhits'] = $mean['fullhits'] / $c;
        $mean['precisionfield'] = $mean['precisionfield'] / $c;
        $mean['timetaken'] = $mean['timetaken'] / $c;
        $mean['wpm'] = $mean['wpm'] / $c;
        $mean['grade'] = $mean['grade'] / $c;

        // Due to limited display space, round off the results.
        $mean['mistakes'] = round($mean['mistakes'], 2);
        $mean['timeinseconds'] = round($mean['timeinseconds'], 2);
        $mean['hitsperminute'] = round($mean['hitsperminute'], 2);
        $mean['fullhits'] = round($mean['fullhits'], 0);
        $mean['precisionfield'] = round($mean['precisionfield'], 2);
        $mean['timetaken'] = round($mean['timetaken'], 0);
        $mean['wpm'] = round($mean['wpm'], 2);
        $mean['grade'] = round($mean['grade'], 2);
        return $mean;
    }

    /**
     * Calculate median (middle).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_median($grades) {
        $median = [];
        $mistakes = [];
        $timeinseconds = [];
        $hitsperminute = [];
        $fullhits = [];
        $precisionfield = [];
        $timetaken = [];
        $wpm = [];
        $grade = [];

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $grade[$c] = $g->grade;
            $c = $c - 1;
        }

        sort($mistakes);
        $count = count($mistakes);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $mistakes[$index];
        } else {                   // Count is even.
            $total = ($mistakes[$index - 1] + $mistakes[$index]) / 2;
        }
        $median['mistakes'] = $total;

        sort($timeinseconds);
        $count = count($timeinseconds);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $timeinseconds[$index];
        } else {                   // Count is even.
            $total = ($timeinseconds[$index - 1] + $timeinseconds[$index]) / 2;
        }
        $median['timeinseconds'] = $total;

        sort($hitsperminute);
        $count = count($hitsperminute);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $hitsperminute[$index];
        } else {                   // Count is even.
            $total = ($hitsperminute[$index - 1] + $hitsperminute[$index]) / 2;
        }
        $median['hitsperminute'] = $total;

        sort($fullhits);
        $count = count($fullhits);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $fullhits[$index];
        } else {                   // Count is even.
            $total = ($fullhits[$index - 1] + $fullhits[$index]) / 2;
        }
        $median['fullhits'] = $total;

        sort($precisionfield);
        $count = count($precisionfield);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $precisionfield[$index];
        } else {                   // Count is even.
            $total = ($precisionfield[$index - 1] + $precisionfield[$index]) / 2;
        }
        $median['precisionfield'] = $total;

        sort($timetaken);
        $count = count($timetaken);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $timetaken[$index];
        } else {                   // Count is even.
            $total = ($timetaken[$index - 1] + $timetaken[$index]) / 2;
        }
        $median['timetaken'] = $total;

        sort($wpm);
        $count = count($wpm);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $wpm[$index];
        } else {                   // Count is even.
            $total = ($wpm[$index - 1] + $wpm[$index]) / 2;
        }
        $median['wpm'] = $total;

        sort($grade);
        $count = count($grade);
        $index = floor($count / 2);
        if (!$count) {
            $total = "no values";
        } else if ($count & 1) {    // Count is odd.
            $total = $grade[$index];
        } else {                   // Count is even.
            $total = ($grade[$index - 1] + $grade[$index]) / 2;
        }
        $median['grade'] = $total;

        return $median;
    }

    /**
     * Calculate mode (item with highest count).
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_mode($grades) {
        $mode = [];
        $mistakes = [];
        $timeinseconds = [];
        $hitsperminute = [];
        $fullhits = [];
        $precisionfield = [];
        $timetaken = [];
        $wpm = [];
        $grade = [];

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            // Convert to date and disregard the seconds so we can get
            // mode to nearest month, day, year, hour and minute.
            $timetaken[$c] = date(get_config('mod_mootyper', 'dateformat'), $g->timetaken);
            $wpm[$c] = $g->wpm;
            $grade[$c] = $g->grade;
            $c = $c - 1;
        }

        // Calculate mode for Total Mistakes.
        $v = array_count_values($mistakes);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['mistakes'] = get_string('nomode', 'mootyper');
        } else {
            $mode['mistakes'] = $total;
        }

        // Calculate mode for Elapsed time.
        $v = array_count_values($timeinseconds);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['timeinseconds'] = get_string('nomode', 'mootyper');
        } else {
            $mode['timeinseconds'] = format_time($total);
        }

        // Calculate mode for Hits Per Minute.
        $v = array_count_values($hitsperminute);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['hitsperminute'] = get_string('nomode', 'mootyper');
        } else {
            $mode['hitsperminute'] = format_float($total);
        }

        // Calculate mode for All Hits.
        $v = array_count_values($fullhits);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['fullhits'] = 'no mode';
        } else {
            $mode['fullhits'] = $total;
        }

        // Calculate mode for Precision.
        $v = array_count_values($precisionfield);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['precisionfield'] = get_string('nomode', 'mootyper');
        } else {
            $mode['precisionfield'] = format_float($total).('%');
        }

        // Calculate mode for Time Completed.
        $v = array_count_values($timetaken);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['timetaken'] = get_string('nomode', 'mootyper');
        } else {
            $mode['timetaken'] = $total;
        }

        // Calculate mode for Words Per Minute.
        $v = array_count_values($wpm);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['wpm'] = get_string('nomode', 'mootyper');
        } else {
            $mode['wpm'] = format_float($total);
        }

        // Calculate mode for Grade.
        $v = array_count_values($grade);
        arsort($v);
        foreach ($v as $k => $v) {
            $total = $k;
            break;
        }
        if ($v <= 1) {
            $mode['grade'] = get_string('nomode', 'mootyper');
        } else {
            $mode['grade'] = format_float($total);
        }

        return $mode;
    }

    /**
     * Calculate range.
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_range($grades) {
        $range = [];
        $mistakes = [];
        $timeinseconds = [];
        $hitsperminute = [];
        $fullhits = [];
        $precisionfield = [];
        $timetaken = [];
        $wpm = [];
        $grade = [];

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $grade[$c] = $g->grade;
            $c = $c - 1;
        }

        $range['mistakes'] = max($mistakes) - min($mistakes);
        $range['timeinseconds'] = max($timeinseconds) - min($timeinseconds);
        $range['hitsperminute'] = max($hitsperminute) - min($hitsperminute);
        $range['fullhits'] = max($fullhits) - min($fullhits);
        $range['precisionfield'] = max($precisionfield) - min($precisionfield);

        // Need to see about refining this a little more.
        $diff1 = (max($timetaken)) - (min($timetaken));
        $days = floor($diff1 / (60 * 60 * 24));
        $diff2 = ($diff1 - ((60 * 60 * 24) * $days));
        $hours = floor($diff2 / (60 * 60));
        $diff3 = ($diff1 - ((60 * 60 * 24) * $days) - (60 * 60 * $hours));
        $minutes = floor($diff3 / 60);
        $diff4 = ($diff1 - ((60 * 60 * 24) * $days) - (60 * 60 * $hours) - (60 * $minutes));
        $seconds = floor($diff4 / 60);
        $range['timetaken'] = $days.' d '.$hours.' h '.$minutes.' m ';

        $range['wpm'] = max($wpm) - min($wpm);
        $range['grade'] = max($grade) - min($grade);

        return $range;
    }


    /**
     * Calculate counts.
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_agcount($grades) {
        $agcount = [];
        $mistakes = [];
        $timeinseconds = [];
        $hitsperminute = [];
        $fullhits = [];
        $precisionfield = [];
        $timetaken = [];
        $wpm = [];
        $grade = [];

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $grade[$c] = 1;
            $c = $c - 1;
        }
        // Hits per minute, Precision, Time taken, and WPM are meaningless as counts.
        $agcount['mistakes'] = array_sum($mistakes);
        $agcount['timeinseconds'] = array_sum($timeinseconds);
        $agcount['hitsperminute'] = array_sum($hitsperminute);
        $agcount['fullhits'] = array_sum($fullhits);
        $agcount['precisionfield'] = array_sum($precisionfield);
        $agcount['timetaken'] = array_sum($timetaken);
        $agcount['wpm'] = array_sum($wpm);
        $agcount['grade'] = array_sum($grade);

        return $agcount;
    }

    /**
     * Calculate aggregatemax.
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_agmax($grades) {
        $agmax = [];
        $mistakes = [];
        $timeinseconds = [];
        $hitsperminute = [];
        $fullhits = [];
        $precisionfield = [];
        $timetaken = [];
        $wpm = [];
        $grade = [];

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $grade[$c] = $g->grade;
            $c = $c - 1;
        }

        $agmax['mistakes'] = max($mistakes);
        $agmax['timeinseconds'] = max($timeinseconds);
        $agmax['hitsperminute'] = max($hitsperminute);
        $agmax['fullhits'] = max($fullhits);
        $agmax['precisionfield'] = max($precisionfield);
        $agmax['timetaken'] = max($timetaken);
        $agmax['wpm'] = max($wpm);
        $agmax['grade'] = max($grade);

        return $agmax;
    }

    /**
     * Calculate aggregatemin.
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_agmin($grades) {
        $agmin = [];
        $mistakes = [];
        $timeinseconds = [];
        $hitsperminute = [];
        $fullhits = [];
        $precisionfield = [];
        $timetaken = [];
        $wpm = [];
        $grade = [];

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $grade[$c] = $g->grade;
            $c = $c - 1;
        }

        $agmin['mistakes'] = min($mistakes);
        $agmin['timeinseconds'] = min($timeinseconds);
        $agmin['hitsperminute'] = min($hitsperminute);
        $agmin['fullhits'] = min($fullhits);
        $agmin['precisionfield'] = min($precisionfield);
        $agmin['timetaken'] = min($timetaken);
        $agmin['wpm'] = min($wpm);
        $agmin['grade'] = min($grade);

        return $agmin;
    }

    /**
     * Calculate aggregatesum.
     *
     * @param int $grades
     * @return string
     */
    public static function get_grades_agsum($grades) {
        $agsum = [];
        $mistakes = [];
        $timeinseconds = [];
        $hitsperminute = [];
        $fullhits = [];
        $precisionfield = [];
        $timetaken = [];
        $wpm = [];
        $grade = [];

        $c = count($grades);

        foreach ($grades as $g) {
            $mistakes[$c] = $g->mistakes;
            $timeinseconds[$c] = $g->timeinseconds;
            $hitsperminute[$c] = $g->hitsperminute;
            $fullhits[$c] = $g->fullhits;
            $precisionfield[$c] = $g->precisionfield;
            $timetaken[$c] = $g->timetaken;
            $wpm[$c] = $g->wpm;
            $grade[$c] = $g->grade;
            $c = $c - 1;
        }

        $agsum['mistakes'] = array_sum($mistakes);
        $agsum['timeinseconds'] = array_sum($timeinseconds);
        $agsum['hitsperminute'] = array_sum($hitsperminute);
        $agsum['fullhits'] = array_sum($fullhits);

        // These next three are meaningless as sums.
        $agsum['precisionfield'] = array_sum($precisionfield);
        $agsum['timetaken'] = array_sum($timeinseconds);
        $agsum['wpm'] = array_sum($wpm);

        $agsum['grade'] = array_sum($grade);

        return $agsum;
    }

    /**
     * Get the latest entry in mdl_mootyper_grades for the current user.
     *
     * Used in gcnext.php.
     * @param int $mootyper  ID of the current MooTyper activity.
     * @param int $user      ID of the current user.
     * @param int $exercise  ID of the current MooTyper exercise.
     * @param int $timetaken Unix time when exercise was completed.
     */
    public static function get_grade_entry($mootyper, $user, $exercise, $timetaken) {
        global $USER, $DB, $CFG;
        $sql = "SELECT * FROM ".$CFG->prefix."mootyper_grades".
               " WHERE mootyper = ".$mootyper
                        ." AND userid = ".$user
                        ." AND exercise = ".$exercise
                        ." AND timetaken = ".$timetaken.
               " ORDER BY timetaken";

        if ($rec = $DB->get_record_sql($sql, [])) {
            return $rec;
        } else {
            return null;
        }

    }

    /**
     * Check to see if this MooTyper is available for use.
     *
     * Used in view.php.
     * @param int $mootyper
     */
    public static function is_available($mootyper) {
        $timeopen = $mootyper->timeopen;
        $timeclose = $mootyper->timeclose;
        return (($timeopen == 0 || time() >= $timeopen) && ($timeclose == 0 || time() < $timeclose));
    }


    /**
     * Check for user precision completion.
     * @param int $mootyper->id
     * @param int $userid
     * @return boolean
     */
    public static function get_user_precision($mootyperid, $userid) {
        global $USER, $DB, $CFG;
        // Need SQL that gets the current precision's for this user in this MooTyper activity.
        $params = ['mootyperid' => $mootyperid, 'userid' => $userid];
        $sql = "SELECT COUNT(mtg.id),
                       mtg.userid,
                       AVG(mtg.precisionfield) AS precisionfield,
                       m.id
                  FROM {mootyper_grades} mtg
                  JOIN {mootyper} m ON mtg.mootyper = m.id
                 WHERE m.id = :mootyperid
                   AND mtg.userid = :userid
                   AND mtg.precisionfield >= 0";
        if ($records = $DB->get_records_sql($sql, $params)) {
            $c = count($records);
            $total = $c;
            foreach ($records as $record) {
                $precision[$c] = $record->precisionfield;
            }
            return [$precision, $total];
        } else {
            return null;
        }
    }

    /**
     * Check for user WPM completion.
     * @param int $mootyper->id
     * @param int $userid
     * @return boolean
     */
    public static function get_user_wpm($mootyperid, $userid) {
        global $USER, $DB, $CFG;
        // Need SQL that gets the current wpm's for this user in this MooTyper activity.
        $params = ['mootyperid' => $mootyperid, 'userid' => $userid];
        $sql = "SELECT COUNT(mtg.id),
                       mtg.userid,
                       AVG(mtg.wpm) AS wpm,
                       m.id
                  FROM {mootyper_grades} mtg
                  JOIN {mootyper} m ON mtg.mootyper = m.id
                 WHERE m.id = :mootyperid
                   AND mtg.userid = :userid
                   AND mtg.wpm >= 0";
        if ($records = $DB->get_records_sql($sql, $params)) {
            $c = count($records);
            $total = $c;
            foreach ($records as $record) {
                $wpm[$c] = $record->wpm;
            }
            return [$wpm, $total];
        } else {
            return null;
        }
    }

    /**
     * Check for user MooTyper Grade completion.
     * @param int $mootyper->id
     * @param int $userid
     * @return boolean
     */
    public static function get_user_mootypergrade($mootyperid, $userid) {
        global $USER, $DB, $CFG;
        // Need SQL that gets the current grades for this user in this MooTyper activity.
        $params = ['mootyperid' => $mootyperid, 'userid' => $userid];
        $sql = "SELECT COUNT(mtg.id),
                       mtg.userid,
                       AVG(mtg.grade) AS grade,
                       m.id
                  FROM {mootyper_grades} mtg
                  JOIN {mootyper} m ON mtg.mootyper = m.id
                 WHERE m.id = :mootyperid
                   AND mtg.userid = :userid
                   AND mtg.grade >= 0";
        if ($records = $DB->get_records_sql($sql, $params)) {
            $c = count($records);
            $total = $c;
            foreach ($records as $record) {
                $mootypergrade[$c] = $record->grade;
            }
            return [$mootypergrade, $total];
        } else {
            return null;
        }
    }
}

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
 * Custom completion - Used in Moodle 3.11 and above (ignored by earlier versions).
 *
 * @package   mod_mootyper
 * @copyright 2021 AL Rachels <drachels@drachels.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

namespace mod_mootyper\completion;

use core_completion\activity_custom_completion;
use mod_mootyper\local\lessons;
use mod_mootyper\local\results;

/**
 * Activity custom completion subclass for the MooTyper activity.
 *
 * Class for defining mod_mootyper's custom completion rules and fetching the completion statuses
 * of the custom completion rules for a given mootyper instance and a user.
 *
 * @package mod_mootyper
 */
class custom_completion extends activity_custom_completion {
    /**
     * Get the completion state for a given completion rule.
     *
     * @param string $rule The completion rule.
     * @return int The completion state.
     */
    public function get_state(string $rule): int {
        global $CFG, $DB;

        $this->validate_rule($rule);

        $userid = $this->userid;
        $mootyperid = $this->cm->instance;

        if (!$mootyper = $DB->get_record('mootyper', ['id' => $mootyperid])) {
            throw new moodle_exception(get_string('incorrectmodule', 'mootyper'));
        }

        $status = COMPLETION_INCOMPLETE;
        $mootyper = $DB->get_record('mootyper', ['id' => $mootyperid]);
        $params = ['mootyperid' => $mootyperid, 'userid' => $userid];

        // 20240129 Retrieve the mode for the curret Mootyper.
        $mtmode = $mootyper->isexam;

        // Need count of mootyper_exercises id,  where me.lesson = mt.lesson.
        $exercisecountforthislesson = count(lessons::get_exercises_by_lesson($mootyper->lesson));

        // 20240127 Modified for Postgresql.
        $finalexercisecompletesql = "SELECT COUNT(*)
                                       FROM {mootyper_grades} mtg
                                       JOIN {mootyper} m ON mtg.mootyper = m.id
                                      WHERE mtg.userid = :userid
                                        AND mtg.pass = 1
                                        AND m.id = :mootyperid";

        // Need SQL that gets the final lesson completed status.
        // mdl_mootyper has lesson id, mode, requiredgoal, requiredwpm, and the four completions.
        $finallessoncompletesql = "SELECT COUNT(mte.id)
                                     FROM {mootyper_exercises} mte
                                     JOIN {mootyper_lessons} mtl
                                     JOIN {mootyper_grades} mtg
                                     JOIN {mootyper} mt
                                    WHERE mte.lesson = mtl.id
                                      AND mte.lesson = mt.lesson
                                      AND mt.id = mtg.mootyper
                                      AND mtg.mootyper = :mootyper
                                      AND mtg.userid = :userid";

        // Need SQL that gets the final precision. 20240219 Changed variable and SQL.
        $finalprecisioncompletesql = "SELECT COUNT(mtg.id),
                                             mtg.userid,
                                             AVG(mtg.precisionfield) AS precisionfield,
                                             m.id
                                        FROM {mootyper_grades} mtg
                                        JOIN {mootyper} m ON mtg.mootyper = m.id
                                       WHERE m.id = :mootyperid
                                         AND mtg.userid = :userid
                                         AND mtg.precisionfield >= 0";

        // Need SQL that gets the final wpm.
        $finalwpmcompletesql = "SELECT AVG(mtg.wpm) AS wpm
                          FROM {mootyper} m
                          JOIN {mootyper_grades} mtg ON mtg.mootyper = m.id
                         WHERE m.id = :mootyperid
                           AND mtg.userid = :userid
                           AND mtg.wpm >= 0";

        // Need SQL that gets the final mootypergrade pass status.
        $finalmootypergradesql = "SELECT AVG(mtg.grade) AS grade
                                    FROM {mootyper} m
                                    JOIN {mootyper_grades} mtg ON mtg.mootyper = m.id
                                   WHERE m.id = :mootyperid
                                     AND mtg.userid = :userid
                                     AND mtg.grade >= 0";

        if ($rule == 'completionexercise') {
            // 20240219 If the $mootyper->completionexercise is set to >0, check the status for completion.
            if ($status = $mootyper->completionexercise <=
                $DB->count_records_sql($finalexercisecompletesql, $params)) {
                $status = $mootyper->completionexercise = 1;
            } else {
                $status = $mootyper->completionexercise = 0;
            }
        } else if ($rule == 'completionlesson') {
            // 20240219 If the $mootyper->completionexercise is set to >0, and if the $mootyper->completionlesson
            // is set to 1, check the status for completion.
            if (($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, $params)) && ($mootyper->completionexercise <> 0)) {
                $status = $mootyper->completionlesson = 1;
            } else {
                // 20240219 If the $mootyper->completionlesson is set to 1, and $mootyper->completionexercise is set to 0, and
                // if the $mootyper->completionexercise is not being used, set the status for completionlesson as complete.
                if (($mootyper->completionlesson == 1) && ($mootyper->completionexercise == 0) && (!$mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, $params))) {
                    $status = $mootyper->completionlesson = 1;
                } else {
                    $status = $mootyper->completionlesson = 0;
                }
            }
        } else if ($rule == 'completionprecision') {
            [$precision, $total] = results::get_user_precision($mootyperid, $userid);
            // Check for completion when mode is exam then set status.
            if ($mootyper->isexam == 1) {
                $precision = $DB->get_record_sql($finalprecisioncompletesql, $params);
                if ($precision->precisionfield >= $mootyper->completionprecision) {
                    $status = $mootyper->completionprecision = 1;
                } else {
                    $status = $mootyper->completionprecision = 0;
                }
                // Check for completion when mode is lesson or practice.
                // I think that here, the precision completion must retrieve ALL grades listed
                // and base the completion on the average precision.
            } else if (($mootyper->isexam == 0) || ($mootyper->isexam == 2)) {
                $precision = $DB->get_records_sql($finalprecisioncompletesql, $params);
                $mootyperprecision = [];
                $mootyperprecision = $DB->get_records_sql($finalprecisioncompletesql, $params);
                ksort($mootyperprecision, SORT_NUMERIC);
                $mootyperprecision = array_values($mootyperprecision);
                if ($mootyperprecision[0]->precisionfield >= $mootyper->completionprecision) {
                    $status = $mootyper->completionprecision = 1;
                } else {
                    $status = $mootyper->completionprecision = 0;
                }
            }
        } else if ($rule == 'completionwpm') {
            [$wpm, $total] = results::get_user_wpm($mootyperid, $userid);
            // Check for completion when mode is exam then set status.
            if ($mootyper->isexam == 1) {
                $wpm = $DB->get_record_sql($finalwpmcompletesql, $params);
                if ($wpm->wpm >= $mootyper->completionwpm) {
                    $status = $mootyper->completionwpm = 1;
                } else {
                    $status = $mootyper->completionwpm = 0;
                }
                // Check for completion when mode is lesson or practice.
                // I think that here, the wpm completion must retrieve ALL WPM's listed
                // and base the completion on the average wpm.
            } else if (($mootyper->isexam == 0) || ($mootyper->isexam == 2)) {
                $wpm = $DB->get_records_sql($finalwpmcompletesql, $params);
                $mootyperwpm = [];
                $mootyperwpm = $DB->get_records_sql($finalwpmcompletesql, $params);
                ksort($mootyperwpm, SORT_NUMERIC);
                $mootyperwpm = array_values($mootyperwpm);
                if ($mootyperwpm[0]->wpm >= $mootyper->completionwpm) {
                    $status = $mootyper->completionwpm = 1;
                } else {
                    $status = $mootyper->completionwpm = 0;
                }
                // Possibly need an else here to set completionwpm when nothing else is set.
            }
        } else if ($rule == 'completionmootypergrade') {
            // 20240222 Got the last of my five completions modified and working.
            [$mootypergrade, $total] = results::get_user_mootypergrade($mootyperid, $userid);
            // Check for completion when mode is exam then set status.
            if ($mootyper->isexam == 1) {
                $wpm = $DB->get_record_sql($finalwpmcompletesql, $params);
                if ($wpm->wpm >= $mootyper->completionmootypergrade) {
                    $status = $mootyper->completionmootypergrade = 1;
                } else {
                    $status = $mootyper->completionmootypergrade = 0;
                }
                // Check for completion when mode is lesson or practice.
                // I think that here, the mootypergrade completion must retrieve ALL grades listed
                // and base the completion on the average grade.
            } else if (($mootyper->isexam == 0) || ($mootyper->isexam == 2)) {
                $mootypergrade = [];
                $mootypergrade = $DB->get_records_sql($finalmootypergradesql, $params);
                ksort($mootypergrade, SORT_NUMERIC);
                $mootypergrade = array_values($mootypergrade);
                if ($mootypergrade[0]->grade >= $mootyper->completionmootypergrade) {
                    $status = $mootyper->completionmootypergrade = 1;
                } else {
                    $status = $mootyper->completionmootypergrade = 0;
                }
                // Possibly need an else here to set completionmootypergrade when nothing else is set.
            }
        }
        return $status ? COMPLETION_COMPLETE : COMPLETION_INCOMPLETE;
    }

    /**
     * Fetch the list of custom completion rules that this module defines.
     *
     * @return array
     */
    public static function get_defined_custom_rules(): array {
        // This function gets called upon after completing the first exercise.
        return [
            'completionexercise',
            'completionlesson',
            'completionprecision',
            'completionwpm',
            'completionmootypergrade',
        ];
    }

    /**
     * Returns an associative array of the descriptions of custom completion rules.
     *
     * @return array
     */
    public function get_custom_rule_descriptions(): array {
        $completionexercise = $this->cm->customdata['customcompletionrules']['completionexercise'] ?? 0;
        $completionlesson = $this->cm->customdata['customcompletionrules']['completionlesson'] ?? 0;
        $completionprecision = $this->cm->customdata['customcompletionrules']['completionprecision'] ?? 0;
        $completionwpm = $this->cm->customdata['customcompletionrules']['completionwpm'] ?? 0;
        $completionmootypergrade = $this->cm->customdata['customcompletionrules']['completionmootypergrade'] ?? 0;
        return [
            'completionexercise' => get_string('completiondetail:exercise', 'mootyper', $completionexercise),
            'completionlesson' => get_string('completiondetail:lesson', 'mootyper', $completionlesson),
            'completionprecision' => get_string('completiondetail:precision', 'mootyper', $completionprecision),
            'completionwpm' => get_string('completiondetail:wpm', 'mootyper', $completionwpm),
            'completionmootypergrade' => get_string('completiondetail:mootypergrade', 'mootyper', $completionmootypergrade),
        ];
    }

    /**
     * Returns an array of all completion rules, in the order they should be displayed to users.
     *
     * @return array
     */
    public function get_sort_order(): array {
        return [
            'completionview',
            'completionexercise',
            'completionlesson',
            'completionprecision',
            'completionwpm',
            'completionmootypergrade',
            'completionusegrade',
            'completionpassgrade',
        ];
    }
}

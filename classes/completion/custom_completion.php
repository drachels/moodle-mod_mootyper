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
use \mod_mootyper\local\lessons;
use \mod_mootyper\local\results;

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
        global $DB;

        $debug = array();
        $debug['Entering custom_completion.php, function get_state(string $rule): '] = $rule;

        $this->validate_rule($rule);

        $userid = $this->userid;
        $mootyperid = $this->cm->instance;

        $debug['The current $userid is: '] = $userid;
        $debug['The current $mootyperid is: '] = $mootyperid;

        if (!$mootyper = $DB->get_record('mootyper', ['id' => $mootyperid])) {
            throw new moodle_exception(get_string('incorrectmodule', 'mootyper'));
        }

        $status = COMPLETION_INCOMPLETE;

        $finalexercisecompleteparams = ['userid' => $userid, 'mootyperid' => $mootyperid];
        $finallessoncompleteparams = ['userid' => $userid, 'mootyperid' => $mootyperid];
        $finalprecisionparams = ['userid' => $userid, 'mootyperid' => $mootyperid];
        $finalwpmparams = ['userid' => $userid, 'mootyperid' => $mootyperid];
        $finalmootypergradeparams = ['userid' => $userid, 'mootyperid' => $mootyperid];

        // All the exercises of a lesson must be successfully completed before bothering to check
        // for lesson, precision, or WPM completion.
        // If the exercises are successfully completed, is the lesson sucessfully compled?
        // If the exercises and the lesson are complete, was the required precision achieved?
        // If the exercises and the lesson are complete, was the required wpm achieved?

        // Need count of exercises in current lesson and then need the count of exercise grades for the current lesson that have been passed.
        // mdl_mootyper_grades has info for mootyper, userid, exercise id, pass status
        // Need $mootyper_lesson, which is id from, mootyper activity.
        // Need count of mootyper_exercises id,  where me.lesson = mt.lesson
        // Need count of mootyper_grades where mtg.exercise = mte.id AND mtg.pass =1
        $exercisecountforthislesson = count(lessons::get_exercises_by_lesson($mootyper->lesson));
        $debug['The current $mootyper->lesson is: '] = $mootyper->lesson;
        $debug['The current $exercisecountforthislesson is: '] = $exercisecountforthislesson;
        $debug['The current $rule being processed is: '] = $rule;


        $finalexercisecompletesql = "SELECT COUNT(mtg.userid),
                                            AVG(mtg.grade),
                                            mtg.timetaken,
                                            mtg.mistakedetails,
                                            mtg.exercise,
                                            AVG(mtg.precisionfield),
                                            AVG(mtg.wpm),
                                            AVG(mtg.pass)
                                       FROM {mootyper} m
                                       JOIN {mootyper_grades} mtg ON mtg.mootyper = m.id
                                      WHERE m.id = :mootyper
                                        AND mtg.userid = :userid
                                        AND mtg.grade >= m.grade_mootyper
                                        AND mtg.precisionfield >= m.requiredgoal
                                        AND mtg.wpm >= m.requiredwpm
                                        AND mtg.pass = 1";

        //$debug['The current $finalexercisecompletesql SQL is: '] = $finalexercisecompletesql;

        // Need SQL that gets the final lesson completed status.
        // mdl_mootyper has lesson id, mode, requiredgoal, requiredwpm, and the four completions
        $finallessoncompletesql = "SELECT COUNT(mte.id)
                                     FROM {mootyper_exercises} mte
                                     JOIN {mootyper_lessons} mtl
                                     JOIN {mootyper_grades} mtg
                                     JOIN {mootyper} mt
                                    WHERE mte.lesson = mtl.id
                                      AND mte.lesson = mt.lesson
                                      AND mt.id = mtg.mootyper
                                      AND mtg.userid = :userid";

        //$debug['The current $finallessoncompletesql SQL is: '] = $finallessoncompletesql;

        // Need SQL that gets the final precision.
        $finalprecisionsql = "SELECT AVG(mtg.precisionfield) AS precisionfield
                                FROM {mootyper} m
                                JOIN {mootyper_grades} mtg ON mtg.mootyper = m.id
                               WHERE m.id = :mootyper
                                 AND mtg.userid = :userid
                                 AND mtg.precisionfield > 0";

        //$debug['The current $finalprecisionsql SQL is: '] = $finalprecisionsql;

        // Need SQL that gets the final wpm.
        $finalwpmsql = "SELECT AVG(mtg.wpm) AS wpm
                          FROM {mootyper} m
                          JOIN {mootyper_grades} mtg ON mtg.mootyper = m.id
                         WHERE m.id = :mootyper
                           AND mtg.userid = :userid
                           AND mtg.wpm >= 0";

        //$debug['The current $finalwpmsql SQL is: '] = $finalwpmsql;

        // Need SQL that gets the final mootypergrade pass status.
        $finalmootypergradesql = "SELECT AVG(mtg.grade) AS grade
                                    FROM {mootyper} m
                                    JOIN {mootyper_grades} mtg ON mtg.mootyper = m.id
                                   WHERE m.id = :mootyper
                                     AND mtg.userid = :userid
                                     AND mtg.grade >= 0";

        //$debug['The current $finalpasssql SQL is: '] = $finalpasssql;
        $debug['The current $finalmootypergradesql SQL is: '] = $finalmootypergradesql;

        if ($rule == 'completionexercise') {
            // Set completionexercise rule only when all exercises for the lesson are completed.
            $status = $mootyper->completionexercise <=
                $DB->count_records_sql($finalexercisecompletesql, ['mootyper' => $mootyperid, 'userid' => $userid]);

            $currentsetofrecords = array();
            $currentsetofrecords = $DB->get_records_sql($finalexercisecompletesql, ['mootyper' => $mootyperid, 'userid' => $userid]);
            $debug['Checking $rule and the current array of records is: '] = $currentsetofrecords;
            $debug['Checking $rule and the current $mootyper->completionexercise is: '] = $mootyper->completionexercise;
            $debug['Checking $rule and the current completionexercise status is: '] = $status;


        } else if ($rule == 'completionlesson') {
            // Set completionlesson rule only when completionexercise is completed.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, ['mootyper' => $mootyperid, 'userid' => $userid])) {
                // Completionlesson is always 1.
                $status = $mootyper->completionlesson = 1;
            } else {
                // Completionlesson is the percentage of items that need checking-off.
                $status = $mootyper->completionlesson = 0;
            }
        } else if ($rule == 'completionprecision') {
            // Set completionprecision rule only when completionexercise is completed.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, ['mootyper' => $mootyperid, 'userid' => $userid])) {
                // Need completionprecision sql.
                $mootyperprecision = array();
                $mootyperprecision = $DB->get_records_sql($finalprecisionsql, ['mootyper' => $mootyperid, 'userid' => $userid]);
                $debug['Checking $mootyperprecision and the current completionprecison status is: '] = $mootyperprecision;

                ksort($mootyperprecision, SORT_NUMERIC);
                $mootyperprecision = array_values($mootyperprecision);
                $debug['Checking $mootyperprecision and the current completionprecison status is: '] = $mootyperprecision;

                if ($mootyperprecision[0]->precisionfield >= $mootyper->completionprecision) {
                    $status = $mootyper->completionprecision = 1;
                } else {
                    $status = $mootyper->completionprecision = 0;
                }
            }
        } else if ($rule == 'completionwpm') {
            // Set completionwpm rule only when completionexercise is completed.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, ['mootyper' => $mootyperid, 'userid' => $userid])) {

                // Need completionwpm sql.
                $mootyperwpm = array();
                $mootyperwpm = $DB->get_records_sql($finalwpmsql, ['mootyper' => $mootyperid, 'userid' => $userid]);
                ksort($mootyperwpm, SORT_NUMERIC);
                $mootyperwpm = array_values($mootyperwpm);
                $debug['Checking $mootyperwpm and the current completionwpm status is: '] = $mootyperwpm;

                if ($mootyperwpm[0]->wpm >= $mootyper->completionwpm) {
                    $status = $mootyper->completionwpm = 1;
                } else {
                    $status = $mootyper->completionwpm = 0;
                }
            }
        } else if ($rule == 'completionmootypergrade') {
            // Set completionmootypergrade rule only when completionexercise is completed.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, ['mootyper' => $mootyperid, 'userid' => $userid])) {
                $mootypergrade = array();
                $mootypergrade = $DB->get_records_sql($finalmootypergradesql, ['mootyper' => $mootyperid, 'userid' => $userid]);
                ksort($mootypergrade, SORT_NUMERIC);
                $mootypergrade = array_values($mootypergrade);
                if ($mootypergrade[0]->grade >= $mootyper->completionmootypergrade) {
                    $status = $mootyper->completionmootypergrade = 1;
                } else {
                    $status = $mootyper->completionmootypergrade = 0;
                }
            }

        }
        $debug['After processing the rule, the current completion $status is: '] = ($status ? COMPLETION_COMPLETE : COMPLETION_INCOMPLETE);

//print_object('==================================================================================');
//print_object($debug);
//print_object('^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^');

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
            //'completionpass',
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
        //$completionpass = $this->cm->customdata['customcompletionrules']['completionpass'] ?? 0;
        $completionmootypergrade = $this->cm->customdata['customcompletionrules']['completionmootypergrade'] ?? 0;
        return [
            'completionexercise' => get_string('completiondetail:exercise', 'mootyper', $completionexercise),
            'completionlesson' => get_string('completiondetail:lesson', 'mootyper', $completionlesson),
            'completionprecision' => get_string('completiondetail:precision', 'mootyper', $completionprecision),
            'completionwpm' => get_string('completiondetail:wpm', 'mootyper', $completionwpm),
            //'completionpass' => get_string('completiondetail:pass', 'mootyper', $completionpass),
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
            //'completionpass',
            'completionmootypergrade',
            'completionusegrade',
            'completionpassgrade',
        ];
    }
}

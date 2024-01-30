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
print_object('CP 0 Spacer 1.');
print_object('CP 0 Spacer 2.');
print_object('CP 0 Spacer 3.');
print_object('CP 0 Spacer 4.');
print_object('CP 0 Spacer 5.');

        $this->validate_rule($rule);

        $userid = $this->userid;
        $mootyperid = $this->cm->instance;

        if (!$mootyper = $DB->get_record('mootyper', ['id' => $mootyperid])) {
            throw new moodle_exception(get_string('incorrectmodule', 'mootyper'));
        }

        $status = COMPLETION_INCOMPLETE;

        $params = ['mootyperid' => $mootyperid, 'userid' => $userid];

        // The required number of exercises of a lesson must be successfully completed before
        // bothering to check for lesson, precision, or WPM completion.
        // If ALL or the REQUIRED number of exercises are successfully completed, is the
        // lesson sucessfully completed?
        // If the exercises and the lesson are complete, was the required precision achieved?
        // If the exercises and the lesson are complete, was the required wpm achieved?
        // If the exercises and the lesson are complete, was the require MooTyper Grade achieved?

        // Need count of exercises in current lesson and then need the count of exercise grades
        // for the current lesson that have been passed.
        // mdl_mootyper_grades has info for mootyper, userid, exercise id, pass status
        // Need $mootyper_lesson, which is id from, mootyper activity.
        // Need count of mootyper_exercises id,  where me.lesson = mt.lesson
        // Need count of mootyper_grades where mtg.exercise = mte.id AND mtg.pass = 1.
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

        // Need SQL that gets the final precision.
        $finalprecisionsql = "SELECT AVG(mtg.precisionfield) AS precisionfield
                                FROM {mootyper} m
                                JOIN {mootyper_grades} mtg ON mtg.mootyper = m.id
                               WHERE m.id = :mootyperid
                                 AND mtg.userid = :userid
                                 AND mtg.precisionfield > 0";

        // Need SQL that gets the final wpm.
        $finalwpmsql = "SELECT AVG(mtg.wpm) AS wpm
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
        $mtmode = $mootyper->isexam;
        if ($rule == 'completionexercise') {
            // Set completionexercise rule 1 when one exercise is used and that ONE completes the lesson.
            $status = $mootyper->completionexercise <=
                $DB->count_records_sql($finalexercisecompletesql, $params);
            $currentsetofrecords = [];
            $currentsetofrecords = $DB->get_records_sql($finalexercisecompletesql, $params);

        if ($mtmode === '1') {
print_object('CP 1 The $mtmode is 1.');
print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object('CP 1 And the $mootyperid is:');
print_object($mootyperid);

}
            // Set completionexercise rule 2, and 5 when all exercises are required for exercise completion and for the lesson completion.
        if (!($mtmode === '1') && ($exercisecountforthislesson)) {
print_object('CP 2 The $mtmode is 0 or 2.');

print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object($mootyper->isexam);
print_object('CP 2 And the $mootyperid is:');

print_object($mootyperid);

}
            // Set completionexercise rule 3 and 6, when only x of exercises is used for excercise completion, but max is needed for lesson completion.

            // Set completionexercise rull 4 and 7, when only x of exercises are used for both excercise and lesson completion.




        } else if ($rule == 'completionlesson') {
            // Set completionlesson rule only when completionexercise is completed.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, $params)) {
                // Completionlesson should always be 1.
                $status = $mootyper->completionlesson = 1;
            } else {
                $status = $mootyper->completionlesson = 0;
            }
        } else if ($rule == 'completionprecision') {
            // Set completionprecision rule only when completionexercise is completed.
            // Take in to account the mode.
            // Exam mode one exercise only is required, and Pass or Fail, both exercise and lesson are completed.
            // Lesson mode one to all exercises in the lesson, and Pass or Fail, all must be completed then both
            // exercise and lesson are completed.
            // Practice mode one to all exercise required, only Passing exercises are considered, give the teacher
            // the option to allow lesson completion on number of exercises required or on the the total
            // number of exercises in the lesson.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, $params)) {
                $mootyperprecision = [];
                $mootyperprecision = $DB->get_records_sql($finalprecisionsql, $params);
                ksort($mootyperprecision, SORT_NUMERIC);
                $mootyperprecision = array_values($mootyperprecision);
                if ($mootyperprecision[0]->precisionfield >= $mootyper->completionprecision) {
                    $status = $mootyper->completionprecision = 1;
                } else {
                    $status = $mootyper->completionprecision = 0;
                }
            }
        } else if ($rule == 'completionwpm') {
            // Set completionwpm rule only when completionexercise is completed.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, $params)) {
                $mootyperwpm = [];
                $mootyperwpm = $DB->get_records_sql($finalwpmsql, $params);
                ksort($mootyperwpm, SORT_NUMERIC);
                $mootyperwpm = array_values($mootyperwpm);
                if ($mootyperwpm[0]->wpm >= $mootyper->completionwpm) {
                    $status = $mootyper->completionwpm = 1;
                } else {
                    $status = $mootyper->completionwpm = 0;
                }
            }
        } else if ($rule == 'completionmootypergrade') {
            // Set completionmootypergrade rule only when completionexercise is completed.
            if ($status = $mootyper->completionexercise <=
                    $DB->count_records_sql($finalexercisecompletesql, $params)) {
                $mootypergrade = [];
                $mootypergrade = $DB->get_records_sql($finalmootypergradesql, $params);
                ksort($mootypergrade, SORT_NUMERIC);
                $mootypergrade = array_values($mootypergrade);
                if ($mootypergrade[0]->grade >= $mootyper->completionmootypergrade) {
                    $status = $mootyper->completionmootypergrade = 1;
                } else {
                    $status = $mootyper->completionmootypergrade = 0;
                }
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

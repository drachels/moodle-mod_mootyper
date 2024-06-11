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
 * Grade item storage for mod_mootyper.
 *
 * @package   mod_mootyper
 * @copyright AL Rachels <drachels@drachels.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types = 1);

namespace mod_mootyper\grades;

use coding_exception;
use context;
use core_grades\component_gradeitem;
use core_grades\local\gradeitem as gradeitem;
use mod_mootyper\local\container as mootyper_container;
use mod_mootyper\local\entities\mootyper as mootyper_entity;
use required_capability_exception;
use stdClass;

/**
 * Grade item storage for mod_mootyper.
 *
 * @package   mod_mootyper
 * @copyright AL Rachels <drachels@drachels.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mootyper_gradeitem extends component_gradeitem {
    /** @var mootyper_entity The mootyper entity being graded */
    protected $mootyper;

    /**
     * Return an instance based on the context in which it is used.
     *
     * @param context $context
     */
    public static function load_from_context(context $context): parent {
        // Get all the factories that are required.
        $vaultfactory = mootyper_container::get_vault_factory();
        $mootypervault = $vaultfactory->get_mootyper_vault();

        $mootyper = $mootypervault->get_from_course_module_id((int) $context->instanceid);

        return static::load_from_mootyper_entity($mootyper);
    }

    /**
     * Return an instance using the mootyper_entity instance.
     *
     * @param mootyper_entity $mootyper
     *
     * @return mootyper_gradeitem
     */
    public static function load_from_mootyper_entity(mootyper_entity $mootyper): self {
        $instance = new static('mod_mootyper', $mootyper->get_context(), 'mootyper');
        $instance->mootyper = $mootyper;

        return $instance;
    }

    /**
     * The table name used for grading.
     *
     * @return string
     */
    protected function get_table_name(): string {
        return 'mootyper_grades';
    }

    /**
     * Whether grading is enabled for this item.
     *
     * @return bool
     */
    public function is_grading_enabled(): bool {
        return $this->mootyper->is_grading_enabled();
    }

    /**
     * Whether the grader can grade the gradee.
     *
     * @param stdClass $gradeduser The user being graded
     * @param stdClass $grader The user who is grading
     * @return bool
     */
    public function user_can_grade(stdClass $gradeduser, stdClass $grader): bool {
        // Validate the required capabilities.
        $managerfactory = mootyper_container::get_manager_factory();
        $capabilitymanager = $managerfactory->get_capability_manager($this->mootyper);

        return $capabilitymanager->can_grade($grader, $gradeduser);
    }

    /**
     * Require that the user can grade, throwing an exception if not.
     *
     * @param stdClass $gradeduser The user being graded
     * @param stdClass $grader The user who is grading
     * @throws required_capability_exception
     */
    public function require_user_can_grade(stdClass $gradeduser, stdClass $grader): void {
        if (!$this->user_can_grade($gradeduser, $grader)) {
            throw new required_capability_exception($this->mootyper->get_context(), 'mod/mootyper:grade', 'nopermissions', '');
        }
    }

    /**
     * Get the grade value for this instance.
     * The itemname is translated to the relevant grade field on the mootyper entity.
     *
     * @return int
     */
    protected function get_gradeitem_value(): int {
        $getter = "get_grade_for_{$this->itemname}";

        return $this->mootyper->{$getter}();
    }

    /**
     * Create an empty mootyper_grade for the specified user and grader.
     *
     * @param stdClass $gradeduser The user being graded
     * @param stdClass $grader The user who is grading
     * @return stdClass The newly created grade record
     * @throws \dml_exception
     */
    public function create_empty_grade(stdClass $gradeduser, stdClass $grader): stdClass {
        global $DB;

        $grade = (object) [
            'mootyper' => $this->mootyper->get_id(),
            'itemnumber' => $this->itemnumber,
            'userid' => $gradeduser->id,
            'timemodified' => time(),
        ];
        $grade->timecreated = $grade->timemodified;

        $gradeid = $DB->insert_record($this->get_table_name(), $grade);

        return $DB->get_record($this->get_table_name(), ['id' => $gradeid]);
    }

    /**
     * Get the grade for the specified user.
     *
     * @param stdClass $gradeduser The user being graded
     * @param stdClass $grader The user who is grading
     * @return stdClass The grade value
     * @throws \dml_exception
     */
    public function get_grade_for_user(stdClass $gradeduser, ?stdClass $grader = null): ?stdClass {
        global $DB;

        $params = [
            'mootyper' => $this->mootyper->get_id(),
            'itemnumber' => $this->itemnumber,
            'userid' => $gradeduser->id,
        ];

        $grade = $DB->get_record($this->get_table_name(), $params);

        if (empty($grade)) {
            $grade = $this->create_empty_grade($gradeduser, $grader);
        }

        return $grade ?: null;
    }

    /**
     * Get the grade status for the specified user.
     * Check if a grade obj exists & $grade->grade !== null.
     * If the user has a grade return true.
     *
     * @param stdClass $gradeduser The user being graded
     * @return bool The grade exists
     * @throws \dml_exception
     */
    public function user_has_grade(stdClass $gradeduser): bool {
        global $DB;

        $params = [
            'mootyper' => $this->mootyper->get_id(),
            'itemnumber' => $this->itemnumber,
            'userid' => $gradeduser->id,
        ];

        $grade = $DB->get_record($this->get_table_name(), $params);

        if (empty($grade) || $grade->grade === null) {
            return false;
        }
        return true;
    }

    /**
     * Get grades for all users for the specified gradeitem.
     *
     * @return stdClass[] The grades
     * @throws \dml_exception
     */
    public function get_all_grades(): array {
        global $DB;

        return $DB->get_records($this->get_table_name(), [
            'mootyper' => $this->mootyper->get_id(),
            'itemnumber' => $this->itemnumber,
        ]);
    }

    /**
     * Get the grade item instance id.
     *
     * This is typically the cmid in the case of an activity, and relates to the iteminstance field in the grade_items
     * table.
     *
     * @return int
     */
    public function get_grade_instance_id(): int {
        return (int) $this->mootyper->get_id();
    }

    /**
     * Create or update the grade.
     *
     * @param stdClass $grade
     * @return bool Success
     * @throws \dml_exception
     * @throws \moodle_exception
     * @throws coding_exception
     */
    protected function store_grade(stdClass $grade): bool {
        global $CFG, $DB;
        require_once("{$CFG->dirroot}/mod/mootyper/lib.php");

        if ($grade->mootyper != $this->mootyper->get_id()) {
            throw new coding_exception('Incorrect mootyper provided for this grade');
        }

        if ($grade->itemnumber != $this->itemnumber) {
            throw new coding_exception('Incorrect itemnumber provided for this grade');
        }

        // Ensure that the grade is valid.
        $this->check_grade_validity($grade->grade);

        $grade->mootyper = $this->mootyper->get_id();
        $grade->timemodified = time();

        $DB->update_record($this->get_table_name(), $grade);

        // Update in the gradebook.
        $mapper = mootyper_container::get_legacy_data_mapper_factory()->get_mootyper_data_mapper();
        mootyper_update_grades($mapper->to_legacy_object($this->mootyper), $grade->userid);

        return true;
    }
}

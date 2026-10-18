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

namespace mod_mootyper\privacy;

/**
 * Tests for the deletion of all user data in a context.
 *
 * @package    mod_mootyper
 * @copyright  2026 onwards AL Rachels
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_mootyper\privacy\provider::delete_data_for_all_users_in_context
 */
final class provider_delete_context_test extends \advanced_testcase {
    /**
     * Create an activity with one attempt, one check and one grade of a user.
     *
     * @param \stdClass $course The course.
     * @param \stdClass $user The user.
     * @return \stdClass The activity record with its cm.
     */
    private function create_activity_with_data(\stdClass $course, \stdClass $user): \stdClass {
        global $DB;

        $activity = $this->getDataGenerator()->create_module('mootyper', ['course' => $course->id]);
        $attemptid = $DB->insert_record('mootyper_attempts', (object)[
            'mootyperid' => $activity->id,
            'userid' => $user->id,
            'timetaken' => time(),
        ]);
        $DB->insert_record('mootyper_checks', (object)[
            'attemptid' => $attemptid,
            'mistakes' => 0,
            'hits' => 1,
            'checktime' => time(),
        ]);
        $DB->insert_record('mootyper_grades', (object)[
            'mootyper' => $activity->id,
            'userid' => $user->id,
            'attemptid' => $attemptid,
            'timetaken' => time(),
        ]);
        return $activity;
    }

    /**
     * Deleting a module context removes the attempts, checks and grades of that activity only.
     */
    public function test_delete_all_users_in_context_removes_attempts_of_activity_only(): void {
        global $DB;

        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $user = $this->getDataGenerator()->create_and_enrol($course, 'student');
        $activity1 = $this->create_activity_with_data($course, $user);
        $activity2 = $this->create_activity_with_data($course, $user);

        provider::delete_data_for_all_users_in_context(\context_module::instance($activity1->cmid));

        $this->assertFalse($DB->record_exists('mootyper_attempts', ['mootyperid' => $activity1->id]));
        $this->assertFalse($DB->record_exists('mootyper_grades', ['mootyper' => $activity1->id]));
        $this->assertTrue($DB->record_exists('mootyper_attempts', ['mootyperid' => $activity2->id]));
        $this->assertTrue($DB->record_exists('mootyper_grades', ['mootyper' => $activity2->id]));
        $this->assertEquals(1, $DB->count_records('mootyper_checks'));
    }

    /**
     * A context that is not a module context deletes nothing.
     */
    public function test_delete_all_users_in_context_ignores_other_contexts(): void {
        global $DB;

        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $user = $this->getDataGenerator()->create_and_enrol($course, 'student');
        $activity = $this->create_activity_with_data($course, $user);

        provider::delete_data_for_all_users_in_context(\context_course::instance($course->id));

        $this->assertTrue($DB->record_exists('mootyper_attempts', ['mootyperid' => $activity->id]));
        $this->assertTrue($DB->record_exists('mootyper_grades', ['mootyper' => $activity->id]));
    }
}

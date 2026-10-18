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

namespace mod_mootyper;

use mod_mootyper\local\lessons;

/**
 * Tests for the course scoping of lesson visibility and editing.
 *
 * @package    mod_mootyper
 * @copyright  2026 onwards AL Rachels
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_mootyper\local\lessons
 */
final class lessons_course_scope_test extends \advanced_testcase {
    /**
     * Create a lesson restricted to a course.
     *
     * @param int $courseid The course the lesson belongs to.
     * @param int $authorid The author.
     * @return int The lesson ID.
     */
    private function create_lesson(int $courseid, int $authorid): int {
        global $DB;

        return $DB->insert_record('mootyper_lessons', (object)[
            'lessonname' => 'Lesson of course ' . $courseid,
            'authorid' => $authorid,
            'visible' => 1,
            'editable' => 1,
            'courseid' => $courseid,
        ]);
    }

    /**
     * A teacher of course A must neither see nor edit a lesson restricted to course B.
     */
    public function test_course_restricted_lesson_of_other_course_is_not_offered(): void {
        $this->resetAfterTest();
        $generator = $this->getDataGenerator();
        $coursea = $generator->create_course();
        $courseb = $generator->create_course();
        $teachera = $generator->create_and_enrol($coursea, 'editingteacher');
        $teacherb = $generator->create_and_enrol($courseb, 'editingteacher');
        $lessonb = $this->create_lesson((int)$courseb->id, (int)$teacherb->id);

        $this->assertFalse(lessons::is_editable_by_me($teachera->id, $coursea->id, $lessonb));
        $offered = array_map('intval', array_column(lessons::get_mootyperlessons($teachera->id, $coursea->id), 'id'));
        $this->assertNotContains($lessonb, $offered);
    }

    /**
     * A teacher of the course a lesson is restricted to can see and edit it.
     */
    public function test_course_restricted_lesson_of_own_course_is_offered(): void {
        $this->resetAfterTest();
        $generator = $this->getDataGenerator();
        $coursea = $generator->create_course();
        $teachera = $generator->create_and_enrol($coursea, 'editingteacher');
        $teachera2 = $generator->create_and_enrol($coursea, 'editingteacher');
        $lessona = $this->create_lesson((int)$coursea->id, (int)$teachera2->id);

        $this->assertTrue(lessons::is_editable_by_me($teachera->id, $coursea->id, $lessona));
        $offered = array_map('intval', array_column(lessons::get_mootyperlessons($teachera->id, $coursea->id), 'id'));
        $this->assertContains($lessona, $offered);
    }
}

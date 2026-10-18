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
 * Tests for the access check used by the lesson export.
 *
 * @package    mod_mootyper
 * @copyright  2026 onwards AL Rachels
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_mootyper\local\lessons::require_available_to_me
 */
final class lessons_export_access_test extends \advanced_testcase {
    /**
     * Create a lesson.
     *
     * @param int $courseid The course the lesson belongs to.
     * @param int $authorid The author.
     * @param int $visible The visibility setting of the lesson.
     * @return int The lesson ID.
     */
    private function create_lesson(int $courseid, int $authorid, int $visible): int {
        global $DB;

        return $DB->insert_record('mootyper_lessons', (object)[
            'lessonname' => 'Lesson ' . $visible,
            'authorid' => $authorid,
            'visible' => $visible,
            'editable' => 2,
            'courseid' => $courseid,
        ]);
    }

    /**
     * A teacher must not get access to a private lesson of another author.
     */
    public function test_export_denied_for_private_lesson_of_other_author(): void {
        $this->resetAfterTest();
        $generator = $this->getDataGenerator();
        $coursea = $generator->create_course();
        $courseb = $generator->create_course();
        $teachera = $generator->create_and_enrol($coursea, 'editingteacher');
        $teacherb = $generator->create_and_enrol($courseb, 'editingteacher');
        $lessonb = $this->create_lesson((int)$courseb->id, (int)$teacherb->id, 2);

        $this->expectException(\moodle_exception::class);
        lessons::require_available_to_me($teachera->id, $coursea->id, $lessonb);
    }

    /**
     * A lesson that does not exist is denied.
     */
    public function test_export_denied_for_missing_lesson(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $teacher = $this->getDataGenerator()->create_and_enrol($course, 'editingteacher');

        $this->expectException(\moodle_exception::class);
        lessons::require_available_to_me($teacher->id, $course->id, 987654);
    }

    /**
     * The author of a private lesson passes the check.
     */
    public function test_export_allowed_for_author(): void {
        $this->resetAfterTest();
        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $teacher = $generator->create_and_enrol($course, 'editingteacher');
        $lesson = $this->create_lesson((int)$course->id, (int)$teacher->id, 2);

        lessons::require_available_to_me($teacher->id, $course->id, $lesson);
        $this->assertTrue(true);
    }

    /**
     * A lesson shared with all teachers can be exported by another teacher, even if only its author may edit it.
     */
    public function test_export_allowed_for_shared_lesson_of_other_author(): void {
        $this->resetAfterTest();
        $generator = $this->getDataGenerator();
        $course = $generator->create_course();
        $teacher = $generator->create_and_enrol($course, 'editingteacher');
        $author = $generator->create_and_enrol($course, 'editingteacher');
        $lesson = $this->create_lesson((int)$course->id, (int)$author->id, 0);

        lessons::require_available_to_me($teacher->id, $course->id, $lesson);
        $this->assertTrue(true);
    }
}

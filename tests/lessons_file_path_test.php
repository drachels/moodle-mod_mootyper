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
 * Tests for the lesson file path helper.
 *
 * @package    mod_mootyper
 * @copyright  2026 onwards AL Rachels
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_mootyper\local\lessons::get_lesson_file_path
 */
final class lessons_file_path_test extends \advanced_testcase {
    /**
     * A lesson name that tries to leave the lessons directory must not resolve to a path.
     */
    public function test_lesson_file_path_rejects_traversal(): void {
        $this->assertNull(lessons::get_lesson_file_path('../../../../tmp/target'));
        $this->assertNull(lessons::get_lesson_file_path('sub/lesson'));
        $this->assertNull(lessons::get_lesson_file_path('sub\\lesson'));
        $this->assertNull(lessons::get_lesson_file_path(''));
    }

    /**
     * A plain lesson name resolves to its text file inside the lessons directory.
     */
    public function test_lesson_file_path_accepts_plain_name(): void {
        global $CFG;

        $this->assertSame(
            $CFG->dirroot . '/mod/mootyper/lessons/04 Buchstaben r, u.txt',
            lessons::get_lesson_file_path('04 Buchstaben r, u')
        );
    }
}

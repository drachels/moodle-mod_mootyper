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
 * This will list all the MooTyper activities in a course and is accessed from the an Activities block.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\course_module_instance_list_viewed;

// Changed to this newer format 03/10/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$id = required_param('id', PARAM_INT);   // Course.
$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
require_course_login($course);

// Trigger course module instance list event.
$params = ['context' => context_course::instance($course->id)];
$event = course_module_instance_list_viewed::create($params);
$event->add_record_snapshot('course', $course);
$event->trigger();

$coursecontext = context_course::instance($course->id);

$PAGE->set_url('/mod/mootyper/index.php', ['id' => $id]);
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($coursecontext);

echo $OUTPUT->header();

if (! $mootypers = get_all_instances_in_course('mootyper', $course)) {
    notice(get_string('nomootypers', 'mootyper'), new moodle_url('/course/view.php', ['id' => $course->id]));
}

$table = new html_table();

if ($course->format == 'weeks') {
    $table->head = [get_string('week'), get_string('name')];
    $table->align = ['center', 'left'];
} else if ($course->format == 'topics') {
    $table->head = [get_string('topic'), get_string('name')];
    $table->align = ['center', 'left', 'left', 'left'];
} else {
    $table->head = [get_string('name')];
    $table->align = ['left', 'left', 'left'];
}

foreach ($mootypers as $mootyper) {
    if (!$mootyper->visible) {
        $link = html_writer::link(
            new moodle_url('/mod/mootyper/view.php', ['id' => $mootyper->coursemodule]),
            format_string($mootyper->name, true),
            ['class' => 'dimmed']);
    } else {
        $link = html_writer::link(
            new moodle_url('/mod/mootyper/view.php', ['id' => $mootyper->coursemodule]),
            format_string($mootyper->name, true));
    }

    if ($course->format == 'weeks' || $course->format == 'topics') {
        $table->data[] = [$mootyper->section, $link];
    } else {
        $table->data[] = [$link];
    }
}

echo $OUTPUT->heading(get_string('modulenameplural', 'mootyper'), 2);
echo html_writer::table($table);

echo $OUTPUT->footer();

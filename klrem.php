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
 * This file is used to remove layouts.
 *
 * Called from layouts.php when clicking on icon to remove a layout.
 * Still under development.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\layout_deleted;

// Changed to this newer format 20190301.
require(__DIR__ . '/../../config.php');

global $DB;
$id = optional_param('id', 0, PARAM_INT); // Course_module ID.
$kb = optional_param('kb', '', PARAM_TEXT); // Name of the keyboard layout to delete.

$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

require_login($course, true);
$context = context_module::instance($cm->id);
// 20220126 If we have a layout name, run the delete code.
if ($kb) {
    // 20220126 Search and retrieve the layout by name.
    $kbrecord = $DB->get_record('mootyper_layouts', ['name' => $kb], '*', MUST_EXIST);

    // 20220126 Get the absolute path to the current working directory.
    $pathtodir = getcwd();
    // 20220126 Create an absolute pointer to the php and js files that are to be deleted.
    $filepointer1 = $pathtodir.'/layouts/'.$kb.'.php';
    $filepointer2 = $pathtodir.'/layouts/'.$kb.'.js';

    // 20220126 Use unlink() function to delete the two physical files for the layout being deleted.
    if (!unlink($filepointer1)) {
        echo ("$filepointer1 cannot be deleted due to an error.");
        die;
    }
    if (!unlink($filepointer2)) {
        echo ("$filepointer2 cannot be deleted due to an error.");
        die;
    }

    // 20220126 Delete the database record for the layout being deleted.
    $DB->delete_records('mootyper_layouts', ['id' => $kbrecord->id]);

    // Trigger module layout_deleted event.
    $params = [
        'objectid' => $course->id,
        'context' => $context,
        'other' => [
            'layout' => $kb,
        ],
    ];
    $event = layout_deleted::create($params);
    $event->trigger();
}
// 20220126 After deletion, return to the list of layouts so we can delete more, if we need to.
$webdir = $CFG->wwwroot . '/mod/mootyper/layouts.php?id='.$id;
header('Location: '.$webdir);

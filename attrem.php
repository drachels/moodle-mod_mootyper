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
 * This file is used to remove the results of a student attempt.
 *
 * This sub-module is called from gview.php (View All Grades).
 * Currently it does NOT include an Are you sure check before it removes.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_login($course, true, $cm);
global $DB;

$mid = optional_param('m_id', 0, PARAM_INT);  // MooTyper id (mdl_mootyper).
$cid = optional_param('c_id', 0, PARAM_INT);  // Course module id (mdl_course_modules).
$gradeid = optional_param('g', 0, PARAM_INT);
$mtmode = optional_param('mtmode', 0, PARAM_INT);

if (isset($gradeid)) {
    $dbgrade = $DB->get_record('mootyper_grades', array('id' => $gradeid));
    $DB->delete_records('mootyper_attempts', array('id' => $dbgrade->attemptid));
    $DB->delete_records('mootyper_grades', array('id' => $dbgrade->id));
}
// Need to add grade removed event here.

// Return to the View my grades or View all grades page.
if ($mtmode == 2) {
    $webdir = $CFG->wwwroot . '/mod/mootyper/owngrades.php?id='.$cid.'&n='.$mid;
} else {
    $webdir = $CFG->wwwroot . '/mod/mootyper/gview.php?id='.$cid.'&n='.$mid;
}
header('Location: '.$webdir);

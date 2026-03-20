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
 * Recover stuck/in-progress MooTyper attempts.
 *
 * @package    mod_mootyper
 * @copyright  2026 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT); // Course module ID.
$scope = optional_param('scope', 'mine', PARAM_ALPHA); // mine|all.

$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
require_sesskey();

$context = context_module::instance($cm->id);
require_capability('mod/mootyper:view', $context);

$params = ['mootyperid' => $mootyper->id, 'inprogress' => 1];

if ($scope === 'all') {
    require_capability('mod/mootyper:viewgrades', $context);
} else {
    $scope = 'mine';
    $params['userid'] = $USER->id;
}

$attempts = $DB->get_records('mootyper_attempts', $params);
$attemptcount = count($attempts);

if ($attemptcount > 0) {
    $attemptids = array_keys($attempts);
    foreach ($attempts as $attempt) {
        $attempt->inprogress = 0;
        $DB->update_record('mootyper_attempts', $attempt);
    }
    $DB->delete_records_list('mootyper_checks', 'attemptid', $attemptids);
}

$notice = get_string('attemptrecoverydone', 'mootyper', $attemptcount);
redirect(new moodle_url('/mod/mootyper/view.php', ['id' => $cm->id]), $notice);

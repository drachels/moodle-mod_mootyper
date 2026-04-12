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
 * Flush MooTyper results to allow restarting a lesson/practice flow.
 *
 * @package    mod_mootyper
 * @copyright  2026 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$id = required_param('id', PARAM_INT); // Course module ID.
$scope = optional_param('scope', 'mine', PARAM_ALPHA); // mine|filtered.
$selecteduserid = optional_param('user', 0, PARAM_INT);
$exerciseid = optional_param('exercise', 0, PARAM_INT);
$jmode = optional_param('jmode', 0, PARAM_INT);

$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
require_sesskey();

$context = context_module::instance($cm->id);
if (!($context instanceof context_module)) {
    redirect(new moodle_url('/mod/mootyper/view.php', ['id' => $cm->id]), get_string('invalidaccess', 'mootyper'));
}
require_capability('mod/mootyper:view', $context);

$userids = [];

if ($scope === 'mine') {
    // Practice-only workflow: student flushes their own results.
    if ((string)$mootyper->isexam !== '2') {
        redirect(new moodle_url('/mod/mootyper/view.php', ['id' => $cm->id]), get_string('invalidaccess', 'mootyper'));
    }
    require_capability('mod/mootyper:viewmygrades', $context);
    $userids = [(int)$USER->id];
    $returnurl = new moodle_url('/mod/mootyper/owngrades.php', ['id' => $cm->id, 'n' => $mootyper->id]);
} else {
    // Teacher/admin workflow: flushes currently filtered results.
    $scope = 'filtered';
    if ((string)$mootyper->isexam !== '0' && (string)$mootyper->isexam !== '1' && (string)$mootyper->isexam !== '2') {
        redirect(new moodle_url('/mod/mootyper/view.php', ['id' => $cm->id]), get_string('invalidaccess', 'mootyper'));
    }
    require_capability('mod/mootyper:viewgrades', $context);

    $groupmode = groups_get_activity_groupmode($cm);
    $currentgroup = groups_get_activity_group($cm, true);

    if ($selecteduserid > 0) {
        if ($groupmode != NOGROUPS && $currentgroup && !groups_is_member($currentgroup, $selecteduserid)) {
            redirect(new moodle_url('/mod/mootyper/gview.php', ['id' => $cm->id, 'n' => $mootyper->id]), get_string('invalidaccess', 'mootyper'));
        }
        $userids = [$selecteduserid];
    } else if ($groupmode != NOGROUPS && $currentgroup) {
        $members = groups_get_members($currentgroup, 'u.id');
        if ($members) {
            $userids = array_keys($members);
        }
    } else {
        $sql = "SELECT DISTINCT userid FROM {mootyper_grades} WHERE mootyper = :mootyperid";
        $records = $DB->get_records_sql($sql, ['mootyperid' => $mootyper->id]);
        foreach ($records as $record) {
            $userids[] = (int)$record->userid;
        }
    }

    $returnurl = new moodle_url('/mod/mootyper/gview.php', [
        'id' => $cm->id,
        'n' => $mootyper->id,
        'jmode' => $jmode,
        'juser' => $selecteduserid,
        'exercise' => $exerciseid,
    ]);
}

$deletedcount = 0;

if (!empty($userids)) {
    $params = [
        'mootyperid' => $mootyper->id,
    ];
    $wheres = ['mootyper = :mootyperid'];

    list($usersql, $userparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED, 'uid');
    $wheres[] = "userid $usersql";
    $params += $userparams;

    if ($exerciseid > 0) {
        $wheres[] = 'exercise = :exerciseid';
        $params['exerciseid'] = $exerciseid;
    }

    $sql = "SELECT id, userid, attemptid FROM {mootyper_grades} WHERE ".implode(' AND ', $wheres);
    $grades = $DB->get_records_sql($sql, $params);

    if ($grades) {
        $gradeids = array_keys($grades);
        $attemptids = [];
        $affectedusers = [];

        foreach ($grades as $grade) {
            $affectedusers[(int)$grade->userid] = true;
            if (!empty($grade->attemptid)) {
                $attemptids[(int)$grade->attemptid] = true;
            }
        }

        if (!empty($attemptids)) {
            $attemptids = array_keys($attemptids);
            $DB->delete_records_list('mootyper_checks', 'attemptid', $attemptids);
            $DB->delete_records_list('mootyper_attempts', 'id', $attemptids);
        }

        require_once($CFG->dirroot.'/rating/lib.php');
        $rm = new rating_manager();
        foreach ($gradeids as $gradeid) {
            $delopt = new stdClass();
            $delopt->contextid = $context->id;
            $delopt->component = 'mod_mootyper';
            $delopt->ratingarea = 'exercises';
            $delopt->itemid = $gradeid;
            $rm->delete_ratings($delopt);
        }

        $DB->delete_records_list('mootyper_grades', 'id', $gradeids);
        $deletedcount = count($gradeids);

        foreach (array_keys($affectedusers) as $userid) {
            mootyper_update_grades($mootyper, $userid);
        }
    }
}

$notice = get_string('flushresultsdone', 'mootyper', $deletedcount);
redirect($returnurl, $notice);

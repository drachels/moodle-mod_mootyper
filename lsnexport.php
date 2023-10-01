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
 * This file is used to export lessons in the lesson format.
 *
 * This file is called from exercises.php when you click on the download icon.
 * It does pop-up a confirmation to export message.
 *
 * @package    mod_mootyper
 * @copyright  2016 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\lesson_exported;

require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $CFG, $DB, $USER;

$id = optional_param('id', 0, PARAM_INT); // Course ID.
$lsn = optional_param('lsn', 0, PARAM_INT); // Lesson ID to download.

if ($id) {
    $course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
} else {
    throw new moodle_exception(get_string('mootypererror', 'mootyper'));
}
require_login($course, true);
$context = context_course::instance($id);

$data = new StdClass();
$data->mootyper = $id;

$params = [];
$params[] = $lsn;
// Get name of lesson to export based on incoming lesson id.
$sql = "SELECT lessonname
        FROM {mootyper_lessons}
        WHERE id = ?";
// 20200613 Changed $sql to use $params.
$fname = $DB->get_record_sql($sql, $params);
$filename = $fname->lessonname;

// Check to see if we need GMT added to filename based on lesson export filename setting.
if (get_config('mod_mootyper', 'lesson_export_filename')) {
    $filename .= '_'.gmdate("Ymd_Hi").'GMT';
}
$filename .= '.txt';

$delimiter = " ";
header('Content-Type: text/plain;charset=utf-8');
header('Content-Disposition: attachement; filename="'.$filename.'";');
header("Pragma: no-cache");
header("Expires: 0");
$f = fopen('php://output', 'w');

// Find out how many exercises are in the lesson we are exporting.
$sqlc = "SELECT COUNT(mte.texttotype)
        FROM {mootyper_lessons} mtl
        LEFT JOIN {mootyper_exercises} mte
        ON mte.lesson =  mtl.id
        WHERE mtl.id = ?";
// 20200613 Changed $sqlc to use $params.
$count = $DB->count_records_sql($sqlc, $params);
// Added mte.id so exercises CAN be duplicates without getting debug message in output file.
$sql = "SELECT mte.id, mte.texttotype, mte.exercisename
        FROM {mootyper_lessons} mtl
        LEFT JOIN {mootyper_exercises} mte
        ON mte.lesson =  mtl.id
        WHERE mtl.id = ?";

// 20200613 Changed $sql to use $params.
if ($exercise = $DB->get_records_sql($sql, $params)) {
    foreach ($exercise as $txt) {
        // Decrement the exercise count so we know when to NOT include
        // a new exercise indicator.
        --$count;
        if ($count > 0) {
            // Write out the next exercise and exercise name with a break indicator.
            $field1 = [$txt->texttotype.chr(10).'/**/'.chr(10)];
            $field2 = [$txt->exercisename.chr(10).'/**/'.chr(10)];
        } else {
            // Write out last exercise with with a break indicator follwed by the exercise name
            // and no break indicator after it.
            $field1 = [$txt->texttotype.chr(10).'/**/'.chr(10)];
            $field2 = [$txt->exercisename.chr(10)];
        }
        // Place the texttotyper followed by the exercise name in our file $f.
        fwrite($f, implode(" ", $field1));
        fwrite($f, implode(" ", $field2));
    }
    // Close download our file then close it.
    fclose($f);
}

// Trigger lesson_export event.
$params = [
    'objectid' => $data->mootyper,
    'context' => $context,
    'other' => $filename,
];
$event = lesson_exported::create($params);
$event->trigger();

return;

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


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

global $CFG, $DB, $USER;

$id = optional_param('id', 0, PARAM_INT); // Course ID.
$lsn = optional_param('lsn', 0, PARAM_INT); // Lesson ID to download.

if ($id) {
    $course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}
require_login($course, true);
$context = context_course::instance($id);

$data = new StdClass();
$data->mootyper = $id;
$context = context_course::instance($id);
// Trigger lesson_export event.
$event = \mod_mootyper\event\lesson_exported::create(array(
    'objectid' => $data->mootyper,
    'context' => $context
));
$event->trigger();

$params = array();
// Get name of lesson to export based on incoming lesson id.
$sql = "SELECT lessonname
        FROM {mootyper_lessons}
        WHERE id = $lsn";
$fname = $DB->get_record_sql($sql);
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
// Commenting out next line as headers/field list not needed for lessons.
// fputcsv($f, $params, $delimiter);
// Find out how many exercises are in the lesson we are exporting.
$sqlc = "SELECT COUNT(mte.texttotype)
        FROM {mootyper_lessons} mtl
        LEFT JOIN {mootyper_exercises} mte
        ON mte.lesson =  mtl.id
        WHERE mtl.id = $lsn";

$count = $DB->count_records_sql($sqlc, $params = null);
// Added mte.id so exercises CAN be duplicates without getting debug message in output file.
$sql = "SELECT mte.id, mte.texttotype
        FROM {mootyper_lessons} mtl
        LEFT JOIN {mootyper_exercises} mte
        ON mte.lesson =  mtl.id
        WHERE mtl.id = $lsn";

if ($exercise = $DB->get_records_sql($sql, $params = null)) {
    foreach ($exercise as $txt) {
        // Decrement the exercise count so we know when to NOT include
        // a new exercise indicator.
        --$count;
        if ($count > 0) {
            // Write out the next exercise with a new exercise indicator and blank line after it.
            // $field = str_replace('\n', chr(13).chr(10), array($txt->texttotype.chr(13).chr(10).'/**/'.chr(13).chr(10)));
            // $field = str_replace('\n',chr(10), array($txt->texttotype.chr(10).'/**/'.chr(10)));
            $field = array($txt->texttotype.chr(10).'/**/'.chr(10));
        } else {
            // Write out last exercise with no indicator and no blank line after it.
            // $field = str_replace('\n', chr(13).chr(10), array($txt->texttotype.chr(13).chr(10)));
            $field = array($txt->texttotype.chr(10));
        }
        // Write out the last exercise without a new exercise indicator after it.
        fwrite($f, implode(" ", $field));
    }
    fclose($f);
}
return;

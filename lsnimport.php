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
 * This file is used to import new lessons and keyboard layouts.
 *
 * Can be called from the MooTyper admin block anytime it is visible.
 * The file scans the lesson and layout folders and checks the files
 * found there against the ones already in the database.
 * Duplicates are skipped while new ones are added with the results
 * listed for the user to see. Continue at the end will take the
 * user to exercises.php for possible editing.
 *
 * @package    mod_mootyper
 * @copyright  2016 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\invalid_access_attempt;
use mod_mootyper\event\lesson_imported;
use mod_mootyper\event\layout_imported;

require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

/**
 * Define the lesson import function.
 * @param string $dafile
 * @param int $authoridarg
 * @param int $visiblearg
 * @param int $editablearg
 * @param int $coursearg
 */
function read_lessons_file($dafile, $authoridarg, $visiblearg, $editablearg, $coursearg=null) {
    global $DB, $CFG, $USER;
    // Scan the mootyper lessons folder for lessonname.txt files.
    $thefile = $CFG->dirroot."/mod/mootyper/lessons/".$dafile;
    // Extract the lesson name from the file name.
    $record = new stdClass();
    $periodpos = strrpos($dafile, '.');
    $lessonname = substr($dafile, 0, $periodpos);

    // Create new record for the mootyper_lessons table for this lessonname.
    $record->lessonname = $lessonname; // Add the lesson name.
    $record->authorid = $authoridarg; // Add the author id.
    $record->visible = $visiblearg; // Add the visibility setting.
    $record->editable = $editablearg; // Add the edit-ability setting.
    if (!is_null($coursearg)) {
        $record->courseid = $coursearg; // If we have a course id use it, otherwise set to null.
    }
    // Create entry in the mootyper_lessons table based on new data.
    $lessonid = $DB->insert_record('mootyper_lessons', $record, true);
    // Now read the whole file so we can split it into exercises.
    $fh = fopen($thefile, 'r');
    $thedata = fread($fh, filesize($thefile));
    fclose($fh);
    $haha = "";
    for ($i = 0; $i < strlen($thedata); $i++) {
        $haha .= $thedata[$i];
    }
    $haha = trim($haha);
    // Break lesson into an array of separate exercises.
    $splitted = explode ('/**/' , $haha);
    // 20210328 Changed for loop to count by two so we can get exercise name along with the exercise text.
    for ($j = 0; $j < count($splitted); $j += 2) {
        // Remove whitespace from both sides of $splitted.
        $exercise = trim($splitted[$j]);
        // 20210328 Added same cleanup for exercisename.
        $exercisename = trim($splitted[$j + 1]);

        $allowed = ['ё', 'ë', '¸', 'á', 'é',
                    'í', 'ï', 'ó', 'ú', '\\',
                    '~', '!', '@', '#', '$',
                    '%', '^', '&', '(', ')',
                    '*', '_', '+', ':', ';',
                    '"', '{', '}', '>', '<',
                    '?', '\'', '-', '/', '=',
                    '.', ',', ' ', '|', '¡',
                    // @codingStandardsIgnoreLine
                    '`', 'ç', 'ñ', 'º', '¿',
                    'ª', '·', '\n', '\r', '\r\n',
                    '\n\r', ']', '[', '¬', '´',
                    '§', '°', '€', '¦',
                    '¢', '£', '?', '¹', '²',
                    '³', '¨', '?', 'ù', 'µ',
                    'û', '÷', '×', 'ł', 'Ł',
                    'ß', '¤', '«', '»',
                   ];
        // Create a number to use as the exercise name. Start with 1 and increment for each exercise detected.
        // 20210328 We now get an actual exercise name from the lessonname.txt file, so $nm not needed now.
        $texttotype = "";
        // Place each character of an exercise into $texttotype.
        for ($k = 0; $k < strlen($exercise); $k++) {
            // Need to check on this.
            // * If it is not a letter
            // * and if it is not a number
            // * compare against $allowed array.
            // * If not included die
            // * or something.
            $ch = $exercise[$k];
            if ($ch == "\n") {
                $texttotype .= '\n';
            } else {
                $texttotype .= $ch;
            }
        }
        // Create new entry in the mootyper_exercises.
        $erecord = new stdClass();
        $erecord->texttotype = $texttotype;
        // 20210328 Save exercise name instead of just a number.
        $erecord->exercisename = $exercisename; // Add the exercise name here.
        $erecord->lesson = $lessonid;
        $erecord->snumber = ($j + 2) / 2;
        $DB->insert_record('mootyper_exercises', $erecord, false);
    }
}

/**
 * Define the keyboard import function.
 * @param string $dafile
 */
function add_keyboard_layout($dafile) {
    global $DB;
    $periodpos = strrpos($dafile, '.');
    $layoutname = substr($dafile, 0, $periodpos);
    $record = (object) [
        'name' => $layoutname,
    ];
    $DB->insert_record('mootyper_layouts', $record, true);
}

/**
 * Define import and update exercise function.
 * @param string $dafile
 * @param int $lsnid
 * @param int $lsn
 */
function update_exercises_file($dafile, $lsnid, $lsn) {
    global $DB, $CFG, $USER;
    // 202104010 Added this new function.
    // Scan the mootyper lessons folder for lessonname.txt files.
    $thefile = $CFG->dirroot."/mod/mootyper/lessons/".$dafile;
    // Extract the lesson name from the file name.
    $record = new stdClass();
    $periodpos = strrpos($dafile, '.');
    // 20240512 Skip files with names starting with a period.
    if ($periodpos <> 0) {
        $lessonname = substr($dafile, 0, $periodpos);

        // Now read the whole lesson file so we can split it into exercises and exercise names.
        $fh = fopen($thefile, 'r');
        $thedata = fread($fh, filesize($thefile));
        fclose($fh);

        $haha = "";
        for ($i = 0; $i < strlen($thedata); $i++) {
            $haha .= $thedata[$i];
        }
        $haha = trim($haha);
        // Break lesson into an array of separate exercises followed by exercise names.
        $splitted = explode ('/**/' , $haha);

        for ($j = 0; $j < count($splitted); $j += 2) {
            // Remove whitespace from both sides of $splitted.
            $fexercise = trim($splitted[$j]);
            $fexercisename = trim($splitted[$j + 1]);

            // Create sql to see how many exercises are in this lesson.
            $sql = "SELECT id, texttotype, exercisename, lesson, snumber
                      FROM {mootyper_exercises}
                     WHERE lesson = '".$lsnid."'";

            // Get the total number of exercises that belong to this lesson.
            $snumber = count($DB->get_records_sql($sql));
            $snum = $j / 2 + 1;
            $sql = "SELECT id, texttotype, exercisename, lesson, snumber
                      FROM {mootyper_exercises}
                     WHERE lesson = '".$lsnid."' AND snumber = '".$snum."'";

            $record = $DB->get_record_sql($sql);
            // If there is no record, we must be adding a new exercise.
            if (!$record) {
                $record = new stdClass();
                $record->texttotype = $fexercise;
                $record->exercisename = $fexercisename;
                $record->lesson = $lsnid;
                $record->snumber = $snum;
                $DB->insert_record('mootyper_exercises', $record, false);
                echo "<tr class='table-success'><td><b>$lsn</td><td>"
                    .get_string('exercise_name_added', 'mootyper', $fexercisename).'</b></td></tr>';
            } else if (($record->texttotype == $fexercise) && ($record->exercisename == $fexercisename)) {
                // If no changes, then do not need to do anything.
                echo "<tr class='table-dark text-dark'><td>$lsn</td><td>".get_string('lsnimportnotadd', 'mootyper').'</td></tr>';
            } else if (($record->texttotype == $fexercise) && !($record->exercisename == $fexercisename)) {
                // If the text is the same but the exercise name is different, then change it.
                $record->exercisename = $fexercisename;
                $DB->update_record('mootyper_exercises', $record, false);
                echo "<tr class='table-success'><td><b>$lsn</td><td>"
                    .get_string('exercise_name_updated', 'mootyper', $fexercisename).'</b></td></tr>';
            } else if (!($record->texttotype == $fexercise) && ($record->exercisename == $fexercisename)) {
                // If the text is different but not the exercise name, then update the text.
                // Need updated string for adding changed text to type.
                $record->texttotype = $fexercise;
                $DB->update_record('mootyper_exercises', $record, false);
                echo "<tr class='table-success'><td><b>$lsn</td><td>".get_string('lsnimportadd', 'mootyper').'</b></td></tr>';
            }
        }
    }
}

// Actual page starts here.
$id = optional_param('id', 0, PARAM_INT); // Course ID.
$lsn = optional_param('lsn', 0, PARAM_INT); // Lesson ID to download.
$cm = get_coursemodule_from_id('mootyper', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

require_login($course, true);
$context = context_module::instance($cm->id);

// 20200706 Added to prevent student direct URL access attempts.
if (!(has_capability('mod/mootyper:aftersetup', $context))) {
    // Trigger invalid_access_attempt with redirect to course page.
    $params = [
        'objectid' => $id,
        'context' => $context,
        'other' => [
            'file' => 'lsnimport.php',
        ],
    ];
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('../../course/view.php?id='.$course->id, get_string('invalidaccessexp', 'mootyper'));
}

// Print the page header.
$PAGE->set_url('/mod/mootyper/exercises.php', ['id' => $course->id]);
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();
echo '<b>'.get_string('lsnimport', 'mootyper').'</b><br><br>';
echo '<b>'.get_string('sflesson', 'mootyper').'</b><br>';
echo '<table class="table table-hover" style="width:100%">';
echo '<tbody>';
// Set pointer to lessons folder, then get all lesson names in there.
$pth = $CFG->dirroot."/mod/mootyper/lessons";
$res = scandir($pth);

for ($i = 0; $i < count($res); $i++) {
    if (is_file($pth."/".$res[$i])) {
        // Get a filename from the lessons folder.
        $fl = $res[$i]; // Argument list dafile, authorid_arg, visible_arg, editable_arg, course_arg.

        // Strip away the .txt portion of the filename.
        $periodpos = strrpos($fl, '.');
        $lsn = substr($fl, 0, $periodpos);
        // Create sql to see if lesson name is already an installed lesson.
        $sql = "SELECT lessonname, id
            FROM {mootyper_lessons}
            WHERE lessonname = '".$lsn."'";

        if ($importlesson = $DB->get_record_sql($sql)) {
            // If it is found in the db, then check to see if it needs to be updated.
            update_exercises_file($fl, $importlesson->id, $lsn);
        } else {
            // If it's not found in the db, then add the new lesson to the database.
            echo "<tr class='table-success'><td><b>$lsn</td><td>".get_string('lsnimportadd', 'mootyper').'</b></td></tr>';
            read_lessons_file($fl, $USER->id, 0, 2);
            // Since we added a new lesson, make a log entry about it.
            $data = new StdClass();
            $data->mootyper = $id;
            $context = context_module::instance($id);
            // Trigger lesson_imported event.
            $params = [
                'objectid' => $data->mootyper,
                'context' => $context,
                'other' => $lsn,
            ];
            $event = lesson_imported::create($params);
            $event->trigger();
        }
    }
}
echo '</tbody>';
echo '</table>';
echo '<br><b>'.get_string('layout', 'mootyper').'</b><br>';
echo '<table class="table table-hover" style="width:100%">';
echo '<thead class="thead-dark">';
echo get_string('layout', 'mootyper');
echo '</thead>';
echo '<tbody>';
// Set pointer to keyboard layouts folder, then get all names in there.
$pth2 = $CFG->dirroot."/mod/mootyper/layouts";
$res2 = scandir($pth2);
for ($j = 0; $j < count($res2); $j++) {
    if (is_file($pth2."/".$res2[$j]) && ( substr($res2[$j], (strripos($res2[$j], '.') + 1) ) == 'php')) {
        // Get a filename from the lessons folder.
        $fl2 = $res2[$j];
        // Strip away the .txt portion of the filename.
        $periodpos = strrpos($fl2, '.');
        $kbl = substr($fl2, 0, $periodpos);
        // Create sql to see if lesson name is already an installed lesson.
        $sql = "SELECT name
            FROM {mootyper_layouts}
            WHERE name = '".$kbl."'";

        if ($importkbl = $DB->get_record_sql($sql)) {
            // If it's true the name is already in the database, do nothing.
            echo "<tr class='table-dark text-dark'><td>$kbl</td><td>".get_string('kblimportnotadd', 'mootyper').'</td></tr>';
        } else {
            // If it's not found in the db, then add the new layout to the database.
            echo "<tr class='table-success'><td><b>$kbl</td><td>".get_string('kblimportadd', 'mootyper').'</b></td></tr>';
            // Actually go add the layout to the database.
            add_keyboard_layout($fl2);
            // Since we added a new layout, make a log entry about it.
            $data = new StdClass();
            $data->mootyper = $id;
            $context = context_module::instance($id);
            // Trigger layout_imported event.
            $params = [
                'objectid' => $data->mootyper,
                'context' => $context,
                'other' => $kbl,
            ];
            $event = layout_imported::create($params);
            $event->trigger();
        }
    }
}
echo '</tbody>';
echo '</table>';

$jlnk2 = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;
// 11/19/19 Change from a, Continue, link to a, Continue, button.
echo '<a href="'.$jlnk2.'" class="btn btn-primary"  style="border-radius: 8px">'.get_string('fcontinue', 'mootyper').'</a><br>';
echo $OUTPUT->footer();
return;

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
 * This file replaces the legacy STATEMENTS section in:
 *
 * db/install.xml,
 * lib.php/modulename_install()
 * post installation hook and partially defaults.php
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die; // @codingStandardsIgnoreLine

/**
 * Post installation procedure.
 *
 * @see upgrade_plugins_modules().
 */
function xmldb_mootyper_install() {
    global $DB, $CFG, $USER;

    $pth = $CFG->dirroot."/mod/mootyper/lessons";
    $res = scandir($pth);
    for ($i = 0; $i < count($res); $i++) {
        if (is_file($pth."/".$res[$i])) {
            $fl = $res[$i]; // Argument list dafile, authorid_arg, visible_arg, editable_arg, course_arg.
            read_lessons_file($fl, $USER->id, 0, 2);
        }
    }
    $pth2 = $CFG->dirroot."/mod/mootyper/layouts";
    $res2 = scandir($pth2);
    for ($j = 0; $j < count($res2); $j++) {
        if (is_file($pth2."/".$res2[$j]) && ( substr($res2[$j], (strripos($res2[$j], '.') + 1) ) == 'php')) {
            $fl2 = $res2[$j];
            add_keyboard_layout($fl2);
        }
    }
}

/**
 * Install keyboard layouts into the database.
 *
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

		// @codingStandardsIgnoreLine
        $allowed = array('ё', 'ë', '¸','á', 'é', 'í', 'ï', 'ó', 'ú', '\\', '~', '!', '@', '#', '$', '%', '^', '&', '(', ')', '*', '_', '+', ':', ';', '"', '{', '}', '>', '<', '?', '\'', '-', '/', '=', '.', ',', ' ', '|', '¡', '`', 'ç', 'ñ', 'º', '¿', 'ª', '·', '\n', '\r', '\r\n', '\n\r', ']', '[', '¬', '´', '`', '§', '°', '€', '¦', '¢', '£', '?', '¹', '²', '³', '¨', '?', 'ù', 'µ', 'û','÷', '×', 'ł', 'Ł', 'ß', '¤', '«', '»');
        // Create a number to use as the exercise name. Start with 1 and increment for each exercise detected.
        // 20210328 We now get an actual exercise name from the lessonname.txt file, so $nm not needed now.
        $texttotype = "";
        // Place each character of an exercise into $texttotype.
        for ($k = 0; $k < strlen($exercise); $k++) {
            // Need to check this.
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
 * Post installation recovery procedure.
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_mootyper_install_recovery() {
}

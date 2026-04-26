<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Populate a MooTyper language course from donor and apply language-specific
 * key mapping (BG_*, HI_*, RU_*, UK_*, SR_*, GR_*, AM_*, TH_*, TE_*, TA_*).
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);
// phpcs:disable moodle.Files.MoodleInternal

/**
 * Get CLI --key=value argument.
 *
 * @param array $argv args.
 * @param string $key key.
 * @param string|null $default default.
 * @return string|null
 */
function arg_value(array $argv, string $key, ?string $default = null): ?string {
    foreach ($argv as $arg) {
        if (strpos($arg, '--' . $key . '=') === 0) {
            return substr($arg, strlen($key) + 3);
        }
    }
    return $default;
}

/**
 * Fail fast for CLI.
 *
 * @param string $message error text.
 * @param int $code exit code.
 */
function fail(string $message, int $code = 1): void {
    fwrite(STDERR, "ERROR: {$message}\n");
    exit($code);
}

$moodleroot = arg_value($argv, 'moodleroot');
$donorcourseid = (int)(arg_value($argv, 'donorcourseid', '0'));
$targetcourseid = (int)(arg_value($argv, 'targetcourseid', '0'));
$create = (int)(arg_value($argv, 'create', '0')) === 1;
$destinationfullnamearg = arg_value($argv, 'destinationfullname');
$destinationshortnamearg = arg_value($argv, 'destinationshortname');
$destinationfullname = $destinationfullnamearg ?? 'Belgium(Dutch) Demo';
$destinationshortname = $destinationshortnamearg ?? 'Belgium(Dutch)-demo';
$categoryid = (int)(arg_value($argv, 'categoryid', '0'));
$targetlanguage = strtolower((string)arg_value($argv, 'targetlanguage', 'nl'));
$layoutoverride = arg_value($argv, 'layoutname', '');

if (!$moodleroot || !is_file($moodleroot . '/config.php')) {
    fail('Missing/invalid --moodleroot');
}
if ($donorcourseid <= 0) {
    fail('Missing/invalid --donorcourseid');
}
if (!$create && $targetcourseid <= 0) {
    fail('Missing/invalid --targetcourseid when create=0');
}
if ($create && $categoryid <= 0) {
    fail('Missing/invalid --categoryid when create=1');
}
if (!in_array($targetlanguage, ['nl', 'fr', 'hi', 'ru', 'uk', 'sr', 'gr', 'am', 'th', 'te', 'ta', 'bg', 'ko'], true)) {
    fail('Unsupported --targetlanguage. Use nl, fr, hi, ru, uk, sr, gr, am, th, te, ta, bg, or ko');
}

if ($layoutoverride !== '') {
    $layoutname = $layoutoverride;
} else if ($targetlanguage === 'hi') {
    $layoutname = 'Hindi(HIV5)';
} else if ($targetlanguage === 'ru') {
    $layoutname = 'Russian(RUV5)';
} else if ($targetlanguage === 'uk') {
    $layoutname = 'Ukrainian(UKV5)';
} else if ($targetlanguage === 'sr') {
    $layoutname = 'Serbian(SR_CRV5)';
} else if ($targetlanguage === 'gr') {
    $layoutname = 'Greek(V5)';
} else if ($targetlanguage === 'am') {
    $layoutname = 'Armenian(V5)';
} else if ($targetlanguage === 'th') {
    $layoutname = 'Thai(V4)';
} else if ($targetlanguage === 'te') {
    $layoutname = 'Telugu(V4)';
} else if ($targetlanguage === 'ta') {
    $layoutname = 'Tamil(V5)e';
} else if ($targetlanguage === 'bg') {
    $layoutname = 'Bulgarian(V5)';
} else if ($targetlanguage === 'fr') {
    $layoutname = 'French(FRV5)';
} else if ($targetlanguage === 'ko') {
    $layoutname = 'Korean(KRV7)';
} else {
    $layoutname = 'Belgium(DutchV5)';
}

require($moodleroot . '/config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/modlib.php');
require_once($CFG->libdir . '/gradelib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
require_once($CFG->libdir . '/filelib.php');

global $DB, $USER, $CFG;
$USER = get_admin();
if (empty($USER) || empty($USER->id)) {
    fail('Admin user not available in CLI context');
}

/**
 * Clone one activity into target course.
 *
 * @param int $sourcecmid source cmid.
 * @param int $targetcourseid target course.
 * @param int $userid running user.
 * @return int new cmid.
 */
function clone_activity_to_course(int $sourcecmid, int $targetcourseid, int $userid): int {
    global $CFG;
    $sourcecontext = context_module::instance($sourcecmid);

    $bc = new backup_controller(
        backup::TYPE_1ACTIVITY,
        $sourcecmid,
        backup::FORMAT_MOODLE,
        backup::INTERACTIVE_NO,
        backup::MODE_IMPORT,
        $userid
    );
    $bc->execute_plan();
    $backupid = $bc->get_backupid();
    $backupbasepath = $bc->get_plan()->get_basepath();
    $bc->destroy();

    $rc = new restore_controller(
        $backupid,
        $targetcourseid,
        backup::INTERACTIVE_NO,
        backup::MODE_IMPORT,
        $userid,
        backup::TARGET_CURRENT_ADDING
    );

    if (!$rc->execute_precheck()) {
        $results = $rc->get_precheck_results();
        $rc->destroy();
        if (empty($CFG->keeptempdirectoriesonbackup)) {
            fulldelete($backupbasepath);
        }
        fail('Restore precheck failed: ' . json_encode($results));
    }

    $rc->execute_plan();

    $newcmid = 0;
    foreach ($rc->get_plan()->get_tasks() as $task) {
        if (is_subclass_of($task, 'restore_activity_task') && $task->get_old_contextid() == $sourcecontext->id) {
            $newcmid = (int)$task->get_moduleid();
            break;
        }
    }

    $rc->destroy();
    if (empty($CFG->keeptempdirectoriesonbackup)) {
        fulldelete($backupbasepath);
    }

    if ($newcmid <= 0) {
        fail('Could not resolve cloned cmid for source cmid ' . $sourcecmid);
    }

    return $newcmid;
}

/**
 * Get activity instance name from course module row.
 *
 * @param stdClass $cmrec course module row.
 * @return string
 */
function get_mod_instance_name(stdClass $cmrec): string {
    global $DB;
    $modname = $DB->get_field('modules', 'name', ['id' => $cmrec->module], MUST_EXIST);
    if ($modname === 'icontent') {
        return (string)$DB->get_field('icontent', 'name', ['id' => $cmrec->instance]);
    }
    if ($modname === 'page') {
        return (string)$DB->get_field('page', 'name', ['id' => $cmrec->instance]);
    }
    if ($modname === 'mootyper') {
        return (string)$DB->get_field('mootyper', 'name', ['id' => $cmrec->instance]);
    }
    if ($modname === 'forum') {
        return (string)$DB->get_field('forum', 'name', ['id' => $cmrec->instance]);
    }
    return $modname;
}

/**
 * Update iContent intro and pages.
 *
 * @param int $icontentid iContent id.
 * @param string $name module title.
 * @param string $intro module intro html.
 * @param array $pages page array.
 * @param bool $draft draft flag.
 * @param string $draftsuffix draft title suffix.
 * @param string $draftintro intro note html.
 * @param string $draftpagesuffix draft page title suffix.
 * @param string $draftpagenote draft page note html.
 */
function update_icontent_intro(
    int $icontentid,
    string $name,
    string $intro,
    array $pages,
    bool $draft = false,
    string $draftsuffix = ' (translation draft)',
    string $draftintro = '<p><strong>Translation draft.</strong> Review version.</p>',
    string $draftpagesuffix = ' (draft)',
    string $draftpagenote = '<p><em>Draft version for translation review.</em></p>'
): void {
    global $DB;

    $ic = $DB->get_record('icontent', ['id' => $icontentid], '*', MUST_EXIST);
    $ic->name = $draft ? ($name . $draftsuffix) : $name;
    $ic->intro = $draft ? ($draftintro . $intro) : $intro;
    $ic->timemodified = time();
    $DB->update_record('icontent', $ic);

    $ipages = $DB->get_records('icontent_pages', ['icontentid' => $icontentid], 'pagenum ASC', 'id,pagenum,title,pageicontent');
    $idx = 0;
    foreach ($ipages as $p) {
        if (!isset($pages[$idx])) {
            break;
        }
        $newp = $pages[$idx];
        $p->title = $draft ? ($newp['title'] . $draftpagesuffix) : $newp['title'];
        $p->pageicontent = $draft ? ($draftpagenote . $newp['content']) : $newp['content'];
        $DB->update_record('icontent_pages', $p);
        $idx++;
    }
}

/**
 * Update page module title + body.
 *
 * @param int $pageid page id.
 * @param string $name title.
 * @param string $intro intro html.
 * @param string $content body html.
 * @param bool $draft draft flag.
 * @param string $draftsuffix draft title suffix.
 * @param string $draftintro intro note html.
 * @param string $draftcontentnote content note html.
 */
function update_page_intro(
    int $pageid,
    string $name,
    string $intro,
    string $content,
    bool $draft = false,
    string $draftsuffix = ' (translation draft)',
    string $draftintro = '<p><strong>Translation draft.</strong> Review version.</p>',
    string $draftcontentnote = '<p><em>Draft version for translation review.</em></p>'
): void {
    global $DB;

    $p = $DB->get_record('page', ['id' => $pageid], '*', MUST_EXIST);
    $p->name = $draft ? ($name . $draftsuffix) : $name;
    $p->intro = $draft ? ($draftintro . $intro) : $intro;
    $p->content = $draft ? ($draftcontentnote . $content) : $content;
    $p->timemodified = time();
    $DB->update_record('page', $p);
}

/**
 * Ensure grade category exists as child of parent.
 *
 * @param int $courseid course id.
 * @param int $parentid parent category id.
 * @param string $name category name.
 * @return int grade category id.
 */
function ensure_grade_category(int $courseid, int $parentid, string $name): int {
    $existing = grade_category::fetch(['courseid' => $courseid, 'parent' => $parentid, 'fullname' => $name]);
    if ($existing) {
        return (int)$existing->id;
    }

    $params = new stdClass();
    $params->courseid = $courseid;
    $params->fullname = $name;
    $params->parent = $parentid;
    $gc = new grade_category($params, false);
    $gc->insert();

    return (int)$gc->id;
}

/**
 * Read a single line (1-based snumber) from a pre-built KO_*.txt lesson file.
 * Each KO_*.txt file has one exercise per line (no blank separators).
 * The file name may have jamo characters after the lesson/test number, so we
 * search by the numeric prefix (e.g. KO_Lesson_01 or KO_Test_01).
 *
 * @param string $lessonname e.g. KO_Lesson_01_asdfjkl; or KO_Test_01
 * @param int $snumber 1-based exercise number.
 * @return string exercise text, or empty string if file/line not found.
 */
function korean_text_from_file(string $lessonname, int $snumber): string {
    global $CFG;
    $dir = $CFG->dirroot . '/mod/mootyper/lessons/';

    // Extract the numeric prefix: KO_Lesson_01 or KO_Test_01.
    if (preg_match('/^(KO_(?:Lesson|Test)_\d+)/', $lessonname, $m)) {
        $prefix = $m[1];
    } else {
        $prefix = $lessonname;
    }

    $files = glob($dir . $prefix . '*.txt');
    if (empty($files)) {
        return '';
    }
    $filepath = $files[0];
    $lines = array_values(array_filter(
        array_map('rtrim', file($filepath, FILE_IGNORE_NEW_LINES)),
        function($l) { return $l !== ''; }
    ));
    $idx = $snumber - 1;
    return isset($lines[$idx]) ? $lines[$idx] : '';
}

/**
 * QWERTY to Belgian AZERTY text remap used by BG_* lessons.
 *
 * @param string $text source text.
 * @return string
 */
function azerty_map_text(string $text): string {
    $map = [
        'a' => 'q',
        'q' => 'a',
        'A' => 'Q',
        'Q' => 'A',
        'w' => 'z',
        'z' => 'w',
        'W' => 'Z',
        'Z' => 'W',
        ';' => 'm',
        'm' => ',',
        ',' => ';',
        ':' => 'M',
        'M' => '?',
        '?' => ':',
    ];
    return strtr($text, $map);
}

/**
 * Convert EN_* lesson names to language-specific naming.
 *
 * @param string $name source lesson name.
 * @param string $targetlanguage target language.
 * @return string
 */
function target_lesson_name(string $name, string $targetlanguage): string {
    if ($targetlanguage === 'hi') {
        $prefix = 'HI_';
    } else if ($targetlanguage === 'ru') {
        $prefix = 'RU_';
    } else if ($targetlanguage === 'uk') {
        $prefix = 'UK_';
    } else if ($targetlanguage === 'sr') {
        $prefix = 'SR_';
    } else if ($targetlanguage === 'gr') {
        $prefix = 'GR_';
    } else if ($targetlanguage === 'am') {
        $prefix = 'AM_';
    } else if ($targetlanguage === 'th') {
        $prefix = 'TH_';
    } else if ($targetlanguage === 'te') {
        $prefix = 'TE_';
    } else if ($targetlanguage === 'ta') {
        $prefix = 'TA_';
    } else if ($targetlanguage === 'bg') {
        $prefix = 'BUL_';
    } else if ($targetlanguage === 'ko') {
        $prefix = 'KO_';
    } else {
        $prefix = 'BG_';
    }
    if (strpos($name, $prefix) === 0) {
        return $name;
    }
    if (strpos($name, 'EN_Lesson_') === 0) {
        return $prefix . 'Lesson_' . substr($name, strlen('EN_Lesson_'));
    }
    if (strpos($name, 'EN_Test_') === 0) {
        return $prefix . 'Test_' . substr($name, strlen('EN_Test_'));
    }
    return $prefix . $name;
}

/**
 * Map EN lesson text by physical key position into Russian(RUV5) layout output.
 *
 * @param string $text source text.
 * @return string
 */
function russian_keypos_map_text(string $text): string {
    $map = [
        'a' => 'ф', 'A' => 'Ф',
        's' => 'ы', 'S' => 'Ы',
        'd' => 'в', 'D' => 'В',
        'f' => 'а', 'F' => 'А',
        'g' => 'п', 'G' => 'П',
        'h' => 'р', 'H' => 'Р',
        'j' => 'о', 'J' => 'О',
        'k' => 'л', 'K' => 'Л',
        'l' => 'д', 'L' => 'Д',
        ';' => 'ж', ':' => 'Ж',
        "'" => 'э', '"' => 'Э',
        'q' => 'й', 'Q' => 'Й',
        'w' => 'ц', 'W' => 'Ц',
        'e' => 'у', 'E' => 'У',
        'r' => 'к', 'R' => 'К',
        't' => 'е', 'T' => 'Е',
        'y' => 'н', 'Y' => 'Н',
        'u' => 'г', 'U' => 'Г',
        'i' => 'ш', 'I' => 'Ш',
        'o' => 'щ', 'O' => 'Щ',
        'p' => 'з', 'P' => 'З',
        '[' => 'х', '{' => 'Х',
        ']' => 'ъ', '}' => 'Ъ',
        'z' => 'я', 'Z' => 'Я',
        'x' => 'ч', 'X' => 'Ч',
        'c' => 'с', 'C' => 'С',
        'v' => 'м', 'V' => 'М',
        'b' => 'и', 'B' => 'И',
        'n' => 'т', 'N' => 'Т',
        'm' => 'ь', 'M' => 'Ь',
        ',' => 'б', '<' => 'Б',
        '.' => 'ю', '>' => 'Ю',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Ukrainian(UKV5) layout output.
 *
 * @param string $text source text.
 * @return string
 */
function ukrainian_keypos_map_text(string $text): string {
    $map = [
        'q' => 'й', 'Q' => 'Й',
        'w' => 'ц', 'W' => 'Ц',
        'e' => 'у', 'E' => 'У',
        'r' => 'к', 'R' => 'К',
        't' => 'е', 'T' => 'Е',
        'y' => 'н', 'Y' => 'Н',
        'u' => 'г', 'U' => 'Г',
        'i' => 'ш', 'I' => 'Ш',
        'o' => 'щ', 'O' => 'Щ',
        'p' => 'з', 'P' => 'З',
        '[' => 'х', '{' => 'Х',
        ']' => 'ї', '}' => 'Ї',
        'a' => 'ф', 'A' => 'Ф',
        's' => 'і', 'S' => 'І',
        'd' => 'в', 'D' => 'В',
        'f' => 'а', 'F' => 'А',
        'g' => 'п', 'G' => 'П',
        'h' => 'р', 'H' => 'Р',
        'j' => 'о', 'J' => 'О',
        'k' => 'л', 'K' => 'Л',
        'l' => 'д', 'L' => 'Д',
        ';' => 'ж', ':' => 'Ж',
        "'" => 'є', '"' => 'Є',
        'z' => 'я', 'Z' => 'Я',
        'x' => 'ч', 'X' => 'Ч',
        'c' => 'с', 'C' => 'С',
        'v' => 'м', 'V' => 'М',
        'b' => 'и', 'B' => 'И',
        'n' => 'т', 'N' => 'Т',
        'm' => 'ь', 'M' => 'Ь',
        ',' => 'б', '<' => 'Б',
        '.' => 'ю', '>' => 'Ю',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Serbian(SR_CRV5) layout output.
 *
 * @param string $text source text.
 * @return string
 */
function serbian_keypos_map_text(string $text): string {
    $map = [
        'q' => 'љ', 'Q' => 'Љ',
        'w' => 'њ', 'W' => 'Њ',
        'e' => 'е', 'E' => 'Е',
        'r' => 'р', 'R' => 'Р',
        't' => 'т', 'T' => 'Т',
        'y' => 'з', 'Y' => 'З',
        'u' => 'у', 'U' => 'У',
        'i' => 'и', 'I' => 'И',
        'o' => 'о', 'O' => 'О',
        'p' => 'п', 'P' => 'П',
        '[' => 'ш', '{' => 'Ш',
        ']' => 'ђ', '}' => 'Ђ',
        'a' => 'а', 'A' => 'А',
        's' => 'с', 'S' => 'С',
        'd' => 'д', 'D' => 'Д',
        'f' => 'ф', 'F' => 'Ф',
        'g' => 'г', 'G' => 'Г',
        'h' => 'х', 'H' => 'Х',
        'j' => 'ј', 'J' => 'Ј',
        'k' => 'к', 'K' => 'К',
        'l' => 'л', 'L' => 'Л',
        ';' => 'ч', ':' => 'Ч',
        "'" => 'ћ', '"' => 'Ћ',
        'z' => 'ѕ', 'Z' => 'Ѕ',
        'x' => 'џ', 'X' => 'Џ',
        'c' => 'ц', 'C' => 'Ц',
        'v' => 'в', 'V' => 'В',
        'b' => 'б', 'B' => 'Б',
        'n' => 'н', 'N' => 'Н',
        'm' => 'м', 'M' => 'М',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Greek(V5) layout output.
 *
 * @param string $text source text.
 * @return string
 */
function greek_keypos_map_text(string $text): string {
    $map = [
        'q' => ';',  'Q' => ':',
        'w' => 'ς',  'W' => '΅',
        'e' => 'ε',  'E' => 'Ε',
        'r' => 'ρ',  'R' => 'Ρ',
        't' => 'τ',  'T' => 'Τ',
        'y' => 'υ',  'Y' => 'Υ',
        'u' => 'θ',  'U' => 'Θ',
        'i' => 'ι',  'I' => 'Ι',
        'o' => 'ο',  'O' => 'Ο',
        'p' => 'π',  'P' => 'Π',
        'a' => 'α',  'A' => 'Α',
        's' => 'σ',  'S' => 'Σ',
        'd' => 'δ',  'D' => 'Δ',
        'f' => 'φ',  'F' => 'Φ',
        'g' => 'γ',  'G' => 'Γ',
        'h' => 'η',  'H' => 'Η',
        'j' => 'ξ',  'J' => 'Ξ',
        'k' => 'κ',  'K' => 'Κ',
        'l' => 'λ',  'L' => 'Λ',
        ';' => '΄',  ':' => '¨',
        'z' => 'ζ',  'Z' => 'Ζ',
        'x' => 'χ',  'X' => 'Χ',
        'c' => 'ψ',  'C' => 'Ψ',
        'v' => 'ω',  'V' => 'Ω',
        'b' => 'β',  'B' => 'Β',
        'n' => 'ν',  'N' => 'Ν',
        'm' => 'μ',  'M' => 'Μ',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Armenian(V5) layout output.
 *
 * @param string $text source text.
 * @return string
 */
function armenian_keypos_map_text(string $text): string {
    $map = [
        'q' => 'ք', 'Q' => 'Ք',
        'w' => 'ո', 'W' => 'Ո',
        'e' => 'ե', 'E' => 'Ե',
        'r' => 'ռ', 'R' => 'Ռ',
        't' => 'տ', 'T' => 'Տ',
        'y' => 'ը', 'Y' => 'Ը',
        'u' => 'ւ', 'U' => 'Ւ',
        'i' => 'ի', 'I' => 'Ի',
        'o' => 'օ', 'O' => 'Օ',
        'p' => 'պ', 'P' => 'Պ',
        '[' => 'խ',
        ']' => 'ծ',
        '\\' => 'շ',
        'a' => 'ա', 'A' => 'Ա',
        's' => 'ս', 'S' => 'Ս',
        'd' => 'դ', 'D' => 'Դ',
        'f' => 'ֆ', 'F' => 'Ֆ',
        'g' => 'գ', 'G' => 'Գ',
        'h' => 'հ', 'H' => 'Հ',
        'j' => 'յ', 'J' => 'Յ',
        'k' => 'կ', 'K' => 'Կ',
        'l' => 'լ', 'L' => 'Լ',
        "'" => '՛',
        'z' => 'զ', 'Z' => 'Զ',
        'x' => 'ղ', 'X' => 'Ղ',
        'c' => 'ց', 'C' => 'Ց',
        'v' => 'վ', 'V' => 'Վ',
        'b' => 'բ', 'B' => 'Բ',
        'n' => 'ն', 'N' => 'Ն',
        'm' => 'մ', 'M' => 'Մ',
        '.' => '․',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Thai(V4) Kedmanee layout output.
 *
 * @param string $text source text.
 * @return string
 */
function thai_keypos_map_text(string $text): string {
    $map = [
        'q' => 'ๆ',  'Q' => '๐',
        'w' => 'ไ',  'W' => '"',
        'e' => 'ำ',  'E' => 'ฎ',
        'r' => 'พ',  'R' => 'ฑ',
        't' => 'ะ',  'T' => 'ธ',
        'y' => 'ั',  'Y' => 'ํ',
        'u' => 'ี',  'U' => '๊',
        'i' => 'ร',  'I' => 'ณ',
        'o' => 'น',  'O' => 'ฯ',
        'p' => 'ย',  'P' => 'ญ',
        '[' => 'บ',  '{' => 'ฐ',
        ']' => 'ล',  '}' => ',',
        'a' => 'ฟ',  'A' => 'ฤ',
        's' => 'ห',  'S' => 'ฆ',
        'd' => 'ก',  'D' => 'ฏ',
        'f' => 'ด',  'F' => 'โ',
        'g' => 'เ',  'G' => 'ฌ',
        'h' => '้',  'H' => '็',
        'j' => '่',  'J' => '๋',
        'k' => 'า',  'K' => 'ษ',
        'l' => 'ส',  'L' => 'ศ',
        ';' => 'ว',  ':' => 'ซ',
        "'" => 'ง',  '"' => '.',
        'z' => 'ผ',  'Z' => '(',
        'x' => 'ป',  'X' => ')',
        'c' => 'แ',  'C' => 'ฉ',
        'v' => 'อ',  'V' => 'ฮ',
        'b' => 'ิ',  'B' => 'ฺ',
        'n' => 'ื',  'N' => '์',
        'm' => 'ท',  'M' => '?',
        ',' => 'ม',  '<' => 'ฒ',
        '.' => 'ใ',  '>' => 'ฬ',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Telugu(V4) Inscript layout output.
 *
 * @param string $text source text.
 * @return string
 */
function telugu_keypos_map_text(string $text): string {
    $map = [
        'q' => 'ౌ', 'Q' => 'ఔ',
        'w' => 'ై', 'W' => 'ఐ',
        'e' => 'ా', 'E' => 'ఆ',
        'r' => 'ీ', 'R' => 'ఈ',
        't' => 'ూ', 'T' => 'ఊ',
        'y' => 'బ', 'Y' => 'భ',
        'u' => 'హ', 'U' => 'ఙ',
        'i' => 'గ', 'I' => 'ఘ',
        'o' => 'ద', 'O' => 'ధ',
        'p' => 'జ', 'P' => 'ఝ',
        '[' => 'డ', '{' => 'ఢ',
        '}' => 'ఞ',
        'a' => 'ో', 'A' => 'ఓ',
        's' => 'ే', 'S' => 'ఏ',
        'd' => '్', 'D' => 'అ',
        'f' => 'ి', 'F' => 'ఇ',
        'g' => 'ు', 'G' => 'ఉ',
        'h' => 'ప', 'H' => 'ఫ',
        'j' => 'ర', 'J' => 'ఱ',
        'k' => 'క', 'K' => 'ఖ',
        'l' => 'త', 'L' => 'థ',
        ';' => 'చ', ':' => 'ఛ',
        "'" => 'ట', '"' => 'ఠ',
        'z' => 'ె', 'Z' => 'ఎ',
        'x' => 'ం', 'X' => 'ఁ',
        'c' => 'మ', 'C' => 'ణ',
        'v' => 'న', 'V' => 'న',
        'b' => 'వ',
        'n' => 'ల', 'N' => 'ళ',
        'm' => 'స', 'M' => 'శ',
        '<' => 'ష',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Tamil(V5)e layout output.
 *
 * @param string $text source text.
 * @return string
 */
function tamil_keypos_map_text(string $text): string {
    $map = [
        'q' => 'ஆ', 'Q' => 'ஸ',
        'w' => 'ஈ', 'W' => 'ஷ',
        'e' => 'ஊ', 'E' => 'ஜ',
        'r' => 'ஐ', 'R' => 'ஹ',
        't' => 'ஏ', 'T' => 'க்ஷ',
        'y' => 'ள', 'Y' => 'ஶ்ரீ',
        'u' => 'ற', 'U' => 'ஶ',
        'i' => 'ன',
        'o' => 'ட', 'O' => '[',
        'p' => 'ண', 'P' => ']',
        '[' => 'ச',
        ']' => 'ஞ',
        'a' => 'அ', 'A' => '௹',
        's' => 'இ', 'S' => '௺',
        'd' => 'உ', 'D' => '௸',
        'f' => '்', 'F' => 'ஃ',
        'g' => 'எ',
        'h' => 'க',
        'j' => 'ப',
        'k' => 'ம', 'K' => '"',
        'l' => 'த', 'L' => ':',
        ';' => 'ந', ':' => ';',
        "'" => 'ய', '"' => "'",
        'z' => 'ஔ', 'Z' => '௳',
        'x' => 'ஓ', 'X' => '௴',
        'c' => 'ஒ', 'C' => '௵',
        'v' => 'வ', 'V' => '௶',
        'b' => 'ங', 'B' => '௷',
        'n' => 'ல', 'N' => 'ௐ',
        'm' => 'ர', 'M' => '/',
    ];
    return strtr($text, $map);
}

/**
 * Map EN lesson text by physical key position into Hindi layout output.
 *
 * @param string $text source text.
 * @return string
 */
function hindi_keypos_map_text(string $text): string {
    $map = [
        chr(96) => '',
        '~' => '',
        '1' => '1',
        '!' => 'ऍ',
        '2' => '2',
        '@' => 'ॅ',
        '3' => '3',
        '#' => '्',
        '4' => '4',
        '$' => 'र्',
        '5' => '5',
        '%' => 'ज्ञ',
        '6' => '6',
        '^' => 'त्र',
        '7' => '7',
        '&' => 'क्ष',
        '8' => '8',
        '*' => 'श्र',
        '9' => '9',
        '(' => '(',
        '0' => '0',
        ')' => ')',
        '-' => '-',
        '_' => 'ः',
        '=' => 'ृ',
        '+' => 'ऋ',
        'q' => 'ौ',
        'Q' => 'औ',
        'w' => 'ै',
        'W' => 'ऐ',
        'e' => 'ा',
        'E' => 'आ',
        'r' => 'ी',
        'R' => 'ई',
        't' => 'ू',
        'T' => 'ऊ',
        'y' => 'ब',
        'Y' => 'भ',
        'u' => 'ह',
        'U' => 'ङ',
        'i' => 'ग',
        'I' => 'घ',
        'o' => 'द',
        'O' => 'ध',
        'p' => 'ज',
        'P' => 'झ',
        '[' => 'ड',
        '{' => 'ढ',
        ']' => '़',
        '}' => 'ञ',
        '\\' => 'ॉ',
        '|' => 'ऑ',
        'a' => 'ो',
        'A' => 'ओ',
        's' => 'े',
        'S' => 'ए',
        'd' => '्',
        'D' => 'अ',
        'f' => 'ि',
        'F' => 'इ',
        'g' => 'ु',
        'G' => 'उ',
        'h' => 'प',
        'H' => 'फ',
        'j' => 'र',
        'J' => 'ऱ',
        'k' => 'क',
        'K' => 'ख',
        'l' => 'त',
        'L' => 'थ',
        ';' => 'च',
        ':' => 'च',
        "'" => 'ट',
        '"' => 'ठ',
        'z' => '',
        'Z' => '',
        'x' => 'ं',
        'X' => 'ँ',
        'c' => 'म',
        'C' => 'ण',
        'v' => 'न',
        'V' => 'न',
        'b' => 'व',
        'B' => 'व',
        'n' => 'ल',
        'N' => 'ळ',
        'm' => 'स',
        'M' => 'श',
        ',' => ',',
        '<' => 'ष',
        '.' => '.',
        '>' => '।',
        '/' => 'य',
        '?' => 'य़',
    ];

    $mapped = strtr($text, $map);
    $mapped = preg_replace('/[A-Za-z]/u', '', $mapped);
    return $mapped ?? '';
}

/**
 * Map EN lesson text by physical key position into Bulgarian(V5) layout output.
 *
 * @param string $text source text.
 * @return string
 */
function bulgarian_keypos_map_text(string $text): string {
    $map = [
        // Home row
        'a' => 'ь', 'A' => 'ѝ',
        's' => 'я', 'S' => 'Я',
        'd' => 'а', 'D' => 'А',
        'f' => 'о', 'F' => 'О',
        'g' => 'ж', 'G' => 'Ж',
        'h' => 'г', 'H' => 'Г',
        'j' => 'т', 'J' => 'Т',
        'k' => 'н', 'K' => 'Н',
        'l' => 'в', 'L' => 'В',
        ';' => 'м', ':' => 'М',
        "'" => 'ч', '"' => 'Ч',
        // Q row
        'q' => ',', 'Q' => 'ы',
        'w' => 'у', 'W' => 'У',
        'e' => 'е', 'E' => 'Е',
        'r' => 'и', 'R' => 'И',
        't' => 'ш', 'T' => 'Ш',
        'y' => 'щ', 'Y' => 'Щ',
        'u' => 'к', 'U' => 'К',
        'i' => 'с', 'I' => 'С',
        'o' => 'д', 'O' => 'Д',
        'p' => 'з', 'P' => 'З',
        '[' => 'ц', '{' => 'Ц',
        ']' => ';', '}' => '§',
        '\\' => '„', '|' => '"',
        // Bottom row
        'z' => 'ю', 'Z' => 'Ю',
        'x' => 'й', 'X' => 'Й',
        'c' => 'ъ', 'C' => 'Ъ',
        'v' => 'э', 'V' => 'Э',
        'b' => 'ф', 'B' => 'Ф',
        'n' => 'х', 'N' => 'Х',
        'm' => 'п', 'M' => 'П',
        ',' => 'р', '<' => 'Р',
        '.' => 'л', '>' => 'Л',
        '/' => 'б', '?' => 'Б',
        '=' => '.', '+' => '€',
    ];
    return strtr($text, $map);
}

/**
 * Convert exercise text for target language.
 *
 * @param string $text source text.
 * @param string $targetlanguage target language.
 * @return string
 */
function map_text_by_language(string $text, string $targetlanguage): string {
    if ($targetlanguage === 'hi') {
        return hindi_keypos_map_text($text);
    }
    if ($targetlanguage === 'ru') {
        return russian_keypos_map_text($text);
    }
    if ($targetlanguage === 'uk') {
        return ukrainian_keypos_map_text($text);
    }
    if ($targetlanguage === 'sr') {
        return serbian_keypos_map_text($text);
    }
    if ($targetlanguage === 'gr') {
        return greek_keypos_map_text($text);
    }
    if ($targetlanguage === 'am') {
        return armenian_keypos_map_text($text);
    }
    if ($targetlanguage === 'th') {
        return thai_keypos_map_text($text);
    }
    if ($targetlanguage === 'te') {
        return telugu_keypos_map_text($text);
    }
    if ($targetlanguage === 'ta') {
        return tamil_keypos_map_text($text);
    }
    if ($targetlanguage === 'bg') {
        return bulgarian_keypos_map_text($text);
    }
    if ($targetlanguage === 'ko') {
        // Korean text is loaded from pre-built KO_*.txt files; return source unchanged.
        // The per-exercise file-read happens in the lesson-populate loop.
        return $text;
    }
    return azerty_map_text($text);
}

if ($create) {
    if ($DB->record_exists('course', ['shortname' => $destinationshortname])) {
        fail('Target shortname already exists: ' . $destinationshortname);
    }

    $newcourse = new stdClass();
    $newcourse->fullname = $destinationfullname;
    $newcourse->shortname = $destinationshortname;
    $newcourse->category = $categoryid;
    $newcourse->visible = 1;
    $newcourse->format = 'topics';
    $newcourse->numsections = 10;
    $created = create_course($newcourse);
    $targetcourseid = (int)$created->id;
} else {
    $targetcourse = $DB->get_record('course', ['id' => $targetcourseid], '*', MUST_EXIST);
    if ($destinationfullnamearg !== null && $destinationfullnamearg !== '' && $targetcourse->fullname !== $destinationfullnamearg) {
        $targetcourse->fullname = $destinationfullnamearg;
        $DB->update_record('course', $targetcourse);
    }
}

$DB->get_record('course', ['id' => $donorcourseid], '*', MUST_EXIST);

$layoutparams = ['n' => $layoutname, 'p' => '%' . $layoutname . '%'];
$layoutid = (int)$DB->get_field_select('mootyper_layouts', 'id', 'name = :n OR name LIKE :p', $layoutparams, IGNORE_MISSING);
if ($layoutid <= 0) {
    fail('Layout not found: ' . $layoutname);
}
$resolvedlayoutname = (string)$DB->get_field('mootyper_layouts', 'name', ['id' => $layoutid], MUST_EXIST);
$modulemootyperid = (int)$DB->get_field('modules', 'id', ['name' => 'mootyper'], MUST_EXIST);

// Normalize course language to requested target language.
$course = $DB->get_record('course', ['id' => $targetcourseid], '*', MUST_EXIST);
$course->lang = $targetlanguage;
$DB->update_record('course', $course);

course_create_sections_if_missing($targetcourseid, range(0, 10));

// Keep one announcements forum and remove everything else before rebuilding.
$modinfo = get_fast_modinfo($targetcourseid);
$keepforumcmid = 0;
foreach ($modinfo->get_cms() as $cm) {
    $isannounce = $cm->modname === 'forum' && (int)$cm->sectionnum === 0;
    $isannounce = $isannounce && strtolower(trim($cm->name)) === 'announcements' && $keepforumcmid === 0;
    if ($isannounce) {
        $keepforumcmid = (int)$cm->id;
        continue;
    }
    course_delete_module($cm->id);
}
rebuild_course_cache($targetcourseid, true);

$mapprimary = [];
$mapdraft = [];
$sourcegeneral = [];
$clonedmootypercmids = [];

// Clone donor sections 0..3.
for ($section = 0; $section <= 3; $section++) {
    $ssec = $DB->get_record('course_sections', ['course' => $donorcourseid, 'section' => $section], '*', MUST_EXIST);
    $seq = trim((string)$ssec->sequence);
    if ($seq === '') {
        continue;
    }
    $sourcecmids = array_values(array_filter(array_map('intval', explode(',', $seq))));

    foreach ($sourcecmids as $sourcecmid) {
        $sourcecm = $DB->get_record('course_modules', ['id' => $sourcecmid], '*', MUST_EXIST);
        $modname = $DB->get_field('modules', 'name', ['id' => $sourcecm->module], MUST_EXIST);
        if ($section === 0 && $modname === 'forum') {
            continue;
        }

        $instancename = get_mod_instance_name($sourcecm);
        $newcmid = clone_activity_to_course($sourcecmid, $targetcourseid, (int)$USER->id);

        rebuild_course_cache($targetcourseid, true);
        $tmodinfo = get_fast_modinfo($targetcourseid);
        $newcm = $tmodinfo->get_cm($newcmid);
        $targetsectioninfo = $tmodinfo->get_section_info($section);
        moveto_module($newcm, $targetsectioninfo);

        if ($modname === 'mootyper') {
            $clonedmootypercmids[] = $newcmid;
        }

        if ($section === 0 && ($modname === 'icontent' || $modname === 'page')) {
            $sourcegeneral[] = ['sourcecmid' => $sourcecmid, 'name' => $instancename, 'mod' => $modname];
            if (stripos($instancename, 'Guided Introduction') !== false) {
                $mapprimary['guided'] = ['cmid' => $newcmid, 'mod' => $modname];
            } else if (stripos($instancename, 'Method of carrying out') !== false) {
                $mapprimary['method'] = ['cmid' => $newcmid, 'mod' => $modname];
            } else if (stripos($instancename, 'Initial position of the fingers') !== false) {
                $mapprimary['fingers'] = ['cmid' => $newcmid, 'mod' => $modname];
            }
        }
    }
}

// Create hidden translation-draft variants in section 0.
foreach ($sourcegeneral as $g) {
    $key = null;
    if (stripos($g['name'], 'Guided Introduction') !== false) {
        $key = 'guided';
    } else if (stripos($g['name'], 'Method of carrying out') !== false) {
        $key = 'method';
    } else if (stripos($g['name'], 'Initial position of the fingers') !== false) {
        $key = 'fingers';
    }
    if (!$key) {
        continue;
    }

    $draftcmid = clone_activity_to_course((int)$g['sourcecmid'], $targetcourseid, (int)$USER->id);
    rebuild_course_cache($targetcourseid, true);
    $tmodinfo = get_fast_modinfo($targetcourseid);
    $draftcm = $tmodinfo->get_cm($draftcmid);
    moveto_module($draftcm, $tmodinfo->get_section_info(0));
    set_coursemodule_visible($draftcmid, 0);
    $mapdraft[$key] = ['cmid' => $draftcmid, 'mod' => $g['mod']];
}

// Minimal complete intro content.
if ($targetlanguage === 'ru') {
    $guidedname = 'MooTyper направленное введение';
    $guidedintro = '<p>Это введение объясняет структуру курса, оценивание и порядок работы с упражнениями.</p>';
    $guidedpages = [
        ['title' => 'Добро пожаловать', 'content' => '<p>Сначала точность, потом скорость.</p>'],
        ['title' => 'Порядок работы', 'content' => '<p>Режим практики, затем режим урока, затем режим экзамена.</p>'],
        ['title' => 'Результаты', 'content' => '<p>Следите за точностью, WPM и выполнением заданий.</p>'],
        ['title' => 'Режим работы', 'content' => '<p>Занимайтесь короткими сессиями и повторяйте трудные клавиши.</p>'],
        ['title' => 'Ошибки', 'content' => '<p>Используйте ошибки как целевую обратную связь.</p>'],
        ['title' => 'Эргономика', 'content' => '<p>Держите запястья прямо и поддерживайте правильную осанку.</p>'],
        ['title' => 'Прогресс', 'content' => '<p>Смотрите на тенденции, а не на единичные результаты.</p>'],
        ['title' => 'Начало', 'content' => '<p>Начните с режима практики.</p>'],
    ];

    $methodname = 'Метод выполнения упражнений';
    $methodintro = '<p>Единый метод позволяет сравнивать все попытки между собой.</p>';
    $methodpages = [
        ['title' => 'Подготовка', 'content' => '<p>Проверьте раскладку клавиатуры и уберите отвлекающие факторы.</p>'],
        ['title' => 'Печать', 'content' => '<p>Соблюдайте ровный ритм.</p>'],
        ['title' => 'Коррекция', 'content' => '<p>Замедлитесь и изолируйте повторяющиеся ошибки.</p>'],
        ['title' => 'Проверка', 'content' => '<p>Проверяйте точность и WPM после каждой попытки.</p>'],
        ['title' => 'Режим работы', 'content' => '<p>Сначала точность, потом скорость.</p>'],
    ];

    $fingersname = 'Исходное положение пальцев на клавиатуре';
    $fingersintro = '<p>Правильное положение пальцев — основа стабильной печати вслепую.</p>';
    $fingerscontent = '<p>Возвращайтесь на исходный ряд, используйте назначенные пальцы и поддерживайте расслабленную позу.</p>';
    $draftsuffix = ' (черновик перевода)';
    $draftintro = '<p><strong>Черновик перевода.</strong> Пожалуйста, проверьте.</p>';
    $draftpagesuffix = ' (черновик)';
    $draftpagenote = '<p><em>Черновая версия для проверки перевода.</em></p>';
} else if ($targetlanguage === 'uk') {
    $guidedname = 'Вступний посібник MooTyper';
    $guidedintro = '<p>Цей посібник пояснює структуру курсу, оцінювання та порядок роботи з вправами.</p>';
    $guidedpages = [
        ['title' => 'Ласкаво просимо', 'content' => '<p>Спочатку точність, потім швидкість.</p>'],
        ['title' => 'Порядок курсу', 'content' => '<p>Режим практики, потім режим уроку, потім режим іспиту.</p>'],
        ['title' => 'Результати', 'content' => '<p>Відстежуйте точність, WPM та виконання завдань.</p>'],
        ['title' => 'Режим роботи', 'content' => '<p>Займайтеся короткими сесіями та повторюйте важкі клавіші.</p>'],
        ['title' => 'Помилки', 'content' => '<p>Використовуйте помилки як цільовий зворотній зв\'язок.</p>'],
        ['title' => 'Ергономіка', 'content' => '<p>Тримайте зап\'ястя прямо та підтримуйте правильну поставу.</p>'],
        ['title' => 'Прогрес', 'content' => '<p>Дивіться на тенденції, а не на окремі результати.</p>'],
        ['title' => 'Початок', 'content' => '<p>Почніть із режиму практики.</p>'],
    ];

    $methodname = 'Метод виконання вправ';
    $methodintro = '<p>Єдиний метод дозволяє порівнювати всі спроби між собою.</p>';
    $methodpages = [
        ['title' => 'Підготовка', 'content' => '<p>Перевірте розкладку клавіатури та приберіть відволікаючі фактори.</p>'],
        ['title' => 'Друк', 'content' => '<p>Дотримуйтесь рівного ритму.</p>'],
        ['title' => 'Виправлення', 'content' => '<p>Сповільніться та ізолюйте повторювані помилки.</p>'],
        ['title' => 'Перевірка', 'content' => '<p>Перевіряйте точність та WPM після кожної спроби.</p>'],
        ['title' => 'Режим роботи', 'content' => '<p>Спочатку точність, потім швидкість.</p>'],
    ];

    $fingersname = 'Початкове положення пальців на клавіатурі';
    $fingersintro = '<p>Правильне положення пальців — основа стабільного сліпого друку.</p>';
    $fingerscontent = '<p>Повертайтесь на основний ряд, використовуйте призначені пальці та підтримуйте розслаблену позу.</p>';
    $draftsuffix = ' (чернетка перекладу)';
    $draftintro = '<p><strong>Чернетка перекладу.</strong> Будь ласка, перевірте.</p>';
    $draftpagesuffix = ' (чернетка)';
    $draftpagenote = '<p><em>Чернетка для перевірки перекладу.</em></p>';
} else if ($targetlanguage === 'sr') {
    $guidedname = 'Увод у MooTyper';
    $guidedintro = '<p>Овај водич објашњава структуру курса, оцењивање и редослед вежбања.</p>';
    $guidedpages = [
        ['title' => 'Добродошли', 'content' => '<p>Прво тачност, па брзина.</p>'],
        ['title' => 'Ток курса', 'content' => '<p>Режим вежбања, затим режим лекције, затим режим испита.</p>'],
        ['title' => 'Резултати', 'content' => '<p>Прати тачност, WPM и извршене задатке.</p>'],
        ['title' => 'Рутина', 'content' => '<p>Вежбај у кратким сесијама и понављај тешке тастере.</p>'],
        ['title' => 'Грешке', 'content' => '<p>Користи грешке као циљану повратну информацију.</p>'],
        ['title' => 'Ергономија', 'content' => '<p>Држи зглобове равно и одржавај правилно седење.</p>'],
        ['title' => 'Напредак', 'content' => '<p>Гледај на трендове, а не на јединачне резултате.</p>'],
        ['title' => 'Почетак', 'content' => '<p>Почни у режиму вежбања.</p>'],
    ];

    $methodname = 'Метод вежбања';
    $methodintro = '<p>Јединствени метод омогућује поређење свих покушаја.</p>';
    $methodpages = [
        ['title' => 'Припрема', 'content' => '<p>Провери распоред тастатуре и уклони ометајуће факторе.</p>'],
        ['title' => 'Куцање', 'content' => '<p>Одржавај сталан ритам.</p>'],
        ['title' => 'Исправка', 'content' => '<p>Успори и изолуј понављајуће грешке.</p>'],
        ['title' => 'Преглед', 'content' => '<p>Провери тачност и WPM после сваког покушаја.</p>'],
        ['title' => 'Рутина', 'content' => '<p>Прво тачност, па брзина.</p>'],
    ];

    $fingersname = 'Почетни положај прстију на тастатури';
    $fingersintro = '<p>Правилан положај прстију је основа стабилног слепог куцања.</p>';
    $fingerscontent = '<p>Враћај прсте на основни ред, користи додељене прсте и одржавај опуштен положај.</p>';
    $draftsuffix = ' (нацрт превода)';
    $draftintro = '<p><strong>Нацрт превода.</strong> Молимо прегледати.</p>';
    $draftpagesuffix = ' (нацрт)';
    $draftpagenote = '<p><em>Нацрт верзија за преглед превода.</em></p>';
} else if ($targetlanguage === 'gr') {
    $guidedname = 'Εισαγωγή στο MooTyper';
    $guidedintro = '<p>Αυτή η εισαγωγή εξηγεί τη δομή του μαθήματος, τη βαθμολόγηση και τη σειρά ασκήσεων.</p>';
    $guidedpages = [
        ['title' => 'Καλώς ήλθατε', 'content' => '<p>Πρώτα ακρίβεια, μετά ταχύτητα.</p>'],
        ['title' => 'Ροή μαθήματος', 'content' => '<p>Λειτουργία εξάσκησης, λειτουργία μαθήματος, λειτουργία εξέτασης.</p>'],
        ['title' => 'Αποτελέσματα', 'content' => '<p>Παρακολούθησε ακρίβεια, WPM και ολοκλήρωση.</p>'],
        ['title' => 'Ρουτίνα', 'content' => '<p>Εξάσκησε σε σύντομες περιόδους και επανέλαβε δύσκολα πλήκτρα.</p>'],
        ['title' => 'Σφάλματα', 'content' => '<p>Χρησιμοποίησε τα λάθη ως στοχευμένη ανατροφοδότηση.</p>'],
        ['title' => 'Εργονομία', 'content' => '<p>Κράτα τους καρπούς ευθείς και διατήρησε σωστή στάση.</p>'],
        ['title' => 'Πρόοδος', 'content' => '<p>Κοίτα τις τάσεις, όχι μεμονωμένα αποτελέσματα.</p>'],
        ['title' => 'Έναρξη', 'content' => '<p>Ξεκίνα στη λειτουργία εξάσκησης.</p>'],
    ];

    $methodname = 'Μέθοδος άσκησης';
    $methodintro = '<p>Μια συνεπής μέθοδος κάνει τις προσπάθειες συγκρίσιμες.</p>';
    $methodpages = [
        ['title' => 'Προετοιμασία', 'content' => '<p>Έλεγξε τη διάταξη πληκτρολογίου και αφαίρεσε περισπασμούς.</p>'],
        ['title' => 'Πληκτρολόγηση', 'content' => '<p>Χρησιμοποίησε σταθερό ρυθμό.</p>'],
        ['title' => 'Διόρθωση', 'content' => '<p>Επιβράδυνε και απομόνωσε επαναλαμβανόμενα λάθη.</p>'],
        ['title' => 'Ανασκόπηση', 'content' => '<p>Έλεγξε ακρίβεια και WPM μετά από κάθε προσπάθεια.</p>'],
        ['title' => 'Ρουτίνα', 'content' => '<p>Πρώτα ακρίβεια, μετά ταχύτητα.</p>'],
    ];

    $fingersname = 'Αρχική θέση των δακτύλων στο πληκτρολόγιο';
    $fingersintro = '<p>Η σωστή τοποθέτηση δακτύλων είναι η βάση σταθερής τυφλής πληκτρολόγησης.</p>';
    $fingerscontent = '<p>Επίστρεφε στη βασική σειρά, χρησιμοποίησε τα καθορισμένα δάκτυλα και διατήρησε χαλαρή στάση.</p>';
    $draftsuffix = ' (σχέδιο μετάφρασης)';
    $draftintro = '<p><strong>Σχέδιο μετάφρασης.</strong> Παρακαλώ ελέγξτε.</p>';
    $draftpagesuffix = ' (σχέδιο)';
    $draftpagenote = '<p><em>Σχέδιο για έλεγχο μετάφρασης.</em></p>';
} else if ($targetlanguage === 'am') {
    $guidedname = 'MooTyper-ի ուղեցույց';
    $guidedintro = '<p>Այս ուղեցույցը բացատրում է դասընթացի կառուցվածքը, գնահատումը և վարժությունների կարգը:</p>';
    $guidedpages = [
        ['title' => 'Բարի գալուստ', 'content' => '<p>Սկզբում ճշտություն, ապա արագություն:</p>'],
        ['title' => 'Դասընթացի հոսք', 'content' => '<p>Պրակտիկայի ռեժիմ, ապա դասի ռեժիմ, ապա քննության ռեժիմ:</p>'],
        ['title' => 'Արդյունքներ', 'content' => '<p>Հետևիր ճշտությանը, WPM-ին և ավարտմանը:</p>'],
        ['title' => 'Ռեժիմ', 'content' => '<p>Կարճ նիստերով վարժվիր և կրկնիր դժվար ստեղները:</p>'],
        ['title' => 'Սխալներ', 'content' => '<p>Օգտագործիր սխալները որպես թիրախային հետադարձ կապ:</p>'],
        ['title' => 'Էրգոնոմիկա', 'content' => '<p>Պահիր դաստակները ուղիղ և ճիշտ կեցվածք:</p>'],
        ['title' => 'Առաջընթաց', 'content' => '<p>Հետևիր միտումներին, ոչ թե առանձին արդյունքներին:</p>'],
        ['title' => 'Սկիզբ', 'content' => '<p>Սկսիր պրակտիկայի ռեժիմից:</p>'],
    ];

    $methodname = 'Վարժությունների մեթոդ';
    $methodintro = '<p>Միատեսակ մեթոդը հնարավոր է դարձնում բոլոր փորձերի համեմատությունը:</p>';
    $methodpages = [
        ['title' => 'Պատրաստություն', 'content' => '<p>Ստուգիր ստեղնաշարի դասավորությունը և հեռացրու շեղողները:</p>'],
        ['title' => 'Մուտքագրում', 'content' => '<p>Օգտագործիր կայուն ռիթմ:</p>'],
        ['title' => 'Ուղղում', 'content' => '<p>Դանդաղիր և առանձնացրու կրկնվող սխալները:</p>'],
        ['title' => 'Ստուգում', 'content' => '<p>Ստուգիր ճշտությունն ու WPM-ը յուրաքանչյուր փորձից հետո:</p>'],
        ['title' => 'Ռեժիմ', 'content' => '<p>Սկզբում ճշտություն, ապա արագություն:</p>'],
    ];

    $fingersname = 'Մատների սկզբնական դիրքը ստեղնաշարի վրա';
    $fingersintro = '<p>Մատների ճիշտ դիրքը կայուն կույր մուտքագրման հիմքն է:</p>';
    $fingerscontent = '<p>Վերադարձիր հիմնական շարք, օգտագործիր նշանակված մատները և պահիր հանգստացված կեցվածք:</p>';
    $draftsuffix = ' (թարգմանության նախագիծ)';
    $draftintro = '<p><strong>Թարգմանության նախագիծ:</strong> Խնդրում ենք ստուգել:</p>';
    $draftpagesuffix = ' (նախագիծ)';
    $draftpagenote = '<p><em>Թարգմանության ստուգման նախագիծ:</em></p>';
} else if ($targetlanguage === 'th') {
    $guidedname = 'บทนำแนะนำ MooTyper';
    $guidedintro = '<p>บทนำนี้อธิบายโครงสร้างของหลักสูตร การให้คะแนน และลำดับการฝึก</p>';
    $guidedpages = [
        ['title' => 'ยินดีต้อนรับ', 'content' => '<p>เริ่มด้วยความแม่นยำก่อน แล้วจึงเพิ่มความเร็ว</p>'],
        ['title' => 'ลำดับหลักสูตร', 'content' => '<p>โหมดฝึก โหมดบทเรียน โหมดสอบ</p>'],
        ['title' => 'ผลลัพธ์', 'content' => '<p>ติดตามความแม่นยำ WPM และความสำเร็จ</p>'],
        ['title' => 'กิจวัตร', 'content' => '<p>ฝึกในช่วงสั้นๆ และทบทวนปุ่มที่ยาก</p>'],
        ['title' => 'ข้อผิดพลาด', 'content' => '<p>ใช้ข้อผิดพลาดเป็นข้อมูลป้อนกลับที่มุ่งเป้า</p>'],
        ['title' => 'การยศาสตร์', 'content' => '<p>รักษาข้อมือตรงและท่านั่งที่ถูกต้อง</p>'],
        ['title' => 'ความก้าวหน้า', 'content' => '<p>มองแนวโน้ม ไม่ใช่ผลลัพธ์เดี่ยว</p>'],
        ['title' => 'เริ่มต้น', 'content' => '<p>เริ่มในโหมดฝึก</p>'],
    ];

    $methodname = 'วิธีการฝึกพิมพ์';
    $methodintro = '<p>วิธีที่สม่ำเสมอทำให้การพยายามทุกครั้งเปรียบเทียบได้</p>';
    $methodpages = [
        ['title' => 'การเตรียมตัว', 'content' => '<p>ตรวจสอบรูปแบบแป้นพิมพ์และกำจัดสิ่งรบกวน</p>'],
        ['title' => 'การพิมพ์', 'content' => '<p>ใช้จังหวะที่สม่ำเสมอ</p>'],
        ['title' => 'การแก้ไข', 'content' => '<p>ชะลอความเร็วและแยกข้อผิดพลาดที่เกิดซ้ำ</p>'],
        ['title' => 'การทบทวน', 'content' => '<p>ตรวจสอบความแม่นยำและ WPM หลังแต่ละครั้ง</p>'],
        ['title' => 'กิจวัตร', 'content' => '<p>ความแม่นยำก่อน ความเร็วทีหลัง</p>'],
    ];

    $fingersname = 'ตำแหน่งนิ้วเริ่มต้นบนแป้นพิมพ์';
    $fingersintro = '<p>การวางนิ้วที่ถูกต้องเป็นพื้นฐานของการพิมพ์สัมผัสที่มั่นคง</p>';
    $fingerscontent = '<p>กลับมาที่แถวหลัก ใช้นิ้วที่กำหนด และรักษาท่าทางที่ผ่อนคลาย</p>';
    $draftsuffix = ' (ร่างคำแปล)';
    $draftintro = '<p><strong>ร่างคำแปล</strong> กรุณาตรวจสอบ</p>';
    $draftpagesuffix = ' (ร่าง)';
    $draftpagenote = '<p><em>ร่างสำหรับตรวจสอบคำแปล</em></p>';
} else if ($targetlanguage === 'te') {
    $guidedname = 'MooTyper పరిచయం';
    $guidedintro = '<p>ఈ పరిచయం కోర్సు నిర్మాణం, స్కోరింగ్ మరియు వ్యాయామాల క్రమాన్ని వివరిస్తుంది.</p>';
    $guidedpages = [
        ['title' => 'స్వాగతం', 'content' => '<p>ముందు కచ్చితత్వం, తర్వాత వేగం.</p>'],
        ['title' => 'కోర్సు క్రమం', 'content' => '<p>అభ్యాస మోడ్, తర్వాత పాఠం మోడ్, తర్వాత పరీక్ష మోడ్.</p>'],
        ['title' => 'ఫలితాలు', 'content' => '<p>కచ్చితత్వం, WPM మరియు పూర్తిని అనుసరించండి.</p>'],
        ['title' => 'దినచర్య', 'content' => '<p>చిన్న సెషన్లలో అభ్యసించండి మరియు కష్టమైన కీలను పునరావృతం చేయండి.</p>'],
        ['title' => 'తప్పులు', 'content' => '<p>తప్పులను లక్ష్యంగా చేసుకున్న అభిప్రాయంగా ఉపయోగించండి.</p>'],
        ['title' => 'ఎర్గోనామిక్స్', 'content' => '<p>మణికట్టులు నేరుగా ఉంచండి మరియు సరైన భంగిమ నిర్వహించండి.</p>'],
        ['title' => 'పురోగతి', 'content' => '<p>ధోరణులను చూడండి, ఒకే ఫలితాలను కాదు.</p>'],
        ['title' => 'ప్రారంభం', 'content' => '<p>అభ్యాస మోడ్‌లో ప్రారంభించండి.</p>'],
    ];

    $methodname = 'వ్యాయామ పద్ధతి';
    $methodintro = '<p>నిరంతర పద్ధతి అన్ని ప్రయత్నాలను పోల్చదగినవిగా చేస్తుంది.</p>';
    $methodpages = [
        ['title' => 'సన్నాహం', 'content' => '<p>కీబోర్డ్ లేఅవుట్ తనిఖీ చేసి పరధ్యాసలను తొలగించండి.</p>'],
        ['title' => 'టైపింగ్', 'content' => '<p>స్థిరమైన లయను ఉపయోగించండి.</p>'],
        ['title' => 'దిద్దుబాటు', 'content' => '<p>మందగించి పునరావృత తప్పులను వేరు చేయండి.</p>'],
        ['title' => 'సమీక్ష', 'content' => '<p>ప్రతి ప్రయత్నం తర్వాత కచ్చితత్వం మరియు WPM తనిఖీ చేయండి.</p>'],
        ['title' => 'దినచర్య', 'content' => '<p>ముందు కచ్చితత్వం, తర్వాత వేగం.</p>'],
    ];

    $fingersname = 'కీబోర్డ్‌పై వేళ్ళ ప్రారంభ స్థానం';
    $fingersintro = '<p>సరైన వేలు స్థానం స్థిరమైన స్పర్శ టైపింగ్‌కు ఆధారం.</p>';
    $fingerscontent = '<p>హోమ్ రోకు తిరిగి వెళ్ళండి, కేటాయించిన వేళ్ళను ఉపయోగించండి మరియు సడలించిన భంగిమ నిర్వహించండి.</p>';
    $draftsuffix = ' (అనువాద ముసాయిదా)';
    $draftintro = '<p><strong>అనువాద ముసాయిదా.</strong> దయచేసి సమీక్షించండి.</p>';
    $draftpagesuffix = ' (ముసాయిదా)';
    $draftpagenote = '<p><em>అనువాద సమీక్ష కోసం ముసాయిదా వెర్షన్.</em></p>';
} else if ($targetlanguage === 'ta') {
    $guidedname = 'MooTyper அறிமுகம்';
    $guidedintro = '<p>இந்த அறிமுகம் பாடத்திட்டத்தின் அமைப்பு, மதிப்பெண் மற்றும் பயிற்சி வரிசையை விளக்குகிறது.</p>';
    $guidedpages = [
        ['title' => 'வரவேற்கிறோம்', 'content' => '<p>முதலில் துல்லியம், பிறகு வேகம்.</p>'],
        ['title' => 'பாடம் வரிசை', 'content' => '<p>பயிற்சி, பாட, தேர்வு பயன்முறை வரிசை.</p>'],
        ['title' => 'முடிவுகள்', 'content' => '<p>துல்லியம், WPM மற்றும் முடிவடைவதை கண்காணிக்கவும்.</p>'],
        ['title' => 'வழக்கம்', 'content' => '<p>குறுகிய அமர்வுகளில் பயிற்சி செய்து கடினமான விசைகளை மீண்டும் செய்யுங்கள்.</p>'],
        ['title' => 'பிழைகள்', 'content' => '<p>தவறுகளை இலக்கு கருத்தாக பயன்படுத்துங்கள்.</p>'],
        ['title' => 'ஆளுமை அறிவியல்', 'content' => '<p>மணிக்கட்டுகளை நேராக வைத்து சரியான தோரணையை பராமரிக்கவும்.</p>'],
        ['title' => 'முன்னேற்றம்', 'content' => '<p>போக்குகளை பாருங்கள், தனிமையான முடிவுகளை அல்ல.</p>'],
        ['title' => 'தொடங்குங்கள்', 'content' => '<p>பயிற்சி பயன்முறையில் தொடங்குங்கள்.</p>'],
    ];

    $methodname = 'பயிற்சி முறை';
    $methodintro = '<p>நிலையான முறை அனைத்து முயற்சிகளையும் ஒப்பிடக்கூடியதாக ஆக்குகிறது.</p>';
    $methodpages = [
        ['title' => 'தயாரிப்பு', 'content' => '<p>விசைப்பலகை தளவமைப்பை சரிபார்த்து கவனச்சிதறல்களை நீக்கவும்.</p>'],
        ['title' => 'தட்டச்சு', 'content' => '<p>நிலையான தாளத்தை பயன்படுத்துங்கள்.</p>'],
        ['title' => 'திருத்தம்', 'content' => '<p>மெதுவாக்கி மீண்டும் வரும் பிழைகளை தனிமைப்படுத்துங்கள்.</p>'],
        ['title' => 'மதிப்பாய்வு', 'content' => '<p>ஒவ்வொரு முயற்சிக்கும் பிறகு துல்லியம் மற்றும் WPM சரிபார்க்கவும்.</p>'],
        ['title' => 'வழக்கம்', 'content' => '<p>முதலில் துல்லியம், பிறகு வேகம்.</p>'],
    ];

    $fingersname = 'விசைப்பலகையில் விரல்களின் ஆரம்ப நிலை';
    $fingersintro = '<p>சரியான விரல் நிலை நிலையான தொடுதல் தட்டச்சுக்கான அடிப்படை.</p>';
    $fingerscontent = '<p>முகப்பு வரிசைக்கு திரும்பி, நியமிக்கப்பட்ட விரல்களை பயன்படுத்துங்கள்.</p>';
    $draftsuffix = ' (மொழிபெயர்ப்பு வரைவு)';
    $draftintro = '<p><strong>மொழிபெயர்ப்பு வரைவு.</strong> தயவுசெய்து மதிப்பாய்வு செய்யுங்கள்.</p>';
    $draftpagesuffix = ' (வரைவு)';
    $draftpagenote = '<p><em>மொழிபெயர்ப்பு மதிப்பாய்வுக்கான வரைவு பதிப்பு.</em></p>';
} else if ($targetlanguage === 'bg') {
    $guidedname = 'Въведение в MooTyper';
    $guidedintro = '<p>Това въведение обяснява структурата, оценяването и начина на работа с упражненията.</p>';
    $guidedpages = [
        ['title' => 'Добре дошли', 'content' => '<p>Първо точност, след това скорост.</p>'],
        ['title' => 'Ред на курса', 'content' => '<p>Режим практика, после режим урок, после режим изпит.</p>'],
        ['title' => 'Резултати', 'content' => '<p>Проследявайте точност, WPM и завършване.</p>'],
        ['title' => 'Рутина', 'content' => '<p>Практикувайте на кратки сесии и повтаряйте трудните клавиши.</p>'],
        ['title' => 'Грешки', 'content' => '<p>Използвайте грешките като целенасочена обратна връзка.</p>'],
        ['title' => 'Ергономия', 'content' => '<p>Дръжте неутрални китки и стабилна поза.</p>'],
        ['title' => 'Напредък', 'content' => '<p>Търсете тенденции, не единични върхове.</p>'],
        ['title' => 'Старт', 'content' => '<p>Започнете в режим практика.</p>'],
    ];

    $methodname = 'Метод за изпълнение на упражненията';
    $methodintro = '<p>Използвайте последователен метод, за да са сравними опитите.</p>';
    $methodpages = [
        ['title' => 'Подготовка', 'content' => '<p>Проверете наредбата на клавиатурата и премахнете разсейванията.</p>'],
        ['title' => 'Писане', 'content' => '<p>Използвайте стабилен ритъм.</p>'],
        ['title' => 'Корекция', 'content' => '<p>Забавете и изолирайте повтарящите се грешки.</p>'],
        ['title' => 'Преглед', 'content' => '<p>Проверете точност и WPM след всеки опит.</p>'],
        ['title' => 'Рутина', 'content' => '<p>Първо точност, после скорост.</p>'],
    ];

    $fingersname = 'Начална позиция на пръстите върху клавиатурата';
    $fingersintro = '<p>Правилното поставяне на пръстите е основата за стабилно сляпо писане.</p>';
    $fingerscontent = '<p>Връщайте се на основния ред, използвайте определените пръсти и запазвайте отпусната поза.</p>';
    $draftsuffix = ' (превод чернова)';
    $draftintro = '<p><strong>Чернова на превода.</strong> Моля, прегледайте.</p>';
    $draftpagesuffix = ' (чернова)';
    $draftpagenote = '<p><em>Черновна версия за преглед на превода.</em></p>';
} else if ($targetlanguage === 'ko') {
    $guidedname = 'MooTyper 안내 소개';
    $guidedintro = '<p>이 안내는 코스 구조, 점수 계산 방식, 연습 순서를 설명합니다.</p>';
    $guidedpages = [
        ['title' => '환영합니다', 'content' => '<p>정확성을 먼저 높이고, 그 다음 속도를 높이세요.</p>'],
        ['title' => '코스 순서', 'content' => '<p>연습 모드, 수업 모드, 시험 모드 순으로 진행하세요.</p>'],
        ['title' => '결과', 'content' => '<p>정확도, WPM, 완료율을 확인하세요.</p>'],
        ['title' => '학습 습관', 'content' => '<p>짧은 세션으로 연습하고 어려운 키를 반복하세요.</p>'],
        ['title' => '오류', 'content' => '<p>실수를 목표 피드백으로 활용하세요.</p>'],
        ['title' => '자세', 'content' => '<p>손목을 곧게 유지하고 올바른 자세를 유지하세요.</p>'],
        ['title' => '진행 상황', 'content' => '<p>단일 결과보다 추세를 확인하세요.</p>'],
        ['title' => '시작', 'content' => '<p>연습 모드에서 시작하세요.</p>'],
    ];

    $methodname = '연습 방법';
    $methodintro = '<p>일관된 방법을 사용하면 모든 시도를 비교할 수 있습니다.</p>';
    $methodpages = [
        ['title' => '준비', 'content' => '<p>키보드 레이아웃을 확인하고 방해 요소를 제거하세요.</p>'],
        ['title' => '타이핑', 'content' => '<p>일정한 리듬을 유지하세요.</p>'],
        ['title' => '수정', 'content' => '<p>속도를 줄이고 반복되는 오류를 찾아 연습하세요.</p>'],
        ['title' => '검토', 'content' => '<p>각 시도 후 정확도와 WPM을 확인하세요.</p>'],
        ['title' => '습관', 'content' => '<p>정확성 먼저, 그 다음 속도입니다.</p>'],
    ];

    $fingersname = '키보드의 손가락 초기 위치';
    $fingersintro = '<p>올바른 손가락 위치는 안정적인 블라인드 타이핑의 기초입니다.</p>';
    $fingerscontent = '<p>홈 행으로 돌아오고, 지정된 손가락을 사용하며, 편안한 자세를 유지하세요.</p>';
    $draftsuffix = ' (번역 초안)';
    $draftintro = '<p><strong>번역 초안입니다.</strong> 검토해 주세요.</p>';
    $draftpagesuffix = ' (초안)';
    $draftpagenote = '<p><em>번역 검토용 초안입니다.</em></p>';
} else if ($targetlanguage === 'hi') {
    $guidedname = 'मूटाइपर निर्देशित परिचय';
    $guidedintro = '<p>यह परिचय पाठ्यक्रम की संरचना, अंकन और अभ्यास क्रम को समझाता है।</p>';
    $guidedpages = [
        ['title' => 'स्वागत', 'content' => '<p>पहले शुद्धता बढ़ाएँ, फिर गति बढ़ाएँ।</p>'],
        ['title' => 'पाठ्यक्रम क्रम', 'content' => '<p>पहले अभ्यास मोड, फिर पाठ मोड, फिर परीक्षा मोड।</p>'],
        ['title' => 'परिणाम', 'content' => '<p>शुद्धता, WPM और प्रगति को नियमित देखें।</p>'],
        ['title' => 'दिनचर्या', 'content' => '<p>छोटे सत्रों में अभ्यास करें और कठिन कुंजियों को दोहराएँ।</p>'],
        ['title' => 'त्रुटियाँ', 'content' => '<p>गलतियों को सुधार संकेत की तरह उपयोग करें।</p>'],
        ['title' => 'बैठक मुद्रा', 'content' => '<p>कलाई सीधी रखें और स्थिर बैठकर टाइप करें।</p>'],
        ['title' => 'प्रगति', 'content' => '<p>एक प्रयास नहीं, निरंतर सुधार को महत्व दें।</p>'],
        ['title' => 'आरंभ', 'content' => '<p>अभी अभ्यास मोड से शुरू करें।</p>'],
    ];

    $methodname = 'अभ्यास करने की विधि';
    $methodintro = '<p>एक समान विधि अपनाने से सभी प्रयास तुलनीय रहते हैं।</p>';
    $methodpages = [
        ['title' => 'तैयारी', 'content' => '<p>कुंजीपटल विन्यास जाँचें और ध्यान भंग करने वाली चीजें हटाएँ।</p>'],
        ['title' => 'टाइपिंग', 'content' => '<p>स्थिर लय के साथ टाइप करें।</p>'],
        ['title' => 'सुधार', 'content' => '<p>गति घटाकर बार-बार होने वाली त्रुटियों पर काम करें।</p>'],
        ['title' => 'समीक्षा', 'content' => '<p>हर प्रयास के बाद शुद्धता और WPM जाँचें।</p>'],
        ['title' => 'दिनचर्या', 'content' => '<p>पहले शुद्धता, फिर गति।</p>'],
    ];

    $fingersname = 'कुंजीपटल पर उंगलियों की प्रारंभिक स्थिति';
    $fingersintro = '<p>सही उंगली स्थिति स्थिर स्पर्श टाइपिंग की आधारशिला है।</p>';
    $fingerscontent = '<p>होम रो पर लौटें, निर्धारित उंगलियों का उपयोग करें और सहज मुद्रा रखें।</p>';
    $draftsuffix = ' (अनुवाद मसौदा)';
    $draftintro = '<p><strong>अनुवाद मसौदा।</strong> कृपया समीक्षा करें।</p>';
    $draftpagesuffix = ' (मसौदा)';
    $draftpagenote = '<p><em>समीक्षा हेतु मसौदा संस्करण।</em></p>';
} else {
    $guidedname = 'MooTyper guided introduction';
    $guidedintro = '<p>This guided intro explains structure, scoring, and how to work through the drills.</p>';
    $guidedpages = [
        ['title' => 'Welcome', 'content' => '<p>Start with accuracy, then increase speed.</p>'],
        ['title' => 'Course flow', 'content' => '<p>Practice mode, then Lesson mode, then Exam mode.</p>'],
        ['title' => 'Results', 'content' => '<p>Track precision, WPM, and completion.</p>'],
        ['title' => 'Routine', 'content' => '<p>Practice short focused sessions and repeat difficult keys.</p>'],
        ['title' => 'Errors', 'content' => '<p>Use mistakes as targeted feedback.</p>'],
        ['title' => 'Ergonomics', 'content' => '<p>Keep neutral wrists and steady posture.</p>'],
        ['title' => 'Progress', 'content' => '<p>Look for trends, not one-off peaks.</p>'],
        ['title' => 'Start', 'content' => '<p>Begin in Practice mode.</p>'],
    ];

    $methodname = 'Method of carrying out the exercises';
    $methodintro = '<p>Use a consistent method so attempts are comparable.</p>';
    $methodpages = [
        ['title' => 'Preparation', 'content' => '<p>Check keyboard layout and remove distractions.</p>'],
        ['title' => 'Typing', 'content' => '<p>Use a steady rhythm.</p>'],
        ['title' => 'Correction', 'content' => '<p>Slow down and isolate recurring errors.</p>'],
        ['title' => 'Review', 'content' => '<p>Check precision and WPM after each attempt.</p>'],
        ['title' => 'Routine', 'content' => '<p>Accuracy first, speed second.</p>'],
    ];

    $fingersname = 'Initial position of the fingers on the keyboard';
    $fingersintro = '<p>Correct finger placement is the base for stable touch typing.</p>';
    $fingerscontent = '<p>Return to home row, use assigned fingers, and keep relaxed posture.</p>';
    $draftsuffix = ' (translation draft)';
    $draftintro = '<p><strong>Translation draft.</strong> Review version.</p>';
    $draftpagesuffix = ' (draft)';
    $draftpagenote = '<p><em>Draft version for translation review.</em></p>';
}

foreach ($mapprimary as $key => $meta) {
    $cm = get_coursemodule_from_id($meta['mod'], $meta['cmid'], $targetcourseid, false, MUST_EXIST);
    if ($key === 'guided' && $meta['mod'] === 'icontent') {
        update_icontent_intro((int)$cm->instance, $guidedname, $guidedintro, $guidedpages, false);
    } else if ($key === 'method' && $meta['mod'] === 'icontent') {
        update_icontent_intro((int)$cm->instance, $methodname, $methodintro, $methodpages, false);
    } else if ($key === 'fingers' && $meta['mod'] === 'page') {
        update_page_intro((int)$cm->instance, $fingersname, $fingersintro, $fingerscontent, false);
    }
}

foreach ($mapdraft as $key => $meta) {
    $cm = get_coursemodule_from_id($meta['mod'], $meta['cmid'], $targetcourseid, false, MUST_EXIST);
    if ($key === 'guided' && $meta['mod'] === 'icontent') {
        update_icontent_intro(
            (int)$cm->instance,
            $guidedname,
            $guidedintro,
            $guidedpages,
            true,
            $draftsuffix,
            $draftintro,
            $draftpagesuffix,
            $draftpagenote
        );
    } else if ($key === 'method' && $meta['mod'] === 'icontent') {
        update_icontent_intro(
            (int)$cm->instance,
            $methodname,
            $methodintro,
            $methodpages,
            true,
            $draftsuffix,
            $draftintro,
            $draftpagesuffix,
            $draftpagenote
        );
    } else if ($key === 'fingers' && $meta['mod'] === 'page') {
        update_page_intro(
            (int)$cm->instance,
            $fingersname,
            $fingersintro,
            $fingerscontent,
            true,
            $draftsuffix,
            $draftintro,
            $draftpagenote
        );
    }
}

// Keep donor intent section names/sequencing.
if ($targetlanguage === 'hi') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang HI}अभ्यास मोड{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang HI}पाठ मोड{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang HI}परीक्षा मोड{mlang}',
    ];
} else if ($targetlanguage === 'ru') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang RU}Режим практики{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang RU}Режим урока{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang RU}Режим экзамена{mlang}',
    ];
} else if ($targetlanguage === 'uk') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang UK}Режим практики{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang UK}Режим уроку{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang UK}Режим іспиту{mlang}',
    ];
} else if ($targetlanguage === 'sr') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang SR}Режим вежбања{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang SR}Режим лекције{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang SR}Режим испита{mlang}',
    ];
} else if ($targetlanguage === 'gr') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang EL}Λειτουργία εξάσκησης{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang EL}Λειτουργία μαθήματος{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang EL}Λειτουργία εξέτασης{mlang}',
    ];
} else if ($targetlanguage === 'am') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang HY}Պրակտիկայի ռեժիմ{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang HY}Դասի ռեժիմ{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang HY}Քննության ռեժիմ{mlang}',
    ];
} else if ($targetlanguage === 'th') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang TH}โหมดฝึก{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang TH}โหมดบทเรียน{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang TH}โหมดสอบ{mlang}',
    ];
} else if ($targetlanguage === 'te') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang TE}అభ్యాస మోడ్{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang TE}పాఠం మోడ్{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang TE}పరీక్ష మోడ్{mlang}',
    ];
} else if ($targetlanguage === 'ta') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang TA}பயிற்சி பயன்முறை{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang TA}பாட பயன்முறை{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang TA}தேர்வு பயன்முறை{mlang}',
    ];
} else if ($targetlanguage === 'bg') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang BG}Режим практика{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang BG}Режим урок{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang BG}Режим изпит{mlang}',
    ];
} else if ($targetlanguage === 'fr') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang FR}Mode pratique{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang FR}Mode leçon{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang FR}Mode examen{mlang}',
    ];
} else if ($targetlanguage === 'ko') {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang KO}연습 모드{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang KO}수업 모드{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang KO}시험 모드{mlang}',
    ];
} else {
    $sectionnames = [
        1 => '{mlang EN}Practice Mode{mlang} {mlang EN_US}Practice Mode{mlang} {mlang NL}Oefenmodus{mlang}',
        2 => '{mlang EN}Lesson Mode{mlang} {mlang EN_US}Lesson Mode{mlang} {mlang NL}Lesmodus{mlang}',
        3 => '{mlang EN}Exam Mode{mlang} {mlang EN_US}Exam Mode{mlang} {mlang NL}Examenmodus{mlang}',
    ];
}
foreach ($sectionnames as $secnum => $secname) {
    $sec = $DB->get_record('course_sections', ['course' => $targetcourseid, 'section' => $secnum], '*', MUST_EXIST);
    $sec->name = $secname;
    $DB->update_record('course_sections', $sec);
}

$sectionbyid = $DB->get_records('course_sections', ['course' => $targetcourseid], '', 'id,section');
$sectionnumbyid = [];
foreach ($sectionbyid as $s) {
    $sectionnumbyid[(int)$s->id] = (int)$s->section;
}

// Apply layout and lesson-mode thresholds.
foreach ($clonedmootypercmids as $cmid) {
    $cm = $DB->get_record('course_modules', ['id' => $cmid], 'id,section,instance', MUST_EXIST);
    $secnum = $sectionnumbyid[(int)$cm->section] ?? -1;

    $mt = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);
    $mt->layout = $layoutid;

    if ($secnum === 2) {
        $mt->timelimit = 10;
        $mt->requiredgoal = 90;
        $mt->requiredwpm = 5;
        $mt->isexam = 0;
    }

    $DB->update_record('mootyper', $mt);
}

// Practice section ordered by lesson id.
$sec1 = $DB->get_record('course_sections', ['course' => $targetcourseid, 'section' => 1], '*', MUST_EXIST);
$sec1cmids = array_values(array_filter(array_map('intval', explode(',', trim((string)$sec1->sequence)))));
$practice = [];
foreach ($sec1cmids as $cmid) {
    $cm = $DB->get_record('course_modules', ['id' => $cmid], 'id,module,instance', IGNORE_MISSING);
    if (!$cm || (int)$cm->module !== $modulemootyperid) {
        continue;
    }
    $lesson = (int)$DB->get_field('mootyper', 'lesson', ['id' => $cm->instance], MUST_EXIST);
    $practice[] = ['cmid' => $cmid, 'lesson' => $lesson];
}
usort($practice, function($a, $b) {
    return $a['lesson'] <=> $b['lesson'];
});
$sec1->sequence = implode(',', array_map(function($r) {
    return $r['cmid'];
}, $practice));
$DB->update_record('course_sections', $sec1);

// Section 0 ordered forum -> 3 primary -> 3 drafts.
$sec0 = $DB->get_record('course_sections', ['course' => $targetcourseid, 'section' => 0], '*', MUST_EXIST);
$newseq0 = [];
if ($keepforumcmid > 0) {
    $newseq0[] = $keepforumcmid;
}
foreach (['guided', 'method', 'fingers'] as $k) {
    if (!empty($mapprimary[$k]['cmid'])) {
        $newseq0[] = (int)$mapprimary[$k]['cmid'];
    }
}
foreach (['guided', 'method', 'fingers'] as $k) {
    if (!empty($mapdraft[$k]['cmid'])) {
        $newseq0[] = (int)$mapdraft[$k]['cmid'];
    }
}
$sec0->sequence = implode(',', $newseq0);
$DB->update_record('course_sections', $sec0);

// Gradebook categories.
$rootcat = grade_category::fetch_course_category($targetcourseid);
$catlesson = ensure_grade_category($targetcourseid, (int)$rootcat->id, 'Lesson');
$catpractice = ensure_grade_category($targetcourseid, (int)$rootcat->id, 'Practice');
$catexam = ensure_grade_category($targetcourseid, (int)$rootcat->id, 'Exam');
$catmisc = ensure_grade_category($targetcourseid, (int)$rootcat->id, 'Misc');

$gicriteria = ['courseid' => $targetcourseid, 'itemtype' => 'mod', 'itemmodule' => 'mootyper'];
$gis = $DB->get_records('grade_items', $gicriteria, '', 'id,iteminstance');
foreach ($gis as $gi) {
    $cmcrit = ['module' => $modulemootyperid, 'instance' => $gi->iteminstance, 'course' => $targetcourseid];
    $cm = $DB->get_record('course_modules', $cmcrit, 'id,section', IGNORE_MISSING);
    if (!$cm) {
        continue;
    }
    $secnum = $sectionnumbyid[(int)$cm->section] ?? -1;
    $catid = $catmisc;
    if ($secnum === 1) {
        $catid = $catpractice;
    } else if ($secnum === 2) {
        $catid = $catlesson;
    } else if ($secnum === 3) {
        $catid = $catexam;
    }
    $g = grade_item::fetch(['id' => $gi->id]);
    $g->set_parent($catid);
}

// Ensure lesson files for currently referenced lessons.
$lessonsused = $DB->get_records_sql(
    "SELECT DISTINCT m.lesson
       FROM {mootyper} m
       JOIN {course_modules} cm ON cm.instance = m.id
      WHERE cm.course = :cid
        AND cm.module = :modid",
    ['cid' => $targetcourseid, 'modid' => $modulemootyperid]
);
foreach ($lessonsused as $lr) {
    $lesson = $DB->get_record('mootyper_lessons', ['id' => (int)$lr->lesson], 'id,lessonname', IGNORE_MISSING);
    if (!$lesson) {
        continue;
    }
    $filepath = $CFG->dirroot . '/mod/mootyper/lessons/' . $lesson->lessonname . '.txt';
    if (!file_exists($filepath)) {
        $exs = $DB->get_records('mootyper_exercises', ['lesson' => $lesson->id], 'snumber ASC,id ASC', 'texttotype');
        $chunks = [];
        foreach ($exs as $ex) {
            $chunks[] = trim((string)$ex->texttotype);
        }
        file_put_contents($filepath, implode("\n\n", $chunks) . "\n");
    }
}

// Language remap (idempotent): map EN_* source to language lesson bank and wire activities.
$sqlacts = "SELECT CONCAT(cm.id,'-',m.id) AS rid, cs.section, cm.id cmid, m.id mid, m.name, m.lesson, m.exercise, ml.lessonname
              FROM {course_modules} cm
              JOIN {course_sections} cs ON cs.id = cm.section
              JOIN {mootyper} m ON m.id = cm.instance
              JOIN {mootyper_lessons} ml ON ml.id = m.lesson
             WHERE cm.course = :cid
               AND cm.module = :modid
               AND cs.section IN (1,2,3)
             ORDER BY cs.section, cm.id";
$acts = $DB->get_records_sql($sqlacts, ['cid' => $targetcourseid, 'modid' => $modulemootyperid]);

$sourcelessons = [];
foreach ($acts as $a) {
    $sourcelessons[(int)$a->lesson] = $a->lessonname;
}

$lessonmap = [];
$exercisemap = [];

foreach ($sourcelessons as $srcid => $srcname) {
    $srclesson = $DB->get_record('mootyper_lessons', ['id' => $srcid], '*', MUST_EXIST);
    $targetname = target_lesson_name($srclesson->lessonname, $targetlanguage);

    $targetlesson = $DB->get_record('mootyper_lessons', ['lessonname' => $targetname], '*', IGNORE_MISSING);
    $populate = false;
    if (!$targetlesson) {
        $newlesson = new stdClass();
        $newlesson->lessonname = $targetname;
        $newlesson->authorid = $srclesson->authorid;
        $newlesson->visible = $srclesson->visible;
        $newlesson->editable = $srclesson->editable;
        $targetlessonid = (int)$DB->insert_record('mootyper_lessons', $newlesson);
        $targetlesson = $DB->get_record('mootyper_lessons', ['id' => $targetlessonid], '*', MUST_EXIST);
        $populate = true;
    } else {
        $targetcount = $DB->count_records('mootyper_exercises', ['lesson' => $targetlesson->id]);
        if ($targetcount === 0) {
            $populate = true;
        }
    }

    if ($populate) {
        $DB->delete_records('mootyper_exercises', ['lesson' => $targetlesson->id]);
        $srcexs = $DB->get_records('mootyper_exercises', ['lesson' => $srcid], 'snumber ASC,id ASC', '*');
        foreach ($srcexs as $ex) {
            $nex = new stdClass();
            if ($targetlanguage === 'ko') {
                // Korean lesson text is pre-built in KO_*.txt files (native jamo).
                // The file names use jamo characters, so use glob to find by prefix.
                $kotext = korean_text_from_file($targetname, (int)$ex->snumber);
                $nex->texttotype = $kotext !== '' ? $kotext : map_text_by_language((string)$ex->texttotype, $targetlanguage);
            } else {
                $nex->texttotype = map_text_by_language((string)$ex->texttotype, $targetlanguage);
            }
            $nex->exercisename = target_lesson_name((string)$ex->exercisename, $targetlanguage);
            $nex->lesson = (int)$targetlesson->id;
            $nex->snumber = (int)$ex->snumber;
            $DB->insert_record('mootyper_exercises', $nex);
        }
    }

    $lessonmap[$srcid] = (int)$targetlesson->id;

    $srcset = $DB->get_records('mootyper_exercises', ['lesson' => $srcid], 'snumber ASC,id ASC', 'id,snumber');
    $tgtset = $DB->get_records('mootyper_exercises', ['lesson' => $targetlesson->id], 'snumber ASC,id ASC', 'id,snumber');

    $tgtbys = [];
    foreach ($tgtset as $te) {
        $tgtbys[(int)$te->snumber] = (int)$te->id;
    }
    foreach ($srcset as $se) {
        $sn = (int)$se->snumber;
        if (isset($tgtbys[$sn])) {
            $exercisemap[(int)$se->id] = $tgtbys[$sn];
        }
    }

    $filepath = $CFG->dirroot . '/mod/mootyper/lessons/' . $targetlesson->lessonname . '.txt';
    $targetex = $DB->get_records('mootyper_exercises', ['lesson' => $targetlesson->id], 'snumber ASC,id ASC', 'texttotype');
    $chunks = [];
    foreach ($targetex as $te) {
        $chunks[] = trim((string)$te->texttotype);
    }
    file_put_contents($filepath, implode("\n\n", $chunks) . "\n");
}

$updated = 0;
foreach ($acts as $a) {
    $mt = $DB->get_record('mootyper', ['id' => $a->mid], '*', MUST_EXIST);

    $oldlesson = (int)$mt->lesson;
    if (!isset($lessonmap[$oldlesson])) {
        continue;
    }
    $mt->lesson = $lessonmap[$oldlesson];

    if ((int)$mt->exercise > 0 && isset($exercisemap[(int)$mt->exercise])) {
        $mt->exercise = $exercisemap[(int)$mt->exercise];
    }

    $lessonname = (string)$DB->get_field('mootyper_lessons', 'lessonname', ['id' => $mt->lesson], MUST_EXIST);
    // Final naming cue: "KB Layout name - Lesson name".
    $mt->name = $resolvedlayoutname . ' - ' . $lessonname;

    $DB->update_record('mootyper', $mt);
    $updated++;
}

rebuild_course_cache($targetcourseid, true);
purge_all_caches();

// Verification summary.
$allmapped = true;
$examok = true;
$nameok = true;
$rs = $DB->get_recordset_sql($sqlacts, ['cid' => $targetcourseid, 'modid' => $modulemootyperid]);
if ($targetlanguage === 'hi') {
    $expectedlessonprefix = 'HI_';
} else if ($targetlanguage === 'ru') {
    $expectedlessonprefix = 'RU_';
} else if ($targetlanguage === 'uk') {
    $expectedlessonprefix = 'UK_';
} else if ($targetlanguage === 'sr') {
    $expectedlessonprefix = 'SR_';
} else if ($targetlanguage === 'gr') {
    $expectedlessonprefix = 'GR_';
} else if ($targetlanguage === 'am') {
    $expectedlessonprefix = 'AM_';
} else if ($targetlanguage === 'th') {
    $expectedlessonprefix = 'TH_';
} else if ($targetlanguage === 'te') {
    $expectedlessonprefix = 'TE_';
} else if ($targetlanguage === 'ta') {
    $expectedlessonprefix = 'TA_';
} else if ($targetlanguage === 'bg') {
    $expectedlessonprefix = 'BUL_';
} else if ($targetlanguage === 'ko') {
    $expectedlessonprefix = 'KO_';
} else {
    $expectedlessonprefix = 'BG_';
}
foreach ($rs as $r) {
    if (strpos((string)$r->lessonname, $expectedlessonprefix) !== 0) {
        $allmapped = false;
    }
    $expectedprefix = $resolvedlayoutname . ' - ';
    if (strpos((string)$r->name, $expectedprefix) !== 0) {
        $nameok = false;
    }
    if ((int)$r->section === 3) {
        $ex = $DB->get_record('mootyper_exercises', ['id' => (int)$r->exercise], 'id,lesson', IGNORE_MISSING);
        if (!$ex || (int)$ex->lesson !== (int)$r->lesson) {
            $examok = false;
        }
    }
}
$rs->close();

$secrows = [];
for ($sn = 0; $sn <= 3; $sn++) {
    $sec = $DB->get_record('course_sections', ['course' => $targetcourseid, 'section' => $sn], 'section,name,sequence', MUST_EXIST);
    $mods = trim((string)$sec->sequence) === '' ? [] : array_values(array_filter(explode(',', $sec->sequence)));
    $secrows[] = "SEC{$sn} name=[{$sec->name}] mods=" . count($mods);
}

$sqlpage = "SELECT COUNT(1) FROM {page} p"
    . " JOIN {course_modules} cm ON cm.instance=p.id"
    . " JOIN {modules} md ON md.id=cm.module AND md.name='page'"
    . " WHERE cm.course=? AND LENGTH(TRIM(p.content))>0";
$contentpage = $DB->count_records_sql($sqlpage, [$targetcourseid]);
$sqlpagetotal = "SELECT COUNT(1) FROM {page} p"
    . " JOIN {course_modules} cm ON cm.instance=p.id"
    . " JOIN {modules} md ON md.id=cm.module AND md.name='page'"
    . " WHERE cm.course=?";
$contentpagetotal = $DB->count_records_sql($sqlpagetotal, [$targetcourseid]);
$sqlicne = "SELECT COUNT(1) FROM {icontent_pages} ip"
    . " JOIN {icontent} i ON i.id=ip.icontentid"
    . " JOIN {course_modules} cm ON cm.instance=i.id"
    . " JOIN {modules} md ON md.id=cm.module AND md.name='icontent'"
    . " WHERE cm.course=? AND LENGTH(TRIM(ip.pageicontent))>0";
$icnonempty = $DB->count_records_sql($sqlicne, [$targetcourseid]);
$sqlict = "SELECT COUNT(1) FROM {icontent_pages} ip"
    . " JOIN {icontent} i ON i.id=ip.icontentid"
    . " JOIN {course_modules} cm ON cm.instance=i.id"
    . " JOIN {modules} md ON md.id=cm.module AND md.name='icontent'"
    . " WHERE cm.course=?";
$ictotal = $DB->count_records_sql($sqlict, [$targetcourseid]);

$sqllessonok = "SELECT COUNT(1) FROM {mootyper} m"
    . " JOIN {course_modules} cm ON cm.instance=m.id"
    . " JOIN {course_sections} cs ON cs.id=cm.section"
    . " WHERE cm.course=? AND cm.module=? AND cs.section=2"
    . " AND m.timelimit=10 AND m.requiredgoal=90 AND m.requiredwpm=5 AND m.isexam=0";
$lessonok = $DB->count_records_sql($sqllessonok, [$targetcourseid, $modulemootyperid]);
$sqllessontotal = "SELECT COUNT(1) FROM {mootyper} m"
    . " JOIN {course_modules} cm ON cm.instance=m.id"
    . " JOIN {course_sections} cs ON cs.id=cm.section"
    . " WHERE cm.course=? AND cm.module=? AND cs.section=2";
$lessontotal = $DB->count_records_sql($sqllessontotal, [$targetcourseid, $modulemootyperid]);

$populateok = "POPULATE_OK moodleroot={$moodleroot} donor={$donorcourseid}";
$populateok .= " target={$targetcourseid} lang={$targetlanguage} layout={$resolvedlayoutname}";
echo $populateok . "\n";
foreach ($secrows as $row) {
    echo $row . "\n";
}
echo "CONTENT page_nonempty={$contentpage}/{$contentpagetotal} icontent_pages_nonempty={$icnonempty}/{$ictotal}\n";
echo "LESSON_SETTINGS ok={$lessonok}/{$lessontotal}\n";
$bgremap = "LESSON_REMAP all_mapped=" . ($allmapped ? 1 : 0) . " exam_links_ok=" . ($examok ? 1 : 0);
$bgremap .= " name_format_ok=" . ($nameok ? 1 : 0) . " lessons_mapped=" . count($lessonmap);
$bgremap .= " activities_updated={$updated}";
echo $bgremap . "\n";

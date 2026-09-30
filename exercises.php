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
 * This file handles mootyper exercises.
 *
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

use mod_mootyper\event\course_exercises_viewed;
use mod_mootyper\event\invalid_access_attempt;
use mod_mootyper\local\lessons;

// Changed to this newer format 03/01/2019.
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

global $DB, $OUTPUT, $PAGE, $USER;

// 20200224 Switched $id to Course_module ID vice course ID.
$id = optional_param('id', 0, PARAM_INT); // Course module ID.
// Changed cmid to course id.
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
            'file' => 'exercises.php',
        ],
    ];
    $event = invalid_access_attempt::create($params);
    $event->trigger();
    redirect('../../course/view.php?id=' . $course->id, get_string('invalidaccessexp', 'mootyper'));
}

$mootyper = $DB->get_record('mootyper', ['id' => $cm->instance], '*', MUST_EXIST);
$lessonpo = optional_param('lesson', 0, PARAM_INT);

// Trigger module exercise_viewed event.
$params = [
    'objectid' => $course->id,
    'context' => $context,
    'other' => $lessonpo,
];
$event = course_exercises_viewed::create($params);
$event->trigger();

// Print the page header.
$PAGE->set_url('/mod/mootyper/exercises.php', ['id' => $id]);
$PAGE->set_title(get_string('etitle', 'mootyper'));
$PAGE->set_heading(get_string('eheading', 'mootyper'));
$PAGE->set_pagelayout('standard');

// Other things you may want to set - remove if not needed.
$PAGE->set_cacheable(false);

// Output starts here.
echo $OUTPUT->header();

// 20200625 Changed from using site default color to current Mootyper
// keyboard background color.
$color3 = mootyper_clean_color((string)$mootyper->keybdbgc);

echo '<div align="center" style="font-size:1em;
     font-weight:bold;background: ' . $color3 . ';
     border:2px solid black;
     -webkit-border-radius:16px;
     -moz-border-radius:16px;border-radius:16px;">';

$lessons = lessons::get_mootyperlessons($USER->id, $id);

if ($lessonpo == 0 && count($lessons) > 0) {
    $lessonpo = $lessons[0]['id'];
}

// Create and show a drop down selector for the lesson name to show.
echo '<form method="post">';
echo '<br>' . get_string('excategory', 'mootyper') . ': <select onchange="this.form.submit()" name="lesson">';

$selectedlessonindex = 0;

for ($ij = 0; $ij < count($lessons); $ij++) {
    if ($lessons[$ij]['id'] == $lessonpo) {
        echo '<option selected="true" value="' . (int)$lessons[$ij]['id'] . '">' . s($lessons[$ij]['lessonname']) . '</option>';
        $selectedlessonindex = $ij;
    } else {
        echo '<option value="' . (int)$lessons[$ij]['id'] . '">' . s($lessons[$ij]['lessonname']) . '</option>';
    }
}

echo '</select>';

// Preload not editable by me message for the current user.
$jlink = get_string('noteditablebyme', 'mootyper');
if (lessons::is_editable_by_me($USER->id, $id, $lessonpo)) {
    echo '<br>';
    echo '</form><br>';
    // Build a link with course id and lsn options to use when exporting the current Lesson.
    $selectedlessonname = $lessons[$selectedlessonindex]['lessonname'];
    $exportconfirm = get_string('exportconfirm', 'mootyper') . $selectedlessonname;
    $jlink = html_writer::link(
        new moodle_url('/mod/mootyper/lsnexport.php', [
            'id' => $id,
            'lsn' => $lessons[$selectedlessonindex]['id'],
        ]),
        html_writer::empty_tag('img', [
            'src' => 'pix/download_all.svg',
            'alt' => get_string('export', 'mootyper'),
        ]) . ' ' . s($selectedlessonname),
        [
            'onclick' => 'return confirm('
                . json_encode($exportconfirm, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ');',
        ]
    );

    // Build a link to let teachers add a new exercise to the Lesson currently being viewed.
    $jlnk3 = new moodle_url('/mod/mootyper/eins.php', ['id' => $id, 'lesson' => $lessonpo]);

    // 20200628 Following variable is temporary for development.
    $vis = $DB->get_record("mootyper_lessons", ['id' => $lessonpo]);
    // 20220125 Added words instead of numbers, to the button.
    $visible = get_string('vaccess' . $vis->visible, 'mootyper');
    $editable = get_string('eaccess' . $vis->editable, 'mootyper');

    // 20200614 Added a button for, Add a new exercise to the Lesson currently being viewed.
    // 20220125 Modified the info on the buttons, words instead of numbers.
    $addexerciseconfirm = get_string('eaddnewex', 'mootyper') . $lessonpo;
    echo html_writer::link(
        $jlnk3,
        get_string('eaddnewex', 'mootyper') . $lessonpo
        . ', ' . get_string('authorid', 'mootyper') . ': ' . $vis->authorid
        . ', ' . get_string('visibility', 'mootyper') . ': ' . $visible
        . ', ' . get_string('editable', 'mootyper') . ': ' . $editable,
        [
            'onclick' => 'return confirm('
                . json_encode($addexerciseconfirm, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ');',
            'class' => 'btn btn-secondary',
            'style' => 'border-radius: 8px',
        ]
    );
} else {
    echo '</form><br>';
}

// 20240120 Moved style1 and style2 to styles.css file.
// Print header row for Lesson table currently being viewed.
echo '<table><tr><td class="style1">' . get_string('ename', 'mootyper') . '</td>
                 <td class="style1">' . s($lessons[$selectedlessonindex]['lessonname']) . '</td>
                 <td class="style1">' . $jlink . '</td></tr>';

// Print table row for each of the exercises in the lesson currently being viewed.
$exercises = $DB->get_records(
    "mootyper_exercises",
    ['lesson' => $lessonpo],
    '',
    'id, exercisename, texttotype, lesson, snumber, dictationdata, dictationdataformat'
);
// 20230110 PostgreSQL gets sloppy with the order, but this seems to fix it.
sort($exercises);
foreach ($exercises as $ex) {
    // 20210326 Shorten displayed exercisename as well as text to type.
    $strtocut = $ex->texttotype;
    if (core_text::strlen($strtocut) > 65) {
        $strtocut = core_text::substr($strtocut, 0, 65) . '...';
    }
    $strtocut = str_replace('\n', '<br>', s($strtocut));
    $exnametocut = $ex->exercisename;
    if (core_text::strlen($exnametocut) > 20) {
        $exnametocut = core_text::substr($exnametocut, 0, 20) . '...';
    }
    $exnametocut = str_replace('\n', '<br>', s($exnametocut));
    // If user can edit, build a delete link to the current exercise.
    $deleteconfirm = get_string('deleteexconfirm', 'mootyper')
        . $lessons[$selectedlessonindex]['lessonname'];
    $jlink1 = html_writer::link(
        new moodle_url('/mod/mootyper/lsnexrem.php', [
            'id' => $id,
            're' => $ex->id,
            'lesson' => $lessonpo,
            'sesskey' => sesskey(),
        ]),
        html_writer::empty_tag('img', [
            'src' => 'pix/delete.png',
            'alt' => get_string('delete', 'mootyper'),
        ]),
        [
            'onclick' => 'return confirm('
                . json_encode($deleteconfirm, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ');',
        ]
    );

    // If user can edit, create an edit link to the current exercise.
    // Use activity ID so we can exit back to the MooTyper activity we came from.
    $jlink2 = html_writer::link(
        new moodle_url('/mod/mootyper/eedit.php', [
            'id' => $id,
            'ex' => $ex->id,
            'lesson' => $mootyper->lesson,
        ]),
        html_writer::empty_tag('img', [
            'src' => 'pix/edit.png',
            'alt' => get_string('eeditlabel', 'mootyper'),
        ])
    );

    // 20210326 Shorten displayed exercisename as well as text to type.
    echo '<tr><td class="style2">' . $exnametocut . '</td><td class="style2">'
        . $strtocut . '</td>';

    // Column 3: Edit/Delete tools and audio player if available.
    echo '<td class="style1">';

    // Display audio/media player if this exercise has audio/media.
    if (!empty($ex->dictationdata)) {
        $dictationformat = empty($ex->dictationdataformat) ? FORMAT_HTML : (int)$ex->dictationdataformat;
        $dictationcontent = file_rewrite_pluginfile_urls(
            $ex->dictationdata,
            'pluginfile.php',
            $context->id,
            'mod_mootyper',
            'dictationdata',
            $ex->id
        );
        echo '<div style="margin: 0 0 8px 0; padding: 0; background: transparent; border: 0; box-shadow: none;">';
        echo format_text($dictationcontent, $dictationformat, ['context' => $context, 'filter' => true, 'para' => false]);
        echo '</div>';
    }

    // If the user can edit or delete this lesson and its exercises, then add edit and delete tools.
    if (lessons::is_editable_by_me($USER->id, $id, $lessonpo)) {
        echo $jlink2 . ' | ' . $jlink1;
    }

    echo '</td>';
    echo '</tr>';
}
echo '</table>';

// Some recorder workflows (notably PoodLL video) may need a short delay before
// the final media file is available. Retry failed video loads automatically.
echo '<script>
(function() {
    var maxRetries = 6;
    var retryDelayMs = 1200;

    function addRetry(video) {
        if (!video || video.dataset.mootyperRetryBound === "1") {
            return;
        }
        video.dataset.mootyperRetryBound = "1";
        var retries = 0;

        function refreshSources() {
            if (retries >= maxRetries) {
                return;
            }
            retries++;

            var sources = video.querySelectorAll("source[src]");
            if (sources.length) {
                sources.forEach(function(source) {
                    var src = source.getAttribute("src") || "";
                    var base = src.split("?")[0];
                    source.setAttribute("src", base + "?mtretry=" + Date.now());
                });
            } else {
                var videosrc = video.getAttribute("src") || "";
                var videobase = videosrc.split("?")[0];
                if (videobase) {
                    video.setAttribute("src", videobase + "?mtretry=" + Date.now());
                }
            }

            video.load();
        }

        video.addEventListener("error", function() {
            setTimeout(refreshSources, retryDelayMs);
        });

        video.addEventListener("loadeddata", function() {
            retries = maxRetries;
        });
    }

    document.querySelectorAll("video").forEach(addRetry);
})();
</script>';

$url = new moodle_url('/mod/mootyper/view.php', ['id' => $id]);
// 20241227 Modified so we have the lesson ID for use in two ways in lsnexrem.php.
$deleteurl = new moodle_url('/mod/mootyper/lsnexrem.php', [
    'id' => $id,
    'lesson' => $lessonpo,
    'rl' => $lessonpo,
    'sesskey' => sesskey(),
]);

$exporturl = new moodle_url('/mod/mootyper/lsnexport.php', [
    'id' => $id,
    'lsn' => $lessons[$selectedlessonindex]['id'],
]);

// 20200414 Added a, Return, button. 20200428 added round corners.
echo '<br>' . html_writer::link($url, get_string('returnto', 'mootyper', $mootyper->name), [
    'class' => 'btn btn-primary',
    'style' => 'border-radius: 8px',
]);

// 20200614 Added an, Add new lesson with exercise, button.
$jlnk2 = new moodle_url('/mod/mootyper/eins.php', ['id' => $id, 'course' => $course->id]);
$addlessonconfirm = json_encode(get_string('eaddnew', 'mootyper'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
echo ' ' . html_writer::link($jlnk2, get_string('eaddnew', 'mootyper'), [
    'onclick' => 'return confirm(' . $addlessonconfirm . ');',
    'class' => 'btn btn-secondary',
    'style' => 'border-radius: 8px',
]);

// 20200613 Added an, Export, lesson button.
$lessonname = $lessons[$selectedlessonindex]['lessonname'];
$exportconfirm = json_encode(
    get_string('exportconfirm', 'mootyper') . $lessonname,
    JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);
echo ' ' . html_writer::link($exporturl, get_string('export', 'mootyper') . ' - ' . s($lessonname), [
    'onclick' => 'return confirm(' . $exportconfirm . ');',
    'class' => 'btn btn-info',
    'style' => 'border-radius: 8px',
]);

if (lessons::is_editable_by_me($USER->id, $id, $lessonpo)) {
    // 20200613 Added a, Delete all from, this lesson button.
    $selectedlessonname = $lessons[$selectedlessonindex]['lessonname'];
    $deleteconfirm = json_encode(
        get_string('deletelsnconfirm', 'mootyper') . $selectedlessonname,
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    );
    echo ' ' . html_writer::link(
        $deleteurl,
        get_string('deleteall', 'mootyper') . ' - ' . s($selectedlessonname) . ' - ' . (int)$lessonpo,
        [
            'onclick' => 'return confirm(' . $deleteconfirm . ');',
            'class' => 'btn btn-danger',
            'style' => 'border-radius: 8px',
        ]
    ) . '</form>';
} else {
    echo '</form>';
}

echo '</div>';

echo $OUTPUT->footer();

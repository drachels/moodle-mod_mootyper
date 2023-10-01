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
 * Moodle renderer used to display special elements of the mootyper module.
 *
 * @package    mod_mootyper
 * @copyright  2016 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

defined('MOODLE_INTERNAL') || die(); // @codingStandardsIgnoreLine

/**
 * A custom renderer class that extends the plugin_renderer_base and is used by the mootyper module.
 *
 * @package mod_mootyper
 * @copyright  2016 AL Rachels (drachels@drachels.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_mootyper_renderer extends plugin_renderer_base {
    /**
     * Returns the header for the mootyper module.
     *
     * @param mootyper $mootyper a mootyper object.
     * @param int $cm id of the mootyper course module that needs to be displayed.
     * @param string $extrapagetitle String to append to the page title.
     * @return string.
     */
    public function header($mootyper, $cm, $extrapagetitle = null) {
        global $CFG, $USER;

        $activityname = format_string($mootyper->name, true);

        if (empty($extrapagetitle)) {
            $title = $this->page->course->shortname.": ".$activityname;
        } else {
            $title = $this->page->course->shortname.": ".$activityname.": ".$extrapagetitle;
        }

        $context = context_module::instance($cm->id);

        // Header setup.
        $this->page->set_title($title);
        $this->page->set_heading($this->page->course->fullname);
        $output = $this->output->header();

        if ($CFG->branch < 400) {
            if (has_capability('mod/mootyper:setup', $context)) {
                $output .= $this->output->heading_with_help($activityname, 'overview', 'mootyper');
            } else {
                $output .= $this->output->heading($activityname);
            }
        }

        if ($CFG->branch > 310) {
            // 20220214 New code from pull request. However, needs to take in to account to show
            // completion only when all the exercises are done, or if it is an exam, after just
            // the one exercise of the exam.
            $cminfo = cm_info::create($cm);
            $completiondetails = \core_completion\cm_completion_details::get_instance($cminfo, $USER->id);
            $activitydates = \core\activity_dates::get_dates_for_module($cminfo, $USER->id);

            if ($CFG->branch < 400) {
                $debug['In renderer.php and checking $CFG->branch which should be less than 400: '] = $CFG->branch;

                $output .= $this->output->activity_information($cminfo, $completiondetails, $activitydates);
            }
        }
        return $output;
    }

    /**
     * Returns the footer
     * @return string
     */
    public function footer() {
        return $this->output->footer();
    }

    /**
     * Returns HTML for a mootyper inaccessible message
     *
     * @param string $message
     * @return <type>
     */
    public function mootyper_inaccessible($message) {
        global $CFG;
        $output = $this->output->box_start('generalbox boxaligncenter');
        $output .= $this->output->box_start('center');
        $output .= (get_string('notavailable', 'mootyper'));
        $output .= $message;
        $output .= $this->output->box('<a href="'.$CFG->wwwroot.'/course/view.php?id='
                . $this->page->course->id .'">'
                . get_string('returnto', 'mootyper', format_string($this->page->course->fullname, true))
                .'</a>', 'mootyperbutton standardbutton');
        $output .= $this->output->box_end();
        $output .= $this->output->box_end();
        return $output;
    }

    /**
     * Returns HTML to prompt the user to log in
     * @param mootyper $mootyper
     * @param bool $failedattempt
     * @return string
     */
    public function login_prompt($mootyper, $failedattempt = false) {
        global $CFG;
        $output = $this->output->box_start('password-form');
        $output .= $this->output->box_start('generalbox boxaligncenter');
        $output .= '<form id="password" method="post" action="'.$CFG->wwwroot.'/mod/mootyper/view.php" autocomplete="off">';
        $output .= '<fieldset class="invisiblefieldset center">';
        $output .= '<input type="hidden" name="id" value="'. $this->page->cm->id .'" />';
        $output .= '<input type="hidden" name="sesskey" value="'.sesskey().'" />';
        if ($failedattempt) {
            $output .= $this->output->notification(get_string('loginfail', 'mootyper'));
        }
        $output .= get_string('passwordprotectedlesson', 'mootyper', format_string($mootyper->name)).'<br /><br />';
        $output .= get_string('enterpassword', 'mootyper')." <input type=\"password\" name=\"userpassword\" /><br /><br />";
        $output .= "<div class='lessonbutton standardbutton submitbutton'><input type='submit' value='"
                .get_string('continue', 'mootyper')."' /></div>";
        $output .= " <div class='lessonbutton standardbutton submitbutton'><input type='submit' name='backtocourse' value='"
                .get_string('cancel', 'mootyper')."' /></div>";
        $output .= '</fieldset></form>';
        $output .= $this->output->box_end();
        $output .= $this->output->box_end();
        return $output;
    }
}

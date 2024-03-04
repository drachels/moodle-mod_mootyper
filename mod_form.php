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
 * The main mootyper configuration form.
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

use mod_mootyper\local\keyboards;
use mod_mootyper\local\lessons;
use core_grades\component_gradeitems;

/**
 * Module instance settings form.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class mod_mootyper_mod_form extends moodleform_mod {

    /**
     * @var $course Protected modifier.
     */
    protected $course = null;

    /**
     * Define the MooTyper mod_form.
     */
    public function definition() {
        global $CFG, $COURSE, $DB;

        $mform = $this->_form;

        // 20200630 Added to fix link to control access to the management link.
        $id = optional_param('update', 0, PARAM_INT); // Course module ID.

        $mootyperconfig = get_config('mod_mootyper');

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), ['size' => '64']);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'mootypername', 'mootyper');
        if ($CFG->branch > 28) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // MooTyper activity setup, Availability settings.
        $mform->addElement('header', 'availabilityhdr', get_string('availability'));

        $mform->addElement('date_time_selector',
            'timeopen',
            get_string('mootyperopentime', 'mootyper'),
            ['optional' => true, 'step' => 1]
            );
        $mform->addElement('date_time_selector',
            'timeclose',
            get_string('mootyperclosetime', 'mootyper'),
            ['optional' => true, 'step' => 1]
            );
        // MooTyper activity password setup.
        $mform->addElement('selectyesno', 'usepassword', get_string('usepassword', 'mootyper'));
        $mform->addHelpButton('usepassword', 'usepassword', 'mootyper');
        $mform->setDefault('usepassword', $mootyperconfig->password);
        $mform->setAdvanced('usepassword', $mootyperconfig->password_adv);

        $mform->addElement('passwordunmask', 'password', get_string('password', 'mootyper'));
        $mform->setDefault('password', '');
        $mform->setAdvanced('password', $mootyperconfig->password_adv);
        $mform->setType('password', PARAM_RAW);
        $mform->disabledIf('password', 'usepassword', 'eq', 0);
        $mform->disabledIf('passwordunmask', 'usepassword', 'eq', 0);

        // MooTyper activity setup, Options settings.
        $mform->addElement('header', 'optionhdr', get_string('options', 'mootyper'));

        // 20191223 Added a dropdown slector for timelimit.
        $tlimit = [];
        for ($i = 0; $i <= 10; $i++) {
            $tlimit[] = $i;
        }
        $mform->addElement('select', 'timelimit', get_string('timelimit', 'mootyper'), $tlimit);
        $mform->addHelpButton('timelimit', 'timelimit', 'mootyper');
        $mform->setDefault('timelimit', $mootyperconfig->defaulttimelimit);

        // Added a dropdown slector for Required precision. 11/25/17.
        $precs = [];
        for ($i = 0; $i <= 100; $i++) {
            $precs[] = $i;
        }
        $mform->addElement('select', 'requiredgoal', get_string('requiredgoal', 'mootyper'), $precs);
        $mform->addHelpButton('requiredgoal', 'requiredgoal', 'mootyper');
        $mform->setDefault('requiredgoal', $mootyperconfig->defaultprecision);

        // 20191214 Added a dropdown slector for WPM rate.
        $wpm = [];
        for ($i = 0; $i <= 100; $i++) {
            $wpm[] = $i;
        }
        $mform->addElement('select', 'requiredwpm', get_string('requiredwpm', 'mootyper'), $wpm);
        $mform->addHelpButton('requiredwpm', 'requiredwpm', 'mootyper');
        $mform->setDefault('requiredwpm', $mootyperconfig->defaultwpm);

        // Add a dropdown slector for text alignment.
        $aligns = [get_string('defaulttextalign_left', 'mod_mootyper'),
            get_string('defaulttextalign_center', 'mod_mootyper'),
            get_string('defaulttextalign_right', 'mod_mootyper'),
            ];
        $mform->addElement('select', 'textalign', get_string('defaulttextalign', 'mootyper'), $aligns);
        $mform->addHelpButton('textalign', 'defaulttextalign', 'mootyper');
        $mform->setDefault('textalign', $mootyperconfig->defaulttextalign);

        // Continuous typing setup.
        $mform->addElement('selectyesno', 'continuoustype', get_string('continuoustype', 'mootyper'));
        $mform->addHelpButton('continuoustype', 'continuoustype', 'mootyper');
        $mform->setDefault('continuoustype', $mootyperconfig->continuoustype);
        $mform->setAdvanced('continuoustype', $mootyperconfig->continuoustype_adv);

        // Count mistyped spaces setup.
        $mform->addElement('selectyesno', 'countmistypedspaces', get_string('countmistypedspaces', 'mootyper'));
        $mform->addHelpButton('countmistypedspaces', 'countmistypedspaces', 'mootyper');
        $mform->setDefault('countmistypedspaces', $mootyperconfig->countmistypedspaces);
        $mform->setAdvanced('countmistypedspaces', $mootyperconfig->countmistypedspaces_adv);

        // Count mistakes setup.
        $mform->addElement('selectyesno', 'countmistakes', get_string('countmistakes', 'mootyper'));
        $mform->addHelpButton('countmistakes', 'countmistakes', 'mootyper');
        $mform->setDefault('countmistakes', $mootyperconfig->countmistakes);
        $mform->setAdvanced('countmistakes', $mootyperconfig->countmistakes_adv);

        // Show keyboard setup.
        $mform->addElement('selectyesno', 'showkeyboard', get_string('showkeyboard', 'mootyper'));
        $mform->addHelpButton('showkeyboard', 'showkeyboard', 'mootyper');
        $mform->setDefault('showkeyboard', $mootyperconfig->showkeyboard);
        $mform->setAdvanced('showkeyboard', $mootyperconfig->showkeyboard_adv);

        // 20171122 Add a dropdown slector for keyboard layouts.
        // Use function in localib.php to get layouts.
        $layouts = keyboards::get_keyboard_layouts_db();
        $mform->addElement('select', 'layout', get_string('layout', 'mootyper'), $layouts);
        $mform->addHelpButton('layout', 'layout', 'mootyper');
        if (get_config('mod_mootyper', 'overwrite_defaultlayout') &&
                isset($mootyperconfig->defaultlayout_filenamewithoutfiletype) &&
                keyboards::is_layout_installed("$mootyperconfig->defaultlayout_filenamewithoutfiletype")) {
            // We should overwrite and the layout is installed!
            $mform->setDefault('layout',
                keyboards::get_id_of_layout_by_layoutname($mootyperconfig->defaultlayout_filenamewithoutfiletype
                ));
        } else {
            // We should not overwrite or the laylout is not installed so we have to use the "normal" default.
            $mform->setDefault('layout', $mootyperconfig->defaultlayout);
        }
        // Add setting for statistics bar background color.
        $attributes = 'size = "20"';
        $mform->setType('statsbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'statsbgc', get_string('statsbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('statsbgc', 'statsbgc', 'mootyper');
        $mform->setDefault('statsbgc', $mootyperconfig->statscolor);

        // Add setting for keytoptextc color setting. 12/15/19.
        $mform->setType('keytoptextc', PARAM_NOTAGS);
        $mform->addElement('text', 'keytoptextc', get_string('keytoptextc', 'mootyper'), $attributes);
        $mform->addHelpButton('keytoptextc', 'keytoptextc', 'mootyper');
        $mform->setDefault('keytoptextc', $mootyperconfig->normalkeytoptextc);

        // Add setting for keytop background color.
        $mform->setType('keytopbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'keytopbgc', get_string('keytopbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('keytopbgc', 'keytopbgc', 'mootyper');
        $mform->setDefault('keytopbgc', $mootyperconfig->normalkeytops);

        // Add setting for keyboard background color.
        $mform->setType('keybdbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'keybdbgc', get_string('keybdbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('keybdbgc', 'keybdbgc', 'mootyper');
        $mform->setDefault('keybdbgc', $mootyperconfig->keyboardbgc);

        // Add setting for cursor color.
        $mform->setType('cursorcolor', PARAM_NOTAGS);
        $mform->addElement('text', 'cursorcolor', get_string('cursorcolor', 'mootyper'), $attributes);
        $mform->addHelpButton('cursorcolor', 'cursorcolor', 'mootyper');
        $mform->setDefault('cursorcolor', $mootyperconfig->cursorcolor);

        // Add setting for texttotype background color.
        $mform->setType('textbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'textbgc', get_string('textbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('textbgc', 'textbgc', 'mootyper');
        $mform->setDefault('textbgc', $mootyperconfig->textbgc);

        // Add setting for mistyped text background color.
        $mform->setType('texterrorcolor', PARAM_NOTAGS);
        $mform->addElement('text', 'texterrorcolor', get_string('texterrorcolor', 'mootyper'), $attributes);
        $mform->addHelpButton('texterrorcolor', 'texterrorcolor', 'mootyper');
        $mform->setDefault('texterrorcolor', $mootyperconfig->texterrorcolor);

        // MooTyper activity, link to Lesson/Categories and exercises.
        // 20200630 When a cmid is available, show the link.
        if ($id) {
            $mform->addElement('header', 'mootyper', get_string('pluginadministration', 'mootyper'));
            $jlnk3 = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$id;
            $mform->addElement('html', '<a id="jlnk3" href="'.$jlnk3.'">'.get_string('emanage', 'mootyper').'</a>');
        }

        // 20200907 Add mootyper grading options for the whole activity.
        if ($CFG->branch > 37) {
            $this->add_mootyper_grade_settings($mform, 'mootyper');
        }

        // The rest of the common activity settings.
        $this->standard_grading_coursemodule_elements();
        $this->standard_coursemodule_elements();
        $this->apply_admin_defaults();
        $this->add_action_buttons();
    }

    /**
     * Add the whole mootyper grade settings to the mform.
     *
     * @param   \mform $mform
     * @param   string $itemname
     */
    private function add_mootyper_grade_settings($mform, string $itemname) {
        global $COURSE;

        $component = "mod_{$this->_modname}";
        $defaultgradingvalue = 0;

        $itemnumber = component_gradeitems::get_itemnumber_from_itemname($component, $itemname);

        $gradefieldname = component_gradeitems::get_field_name_for_itemnumber($component, $itemnumber, 'grade');
        $gradecatfieldname = component_gradeitems::get_field_name_for_itemnumber($component, $itemnumber, 'gradecat');
        $gradepassfieldname = component_gradeitems::get_field_name_for_itemnumber($component, $itemnumber, 'gradepass');
        $sendstudentnotificationsfieldname = component_gradeitems::get_field_name_for_itemnumber($component, $itemnumber,
                'sendstudentnotifications');

        // The advancedgradingmethod is different in that it is suffixed with an area name... which is not the
        // itemnumber.
        $methodfieldname = "advancedgradingmethod_{$itemname}";

        $headername = "{$gradefieldname}_header";
        $mform->addElement('header', $headername, get_string("grade_{$itemname}_header", $component));

        $isupdate = !empty($this->_cm);
        $gradeoptions = [
            'isupdate' => $isupdate,
            'currentgrade' => false,
            'hasgrades' => false,
            'canrescale' => false,
            'useratings' => false,
        ];

        if ($isupdate) {
            $gradeitem = grade_item::fetch([
                'itemtype' => 'mod',
                'itemmodule' => $this->_cm->modname,
                'iteminstance' => $this->_cm->instance,
                'itemnumber' => $itemnumber,
                'courseid' => $COURSE->id,
            ]);
            if ($gradeitem) {
                $gradeoptions['currentgrade'] = $gradeitem->grademax;
                $gradeoptions['currentgradetype'] = $gradeitem->gradetype;
                $gradeoptions['currentscaleid'] = $gradeitem->scaleid;
                $gradeoptions['hasgrades'] = $gradeitem->has_grades();
            }
        }
        $mform->addElement(
            'modgrade',
            $gradefieldname,
            get_string("{$gradefieldname}_title", $component),
            $gradeoptions
        );
        $mform->addHelpButton($gradefieldname, 'modgrade', 'grades');
        $mform->setDefault($gradefieldname, $defaultgradingvalue);

        if (!empty($this->current->_advancedgradingdata['methods']) && !empty($this->current->_advancedgradingdata['areas'])) {
            $areadata = $this->current->_advancedgradingdata['areas'][$itemname];
            $mform->addElement(
                'select',
                $methodfieldname,
                get_string('gradingmethod', 'core_grading'),
                $this->current->_advancedgradingdata['methods']
            );
            $mform->addHelpButton($methodfieldname, 'gradingmethod', 'core_grading');
            $mform->hideIf($methodfieldname, "{$gradefieldname}[modgrade_type]", 'eq', 'none');
        }

        // Grade category.
        $mform->addElement(
            'select',
            $gradecatfieldname,
            get_string('gradecategoryonmodform', 'grades'),
            grade_get_categories_menu($COURSE->id, $this->_outcomesused)
        );
        $mform->addHelpButton($gradecatfieldname, 'gradecategoryonmodform', 'grades');
        $mform->hideIf($gradecatfieldname, "{$gradefieldname}[modgrade_type]", 'eq', 'none');

        // Grade to pass.
        $mform->addElement('text', $gradepassfieldname, get_string('gradepass', 'grades'));
        $mform->addHelpButton($gradepassfieldname, 'gradepass', 'grades');
        $mform->setDefault($gradepassfieldname, '');
        $mform->setType($gradepassfieldname, PARAM_RAW);
        $mform->hideIf($gradepassfieldname, "{$gradefieldname}[modgrade_type]", 'eq', 'none');

    }


    /**
     * Handle definition after data for grade settings.
     *
     * @param array $data
     * @param array $files
     * @param array $errors
     */
    private function validation_mootyper_grade(array $data, array $files, array $errors) {
        global $COURSE;

        $mform = $this->_form;

        $component = "mod_mootyper";
        $itemname = 'mootyper';
        $itemnumber = component_gradeitems::get_itemnumber_from_itemname($component, $itemname);
        $gradefieldname = component_gradeitems::get_field_name_for_itemnumber($component, $itemnumber, 'grade');
        $gradepassfieldname = component_gradeitems::get_field_name_for_itemnumber($component, $itemnumber, 'grade');

        $gradeitem = grade_item::fetch([
            'itemtype' => 'mod',
            'itemmodule' => $data['modulename'],
            'iteminstance' => $data['instance'],
            'itemnumber' => $itemnumber,
            'courseid' => $COURSE->id,
        ]);

        if ($mform->elementExists('cmidnumber') && $this->_cm) {
            if (!grade_verify_idnumber($data['cmidnumber'], $COURSE->id, $gradeitem, $this->_cm)) {
                $errors['cmidnumber'] = get_string('idnumbertaken');
            }
        }

        // Check that the grade pass is a valid number.
        $gradepassvalid = false;
        if (isset($data[$gradepassfieldname])) {
            if (unformat_float($data[$gradepassfieldname], true) === false) {
                $errors[$gradepassfieldname] = get_string('err_numeric', 'form');
            } else {
                $gradepassvalid = true;
            }
        }

        // Grade to pass: ensure that the grade to pass is valid for points and scales.
        // If we are working with a scale, convert into a positive number for validation.
        if ($gradepassvalid && isset($data[$gradepassfieldname]) && (!empty($data[$gradefieldname]))) {
            $grade = $data[$gradefieldname];
            if (unformat_float($data[$gradepassfieldname]) > $grade) {
                $errors[$gradepassfieldname] = get_string('gradepassgreaterthangrade', 'grades', $grade);
            }
        }
    }


    /**
     * Enforce validation rules here.
     *
     * @param array $data Post data to validate
     * @param array $files
     * @return array
     **/
    public function validation($data, $files) {

        $errors = parent::validation($data, $files);

        // Check open and close times are consistent.
        if ($data['timeopen'] != 0 && $data['timeclose'] != 0 &&
                $data['timeclose'] < $data['timeopen']) {
            $errors['timeclose'] = get_string('closebeforeopen', 'mootyper');
        }

        if (!empty($data['usepassword']) && empty($data['password'])) {
            $errors['password'] = get_string('emptypassword', 'mootyper');
        }

        return $errors;
    }

    /**
     * Add elements for setting the custom completion rules on the form.
     *
     * @return array Array of string IDs of added items, empty array if none.
     */
    public function add_completion_rules() {
        // Added for branch check.
        global $CFG;
        $mform = $this->_form;

        // 20230926 Changed code for Moodle 4.3 to add a suffix.
        if ($CFG->branch < 403) {
            $suffix = '';
        } else {
            $suffix = $this->get_suffix();
        }
        $completionexercisegroup = 'completionexercisegroup'.$suffix;
        $completionexerciseenabled = 'completionexerciseenabled'.$suffix;
        $completionexercise = 'completionexercise'.$suffix;

        $group = [];
        $group[] = $mform->createElement('checkbox',
            $completionexerciseenabled,
            '',
            get_string('completionexercise', 'mootyper')
            );
        $group[] = $mform->createElement('text',
            $completionexercise,
            '',
            ['size' => 3]
        );
        $mform->setType($completionexercise, PARAM_INT);
        $mform->addGroup($group,
            $completionexercisegroup,
            get_string('completionexercisegroup', 'mootyper'),
            [' '],
            false
        );
        $mform->disabledIf($completionexercise, $completionexerciseenabled, 'notchecked');
        $mform->addHelpButton($completionexercisegroup, 'completionexercisegroup', 'mootyper');

        // 20230926 Added new Require Exercise and changed code for Moodle 4.3.
        $completionlessongroup = 'completionlessongroup'.$suffix;
        $completionlessonenabled = 'completionlessonenabled'.$suffix;
        $completionlesson = 'completionlesson'.$suffix;
        // Line 467 will add text in front of the completionlesson string. Line 480 adds text after the completionlesson string.
        $group = [];
        $group[] = $mform->createElement('checkbox',
            $completionlessonenabled,
            '',
            get_string('completionlesson', 'mootyper'),
            '',
        );

        $group[] = $mform->createElement('selectyesno',
            $completionlesson,
            '',
            get_string('completionlesson', 'mootyper'),
        );



/*
        $group[] = $mform->createElement('text',
            $completionlesson,
            '',
            ['size' => 3]
        );
*/
        $mform->setType($completionlesson, PARAM_INT);
        $mform->addGroup($group,
            $completionlessongroup,
            get_string('completionlessongroup', 'mootyper'),
            [' '],
            false
        );
        $mform->disabledIf($completionlesson, $completionlessonenabled, 'notchecked');
        $mform->addHelpButton($completionlessongroup, 'completionlessongroup', 'mootyper');

        // 20230926 Added new Require Precision and changed code for Moodle 4.3.
        $completionprecisiongroup = 'completionprecisiongroup'.$suffix;
        $completionprecisionenabled = 'completionprecisionenabled'.$suffix;
        $completionprecision = 'completionprecision'.$suffix;

        $group = [];
        $group[] = $mform->createElement('checkbox',
            $completionprecisionenabled,
            '',
            get_string('completionprecision', 'mootyper')
        );
        $group[] = $mform->createElement('text', $completionprecision, '', ['size' => 3]);
        $mform->setType($completionprecision, PARAM_INT);
        $mform->addGroup($group,
            $completionprecisiongroup,
            get_string('completionprecisiongroup', 'mootyper'),
            [' '],
            false
        );
        $mform->disabledIf($completionprecision, $completionprecisionenabled, 'notchecked');
        $mform->addHelpButton($completionprecisiongroup, 'completionprecisiongroup', 'mootyper');

        // 20230926 Added new Require WPM and changed code for Moodle 4.3.
        $completionwpmgroup = 'completionwpmgroup'.$suffix;
        $completionwpmenabled = 'completionwpmenabled'.$suffix;
        $completionwpm = 'completionwpm'.$suffix;

        $group = [];
        $group[] = $mform->createElement('checkbox',
            'completionwpmenabled',
            '',
            get_string('completionwpm', 'mootyper')
        );
        $group[] = $mform->createElement('text', 'completionwpm', '', ['size' => 3]);
        $mform->setType('completionwpm', PARAM_INT);
        $mform->addGroup($group,
            'completionwpmgroup',
            get_string('completionwpmgroup', 'mootyper'),
            [' '],
            false
        );
        $mform->disabledIf($completionwpm, $completionwpmenabled, 'notchecked');
        $mform->addHelpButton($completionwpmgroup, 'completionwpmgroup', 'mootyper');

        // 20230926 Added new MooTyper Grade and changed code for Moodle 4.3.
        $completionmootypergradegroup = 'completionmootypergradegroup'.$suffix;
        $completionmootypergradeenabled = 'completionmootypergradeenabled'.$suffix;
        $completionmootypergrade = 'completionmootypergrade'.$suffix;

        $group = [];
        $group[] = $mform->createElement('checkbox',
            'completionmootypergradeenabled',
            '',
            get_string('completionmootypergrade', 'mootyper')
        );
        $group[] = $mform->createElement('text', 'completionmootypergrade', '', ['size' => 3]);
        $mform->setType('completionmootypergrade', PARAM_INT);
        $mform->addGroup($group,
            'completionmootypergradegroup',
            get_string('completionmootypergradegroup', 'mootyper'),
            [' '],
            false
        );
        $mform->disabledIf($completionmootypergrade, $completionmootypergradeenabled, 'notchecked');
        $mform->addHelpButton($completionmootypergradegroup, 'completionmootypergradegroup', 'mootyper');

        return [$completionexercisegroup,
            $completionlessongroup,
            $completionprecisiongroup,
            $completionwpmgroup,
            $completionmootypergradegroup,
        ];
    }

    /**
     * Called during validation to see whether some module-specific completion rules are selected.
     *
     * @param array $data Input data not yet validated.
     * @return bool True if one or more rules is enabled, false if none are.
     */
    public function completion_rule_enabled($data) {
        global $CFG;

        // 20230926 Changed code for Moodle 4.3.
        if ($CFG->branch < 403) {
            $suffix = '';
        } else {
            $suffix = $this->get_suffix();
        }
        $completionexerciseenabled = 'completionexerciseenabled'.$suffix;
        $completionexercise = 'completionexercise'.$suffix;

        $completionlessonenabled = 'completionlessonenabled'.$suffix;
        $completionlesson = 'completionlesson'.$suffix;

        $completionprecisiontenabled = 'completionprecisiontenabled'.$suffix;
        $completionprecision = 'completionprecision'.$suffix;

        $completionwpmenabled = 'completionwpmenabled'.$suffix;
        $completionwpm = 'completionwpm'.$suffix;

        $completionmootypergradeenabled = 'completionmootypergradeenabled'.$suffix;
        $completionmootypergrade = 'completionmootypergrade'.$suffix;

        return (!empty($data[$completionexerciseenabled]) && $data[$completionexercise] != 0)
            || (!empty($data[$completionlessonenabled]) && $data[$completionlesson] != 0)
            || (!empty($data[$completionprecisiontenabled]) && $data[$completionprecision] != 0)
            || (!empty($data[$completionwpmenabled]) && $data[$completionwpm] != 0)
            || (!empty($data[$completionmootypergradeenabled]) && $data[$completionmootypergrade] != 0);
    }

    /**
     * Return submitted data if properly submitted or returns NULL if validation fails or
     * if there is no submitted data.
     *
     * Do not override this method, override data_postprocessing() instead.
     *
     * @return object submitted data; NULL if not valid or not submitted or cancelled
     */
    public function get_data() {
        $data = parent::get_data();
        if (!$data) {
            return false;
        }

        // Turn off completion settings if the checkboxes aren't ticked.
        if (isset($data->completionexercise)) {
            $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completionexerciseenabled) || !$autocompletion) {
                $data->completionexercise = 0;
            }
        } else if (isset($data->completionlesson)) {
            $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completionlessonenabled) || !$autocompletion) {
                $data->completionlesson = 0;
            }
        } else if (isset($data->completionprecision)) {
            $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completionprecisionenabled) || !$autocompletion) {
                $data->completionprecision = 0;
            }
        } else if (isset($data->completionwpm)) {
            $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completionwpmenabled) || !$autocompletion) {
                $data->completionwpm = 0;
            }
        } else if (isset($data->completionmootypergrade)) {
            $autocompletion = !empty($data->completion) && $data->completion == COMPLETION_TRACKING_AUTOMATIC;
            if (empty($data->completionmootypergradeenabled) || !$autocompletion) {
                $data->completionmootypergrade = 0;
            }
        }

        return $data;
    }

    /**
     * Any data processing needed before the form is displayed
     * (needed to set up draft areas for editor and filemanager elements)
     * @param array $defaultvalues
     */
    public function data_preprocessing(&$defaultvalues) {
        parent::data_preprocessing($defaultvalues);

        // Set up the completion checkboxes which aren't part of standard data.
        // We also make the default value (if you turn on the checkbox) for those
        // numbers to be 1, this will not apply unless checkbox is ticked.
        $defaultvalues['completionexerciseenabled'] =
            !empty($defaultvalues['completionexercise']) ? 1 : 0;
        if (empty($defaultvalues['completionexercise'])) {
            $defaultvalues['completionexercise'] = 0;
        }

        $defaultvalues['completionlessonenabled'] =
            !empty($defaultvalues['completionlesson']) ? 1 : 0;
        if (empty($defaultvalues['completionlesson'])) {
            $defaultvalues['completionlesson'] = 0;
        }

        $defaultvalues['completionprecisionenabled'] =
            !empty($defaultvalues['completionprecision']) ? 1 : 0;
        if (empty($defaultvalues['completionprecision'])) {
            $defaultvalues['completionprecision'] = 0;
        }

        $defaultvalues['completionwpmenabled'] =
            !empty($defaultvalues['completionwpm']) ? 1 : 0;
        if (empty($defaultvalues['completionwpm'])) {
            $defaultvalues['completionwpm'] = 0;
        }

        $defaultvalues['completionmootypergradeenabled'] =
            !empty($defaultvalues['completionmootypergrade']) ? 1 : 0;
        if (empty($defaultvalues['completionmootypergrade'])) {
            $defaultvalues['completionmootypergrade'] = 0;
        }
    }
}

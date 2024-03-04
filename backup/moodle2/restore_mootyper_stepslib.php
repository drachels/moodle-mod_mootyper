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
 * Define all the restore steps that will be used by the restore_mootyper_activity_task.
 *
 * @package mod_mootyper
 * @copyright 2016 onwards AL Rachels (drachels@drachels.com).
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die(); // @codingStandardsIgnoreLine

/**
 * Define the complete mootyper structure for restore, with file and id annotations.
 *
 * Structure step to restore one mootyper activity.
 *
 * @package mod_mootyper
 * @copyright 2016 onwards AL Rachels (drachels@drachels.com).
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class restore_mootyper_activity_structure_step extends restore_activity_structure_step {

    /**
     * @var stdClass data Can only be inserted after the MooTyper activity
     * data is stored and we know the exercise, lesson, and layout id. Therefore,
     * process_mootyper sets this, and then inform_new_usage_id saves it.
     */
    protected $currentattemptdata;
    /**
     * @var stdClass data Can only be inserted after the MooTyper activity
     * data is stored and we know the exercise, lesson, and layout id. Therefore,
     * process_mootyper sets this, and then inform_new_usage_id saves it.
     */
    protected $newmootyperdata;
    /**
     * @var stdClass data Can only be inserted after the MooTyper activity
     * data is stored and we know the exercise, lesson, and layout id. Therefore,
     * process_mootyper sets this, and then inform_new_usage_id saves it.
     */
    protected $newexercisedata;

    /**
     * Define the structure of the restore workflow.
     *
     * @return restore_path_element $structure
     */
    protected function define_structure() {
        $paths = [];
        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');
        // Define each element separated.
        $paths[] = new restore_path_element('mootyper', '/activity/mootyper');
        $paths[] = new restore_path_element('mootyper_attempt', '/activity/mootyper/attempts/attempt');
        $paths[] = new restore_path_element('mootyper_check', '/activity/mootyper/attempts/attempt/checks/check');
        $paths[] = new restore_path_element('mootyper_layout', '/activity/mootyper/layouts/layout');
        $paths[] = new restore_path_element('mootyper_lesson', '/activity/mootyper/lessons/lesson');
        $paths[] = new restore_path_element('mootyper_exercise', '/activity/mootyper/exercises/exercise');
        if ($userinfo) {
            $paths[] = new restore_path_element('mootyper_grade', '/activity/mootyper/grades/grade');
        }
        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process an assign restore.
     *
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        $oldexercise = $data->exercise;
        $oldlesson = $data->lesson;
        $oldlayout = $data->layout;
        $data->course = $this->get_courseid();
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        // Insert the mootyper record.
        $newitemid = $DB->insert_record('mootyper', $data);
        $this->currentattemptdata = $data;
        $this->apply_activity_instance($newitemid);
        $this->newmootyperdata = $DB->get_record('mootyper', ['id' => $newitemid]);
    }

    /**
     * Process a submission restore.
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper_attempt($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        $data->mootyperid = $this->get_new_parentid('mootyper');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $newitemid = $DB->insert_record('mootyper_attempts', $data);
        $this->set_mapping('mootyper_attempt', $oldid, $newitemid);
    }
    /**
     * Process a check restore.
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper_check($data) {
        global $DB;
        $data = (object)$data;
        $oldattemptid = $data->attemptid;
        $data->course = $this->get_courseid();
        $data->mootyperid = $this->get_new_parentid('mootyper');
        $data->attemptid = $this->get_mappingid('mootyper_attempt', $data->attemptid);
        $newitemid = $DB->insert_record('mootyper_checks', $data);
    }

    /**
     * Process a layout restore - TODO: Check on remving this as I am pretty sure it is not needed.
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper_layout($data) {
        global $DB;
        $data = (object)$data;
        $mootyper = $this->currentattemptdata;
        $newmootyper = $this->newmootyperdata;
        $oldid = $data->id;
        $layout = $DB->get_record('mootyper_layouts', ['name' => $data->name]);
        if (!$layout) {
            $data->mootyper = $this->get_new_parentid('mootyper');
            // Do this only if the layout does not already exist!
            $newitemid = $DB->insert_record('mootyper_layouts', $data);
            $this->set_mapping('mootyper_layout', $oldid, $newitemid);
        } else {
            $newmootyper->layout = $layout->id;
            $this->newmootyperdata = $newmootyper;
        }
    }
    /**
     * Process a lesson restore
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper_lesson($data) {
        global $DB;
        $data = (object)$data;
        $newmootyper = $this->newmootyperdata;
        $newexercisedata = $this->newexercisedata;
        $lesson = $DB->get_record('mootyper_lessons', ['lessonname' => $data->lessonname]);
        if (!$lesson) {
            $oldid = $data->id;
            $data->mootyper = $this->get_new_parentid('mootyper');
            $newitemid = $DB->insert_record('mootyper_lessons', $data);
            $this->set_mapping('mootyper_lesson', $oldid, $newitemid);
            $newmootyper->lesson = $newitemid;
        } else {
            $newmootyper->lesson = $lesson->id;
        }
    }

    /**
     * Process an exercise restore.
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper_exercise($data) {
        global $DB;
        //$debug['CP666661-enter-process_mootyper_exercise($data): '] = 'Entering function process_mootyper_exercise($data).';

        $data = (object)$data;
        $oldid = $data->id;

        // Get the current copy of the mootyper activity.
        $newmootyper = $this->newmootyperdata;
        $this->newexercisedata = $data;
        $newexercisedata = $this->newexercisedata;

        // If there are any exercises for this lesson, go ahead and process them.
        // Should only find a maximum of one exercise.
        if ($exercises = $DB->get_records('mootyper_exercises', ['lesson' => $newmootyper->lesson, 'snumber' => $data->snumber])) {
            // Make sure we are using the right exercise.
            foreach ($exercises as $exercise) {
                if (($exercise->texttotype = $data->texttotype)
                    && ($exercise->exercisename = $data->exercisename)
                    && ($exercise->lesson = $newmootyper->lesson)
                    && ($exercise->snumber = $data->snumber)) {

                    $newexercisedata->id = $exercise->id;
                    $this->set_mapping('mootyper_exercise', $oldid, $exercise->id);
                    // If this mootyper is an exam, update the exam exerciseid in the mootyper.
                    if (($newmootyper->isexam = 1) && ($newmootyper->exercise = $oldid)) {
                        ($newmootyper->exercise = $exercise->id);
                    }
                }
            }

        } else {
            // This exercise is not in the database so add it.
            // Process data to get the ID.
            $data->mootyper = $this->get_new_parentid('mootyper'); // Fixed. Was using lesson which was wrong.

            // Insert new lesson ID into our current data.
            $data->lesson = $newmootyper->lesson;

            $newitemid = $DB->insert_record('mootyper_exercises', $data);

            $this->set_mapping('mootyper_exercise', $oldid, $newitemid);
            $data->id = $newitemid;
        }
    }

    /**
     * Process a grade restore
     * @param object $data The data in object form
     * @return void
     */
    protected function process_mootyper_grade($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        $data->mootyper = $this->get_new_parentid('mootyper');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->exercise = $this->get_mappingid('mootyper_exercise', $data->exercise);
        $data->attemptid = $this->get_mappingid('mootyper_attempt', $data->attemptid);
        $data->timetaken = $this->apply_date_offset($data->timetaken);
        $newitemid = $DB->insert_record('mootyper_grades', $data);
        $this->set_mapping('mootyper_grade', $oldid, $newitemid);
        $newgrade = $DB->get_record('mootyper_grades', ['id' => $newitemid]);
    }


    /**
     * Once the database tables have been fully restored, restore the files.
     * @return void
     */
    protected function after_execute() {
        global $DB;
        $newmootyper = $this->newmootyperdata;
        $DB->update_record('mootyper', $newmootyper);
        // Add mootyper related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_mootyper', 'intro', null);
        $this->add_related_files('mod_mootyper', 'introattachment', null);

    }
}

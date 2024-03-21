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
 * Define all the backup steps that will be used by the backup_mootyper_activity_task.
 *
 * @package mod_mootyper
 * @copyright 2016 onwards AL Rachels (drachels@drachels.co9m)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die(); // @codingStandardsIgnoreLine


/**
 * Define the complete mootyper structure for backup, with file and id annotations.
 *
 * @package mod_mootyper
 * @copyright 2016 onwards AL Rachels (drachels@drachels.co9m)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_mootyper_activity_structure_step extends backup_activity_structure_step {

    /**
     * Define the structure for the MooTyper activity.
     * @return void
     */
    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $mootyper = new backup_nested_element('mootyper',
            ['id'],
            ['name',
            'intro',
            'introformat',
            'timecreated',
            'timemodified',
            'timeopen',
            'timeclose',
            'assessed',
            'assesstimestart',
            'assesstimefinished',
            'scale',
            'grade_mootyper',
            'usepassword',
            'password',
            'exercise',
            'lesson',
            'isexam',
            'requiredgoal',
            'requiredwpm',
            'timelimit',
            'layout',
            'showkeyboard',
            'continuoustype',
            'countmistypedspaces',
            'statsbgc',
            'keytoptextc',
            'keytopbgc',
            'keybdbgc',
            'textalign',
            'cursorcolor',
            'textbgc',
            'texterrorcolor',
            'countmistakes',
            'completionexercise',
            'completionlesson',
            'completionprecision',
            'completionwpm',
            'completionmootypergrade', ]
        );

        $attempts = new backup_nested_element('attempts');

        $attempt = new backup_nested_element('attempt',
                                                ['id'],
                                                ['mootyperid',
                                                'userid',
                                                'timetaken',
                                                'inprogress',
                                                'suspicion', ]
                                            );

        $checks = new backup_nested_element('checks');

        $check = new backup_nested_element('check',
                                          ['id'],
                                          ['attemptid',
                                           'mistakes',
                                           'hits',
                                           'checktime', ]
                                           );

        $exercises = new backup_nested_element('exercises');

        $exercise = new backup_nested_element('exercise',
                                                 ['id'],
                                                 ['texttotype',
                                                 'exercisename',
                                                 'lesson',
                                                 'snumber', ]
                                             );

        $layouts = new backup_nested_element('layouts');

        $layout = new backup_nested_element('layout',
                                               ['id'],
                                               ['name']
                                            );

        $lessons = new backup_nested_element('lessons');

        $lesson = new backup_nested_element('lesson',
                                               ['id'],
                                               ['lessonname',
                                               'authorid',
                                               'visible',
                                               'editable',
                                               'courseid', ]
                                            );

        $grades = new backup_nested_element('grades');

        $grade = new backup_nested_element('grade',
                                              ['id'],
                                              ['mootyper',
                                              'userid',
                                              'grade',
                                              'mistakes',
                                              'timeinseconds',
                                              'hitsperminute',
                                              'fullhits',
                                              'precisionfield',
                                              'timetaken',
                                              'exercise',
                                              'pass',
                                              'attemptid',
                                              'wpm',
                                              'mistakedetails', ]
                                          );

        // Build the tree.
        $mootyper->add_child($attempts);
        $attempts->add_child($attempt);

        $attempt->add_child($checks);
        $checks->add_child($check);

        $mootyper->add_child($layouts);
        $layouts->add_child($layout);

        $mootyper->add_child($lessons);
        $lessons->add_child($lesson);

        $mootyper->add_child($exercises);
        $exercises->add_child($exercise);

        $mootyper->add_child($grades);
        $grades->add_child($grade);

        // Define sources.
        $mootyper->set_source_table('mootyper', ['id' => backup::VAR_ACTIVITYID]);
        $attempt->set_source_table('mootyper_attempts', ['mootyperid' => backup::VAR_PARENTID], 'id ASC');
        $check->set_source_table('mootyper_checks', ['attemptid' => backup::VAR_PARENTID], 'id ASC');

        // 20230709 Retrieve the layout data used in this MooTyper activity.
        $layout->set_source_sql('
            SELECT m.id, m.layout, mlay.*
              FROM {mootyper} m
              JOIN {mootyper_layouts} mlay ON m.layout = mlay.id
             WHERE m.layout = mlay.id AND m.id = ?',
                   [backup::VAR_PARENTID], 'layout');

        // 20230709 Retrieve lesson data used in this MooTyper activity.
        $lesson->set_source_sql('
            SELECT m.id, m.lesson, mles.*
              FROM {mootyper} m
              JOIN {mootyper_lessons} mles ON m.lesson = mles.id
             WHERE m.lesson = mles.id AND m.id = ?',
                   [backup::VAR_PARENTID], 'lesson');

        // 20230709 Retrieve all the exercises data that belongs to the lesson used in this MooTyper activity.
        $exercise->set_source_sql('
            SELECT m.id, m.lesson, m.exercise, mles.id, me.*
              FROM {mootyper} m
              JOIN {mootyper_lessons} mles ON m.lesson = mles.id
              JOIN {mootyper_exercises} me ON mles.id = me.lesson
             WHERE m.lesson = mles.id AND me.lesson = mles.id AND m.id = ?',
                   [backup::VAR_PARENTID], 'exercise');

        // All the rest of elements only happen if we are including user info.
        if ($userinfo) {
            $grade->set_source_table('mootyper_grades', ['mootyper' => backup::VAR_PARENTID]);
        }

        // Define id annotations.
        $attempt->annotate_ids('mootyper', 'mootyperid');
        $check->annotate_ids('attempts', 'attemptid');
        $layout->annotate_ids('layouts', 'id');
        $lesson->annotate_ids('lessons', 'id');
        $exercise->annotate_ids('exercises', 'id');
        $grade->annotate_ids('mootyper', 'mootyper');
        $grade->annotate_ids('user', 'userid');

        // Define file annotations.
        // These file areas don't have an itemid.
        $mootyper->annotate_files('mod_mootyper', 'intro', null);

        // Return the root element (mootyper), wrapped into standard activity structure.
        return $this->prepare_activity_structure($mootyper);
    }
}

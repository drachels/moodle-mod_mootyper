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
 * English strings for mootyper
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['agavg'] = 'Average';
$string['agcount'] = 'Count';
$string['agmax'] = 'Maximum';
$string['agmin'] = 'Minimum';
$string['agsum'] = 'Sum';
$string['allstring'] = 'All';
$string['average'] = 'Average';
$string['authorid'] = 'Author ID';
$string['mean'] = 'Mean';
$string['median'] = 'Median';
$string['mode'] = 'Mode';
$string['range'] = 'Range';
$string['bymootyper'] = 'by exercise';
$string['byuser'] = 'by user';
$string['calendarend'] = '{$a} closes';
$string['calendarstart'] = '{$a} opens';
$string['cancel'] = 'Cancel';
$string['charttitleallgrades'] = 'All Grades';
$string['charttitlemyowngrades'] = 'My Own Grades';
$string['chere'] = 'Click here to start';
$string['closebeforeopen'] = 'Could not update the MooTyper. You have specified a close date before the open date.';

$string['completionexercise'] = 'Student must complete all exercises for this lesson to complete this activity. ';
$string['completionexercisedesc'] = 'Student must complete {$a} exercise(s).';
$string['completionexercisegroup'] = 'Require exercise completion';

$string['completiondetail:exercise'] = 'Student must complete exercise: {$a}';
$string['completiondetail:lesson'] = 'Student must complete lesson: {$a}';
$string['completiondetail:precision'] = 'Student must achieve precision: {$a}';
$string['completiondetail:wpm'] = 'Student must achieve wpm: {$a}';
$string['completiondetail:mootypergrade'] = 'Student must achieve MT Grade: {$a}';

$string['completionlesson'] = 'Student must complete this lesson to complete this activity';
$string['completionlessondesc'] = 'Student must complete {$a} lesson.';
$string['completionlessongroup'] = 'Require lesson completion';

$string['completionmootypergrade'] = 'Student must achieve a passing MooTyper Grade to complete this activity. ';
$string['completionmootypergradedesc'] = 'Student must achieve a MooTyper Grade of {$a}.';
$string['completionmootypergradegroup'] = 'Require Grade';

$string['completionprecision'] = 'Student must achieve a passing precision to complete this activity. ';
$string['completionprecisiondesc'] = 'Student must achieve {$a}% precision.';
$string['completionprecisiongroup'] = 'Require precision';

$string['completionwpm'] = 'Student must achieve a passing WPM to complete this activity. ';
$string['completionwpmdesc'] = 'Student must achieve {$a} WPM.';
$string['completionwpmgroup'] = 'Require WPM';

$string['configdateformat'] = 'This defines how dates are shown in grade reports. The default value, "M d, Y G:i" is Month, day, year and 24 hour format time. Refer to Date in the PHP manual for more examples and predefined date constants.';
$string['configrequirepassword'] = 'Configure password';
$string['configpassword_desc'] = 'Whether a password is required in order to access the lesson.';
$string['configtimelimit_desc'] = 'If a time limit is set, it is displayed above the status bar. When the limit is reached, the exercise is ended. If set to zero, then there is no time limit.';
$string['continue'] = 'Continue';
$string['continuoustype'] = 'Continuous typing';
$string['continuoustype_help'] = 'If enabled, a mistyped character does not have to be corrected.';
$string['countmistakes'] = 'Count all keystrokes';
$string['countmistakes_help'] = 'If enabled, all keystrokes for the same mistake are added to the mistake total.';
$string['countmistypedspaces'] = 'Count mistyped spaces';
$string['countmistypedspaces_help'] = 'If enabled, a mistyped space is counted as a mistake.';
$string['course_exercises_viewed'] = 'Viewed exercises';

$string['createdin'] = 'Created in course: ';


$string['crontask'] = 'Background processing for MooTyper module';
$string['csvexport'] = 'Export to .csv';
$string['cursorcolor_title'] = 'Cursor color';
$string['cursorcolor_descr'] = 'This sets the cursor color of the next letter to type.';
$string['cursorcolor_colour'] = '#00ff00';
$string['cursorcolor'] = 'Cursor color';
$string['cursorcolor_help'] = 'This sets the color around the typing cursor. You can enter a hex color or named color. e.g. #95fc89 or green';
$string['dateformat'] = 'Default date format';
$string['defaultfmode_help'] = 'Default mode can be Lesson, Exam, or Practice.';
$string['defaultlayout'] = 'Default keyboard layout';
$string['defaultlayout_desc'] = 'If overwrite_defaultlayout is not activated then this layout will be used in new activitys as default but trainer can chose a different layout.';
$string['defaultlayout_filenamewithoutfiletype'] = 'Defaultlayout filename (without filetype!)';
$string['default_defaultlayout_filenamewithoutfiletype'] = 'English(USV5)';
$string['defaultlayout_filenamewithoutfiletype_desc'] = 'This is the filename of the defaultlayout that should be used if overwrite_defaultlayout is activated. If file does not exist, then the normal defaultlayout will be used.';
$string['defaultprecision'] = 'Default typing precision %';
$string['defaulttextalign'] = 'Text alignment';
$string['defaulttextalign_help'] = 'Set text alignment while typing exercises to left, center, or right.';
$string['defaulteditalign'] = 'Edit text alignment';
$string['defaulteditalign_help'] = 'Set text alignment while editing exercises to left, center, or right.';
$string['defaulttextalign_left'] = 'left';
$string['defaulttextalign_center'] = 'center';
$string['defaulttextalign_right'] = 'right';
$string['defaulttextalign_warning'] = 'NOTE: Set alignment before typing!';
$string['defaulttimelimit'] = 'Default time limit';
$string['defaultwpm'] = 'Default words per minute rate';
$string['delete'] = 'Delete';
$string['deleteall'] = 'Delete all from ';
$string['deletekb'] = 'Delete keyboard layouts';
$string['deletegrade'] = 'Need to delete.';
$string['deletegradeconfirm'] = 'Confirm you are about to delete exercise results for ';
$string['deletelsnconfirm'] = 'Confirm you are about to completely delete ';
$string['deleteexconfirm'] = 'Confirm you are about to delete an exercise from ';
$string['deletekb'] = 'Delete - {$a}';
$string['export'] = 'Export ';
$string['exportconfirm'] = 'Confirm you want to export ';
$string['eaccess0'] = 'Any teacher';
$string['eaccess1'] = 'Teacher inside this course';
$string['eaccess2'] = 'Only me';
$string['eaddnew'] = 'Add new lesson with exercise';
$string['eaddnewex'] = 'Add new exercise to this lesson, ID: ';
$string['editable'] = 'Can edit';
$string['editexercises'] = 'Export / edit exercises';
$string['eeditlabel'] = 'Edit';
$string['eheading'] = 'Manage MooTyper Exercises';
$string['emanage'] = 'To manage exercises and lessons click here';
$string['emptypassword'] = 'Password cannot be empty';
$string['ename'] = 'Exercise: ';
$string['ename_help'] = 'Exercise name can only be changed when the Mode is Exam. ';
$string['endlesson'] = 'End of the lesson';
$string['enterpassword'] = 'Please enter the password:';
$string['etext'] = 'Text';
$string['etitle'] = 'MooTyper exercises';
$string['examdone'] = 'Exam completed.';
$string['excategory'] = 'Lesson name';
$string['excategory_help'] = 'Pick a Lesson name to use for this MooTyper.';
$string['exercise'] = 'Exercise = {$a} of ';
$string['exercise_abreviation'] = 'Ex';
$string['exercise_added'] = 'Added exercise';
$string['exercise_edited'] = 'Edited exercise';
$string['exercise_completed'] = 'Completed an exercise';
$string['exercise_deleted'] = 'Deleted an exercise';
$string['exercise_name'] = 'Exercise name';
$string['exercise_name_added'] = 'Exercise name, {$a}, was successfully added in the database. ';
$string['exercise_name_updated'] = 'Exercise name, {$a}, was successfully updated in the database. ';
$string['eventlessonexport'] = 'Exported lesson';
$string['failbgc_title'] = 'Failing grade background color';
$string['failbgc_descr'] = 'This sets the background color for a failing grade.';
$string['failbgc_colour'] = '#FF6C6C';
$string['fapply'] = 'Apply';
$string['fconfirm'] = 'Confirm';
$string['fcontinue'] = 'Continue';
$string['fexercise'] = 'Exercise';
$string['flesson'] = 'Lesson';
$string['flesson_help'] = 'Select a lesson and use it\'s exercises in MooTyper activity.';
$string['fmode'] = 'Mode';
$string['fmode_help'] = 'Lesson mode will present each exercise in a Lesson until all are completed. Exam mode will show only the selected exercise for the exam. Practice mode works the same as Lesson mode, but students can remove grades and repeat exercises. Once you click Save and display, you cannot change the mode.';
$string['fnewexercise'] = 'Exercise will be a part of lesson';
$string['fnewlesson'] = 'create new lesson';
$string['fsecurity'] = 'Security';
$string['fsettings'] = 'Settings';
$string['fsetup'] = 'Setup';
$string['fullhits'] = 'All hits';
$string['gradehide'] = 'Hide Grade';
$string['gradeshow'] = 'Show Grade';
$string['gradesfilename'] = 'grades.csv';
$string['gradeitemnameforwholemootyper'] = 'Whole mootyper grade for {$a->name}';
$string['gradeitemnameforrating'] = 'Rating grade for {$a->name}';
$string['grade_deleted'] = 'Deleted a grade';
$string['grade_mootyper_header'] = 'Whole MooTyper grading';
$string['grade_mootyper_name'] = 'Whole Mootyper';
$string['grade_mootyper_title'] = 'Grade';
$string['grade_rating_name'] = 'Rating';
$string['gviewmode'] = 'View';
$string['hitsperminute'] = 'Hits/minute';
$string['hpmhide'] = 'Hide Hits/minute';
$string['hpmshow'] = 'Show Hits/minute';
$string['incorrectmodule'] = 'Course Module ID was incorrect';

$string['invalidaccess'] = 'Invalid access';
$string['invalidaccessexp'] = 'You do not have permission to view the page you attempted to access! The attempt was logged!';
$string['isexamtext'] = 'Exam';
$string['kblimportadd'] = 'Layout was successfully added to the database.';
$string['kblimportnotadd'] = 'Layout already in the database. Nothing to do.';
$string['keyboardbgc_title'] = 'Keyboard background color';
$string['keyboardbgc_descr'] = 'This sets the background color around keyboard keys.';
$string['keyboardbgc_colour'] = '#DDDDDD';
$string['keybdbgc'] = 'Keyboard background color';
$string['keybdbgc_help'] = 'This sets the background color around keyboard keys. You can enter a hex color or named color. e.g. #95fc89 or green';
$string['keytopbgc'] = 'Key top color';
$string['keytopbgc_help'] = 'This sets the color of key tops except homerow keys. You can enter a hex color or named color. e.g. #95fc89 or green';
$string['keytoptextc'] = 'Key top text color';
$string['keytoptextc_help'] = 'This sets the color of key top text. You can enter a hex color or named color. e.g. #000000 or black';
$string['layout'] = 'Keyboard layout';
$string['layout_deleted'] = 'Keyboard layout deleted';
$string['layout_help'] = 'You can select a Keyboard layout to be shown when the Show keyboard option is set to Yes.';
$string['layout_imported'] = 'Keyboard layout imported';
$string['lesson_completed'] = 'Lesson completed';
$string['lesson_deleted'] = 'Deleted a lesson and its exercises';
$string['lesson_export'] = 'Lesson export setting';
$string['lesson_export_filename'] = 'Lesson export filename';
$string['lesson_export_filenameconfig'] = 'Add GMT as part of exported lesson filenames for simple versioning system.';
$string['lesson_exported'] = 'Exported lesson';
$string['lesson_imported'] = 'Imported lesson';

$string['lessonincluded'] = 'This lesson was included with the MooTyper plugin.';

$string['loginfail'] = 'Login failed, please try again...';
$string['loheading'] = 'Manage MooTyper Layouts';
$string['lsnname'] = 'Lesson name';
$string['lsnimport'] = 'Import lesson / KB layout';
$string['lsnimportadd'] = 'Was successfully added to the database.';
$string['lsnimportnotadd'] = 'Already in the database. Nothing to do.';
$string['mistakedetails'] = 'Mistake details: ';
$string['modulename'] = 'MooTyper';
$string['modulename_help'] = 'The mootyper module allows us to learn how to type';
$string['modulenameplural'] = 'MooTypers';
$string['mootyper:addinstance'] = 'Add instance';
$string['mootyper:aftersetup'] = 'After setup';
$string['mootyperclosed'] = 'This MooTyper closed on {$a}.';
$string['mootyper:editall'] = 'Edit all';
$string['mootypererror'] = 'You must specify a course_module ID or an instance ID';
$string['mootyperopen'] = 'This MooTyper will be open on {$a}.';
$string['mootyper:rate'] = 'Rate exercises';
$string['mootyper:setup'] = 'Setup';
$string['mootyper:view'] = 'View';
$string['mootyper:viewgrades'] = 'View all grades';
$string['mootyper:viewmygrades'] = 'View my grades';
$string['mootyper'] = 'mootyper';
$string['mootyperclosetime'] = 'Close time';
$string['mootyperfieldset'] = 'Custom example fieldset';
$string['mootypername_help'] = 'Because of the variety of lessons and exercises you can have, the name should make it clear which MooTyper lesson or exam this acitivity is set for. Markdown syntax is supported.';
$string['mootypername'] = 'Test title';
$string['mootyperopentime'] = 'Open time';
$string['nogrades'] = 'No grades yet...';
$string['nomistakes'] = 'No mistakes.';
$string['nomode'] = 'no mode';
$string['normalkeytops_title'] = 'Key top color';
$string['normalkeytops_descr'] = 'This sets the color of key tops except homerow keys.';
$string['normalkeytops_colour'] = '#CCCCCC';
$string['normalkeytoptextc_title'] = 'Key top text color';
$string['normalkeytoptextc_descr'] = 'This sets the color of text on key tops.';
$string['normalkeytoptextc_colour'] = '#000000';
$string['notavailable'] = '<b>Not currently available!<br></b>';
$string['noteditablebyme'] = 'Not editable by me.';
$string['notreadyyet'] = 'Not ready yet...please try again later.';
$string['options'] = 'Options';
$string['overview'] = 'Overview';
$string['overview_help'] = 'MooTyper is an activity for learning to type using a computer keyboard. Some lessons with multiple exercises are included and teachers, managers, and admins can add more. Lessons can be downloaded for backup or transfer to other Moodle users. Lessons can also be uploaded to augment those already installed.';
$string['overwrite_defaultlayout'] = 'Overwrite the defaultlayout-setting';
$string['passbgc_title'] = 'Passing grade background color';
$string['passbgc_descr'] = 'This sets the background color for a passing grade.';
$string['passbgc_colour'] = '#7FEF6C';
$string['password'] = 'Password';
$string['passwordprotectedlesson'] = '{$a} is a password protected MooTyper activity.';
$string['pluginadministration'] = 'MooTyper Administration';
$string['pluginname'] = 'MooTyper';
$string['practice'] = 'Practice';
$string['precision'] = 'Precision';
$string['precisionhide'] = 'Hide Precision';
$string['precisionshow'] = 'Show Precision';
$string['privacy:metadata:mootyper_attempts'] = 'Stores data regarding the status of an attempt.';
$string['privacy:metadata:mootyper_attempts:mootyperid'] = 'ID of the MooTyper activity for this attempt.';
$string['privacy:metadata:mootyper_attempts:userid'] = 'Userid of person making this attempt.';
$string['privacy:metadata:mootyper_attempts:timetaken'] = 'When the attempt was started.';
$string['privacy:metadata:mootyper_attempts:inprogress'] = 'Status: completed = 0 or still in progress = 1. ';
$string['privacy:metadata:mootyper_attempts:suspicion'] = 'Flag if too much time or too many mistakes.';
$string['privacy:metadata:mootyper_grades'] = 'Stores results of a completed exercise attempt.';
$string['privacy:metadata:mootyper_grades:mootyper'] = 'ID of the MooTyper activity for this attempt.';
$string['privacy:metadata:mootyper_grades:userid'] = 'Userid of person making this attempt.';
$string['privacy:metadata:mootyper_grades:grade'] = 'Grade for this attempt.';
$string['privacy:metadata:mootyper_grades:mistakes'] = 'Number of mistakes counted for this attempt.';
$string['privacy:metadata:mootyper_grades:timeinseconds'] = 'Total elapsed time in seconds for this attempt.';
$string['privacy:metadata:mootyper_grades:hitsperminute'] = 'Keystroke rate per minute.';
$string['privacy:metadata:mootyper_grades:fullhits'] = 'Number of keystrokes for this attempt.';
$string['privacy:metadata:mootyper_grades:precisionfield'] = 'Typing accuracy.';
$string['privacy:metadata:mootyper_grades:timetaken'] = 'When this attempt was completed?';
$string['privacy:metadata:mootyper_grades:exercise'] = 'The exercise used for this attempt';
$string['privacy:metadata:mootyper_grades:pass'] = 'Was this attempt a pass or fail?';
$string['privacy:metadata:mootyper_grades:attemptid'] = 'ID number of this attempt';
$string['privacy:metadata:mootyper_grades:wpm'] = 'Rate in Words Per Minute for this attempt.';
$string['privacy:metadata:mootyper_lessons'] = 'Stores lessons for MooTyper.';
$string['privacy:metadata:mootyper_lessons:lessonname'] = 'The name of the lesson.';
$string['privacy:metadata:mootyper_lessons:authorid'] = 'The userid who added the lesson.';
$string['privacy:metadata:mootyper_lessons:visible'] = 'Visibilty: only me, teacher inside this course, or all teachers.';
$string['privacy:metadata:mootyper_lessons:editable'] = ' Editable by: only me, teacher inside this course, or any teachers.';
$string['privacy:metadata:mootyper_lessons:courseid'] = 'Courseid where the lesson was created.';
$string['privacy:metadata:mootyper_exercises'] = 'Stores exercises for each lesson.';
$string['privacy:metadata:mootyper_exercises:texttotype'] = 'The text of the exercise.';
$string['privacy:metadata:mootyper_exercises:exercisename'] = 'The exercise name.';
$string['privacy:metadata:mootyper_exercises:lesson'] = 'The lesson this exercise is in.';
$string['privacy:metadata:mootyper_exercises:snumber'] = 'The sequence number in the lesson.';
$string['reqfield'] = 'Required field';
$string['reqgoalwpm'] = ' The required Precision was {$a->goal}% and the required WPM was {$a->wpm}. Your combined result is {$a->currentresult}%.';
$string['requiredgoal'] = 'Required precision';
$string['requiredgoal_help'] = 'Enter precision required for successful completion of an exercise.';
$string['requiredwpm'] = 'Required WPM';
$string['requiredwpm_help'] = 'Enter WPM rate required for successful completion of an exercise.';
$string['requirepassword'] = 'Require password';
$string['resetmootyperall'] = 'Reset all MooTyper attempts and grades';
$string['returnto'] = 'Return to {$a}';
$string['rhitspermin'] = 'Hits per minute';
$string['rmistakes'] = 'Mistakes';
$string['rprecision'] = 'Precision';
$string['rprogress'] = 'Progress';
$string['rtime'] = 'Time';
$string['sflesson'] = 'Lesson';
$string['showkeyboard'] = 'Show keyboard';
$string['showkeyboard_help'] = 'If enabled, a visual representation of the currently selected keyboard layout will be shown while a user is typing an exercise.';
$string['showrecentactivity'] = 'Show recent activity';
$string['showrecentactivityconfig'] = 'Everyone can see notifications in recent activity reports.';
$string['statsbgc'] = 'Statistics background color';
$string['statsbgc_help'] = 'This sets the background color of the statistics bar. You can enter a hex color or named color. e.g. #95fc89 or green';
$string['statscolor_title'] = 'Statistics bar background color';
$string['statscolor_descr'] = 'This sets the background color of the statistics bar.';
$string['statscolor_colour'] = '#7FEF6C';
$string['student'] = 'Student';
$string['suspicion_title'] = 'Suspicious grade warning color';
$string['suspicion_descr'] = 'This sets the marker color for a grade with suspicious results.';
$string['suspicion_colour'] = '#FFFF00';
$string['testing'] = 'Testing code';
$string['textbgc_title'] = 'Text background color';
$string['textbgc_descr'] = 'This sets the background color of the exercise text.';
$string['textbgc_colour'] = '#F0E68C';
$string['textbgc'] = 'Text background color';
$string['textbgc_help'] = 'This sets the background color of the exercise text. You can enter a hex color or named color. e.g. #95fc89 or green';
$string['texterrorcolor_title'] = 'Text error color';
$string['texterrorcolor_descr'] = 'This sets the background color of a mistyped letter.';
$string['texterrorcolor_colour'] = '#FF9999';
$string['texterrorcolor'] = 'Text error color';
$string['texterrorcolor_help'] = 'This sets the background color of mistyped text. You can enter a hex color or named color. e.g. #95fc89 or green';
$string['timeisup'] = 'Time is up';
$string['timelimit'] = 'Time limit';
$string['timelimit_help'] = 'If enabled, the time limit is displayed above the status bar. When the limit is reached, the exercise is ended. If set to zero, then there is no time limit. Letters typed after time is elapsed aren\'t counted';
$string['timeinseconds'] = 'Elapsed time';
$string['timetaken'] = 'Completed';
$string['usepassword'] = 'Password protected lesson';
$string['usepassword_help'] = 'If enabled, a password is required in order to access the lesson.';
$string['vaccess0'] = 'All teachers';
$string['vaccess1'] = 'Teacher inside this course';
$string['vaccess2'] = 'Only me';
$string['viewallmootypers'] = 'View all course mootypers';
$string['viewgrades'] = 'View all grades';
$string['viewmygrades'] = 'View my grades';
$string['visibility'] = 'Lesson visible to';
$string['vmistakes'] = 'Mistakes';
$string['wpm'] = 'WPM';
$string['wpmhide'] = 'Hide WPM';
$string['wpmshow'] = 'Show WPM';
$string['xaxislabel'] = 'Range';

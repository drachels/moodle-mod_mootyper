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
 * This file keeps track of upgrades to the mootyper module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute mootyper upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_mootyper_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    // An upgrade begins here. For each one, you'll need one
    // block of code similar to the next one. Please, delete
    // this comment lines once this file start handling proper
    // upgrade code.

    // First example, some fields were added to install.xml on 2007/04/01.

    if ($oldversion < 2007040100) {

        // Define field course to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('course', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'id');

        // Add field course.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field intro to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('intro', XMLDB_TYPE_TEXT, 'medium', null, null, null, null, 'name');

        // Add field intro.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field introformat to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0',
            'intro');

        // Add field introformat.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Once we reach this point, we can store the new version and consider the module
        // upgraded to the version 2007040100 so the next time this block is skipped.
        upgrade_mod_savepoint(true, 2007040100, 'mootyper');
    }

    // Second example, some hours later, the same day 2007/04/01
    // two more fields and one index were added to install.xml (note the micro increment
    // "01" in the last two digits of the version.
    if ($oldversion < 2007040101) {

        // Define field timecreated to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0',
            'introformat');

        // Add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timemodified to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0',
            'timecreated');

        // Add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define index course (not unique) to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $index = new xmldb_index('courseindex', XMLDB_INDEX_NOTUNIQUE, array('course'));

        // Add index to course field.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Another save point reached.
        upgrade_mod_savepoint(true, 2007040101, 'mootyper');
    }
    // Attempid modified.
    if ($oldversion < 2013012100) {
        $table = new xmldb_table('mootyper_grades');
        $field = new xmldb_field('wpm', XMLDB_TYPE_NUMBER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'attemptid');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2013012100, 'mootyper');
    }
    // New field usepassword added after timeclose for version 3.1.2.
    if ($oldversion < 2016080700) {

        // Define field usepassword to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('usepassword', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, '0', 'timeclose');

        // Conditionally launch add field usepassword.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Mootyper savepoint reached.
        upgrade_mod_savepoint(true, 2016080700, 'mootyper');
    }
    // New field continuoustyping added after showkeyboard for version 3.1.4.
    if ($oldversion < 2017060400.2) {

        // Define field continuoustype to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('continuoustype', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, '0', 'showkeyboard');

        // Conditionally launch add field continuoustype.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Mootyper savepoint reached.
        upgrade_mod_savepoint(true, 2017060400.2, 'mootyper');
    }
    // New field countmistypedspaces added after continuoustype for version 3.3.0.
    if ($oldversion < 2017090200) {

        // Define field countmistypedspaces to be added to mootyper.
        $table = new xmldb_table('mootyper');
        $field = new xmldb_field('countmistypedspaces', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, '0', 'continuoustype');

        // Conditionally launch add field countmistypedspaces.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Mootyper savepoint reached.
        upgrade_mod_savepoint(true, 2017090200, 'mootyper');
    }
    return true;
}

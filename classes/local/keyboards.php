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
 * Keyboard utilities for MooTyper.
 *
 * 3/19/2020 Moved these functions from locallib.php to here.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_mootyper\local;
defined('MOODLE_INTERNAL') || die(); // phpcs:ignore
/**
 * Utility class for MooTyper keyboards.
 *
 * @package    mod_mootyper
 * @copyright  AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class keyboards {

    /**
     * Checks if a layout given by name is installed.
     *
     * @param string $layoutname
     * @return bool true if layout with name $layoutname is installed
     * @throws \dml_exception
     */
    public static function is_layout_installed(string $layoutname = ''): bool {
        global $DB;
        $params = ['name' => $layoutname];
        $layouts = $DB->get_records('mootyper_layouts', $params);
        if (empty($layouts)) {
            return false;
        }
        return true;
    }

    /**
     * Gets the id of a layout given by name.
     *
     * @param string $layoutname
     * @return string id of the layout if installed, els 0 if layout was not found
     * @throws \dml_exception
     */
    public static function get_id_of_layout_by_layoutname(string $layoutname = ''): string {
        global $DB;
        if (!self::is_layout_installed($layoutname)) {
            return 0;
        } else {
            $params = ['name' => $layoutname];
            $layouts = $DB->get_records('mootyper_layouts', $params);
            foreach ($layouts as $layout) {
                $layoutid = $layout->id;
            }
            return $layoutid;
        }
    }

    /**
     * Get a list of MooTyper keyboards.
     * @return array
     */
    public static function get_keyboard_layouts_db() {
        global $DB;
        $lss = [];
        if ($layouts = $DB->get_records('mootyper_layouts')) {
            foreach ($layouts as $ex) {
                $lss[$ex->id] = $ex->name;
            }
        }
        // Sort the array alphabetically by name, and keep the id associated with the name.
        asort($lss);
        return $lss;
    }

    /**
     * Get the MooTyper keyboard definition to use.
     * @param int $lid
     */
    public static function get_instance_layout_file($lid) {
        global $CFG, $DB;
        $dbrec = $DB->get_record('mootyper_layouts', ['id' => $lid]);
        return "$CFG->dirroot/mod/mootyper/layouts/$dbrec->name.php";
    }

    /**
     * Get the MooTyper keyboard keystroke checker to use.
     * @param int $lid
     */
    public static function get_instance_layout_js_file($lid) {
        global $CFG, $DB;
        $dbrec = $DB->get_record('mootyper_layouts', ['id' => $lid]);
        return "$CFG->wwwroot/mod/mootyper/layouts/$dbrec->name.js";
    }
}

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

namespace mod_mootyper\task;
defined('MOODLE_INTERNAL') || die(); // @codingStandardsIgnoreLine
use context_module;
use stdClass;

/**
 * A schedule task for mootyper cron.
 *
 * @package   mod_mootyper
 * @copyright 2021 AL Rachels <drachels@drachels.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cron_task extends \core\task\scheduled_task {

    // Use the logging trait to get some nice, juicy, logging.
    // Uncomment as needed, use \core\task\logging_trait;.

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('crontask', 'mod_mootyper');
    }

    /**
     * Run mootyper cron.
     */
    public function execute() {
        // 20210722 Converted from old cron to task.
        // Commented out as not currently needed.
        // $this->log_start("Processing MooTyper information.");
        // $this->log_finish("Processing MooTyper cron is completed.");

        $timeout = 5;

        // A namespace for the locks. Must be prefixed with the component name to prevent conflicts.
        // ...$locktype = 'mod_mootyper_renderer_resource';.
        // ...$locktype = 'mod_mootyper_file_upload';.
        $locktype = 'mod_mootyper_renderer_factory';

        // Resource key - needs to uniquely identify the resource that is to be locked. E.g. If you
        // want to prevent a user from running multiple course backups - include the userid in the key.
        $resource = 'user:' . $USER->id;

        // Get an instance of the currently configured lock_factory.
        $lockfactory = \core\lock\lock_config::get_lock_factory($locktype);

        // Get a new lock for the resource, wait for it if needed.
        if ($lock = $lockfactory->get_lock($resource, $timeout)) {
            // We have exclusive access to the resource, do the slow zip file generation...
            if ($someerror) {
                // Always release locks on failure.
                $lock->release();
                throw new moodle_exception('lockfailure');
            }
            // Release the lock once finished.
            $lock->release();
        } else {
            // We did not get access to the resource in time, give up.
            throw new moodle_exception('locktimeout');
        }
        return;
    }
}

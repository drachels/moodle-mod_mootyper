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
 * Capability definitions for the mootyper module.
 *
 * @package    mod_mootyper
 * @copyright  2011 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels drachels@drachels.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'mod/mootyper:setup' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],

    'mod/mootyper:editall' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'legacy' => [
            'manager' => CAP_ALLOW,
        ],
    ],

    // Added on 29.09.2012 as a fix for the plugin installation on Moodle 2.3.2+.
    'mod/mootyper:addinstance' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/course:manageactivities',
    ],

    'mod/mootyper:viewgrades' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => [
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],

    'mod/mootyper:viewmygrades' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => [
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
            'student' => CAP_ALLOW,
        ],
    ],

    'mod/mootyper:aftersetup' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],

    'mod/mootyper:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => [
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],

    'mod/mootyper:rate' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => [
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],
];

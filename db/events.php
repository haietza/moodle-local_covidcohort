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
 * Event listeners.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$callback = 'local_covidcohort_observer::manage_events';

$observers = array (
    array (
        'eventname' => '\core\event\cohort_member_added',
        'callback' => $callback,
    ),
    array (
        'eventname' => '\core\event\cohort_member_removed',
        'callback' => $callback,
    ),
    array (
        'eventname' => '\core\event\role_assigned',
        'callback' => $callback,
    ),
    array (
        'eventname' => '\core\event\role_unassigned',
        'callback' => $callback,
    )
);
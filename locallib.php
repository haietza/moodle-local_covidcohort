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
 * Local functions for plugin.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/cohort/lib.php');

/**
 * Add/remove users to COVID testing required cohort,
 * and assign/unassign custom role for Dashboard notification.
 *
 * @param string $action add or remove
 * @param array $users users to add/remove
 */
function assign_users_to_cohort($action, $users) {
    global $DB;

    $cohortshortname = get_config('local_covidcohort', 'cohortshortname');
    $cohortid = $DB->get_field('cohort', 'id', array('idnumber' => $cohortshortname));
    if (!$cohortid) {
        mtrace(get_string('nocohort', 'local_covidcohort'));
        return;
    }

    mtrace(get_string('logaction', 'local_covidcohort', $action));

    foreach ($users as $user) {
        $userid = $DB->get_field('user', 'id', array('username' => $user));
        if ($userid) {
            if ($action == 'add') {
                cohort_add_member($cohortid, $userid);
            } else if ($action == 'remove') {
                cohort_remove_member($cohortid, $userid);
            }
        } else {
            mtrace(get_string('nouser', 'local_covidcohort', $user));
        }
    }
}
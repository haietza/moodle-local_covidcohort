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
 * @copyright 2021, Michelle Melton <meltonml@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/cohort/lib.php');

/**
 * Add users to COVID testing required cohort,
 * and assign custom role for Dashboard notification.
 * 
 * @param array users to add
 * @return array return type and message
 */
function add_users_to_cohort($users) {
    global $DB;

    $context = context_system::instance();
    
    $cohortshortname = get_config('local_covidcohort', 'cohortshortname');
    $cohortid = $DB->get_field('cohort', 'id', array('idnumber' => $cohortshortname));
    
    $roleshortname = get_config('local_covidcohort', 'cohortroleshortname');
    $roleid = $DB->get_field('role', 'id', array('shortname' => $roleshortname));
    
    if (!$cohortid) {
        return array('error' => get_string('nocohort', 'local_covidcohort'));
    }
    if (!$roleid) {
        return array('error' => get_string('norole', 'local_covidcohort'));
    }
    
    foreach ($users as $user) {
        $userid = $DB->get_field('user', 'id', array('username' => $user));
        if ($userid) {
            cohort_add_member($cohortid, $userid);
            role_assign($roleid, $userid, $context->id);
        }
    }
    
    return array('success' => get_string('success', 'moodle'));
}

/**
 * Remove users from COVID testing required cohort,
 * and remove custom role for Dashboard notification.
 *
 * @param array users to remove
 */
function remove_users_from_cohort($users) {
    global $DB;
    
    $context = context_system::instance();
    
    $cohortshortname = get_config('local_covidcohort', 'cohortshortname');
    $cohortid = $DB->get_field('cohort', 'id', array('idnumber' => $cohortshortname));
    
    $roleshortname = get_config('local_covidcohort', 'cohortroleshortname');
    $roleid = $DB->get_field('role', 'id', array('shortname' => $roleshortname));
    
    if (!$cohortid) {
        return array('error' => get_string('nocohort', 'local_covidcohort'));
    }
    if (!$roleid) {
        return array('error' => get_string('norole', 'local_covidcohort'));
    }
    
    foreach ($users as $user) {
        $userid = $DB->get_field('user', 'id', array('username' => $user));
        if ($userid) {
            cohort_remove_member($cohortid, $userid);
            role_unassign($roleid, $userid, $context->id);
        }
    }
    return array('success' => get_string('success', 'moodle'));
}
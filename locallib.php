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
 * @package   local_metagroup
 * @copyright 2020, Michelle Melton <meltonml@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add users to COVID testing required cohort,
 * and assign custom role for Dashboard notification.
 * 
 * @param array users to add
 */
function add_users_to_cohort($users) {
    global $DB;
    
    $context = context_system::instance();
    $cohorts = cohort_get_cohorts($context->id);
    foreach ($cohorts as $cohort) {
        if ($cohort->idnumber == 'covid') {
            $covidcohort = $cohort;
            break;
        }
    }
    
    foreach ($users as $user) {
        $userid = $DB->get_record('user', array('username' => $user))->id;
        cohort_add_member($covidcohort->id, $userid);
        
        $roleid = $DB->get_record('role', array('shortname' => 'covidcohort'));
        role_assign($roleid, $userid, $context->id);
    }
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
    $cohorts = cohort_get_cohorts($context->id);
    foreach ($cohorts as $cohort) {
        if ($cohort->idnumber == 'covid') {
            $covidcohort = $cohort;
            break;
        }
    }
    
    foreach ($users as $user) {
        $userid = $DB->get_record('user', array('username' => $user))->id;
        cohort_remove_member($covidcohort->id, $userid);
        
        $roleid = $DB->get_record('role', array('shortname' => 'covidcohort'));
        role_unassign($roleid, $userid, $context->id);
    }
}
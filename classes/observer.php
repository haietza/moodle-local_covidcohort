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
 * Event observer.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Event observer.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_covidcohort_observer {

    /**
     * Handlers for observed events.
     *
     * @param object $event
     */
    public static function manage_events($event) {
        global $DB;
        $context = context_system::instance();
        switch ($event->eventname) {
            case '\core\event\cohort_member_added':
            case '\core\event\cohort_member_removed':
                $cohortshortname = get_config('local_covidcohort', 'cohortshortname');
                $cohortid = $DB->get_field('cohort', 'id', array('idnumber' => $cohortshortname));
                if (!empty($cohortshortname) && !$cohortid) {
                    mtrace(get_string('nocohort', 'local_covidcohort'));
                    return;
                }

                if ($event->objectid == $cohortid) {
                    $roleshortname = get_config('local_covidcohort', 'cohortroleshortname');
                    $roleid = $DB->get_field('role', 'id', array('shortname' => $roleshortname));
                    if (!$roleid) {
                        mtrace(get_string('norole', 'local_covidcohort'));
                        return;
                    }
                    if ($event->eventname == '\core\event\cohort_member_added') {
                        role_assign($roleid, $event->relateduserid, $context->id);
                    } else {
                        role_unassign($roleid, $event->relateduserid, $context->id);
                    }
                }
            case '\core\event\role_assigned':
            case '\core\event\role_unassigned':
                $roleshortname = get_config('local_covidcohort', 'cohortroleshortname');
                $roleid = $DB->get_field('role', 'id', array('shortname' => $roleshortname));
                if (!empty($roleshortname) && !$roleid) {
                    mtrace(get_string('norole', 'local_covidcohort'));
                    return;
                }

                if ($event->objectid == $roleid) {
                    $cohortshortname = get_config('local_covidcohort', 'cohortshortname');
                    $cohortid = $DB->get_field('cohort', 'id', array('idnumber' => $cohortshortname));
                    if (!$cohortid) {
                        mtrace(get_string('nocohort', 'local_covidcohort'));
                        return;
                    }
                    if ($event->eventname == '\core\event\role_assigned') {
                        cohort_add_member($cohortid, $event->relateduserid);
                    } else {
                        cohort_remove_member($cohortid, $event->relateduserid);
                    }
                }
        }
    }
}
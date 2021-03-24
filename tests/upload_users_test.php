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
 * COVID cohort upload users tests.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/local/covidcohort/classes/forms/upload_users_form.php');

/**
 * Unit tests for {@link local_covidcohort}.
 * @group local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class local_covidcohort_upload_users_testcase extends advanced_testcase {
    public function test_upload_users_no_users() {
        global $DB;
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);
        $rolerecord = array(
            'name' => 'COVID manager',
            'shortname' => 'covidmanager',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        $rolecapabilities = array(
            'contextid' => 1,
            'roleid' => $roleid,
            'capability' => 'local/covidcohort:assign',
            'permission' => 1
        );
        $DB->insert_record('role_capabilities', $rolecapabilities);
        role_assign($roleid, $user->id, context_system::instance()->id);

        $submitteddata = array(
            'action' => 'add'
        );

        upload_users_form::mock_submit($submitteddata);

        $form = new upload_users_form();
        $form->set_data($submitteddata);
        $actualfromform = $form->get_data();

        $this->assertNull($actualfromform);
    }
}
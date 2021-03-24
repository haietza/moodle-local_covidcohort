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
 * COVID cohort ron tasks tests.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;

/**
 * Unit tests for {@link local_covidcohort}.
 * @group local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class local_covidcohort_tasks_testcase extends advanced_testcase {
    public function test_assign_users_add() {
        $this->resetAfterTest();

        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);
        set_config('cohortshortname', $cohort->idnumber, 'local_covidcohort');

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();

        $users = array($user1->username, $user2->username);

        $assignusers = new \local_covidcohort\task\assign_users();
        $assignusers->set_custom_data(array(
            'action' => 'add',
            'users' => $users
        ));
        \core\task\manager::queue_adhoc_task($assignusers);

        $this->runAdhocTasks('\local_covidcohort\task\assign_users');

        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));
        $expectedstring = get_string('logaction', 'local_covidcohort', 'add') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_assign_users_remove() {
        $this->resetAfterTest();

        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);
        set_config('cohortshortname', $cohort->idnumber, 'local_covidcohort');

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);
        cohort_add_member($cohort->id, $user2->id);

        $users = array($user1->username, $user2->username);

        $assignusers = new \local_covidcohort\task\assign_users();
        $assignusers->set_custom_data(array(
            'action' => 'remove',
            'users' => $users
        ));
        \core\task\manager::queue_adhoc_task($assignusers);

        $this->runAdhocTasks('\local_covidcohort\task\assign_users');

        $this->assertFalse(cohort_is_member($cohort->id, $user1->id));
        $expectedstring = get_string('logaction', 'local_covidcohort', 'remove') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }
}
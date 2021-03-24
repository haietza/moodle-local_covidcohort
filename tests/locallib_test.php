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
 * COVID cohort locallib tests.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/local/covidcohort/locallib.php');

/**
 * Unit tests for {@link local_covidcohort}.
 * @group local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class local_covidcohort_locallib_testcase extends advanced_testcase {
    public function test_assign_users_to_cohort_add() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);
        set_config('cohortshortname', $cohort->idnumber, 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();
        $users = array($user1->username);
        assign_users_to_cohort('add', $users);

        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));
        $expectedstring = get_string('logaction', 'local_covidcohort', 'add') . PHP_EOL
            . get_string('norole', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_assign_users_to_cohort_add_no_cohort_db() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        set_config('cohortshortname', $cohortrecord['idnumber'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();
        $users = array($user1->username);
        assign_users_to_cohort('add', $users);

        $cohorts = cohort_get_user_cohorts($user1->id);
        $expected = array();
        $this->assertTrue($cohorts == $expected);
        $expectedstring = get_string('nocohort', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_assign_users_to_cohort_add_no_cohort_config() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $this->getDataGenerator()->create_cohort($cohortrecord);

        $user1 = $this->getDataGenerator()->create_user();
        $users = array($user1->username);
        assign_users_to_cohort('add', $users);

        $cohorts = cohort_get_user_cohorts($user1->id);
        $expected = array();
        $this->assertTrue($cohorts == $expected);
        $expectedstring = get_string('nocohort', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_assign_users_to_cohort_add_no_user() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);
        set_config('cohortshortname', $cohort->idnumber, 'local_covidcohort');

        $username = 'badusername@email.com';
        $users = array($username);
        assign_users_to_cohort('add', $users);

        $expectedstring = get_string('logaction', 'local_covidcohort', 'add') . PHP_EOL
            . get_string('nouser', 'local_covidcohort', $username) . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_assign_users_to_cohort_remove() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);
        set_config('cohortshortname', $cohort->idnumber, 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();
        $users = array($user1->username);
        cohort_add_member($cohort->id, $user1->id);
        assign_users_to_cohort('remove', $users);

        $this->assertFalse(cohort_is_member($cohort->id, $user1->id));
        $expectedstring = get_string('norole', 'local_covidcohort') . PHP_EOL
            . get_string('logaction', 'local_covidcohort', 'remove') . PHP_EOL
            . get_string('norole', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_assign_users_to_cohort_remove_no_cohort() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        set_config('cohortshortname', $cohortrecord['idnumber'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();
        $users = array($user1->username);
        assign_users_to_cohort('remove', $users);

        $cohorts = cohort_get_user_cohorts($user1->id);
        $expected = array();
        $this->assertTrue($cohorts == $expected);
        $expectedstring = get_string('nocohort', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }
}
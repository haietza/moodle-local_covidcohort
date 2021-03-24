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
 * COVID cohort observer tests.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Unit tests for {@link local_covidcohort}.
 * @group local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class local_covidcohort_observer_testcase extends advanced_testcase {
    public function test_cohort_member_added() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\cohort_member_added::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertTrue(user_has_role_assignment($user1->id, $roleid));
    }

    public function test_cohort_member_added_no_role_db() {
        $this->resetAfterTest(true);
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
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\cohort_member_added::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $expectedstring = get_string('norole', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_cohort_member_added_no_role_config() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\cohort_member_added::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertFalse(user_has_role_assignment($user1->id, $roleid));
        $expectedstring = get_string('norole', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_cohort_member_added_no_cohort_config() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\cohort_member_added::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertFalse(user_has_role_assignment($user1->id, $roleid));
    }

    public function test_cohort_member_added_different_cohort() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);
        set_config('cohortshortname', $cohort->idnumber, 'local_covidcohort');

        $cohortrecord2 = array(
            'contextid' => context_system::instance()->id,
            'name' => 'Other',
            'idnumber' => 'other'
        );
        $cohort2 = $this->getDataGenerator()->create_cohort($cohortrecord2);

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\cohort_member_added::create(array(
            'context' => context::instance_by_id($cohort2->contextid),
            'objectid' => $cohort2->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertFalse(user_has_role_assignment($user1->id, $roleid));
    }

    public function test_cohort_member_removed() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);
        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));
        $this->assertTrue(user_has_role_assignment($user1->id, $roleid));

        $event = \core\event\cohort_member_removed::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertFalse(user_has_role_assignment($user1->id, $roleid));
    }

    public function test_cohort_member_removed_no_role_db() {
        $this->resetAfterTest(true);
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
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);
        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));

        $event = \core\event\cohort_member_removed::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $expectedstring = get_string('norole', 'local_covidcohort') . PHP_EOL . get_string('norole', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_cohort_member_removed_no_role_config() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);
        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));

        $event = \core\event\cohort_member_removed::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertFalse(user_has_role_assignment($user1->id, $roleid));
        $expectedstring = get_string('norole', 'local_covidcohort') . PHP_EOL . get_string('norole', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_cohort_member_removed_no_cohort_config() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);
        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));

        $event = \core\event\cohort_member_removed::create(array(
            'context' => context::instance_by_id($cohort->contextid),
            'objectid' => $cohort->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertFalse(user_has_role_assignment($user1->id, $roleid));
    }

    public function test_cohort_member_removed_different_cohort() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);
        set_config('cohortshortname', $cohort->idnumber, 'local_covidcohort');

        $cohortrecord2 = array(
            'contextid' => context_system::instance()->id,
            'name' => 'Other',
            'idnumber' => 'other'
        );
        $cohort2 = $this->getDataGenerator()->create_cohort($cohortrecord2);

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort2->id, $user1->id);
        $this->assertTrue(cohort_is_member($cohort2->id, $user1->id));

        $event = \core\event\cohort_member_removed::create(array(
            'context' => context::instance_by_id($cohort2->contextid),
            'objectid' => $cohort2->id,
            'relateduserid' => $user1->id,
        ));
        $event->trigger();

        $this->assertFalse(user_has_role_assignment($user1->id, $roleid));
    }

    public function test_role_assigned() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\role_assigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));
    }

    public function test_role_assigned_no_role_config() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\role_assigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $this->assertFalse(cohort_is_member($cohort->id, $user1->id));
    }

    public function test_role_assigned_no_cohort_config() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $cohort = $this->getDataGenerator()->create_cohort($cohortrecord);

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\role_assigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $this->assertFalse(cohort_is_member($cohort->id, $user1->id));
        $expectedstring = get_string('nocohort', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_role_assigned_no_cohort_db() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        set_config('cohortshortname', $cohortrecord['idnumber'], 'local_covidcohort');

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\role_assigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $expectedstring = get_string('nocohort', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_role_assigned_different_role() {
        $this->resetAfterTest(true);
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

        $rolerecord2 = array(
            'name' => 'Other',
            'shortname' => 'other',
            'archetype' => 'user'
        );
        $roleid2 = $this->getDataGenerator()->create_role($rolerecord2);

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\role_assigned::create(array(
            'objectid' => $roleid2,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid2,
                'component' => ''
            )
        ));
        $event->trigger();

        $this->assertFalse(cohort_is_member($cohort->id, $user1->id));
    }

    public function test_role_unassigned() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);
        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));
        $this->assertTrue(user_has_role_assignment($user1->id, $roleid));

        $event = \core\event\role_unassigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $this->assertFalse(cohort_is_member($cohort->id, $user1->id));
    }

    public function test_role_unassigned_no_role_config() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);

        $event = \core\event\role_unassigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $expectedstring = get_string('norole', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_role_unassigned_no_cohort_config() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        $this->getDataGenerator()->create_cohort($cohortrecord);

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\role_unassigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $expectedstring = get_string('nocohort', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_role_unassigned_no_cohort_db() {
        $this->resetAfterTest(true);
        $cohortrecord = array(
            'contextid' => context_system::instance()->id,
            'name' => 'COVID',
            'idnumber' => 'covid'
        );
        set_config('cohortshortname', $cohortrecord['idnumber'], 'local_covidcohort');

        $rolerecord = array(
            'name' => 'COVID',
            'shortname' => 'covid',
            'archetype' => 'user'
        );
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $user1 = $this->getDataGenerator()->create_user();

        $event = \core\event\role_unassigned::create(array(
            'objectid' => $roleid,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid,
                'component' => ''
            )
        ));
        $event->trigger();

        $expectedstring = get_string('nocohort', 'local_covidcohort') . PHP_EOL;
        $this->expectOutputString($expectedstring);
    }

    public function test_role_unassigned_different_role() {
        $this->resetAfterTest(true);
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
        $roleid = $this->getDataGenerator()->create_role($rolerecord);
        set_config('cohortroleshortname', $rolerecord['shortname'], 'local_covidcohort');

        $rolerecord2 = array(
            'name' => 'Other',
            'shortname' => 'other',
            'archetype' => 'user'
        );
        $roleid2 = $this->getDataGenerator()->create_role($rolerecord2);

        $user1 = $this->getDataGenerator()->create_user();

        cohort_add_member($cohort->id, $user1->id);
        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));
        $this->assertTrue(user_has_role_assignment($user1->id, $roleid));

        $event = \core\event\role_unassigned::create(array(
            'objectid' => $roleid2,
            'relateduserid' => $user1->id,
            'context' => context_system::instance(),
            'other' => array(
                'id' => $roleid2,
                'component' => ''
            )
        ));
        $event->trigger();

        $this->assertTrue(cohort_is_member($cohort->id, $user1->id));
    }
}
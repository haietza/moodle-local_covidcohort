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
 * COVID cohort upload users form.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
require_once("$CFG->libdir/formslib.php");

/**
 * Upload users form.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class upload_users_form extends moodleform {

    /**
     * Define upload users form.
     * {@inheritDoc}
     * @see moodleform::definition()
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('select', 'action', get_string('action', 'local_covidcohort'),
            array('add' => 'Add to COVID cohort', 'remove' => 'Remove from COVID cohort'));
        $mform->addHelpButton('action', 'action', 'local_covidcohort');

        $mform->addElement('filepicker', 'usersfile', get_string('usersfile', 'local_covidcohort'),
            null, array('accepted_types' => '.csv'));
        $mform->addHelpButton('usersfile', 'usersfile', 'local_covidcohort');

        $mform->addElement('hidden', 'cohortshortname', get_config('local_covidcohort', 'cohortshortname'));
        $mform->setType('cohortshortname', PARAM_TEXT);
        $mform->addElement('hidden', 'cohortroleshortname', get_config('local_covidcohort', 'cohortroleshortname'));
        $mform->setType('cohortroleshortname', PARAM_TEXT);

        $this->add_action_buttons();
    }

    /**
     * Validate account linking form data.
     * Some code taken from login/token.php
     *
     * @param array $data data submitted
     * @param array $files files submitted
     * @return $errors array of error message to display on form
     */
    public function validation($data, $files) {
        global $DB;
        $errors = array();
        
        $cohortshortname = $data['cohortshortname'];
        $cohort = $DB->get_record('cohort', array('idnumber' => $cohortshortname));
        if (empty($cohort)) {
            $errors['action'] = get_string('nocohortform', 'local_covidcohort');
        }
        
        $cohortroleshortname = $data['cohortroleshortname'];
        $cohortrole= $DB->get_record('role', array('shortname' => $cohortroleshortname));
        if (empty($cohortrole)) {
            $errors['action'] = get_string('noroleform', 'local_covidcohort');
        }
        
        return $errors;
    }
}
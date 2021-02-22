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

class upload_users_form extends moodleform {

    /**
     * Define upload users form.
     * {@inheritDoc}
     * @see moodleform::definition()
     */
    public function definition() {
        $mform = $this->_form;
        
        $mform->addElement('select', 'action', get_string('action', 'local_covidcohort'), array('add' => 'Add to COVID cohort', 'remove' => 'Remove from COVID cohort'));
        $mform->addHelpButton('action', 'action', 'local_covidcohort');
        
        $mform->addElement('filepicker', 'usersfile', get_string('usersfile', 'local_covidcohort'), null, array('accepted_types' => '.csv'));
        $mform->addHelpButton('usersfile', 'usersfile', 'local_covidcohort');
        
        $this->add_action_buttons();
    }

    /**
     * Validate account linking form data.
     * Some code taken from login/token.php
     *
     * @param $data data submitted
     * @param $files files submitted
     * @return $errors array of error message to display on form
     */
    public function validation($data, $files) {
        global $DB;
        $errors = array();

        return $errors;
    }
}
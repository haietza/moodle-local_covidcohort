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
 * Implementation of edit form.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/local/covidcohort/classes/forms/upload_users_form.php');
require_login();
$context = context_system::instance();
require_capability('local/covidcohort:assign', $context);

$PAGE->set_url($CFG->wwwroot . '/local/covidcohort/upload_users.php');
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/local/covidcohort/upload_users.php');

$mform = new upload_users_form();

if ($mform->is_cancelled()) {
    redirect($return);
} else if ($fromform = $mform->get_data()) {
    // In this case you process validated data. $mform->get_data() returns data posted in form.
    $action = $fromform->action;
    $usersfile = $mform->get_file_content('usersfile');
    $users = preg_split('/\n|\r\n?/', $usersfile, -1, PREG_SPLIT_NO_EMPTY);

    if (count($users) > 0) {
        // Prepare ad hoc task.
        $assignusers = new assign_users();
        $assignusers->set_custom_data(array(
            'action' => $action,
            'users' => $users
        ));
        // Queue it.
        \core\task\manager::queue_adhoc_task($assignusers);
        $returnmessage = array('success' => get_string('success', 'local_covidcohort'));
        redirect($return, $returnmessage['success'], null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        $returnmessage = array('error' => get_string('usersfileempty', 'local_covidcohort'));
        redirect($return, $returnmessage['error'], null, \core\output\notification::NOTIFY_ERROR);
    }
} else {
    // This branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    $PAGE->set_heading(get_string('pluginname', 'local_covidcohort'));
    $PAGE->set_title(get_string('pluginname', 'local_covidcohort'));

    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('pluginname', 'local_covidcohort'));
    $mform->display();
    echo $OUTPUT->footer();
}
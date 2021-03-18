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
 * Plugin settings.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State University, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Ensure the configurations for this site are set.
if ($hassiteconfig) {
    $settingscategory = new admin_category('covidcohort', get_string('pluginname', 'local_covidcohort'));
    $ADMIN->add('localplugins', $settingscategory);

    $settings = new admin_settingpage('local_covidcohort_settings', get_string('pluginsettings', 'local_covidcohort'));
    $settings->add(new admin_setting_configtext('local_covidcohort/cohortshortname',
        get_string('cohortshortname', 'local_covidcohort'), get_string('cohortshortname_desc', 'local_covidcohort'), ''));
    $settings->add(new admin_setting_configtext('local_covidcohort/cohortroleshortname',
        get_string('roleshortname', 'local_covidcohort'), get_string('roleshortname_desc', 'local_covidcohort'), ''));
    $settings->add(new admin_setting_configtext('local_covidcohort/usertourid',
        get_string('usertourid', 'local_covidcohort'), get_string('usertourid_desc', 'local_covidcohort'), ''));
    $ADMIN->add('covidcohort', $settings);

    $uploadusersform = new admin_externalpage('local_covidcohort_upload', get_string('uploadusers', 'local_covidcohort'),
        $CFG->wwwroot . '/local/covidcohort/upload_users.php', 'moodle/cohort:assign');
    $ADMIN->add('covidcohort', $uploadusersform);
}
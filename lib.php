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
 * Library functions for plugin.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Add the Upload users link to the Navigation block.
 * @param object $navigation
 */
function local_covidcohort_extend_navigation($navigation) {
    global $USER, $PAGE;

    if (empty($USER->id)) {
        return;
    }

    $context = context_user::instance($USER->id);

    if (!has_capability('local/covidcohort:assign', $context, $USER)) {
        return;
    }

    $covidcohortnode = $PAGE->navigation->add(get_string('pluginname', 'local_covidcohort'),
        new moodle_url('/local/covidcohort/upload_users.php'), navigation_node::TYPE_CONTAINER);
    $covidcohortnode->add(get_string('uploadusers', 'local_covidcohort'), new moodle_url('/local/covidcohort/upload_users.php'));
}
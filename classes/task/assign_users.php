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
 * Cron tasks for plugin.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_covidcohort\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/covidcohort/locallib.php');

/**
 * Ad hoc task for plugin to assign users.
 *
 * @package   local_covidcohort
 * @author    Michelle Melton <meltonml@appstate.edu>
 * @copyright (c) 2021 Appalachian State Universtiy, Boone, NC
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_users extends \core\task\adhoc_task {
    /**
     * Execute ad hock task.
     * {@inheritDoc}
     * @see \core\task\task_base::execute()
     */
    public function execute() {
        $data = $this->get_custom_data();
        assign_users_to_cohort($data->action, $data->users);
    }
}
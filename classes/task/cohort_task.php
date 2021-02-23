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
 * @copyright 2021, Michelle Melton <meltonml@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_covidcohort\task;
use tool_usertours\tour;

defined('MOODLE_INTERNAL') || die();

class cohort_task extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('pluginname', 'local_covidcohort');
    }
    
    public function execute() {
        $tourid = get_config('local_covidcohort', 'usertourid');
        if ($tourid) {
            $tour = tour::instance($tourid);
            $tour->mark_major_change();
        } 
    } 
}
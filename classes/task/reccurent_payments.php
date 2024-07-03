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
 * @package   paygw_yookassa
 * @copyright 2024 Alex Orlov <snickser@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_yookassa\task;

defined('MOODLE_INTERNAL') || die();

use core_payment\helper;
use paygw_yookassa\notifications;

require_once($CFG->libdir . '/filelib.php');

/**
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reccurent_payments extends \core\task\scheduled_task {
    /**
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'paygw_yookassa');
    }
    /**
     * Execute.
     */
    public function execute() {
        global $DB, $CFG;
        mtrace('Start');

$yookassatx = $DB->get_records('paygw_yookassa', ['success' => 1, 'success' => 3]);

echo serialize($yookassatx);



        mtrace('End');
    }//end of function execute()
}// End of class

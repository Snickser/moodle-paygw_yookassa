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
 * recurrent payments
 *
 * @package    paygw_yookassa
 * @copyright  2024 Alex Orlov <snicker@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_yookassa\task;

defined('MOODLE_INTERNAL') || die();

use core_payment\helper;
use paygw_yookassa\notifications;

require_once($CFG->libdir . '/filelib.php');

/**
 * Default tasks.
 *
 * @package    paygw_yookassa
 * @copyright  2024 Alex Orlov <snicker@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class recurrent_payments extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task (shown to admins).
     *
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

        $ctime = strtotime("today");
        $yookassatx = $DB->get_records_sql('SELECT * FROM {paygw_yookassa} WHERE (success=1 OR success=3) ' .
                  'AND recurrent>0 AND recurrent < ?', [ $ctime ]);

        foreach ($yookassatx as $data) {
            // To avoid abuse.
            sleep(1);

            // Get payment data.
            if (!$payment = $DB->get_record('payments', ['id' => $data->paymentid])) {
                continue;
            }

            $component   = $payment->component;
            $paymentarea = $payment->paymentarea;
            $itemid      = $payment->itemid;
            $paymentid   = $payment->id;
            $userid      = $payment->userid;

            // Get config.
            $config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'yookassa');

            // Make invoice.
            $invoice = new \stdClass();
            $invoice->amount = [ "value" => $payment->amount, "currency" => $payment->currency ];
            $invoice->capture = "true";
            $invoice->payment_method_id = $data->invoiceid;
            $invoice->description = "recurrent payment " . $data->paymentid;

            $user = \core_user::get_user($userid);

            $invoice->receipt = [
              "customer" => [
                "email" => $user->email,
              ],
              "items" => [
                [
                  "description" => $invoice->description,
                  "quantity" => 1,
                  "amount" => [
                    "value" => $payment->amount,
                    "currency" => $payment->currency,
                  ],
                  "vat_code" => $config->vatcode,
                  "payment_subject" => "payment",
                  "payment_mode" => "full_payment",
                ],
              ],
              "tax_system_code" => $config->taxsystemcode,
            ];

            $jsondata = json_encode($invoice);

            // Make payment.
            $location = 'https://api.yookassa.ru/v3/payments';
            $options = [
              'CURLOPT_RETURNTRANSFER' => true,
              'CURLOPT_TIMEOUT' => 30,
              'CURLOPT_HTTP_VERSION' => CURL_HTTP_VERSION_1_1,
              'CURLOPT_SSLVERSION' => CURL_SSLVERSION_TLSv1_2,
              'CURLOPT_HTTPHEADER' => [
                'Idempotence-Key: ' . uniqid($data->paymentid, true),
                'Content-Type: application/json',
              ],
              'CURLOPT_HTTPAUTH' => CURLAUTH_BASIC,
              'CURLOPT_USERPWD' => $config->shopid . ':' . $config->apikey,
            ];
            $curl = new \curl();
            $jsonresponse = $curl->post($location, $jsondata, $options);

            $response = json_decode($jsonresponse);

            if (($response->status !== 'succeeded' && $response->status !== 'pending') || $response->paid != true) {
                echo serialize($response) . "\n";
                mtrace("$data->paymentid is not valid");
                $data->recurrent = 0;
                $DB->update_record('paygw_yookassa', $data);
            } else {
                mtrace("$data->paymentid order paid successfully");
            }
        }
        mtrace('End.');
    }
}

<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     paygw_yookassa
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;
use paygw_yookassa\notifications;

require("../../../config.php");
global $CFG, $USER, $DB;

require_once($CFG->libdir . '/filelib.php');

defined('MOODLE_INTERNAL') || die();

$source = file_get_contents('php://input');
$data = json_decode($source, false);

// Check json.
if ($data === null) {
    $lasterror = json_last_error_msg();
    die('Invalid json in request: ' . $lasterror);
}

if ($data->event !== 'payment.succeeded') {
    die('FAIL. Payment not successed');
}

// Check data.
if (isset($data->object->id)) {
    $invoiceid  = clean_param($data->object->id, PARAM_ALPHANUMEXT);
} else {
    die('FAIL. No invoiceid.');
}

if (isset($data->object->amount->value)) {
    $outsumm = clean_param($data->object->amount->value, PARAM_FLOAT);
} else {
    die('FAIL. No amount.');
}

// Get paymentid.
if (!$yookassatx = $DB->get_record('paygw_yookassa', ['invoiceid' => $invoiceid])) {
    die('FAIL. Not a valid transaction id');
}

// Get payment data.
if (!$payment = $DB->get_record('payments', ['id' => $yookassatx->paymentid])) {
    die('FAIL. Not a valid payment.');
}
$component   = $payment->component;
$paymentarea = $payment->paymentarea;
$itemid      = $payment->itemid;
$paymentid   = $payment->id;
$userid      = $payment->userid;

// Get config.
$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'yookassa');

// Check payment on site.
$location = 'https://api.yookassa.ru/v3/payments/' . $invoiceid;
$options = [
    'CURLOPT_RETURNTRANSFER' => true,
    'CURLOPT_TIMEOUT' => 30,
    'CURLOPT_HTTP_VERSION' => CURL_HTTP_VERSION_1_1,
    'CURLOPT_SSLVERSION' => CURL_SSLVERSION_TLSv1_2,
    'CURLOPT_HTTPAUTH' => CURLAUTH_BASIC,
    'CURLOPT_USERPWD' => $config->shopid . ':' . $config->apikey,
];
$curl = new curl();
$jsonresponse = $curl->get($location, null, $options);

$response = json_decode($jsonresponse, false);

if ($response->status !== 'succeeded' || $response->paid !== true) {
    die("FAIL. Invoice not paid.");
}

// Update payment.
$payment->amount = $outsumm;
$DB->update_record('payments', $payment);

// Deliver order.
helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

// Notify user.
notifications::notify(
    $userid,
    $payment->amount,
    $payment->currency,
    $paymentid,
    $response->description,
    'Success completed'
);

// Write to DB.
if ($response->test == true) {
    $yookassatx->success = 3;
} else {
    $yookassatx->success = 1;
}
if (!$DB->update_record('paygw_yookassa', $yookassatx)) {
    die('FAIL. Update db error.');
} else {
    die("OK");
}

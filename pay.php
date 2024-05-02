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
 * Redirects user to the payment page
 *
 * @package   paygw_yookassa
 * @copyright 2024 Alex Orlov <snickser@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();

global $CFG, $USER, $DB;

$userid = $USER->id;

$component   = required_param('component', PARAM_ALPHANUMEXT);
$paymentarea = required_param('paymentarea', PARAM_ALPHANUMEXT);
$itemid      = required_param('itemid', PARAM_INT);
$description = required_param('description', PARAM_TEXT);

$password    = optional_param('password', null, PARAM_TEXT);
$skipmode    = optional_param('skipmode', 0, PARAM_INT);
$costself    = optional_param('costself', null, PARAM_TEXT);

$description = json_decode("\"$description\"");


$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'yookassa');
$payable = helper::get_payable($component, $paymentarea, $itemid);// Get currency and payment amount.
$currency = $payable->get_currency();
$surcharge = helper::get_gateway_surcharge('yookassa');// In case user uses surcharge.
// TODO: Check if currency is IDR. If not, then something went really wrong in config.
$cost = helper::get_rounded_cost($payable->get_amount(), $payable->get_currency(), $surcharge);

// Check self cost.
if (!empty($costself)) {
    $cost = $costself;
}
// Check maxcost.
if ($config->maxcost && $cost > $config->maxcost) {
    $cost = $config->maxcost;
}
$cost = number_format($cost, 2, '.', '');

// Get course and groups for user.
if ($component == "enrol_fee") {
    $cs = $DB->get_record('enrol', ['id' => $itemid]);
    $cs->course = $cs->courseid;
} else if ($component == "mod_gwpayments") {
    $cs = $DB->get_record('gwpayments', ['id' => $itemid]);
} else if ($paymentarea == "cmfee") {
    $cs = $DB->get_record('course_modules', ['id' => $itemid]);
} else if ($paymentarea == "sectionfee") {
    $cs = $DB->get_record('course_sections', ['id' => $itemid]);
}
$groupnames = '';
if (!empty($cs->course)) {
    $courseid = $cs->course;
    if ($gs = groups_get_user_groups($courseid, $userid, true)) {
        foreach ($gs as $gr) {
            foreach ($gr as $g) {
                $groups[] = groups_get_group_name($g);
            }
        }
        if (isset($groups)) {
            $groupnames = implode(',', $groups);
        }
    }
} else {
    $courseid = '';
}

// Write tx to DB.
$paygwdata = new stdClass();
$paygwdata->courseid = $courseid;
$paygwdata->groupnames = $groupnames;
if (!$transactionid = $DB->insert_record('paygw_yookassa', $paygwdata)) {
    die(get_string('error_txdatabase', 'paygw_yookassa'));
}
$paygwdata->id = $transactionid;

// Build redirect.
$url = helper::get_success_url($component, $paymentarea, $itemid);

// Check passwordmode or skipmode.
if (!empty($password) || $skipmode) {
    $success = false;
    if ($config->skipmode) {
        $success = true;
    } else if ($config->passwordmode && !empty($config->password)) {
        // Check password.
        if ($password === $config->password) {
            $success = true;
        }
    }

    if ($success) {
        // Make fake pay.
        $paymentid = helper::save_payment(
            $payable->get_account_id(),
            $component,
            $paymentarea,
            $itemid,
            $userid,
            0,
            $payable->get_currency(),
            'yookassa'
        );
        helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

        // Write to DB.
        $paygwdata->success = 2;
        $paygwdata->paymentid = $paymentid;
        $DB->update_record('paygw_yookassa', $paygwdata);

        redirect($url, get_string('password_success', 'paygw_yookassa'), 0, 'success');
    } else {
        redirect($url, get_string('password_error', 'paygw_yookassa'), 0, 'error');
    }
    die; // Never.
}

// Save payment.
$paymentid = helper::save_payment(
    $payable->get_account_id(),
    $component,
    $paymentarea,
    $itemid,
    $userid,
    $cost,
    $payable->get_currency(),
    'yookassa'
);

// Make invoice.
$payment = new stdClass();
$payment->amount = [ "value" => $cost, "currency" => $currency ];
$payment->confirmation = [
  "type" => "redirect",
  "return_url" => $CFG->wwwroot . '/payment/gateway/yookassa/return.php?ID=' . $paymentid,
];
$payment->capture = "true";
$payment->description = $description;
if (!empty($config->paymentmethod)) {
    $payment->payment_method_data = [
    "type" => $config->paymentmethod,
    ];
}
$payment->receipt = [
 "customer" => [
   "email" => $USER->email,
 ],
 "items" => [
   [
    "description" => $description,
    "quantity" => 1,
    "amount" => [
       "value" => $cost,
       "currency" => $currency,
    ],
    "vat_code" => $config->vatcode,
    "payment_subject" => "payment",
    "payment_mode" => "full_payment",
   ],
 ],
 "tax_system_code" => $config->taxsystemcode,
];

$jsondata = json_encode($payment);

// Make payment.
$location = 'https://api.yookassa.ru/v3/payments';
$options = [
    'CURLOPT_RETURNTRANSFER' => true,
    'CURLOPT_TIMEOUT' => 30,
    'CURLOPT_HTTP_VERSION' => CURL_HTTP_VERSION_1_1,
    'CURLOPT_SSLVERSION' => CURL_SSLVERSION_TLSv1_2,
    'CURLOPT_HTTPHEADER' => [
        'Idempotence-Key: ' . uniqid($paymentid, true),
        'Content-Type: application/json',
    ],
    'CURLOPT_HTTPAUTH' => CURLAUTH_BASIC,
    'CURLOPT_USERPWD' => $config->shopid . ':' . $config->apikey,
];
$curl = new curl();
$jsonresponse = $curl->post($location, $jsondata, $options);

$response = json_decode($jsonresponse);

if (!isset($response->confirmation)) {
    redirect($url, get_string('payment_error', 'paygw_yookassa') . " (response error)", 0, 'error');
}

$confirmationurl = $response->confirmation->confirmation_url;

if (empty($confirmationurl)) {
    $error = $response->description;
    redirect($url, get_string('payment_error', 'paygw_yookassa') . " ($error)", 0, 'error');
}

// Write to DB.
$paygwdata->paymentid = $paymentid;
$paygwdata->invoiceid = $response->id;
$DB->update_record('paygw_yookassa', $paygwdata);

redirect($confirmationurl);

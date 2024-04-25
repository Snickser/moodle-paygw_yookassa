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

require("../../../config.php");
global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

$source = file_get_contents('php://input');
$data = json_decode($source, false);

$orderid  = $data->object->id;
$outsumm  = $data->object->amount->value;

// file_put_contents("/tmp/yyyyy", serialize($data) . "\n", FILE_APPEND);

if ($data->event !== 'payment.succeeded') {
    die('FAIL. Payment not successed');
}

if (!$yookassatx = $DB->get_record('paygw_yookassa', ['orderid' => $orderid])) {
    die('FAIL. Not a valid transaction id');
}

if (! $userid = $DB->get_record("user", ["id" => $yookassatx->userid])) {
    die('FAIL. Not a valid user id.');
}


$component   = $yookassatx->component;
$paymentarea = $yookassatx->paymentarea;
$itemid      = $yookassatx->itemid;
$userid      = $yookassatx->userid;

// Get config
$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'yookassa');
$payable = helper::get_payable($component, $paymentarea, $itemid);

// Check that amount paid is the correct amount
if ((float) $yookassatx->cost <= 0) {
    $cost = (float) $payable->get_amount();
} else {
    $cost = (float) $yookassatx->cost;
}

// Use the same rounding of floats as on the paygw form.
$cost = number_format($cost, 2, '.', '');
$outsumm = number_format($outsumm, 2, '.', '');

if ($yookassatx->currency == 'RUB') {
    if ($outsumm !== $cost) {
        die('FAIL. Amount does not match.');
    }
}

// Deliver course
// $fee = helper::get_rounded_cost($payable->get_amount(), $payable->get_currency(), helper::get_gateway_surcharge('yookassa'));
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
helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

// Write to DB
$yookassatx->success = 1;
if (!$DB->update_record('paygw_yookassa', $yookassatx)) {
    die('FAIL. Update db error.');
} else {
    die("OK");
}

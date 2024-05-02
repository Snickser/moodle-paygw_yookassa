<?php

use core_payment\helper;

require("../../../config.php");
global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();

$id = required_param('ID', PARAM_INT);

if (!$yookassatx = $DB->get_record('paygw_yookassa', ['paymentid' => $id])) {
    die('FAIL. Not a valid transaction id');
}

if (!$payment = $DB->get_record('payments', ['id' => $yookassatx->paymentid])) {
    die('FAIL. Not a valid payment.');
}

$paymentarea = $payment->paymentarea;
$component   = $payment->component;
$itemid      = $payment->itemid;

$url = helper::get_success_url($component, $paymentarea, $itemid);

if ($yookassatx->success) {
    redirect($url, get_string('payment_success', 'paygw_yookassa'), 0, 'success');
} else {
    redirect($url, get_string('payment_error', 'paygw_yookassa'), 0, 'error');
}

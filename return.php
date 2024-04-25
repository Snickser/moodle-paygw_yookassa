<?php

use core_payment\helper;

require("../../../config.php");
global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();

// file_put_contents("/tmp/xxxx", serialize($_REQUEST)."\n", FILE_APPEND);

$id = required_param('ID', PARAM_INT);

if (!$yookassatx = $DB->get_record('paygw_yookassa', ['id' => $id])) {
    die('FAIL. Not a valid transaction id');
}

$paymentarea = $yookassatx->paymentarea;
$component   = $yookassatx->component;
$itemid      = $yookassatx->itemid;

$url = helper::get_success_url($component, $paymentarea, $itemid);
if ($yookassatx->success) {
    redirect($url, get_string('payment_success', 'paygw_yookassa'), 0, 'success');
} else {
    redirect($url, get_string('payment_error', 'paygw_yookassa'), 0, 'error');
}

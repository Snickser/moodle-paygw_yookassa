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
 * @category    admin
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require_once(__DIR__ . '/../../../config.php');

global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();
require_sesskey();

$component   = required_param('component', PARAM_COMPONENT);
$paymentarea = required_param('paymentarea', PARAM_AREA);
$itemid      = required_param('itemid', PARAM_INT);
$description = required_param('description', PARAM_TEXT);

$description = json_decode('"' . $description . '"');

$params = [
    'component'   => $component,
    'paymentarea' => $paymentarea,
    'itemid'      => $itemid,
    'description' => $description,
];

$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'yookassa');
$payable = helper::get_payable($component, $paymentarea, $itemid);// Get currency and payment amount.
$currency = $payable->get_currency();
$surcharge = helper::get_gateway_surcharge('yookassa');// In case user uses surcharge.
$fee = helper::get_rounded_cost($payable->get_amount(), $currency, $surcharge);

// Get course info.
$enrolperiod = 0;
$enrolperioddesc = null;
$uninterrupted = false;
$enrolperiod = true;
$plugin = \core_plugin_manager::instance()->get_plugin_info('enrol_yafee');
$ver = 2025040100;
if ($component == "enrol_yafee") {
    $cs = $DB->get_record('enrol', ['id' => $itemid, 'enrol' => 'yafee']);
    $enrolperiod = $cs->enrolperiod;
    // Check uninterrupted cost.
    if ($cs->customint5) {
        if ($data = $DB->get_record('user_enrolments', ['userid' => $USER->id, 'enrolid' => $cs->id])) {
            // Prepare month and year.
            $timeend = time();
            if (isset($data->timeend)) {
                $timeend = $data->timeend;
            }
            $t1 = getdate($timeend);
            $t2 = getdate(time());
            // Check periods.
            if ($data->timeend < time() && $data->timestart) {
                if ($cs->enrolperiod) {
                    $price = $fee / $cs->enrolperiod;
                    $delta = ceil((time() - $data->timestart) / $cs->enrolperiod) * $cs->enrolperiod +
                             $data->timestart - $data->timeend;
                    if ($plugin->versiondisk < $ver) {
                        $fee = $delta * $price;
                    }
                    $uninterrupted = true;
                } else if ($cs->customchar1 == 'month' && $cs->customint7 > 0) {
                    $delta = ($t2['year'] - $t1['year']) * 12 + $t2['mon'] - $t1['mon'] + 1;
                    if ($plugin->versiondisk < $ver) {
                        $fee = $delta * $fee;
                    }
                    $uninterrupted = true;
                } else if ($cs->customchar1 == 'year' && $cs->customint7 > 0) {
                    $delta = ($t2['year'] - $t1['year']) + 1;
                    if ($plugin->versiondisk < $ver) {
                        $fee = $delta * $fee;
                    }
                    $uninterrupted = true;
                }
                $fee = helper::get_rounded_cost($fee, $currency, $surcharge);
            }
        }
    }
    // Set month/year period.
    if ($cs->customchar1 == 'month' && $cs->customint7 > 0) {
        $enrolperiod = $cs->customint7;
        $enrolperioddesc = get_string('months');
        $showenrolperiod = false;
    } else if ($cs->customchar1 == 'year' && $cs->customint7 > 0) {
        $enrolperiod = $cs->customint7;
        $enrolperioddesc = get_string('years');
        $showenrolperiod = false;
    }
} else if ($component == "mod_gwpayments") {
    $cs = $DB->get_record('gwpayments', ['id' => $itemid]);
    $enrolperiod = $cs->costduration;
}

// Set standard periods.
if ($enrolperiod > 0 && $enrolperiod) {
    if ($enrolperiod >= 86400 * 7) {
        $enrolperioddesc = get_string('weeks');
        $enrolperiod = round($enrolperiod / (86400 * 7));
    } else if ($enrolperiod >= 86400) {
        $enrolperioddesc = get_string('days');
        $enrolperiod = round($enrolperiod / 86400);
    } else if ($enrolperiod >= 3600) {
        $enrolperioddesc = get_string('hours');
        $enrolperiod = round($enrolperiod / 3600);
    } else if ($enrolperiod >= 60) {
        $enrolperioddesc = get_string('minutes');
        $enrolperiod = round($enrolperiod / 60);
    } else {
        $enrolperioddesc = get_string('seconds');
    }
}

// Set the context of the page.
$PAGE->set_context(context_system::instance());

$PAGE->set_url('/payment/gateway/yookassa/method.php', $params);
$string = get_string('payment', 'paygw_yookassa');
$PAGE->set_title(format_string($string));
$PAGE->set_heading(format_string($string));

// Set the appropriate headers for the page.
$PAGE->set_cacheable(false);
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();

$templatedata = new stdClass();
$templatedata->component   = $component;
$templatedata->paymentarea = $paymentarea;
$templatedata->itemid      = $itemid;
$templatedata->fee         = $fee;
$templatedata->currency    = $currency;
$templatedata->sesskey     = sesskey();

if ($config->showduration) {
    $templatedata->enrolperiod = $enrolperiod;
    $templatedata->enrolperiod_desc = $enrolperioddesc;
}

$templatedata->passwordmode = $config->passwordmode;

if ($config->suggest < $fee) {
    $templatedata->suggest = $fee;
} else {
    $templatedata->suggest = $config->suggest;
}

if (isset($config->maxcost)) {
    $templatedata->maxcost = $config->maxcost;
    if ($config->maxcost && $config->maxcost < $fee) {
        $fee = $config->maxcost;
        $templatedata->fee = $fee;
    }
}

$templatedata->fixcost = $config->fixcost;

if (!$config->fixcost) {
    if ($config->suggest < $fee) {
        $templatedata->suggest = $fee;
    } else {
        $templatedata->suggest = $config->suggest;
    }
} else {
    $templatedata->localizedcost = \core_payment\helper::get_cost_as_string($fee, $currency);
}

if ($uninterrupted && $fee != $cs->cost) {
    $templatedata->uninterrupted = true;
}

$templatedata->skipmode = $config->skipmode;

if ($config->skipmode || $config->passwordmode) {
    $templatedata->usedetails = $config->usedetails;
}

if (!empty($config->fixdesc)) {
    $templatedata->description = $config->fixdesc;
    $templatedata->fixdesc = 1;
} else {
    $templatedata->description = $description;
}

$templatedata->recurrent = $config->recurrent;

$templatedata->image = $OUTPUT->image_url('img', 'paygw_yookassa');

echo $OUTPUT->render_from_template('paygw_yookassa/method', $templatedata);

echo $OUTPUT->footer();

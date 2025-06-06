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
 * Contains class for yookassa payment gateway.
 *
 * @package    paygw_yookassa
 * @copyright  2024 Alex Orlov <snickser@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_yookassa;

/**
 * The gateway class for yookassa payment gateway.
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gateway extends \core_payment\gateway {
    /**
     * Configuration form for currency
     */
    public static function get_supported_currencies(): array {
        // 3-character ISO-4217: https://en.wikipedia.org/wiki/ISO_4217#Active_codes.
        return [
            'RUB',
        ];
    }

    /**
     * Configuration form for the gateway instance
     *
     * Use $form->get_mform() to access the \MoodleQuickForm instance
     *
     * @param \core_payment\form\account_gateway $form
     */
    public static function add_configuration_to_gateway_form(\core_payment\form\account_gateway $form): void {
        $mform = $form->get_mform();

        $mform->addElement('text', 'shopid', get_string('shopid', 'paygw_yookassa'));
        $mform->setType('shopid', PARAM_TEXT);
        $mform->addRule('shopid', get_string('required'), 'required', null, 'client');

        $mform->addElement('passwordunmask', 'apikey', get_string('apikey', 'paygw_yookassa'), ['size' => 50]);
        $mform->setType('apikey', PARAM_TEXT);
        $mform->addRule('apikey', get_string('required'), 'required', null, 'client');

        $options = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        ];
        $mform->addElement(
            'select',
            'taxsystemcode',
            get_string('taxsystemcode', 'paygw_yookassa'),
            $options
        );
        $mform->setType('taxsystemcode', PARAM_INT);
        $mform->addHelpButton('taxsystemcode', 'taxsystemcode', 'paygw_yookassa');

        $options = [
        1 => get_string('no'),
        2 => "0%",
        3 => "10%",
        4 => "20%",
        5 => "10/110",
        6 => "20/120",
        7 => "5%",
        8 => "7%",
        9 => "5/105",
        10 => "7/107",
        ];
        $mform->addElement(
            'select',
            'vatcode',
            get_string('vatcode', 'paygw_yookassa'),
            $options,
        );
        $mform->setType('vatcode', PARAM_INT);
        $mform->addHelpButton('vatcode', 'vatcode', 'paygw_yookassa');

        $options = [
        '' => get_string('yookassa', 'paygw_yookassa'),
        'bank_card' => get_string('plastic', 'paygw_yookassa'),
        'yoo_money' => get_string('wallet', 'paygw_yookassa'),
        'sbp' => get_string('sbp', 'paygw_yookassa'),
        ];
        $mform->addElement(
            'select',
            'paymentmethod',
            get_string('paymentmethod', 'paygw_yookassa'),
            $options,
        );
        $mform->setType('paymentmethod', PARAM_TEXT);
        $mform->addHelpButton('paymentmethod', 'paymentmethod', 'paygw_yookassa');

        $mform->addElement(
            'advcheckbox',
            'recurrent',
            get_string('recurrent', 'paygw_yookassa')
        );
        $mform->setType('recurrent', PARAM_INT);
        $mform->addHelpButton('recurrent', 'recurrent', 'paygw_yookassa');

        $plugininfo = \core_plugin_manager::instance()->get_plugin_info('report_payments');
        if ($plugininfo->versiondisk < 3024070800) {
            $mform->addElement('static', 'noreport', null, get_string('noreportplugin', 'paygw_yookassa'));
            $mform->hideIf('noreport', 'recurrent', "neq", 1);
        }

        $options = [0 => get_string('no')];
        for ($i = 1; $i <= 28; $i++) {
            $options[] = $i;
        }
        $mform->addElement(
            'select',
            'recurrentday',
            get_string('recurrentday', 'paygw_yookassa'),
            $options,
        );
        $mform->addHelpButton('recurrentday', 'recurrentday', 'paygw_yookassa');
        $mform->setDefault('recurrentday', 1);
        $mform->hideIf('recurrentday', 'recurrent', "neq", 1);

        $mform->addElement('duration', 'recurrentperiod', get_string('recurrentperiod', 'paygw_yookassa'), ['optional' => false]);
        $mform->setType('recurrentperiod', PARAM_INT);
        $mform->hideIf('recurrentperiod', 'recurrent', "neq", 1);
        $mform->hideIf('recurrentperiod', 'recurrentday', "neq", 0);
        $mform->addHelpButton('recurrentperiod', 'recurrentperiod', 'paygw_yookassa');

        $options = [
        'last' => get_string('recurrentcost1', 'paygw_yookassa'),
        'fee' => get_string('recurrentcost2', 'paygw_yookassa'),
        'suggest' => get_string('recurrentcost3', 'paygw_yookassa'),
        ];
        $mform->addElement(
            'select',
            'recurrentcost',
            get_string('recurrentcost', 'paygw_yookassa'),
            $options,
        );
        $mform->setType('recurrentcost', PARAM_TEXT);
        $mform->addHelpButton('recurrentcost', 'recurrentcost', 'paygw_yookassa');
        $mform->setDefault('recurrentcost', 'fee');
        $mform->hideIf('recurrentcost', 'recurrent', "neq", 1);

        $mform->addElement(
            'advcheckbox',
            'sendlinkmsg',
            get_string('sendlinkmsg', 'paygw_yookassa')
        );
        $mform->setType('sendlinkmsg', PARAM_INT);
        $mform->addHelpButton('sendlinkmsg', 'sendlinkmsg', 'paygw_yookassa');
        $mform->setDefault('sendlinkmsg', 1);

        $mform->addElement('text', 'fixdesc', get_string('fixdesc', 'paygw_yookassa'), ['size' => 50]);
        $mform->setType('fixdesc', PARAM_TEXT);
        $mform->addRule('fixdesc', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('fixdesc', 'fixdesc', 'paygw_yookassa');

        $mform->addElement('static');

        $mform->addElement(
            'advcheckbox',
            'skipmode',
            get_string('skipmode', 'paygw_yookassa')
        );
        $mform->setType('skipmode', PARAM_INT);
        $mform->addHelpButton('skipmode', 'skipmode', 'paygw_yookassa');

        $mform->addElement(
            'advcheckbox',
            'passwordmode',
            get_string('passwordmode', 'paygw_yookassa')
        );
        $mform->setType('passwordmode', PARAM_INT);
        $mform->disabledIf('passwordmode', 'skipmode', "neq", 0);

        $mform->addElement('passwordunmask', 'password', get_string('password', 'paygw_yookassa'), ['size' => 20]);
        $mform->setType('password', PARAM_TEXT);
        $mform->addHelpButton('password', 'password', 'paygw_yookassa');

        $mform->addElement(
            'advcheckbox',
            'usedetails',
            get_string('usedetails', 'paygw_yookassa')
        );
        $mform->setType('usedetails', PARAM_INT);
        $mform->addHelpButton('usedetails', 'usedetails', 'paygw_yookassa');

        $mform->addElement(
            'advcheckbox',
            'showduration',
            get_string('showduration', 'paygw_yookassa')
        );
        $mform->setType('showduration', PARAM_INT);

        $mform->addElement(
            'advcheckbox',
            'fixcost',
            get_string('fixcost', 'paygw_yookassa')
        );
        $mform->setType('fixcost', PARAM_INT);
        $mform->addHelpButton('fixcost', 'fixcost', 'paygw_yookassa');

        $mform->addElement('text', 'suggest', get_string('suggest', 'paygw_yookassa'), ['size' => 10]);
        $mform->setType('suggest', PARAM_TEXT);
        $mform->disabledIf('suggest', 'fixcost', "neq", 0);

        $mform->addElement('text', 'maxcost', get_string('maxcost', 'paygw_yookassa'), ['size' => 10]);
        $mform->setType('maxcost', PARAM_TEXT);
        $mform->disabledIf('maxcost', 'fixcost', "neq", 0);

        global $CFG;
        $mform->addElement('html', '<div class="label-callback" style="background: pink; padding: 15px;">' .
                                    get_string('callback_url', 'paygw_yookassa') . '<br>');
        $mform->addElement('html', $CFG->wwwroot . '/payment/gateway/yookassa/callback.php<br>');
        $mform->addElement('html', get_string('callback_help', 'paygw_yookassa') . '</div><br>');

        $plugininfo = \core_plugin_manager::instance()->get_plugin_info('paygw_yookassa');
        $donate = get_string('donate', 'paygw_yookassa', $plugininfo);
        $mform->addElement('html', $donate);
    }

    /**
     * Validates the gateway configuration form.
     *
     * @param \core_payment\form\account_gateway $form
     * @param \stdClass $data
     * @param array $files
     * @param array $errors form errors (passed by reference)
     */
    public static function validate_gateway_form(
        \core_payment\form\account_gateway $form,
        \stdClass $data,
        array $files,
        array &$errors
    ): void {
        if ($data->enabled && empty($data->shopid)) {
            $errors['enabled'] = get_string('gatewaycannotbeenabled', 'payment');
        }
        if ($data->maxcost && $data->maxcost < $data->suggest) {
            $errors['maxcost'] = get_string('maxcosterror', 'paygw_yookassa');
        }
        if (!$data->suggest && $data->recurrentcost == 'suggest' && $data->recurrent) {
            $errors['suggest'] = get_string('suggesterror', 'paygw_yookassa');
        }
        if (!$data->recurrentperiod && $data->recurrent && !$data->recurrentday) {
            $errors['recurrentperiod'] = get_string('recurrentperioderror', 'paygw_yookassa');
        }
    }
}

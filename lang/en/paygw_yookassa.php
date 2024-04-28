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
 * Strings for component 'paygw_yookassa', language 'en'
 *
 * @package    paygw_yookassa
 * @copyright  2024 Alex Orlov <snickser@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'YooKassa payment';
$string['pluginname_desc'] = 'The yookassa plugin allows you to receive payments via yookassa.';
$string['gatewaydescription'] = 'YooKassa is an authorised payment gateway provider for processing credit card transactions.';
$string['gatewayname'] = 'YooKassa';
$string['callback_url'] = 'Notification URL:';
$string['callback_help'] = 'Copy this line and paste it into "HTTP notifications" in the YooKassa store settings, and enable "payment.succeeded" notifications there.';
$string['payment_success'] = 'Payment Successful';
$string['payment_error'] = 'Payment Error';
$string['password_success'] = 'Paymemt password accepted';
$string['password_error'] = 'Invalid payment password';
$string['paymore'] = 'If you want to donate more, simply enter your amount instead of the indicated amount.';
$string['sendpaymentbutton'] = 'Send payment via yookassa!';
$string['skippaymentbutton'] = 'Skip payment :(';
$string['abouttopay'] = 'You are about to pay for';
$string['payment'] = 'Donation';
$string['password'] = 'Password';
$string['passwordmode'] = 'Password';
$string['password_help'] = 'Using this password you can bypass the payback process. It can be useful when it is not possible to make a payment.';
$string['password_text'] = 'If you are unable to make a payment, then ask your curator for a password and enter it.';
$string['apikey'] = 'API Key';
$string['shopid'] = 'ShopID';
$string['istestmode'] = 'Test mode';
$string['suggest'] = 'Suggested cost';
$string['maxcost'] = 'Maximium cost';
$string['skipmode'] = 'Can skip payment';
$string['skipmode_help'] = 'This setting allows a payment bypass button, which can be useful in public courses with optional payment.';
$string['skipmode_text'] = 'If you are not able to make a donation through the payment system, you can click on this button.';
$string['fixdesc'] = 'Fixed payment comment';
$string['fixdesc_help'] = 'This setting sets a fixed comment for all payments.';
$string['showduration'] = 'Show duration of training';
$string['usedetails'] = 'Make it collapsible';
$string['usedetails_help'] = 'Display a button or password in a collapsed block.';
$string['usedetails_text'] = 'Click here if you are unable to donate.';
$string['internalerror'] = 'An internal error has occurred. Please contact us.';
$string['privacy:metadata'] = 'The yookassa plugin does not store any personal data.';
$string['vatcode'] = 'VAT rate';
$string['vatcode_help'] = 'VAT rate according to YooKass documentation.';
$string['taxsystemcode'] = 'Tax type';
$string['taxsystemcode_help'] = 'Type of tax system for generating checks:<br>
1 - General taxation system<br>
2 - Simplified (STS, income)<br>
3 - Simplified (STS, income minus expenses)<br>
4 - Single tax on imputed income (UTII)<br>
5 - Unified Agricultural Tax (UST)<br>
6 - Patent taxation system';

/* Payment systems */
$string['paymentmethod'] = 'Payment method';
$string['paymentmethod_help'] = 'Sets the payment method. Make sure the method you choose is supported by your store.';
$string['yookassa'] = 'YooKassa (all methods)';
$string['wallet'] = 'YooMoney wallet';
$string['plastic'] = 'VISA, MasterCard, MIR';
$string['sbp'] = 'SBP (QR-code)';

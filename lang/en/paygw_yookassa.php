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
$string['abouttopay'] = 'You are about to pay for';
$string['apikey'] = 'API Key';
$string['callback_help'] = 'Copy this line and paste it into "HTTP notifications" in the YooKassa store settings, and enable "payment.succeeded" and "payment.canceled" notifications there.';
$string['callback_url'] = 'Notification URL:';
$string['fixdesc'] = 'Fixed payment comment';
$string['fixdesc_help'] = 'This setting sets a fixed comment for all payments.';
$string['gatewaydescription'] = 'YooKassa is an authorised payment gateway provider for processing credit card transactions.';
$string['gatewayname'] = 'YooKassa';
$string['internalerror'] = 'An internal error has occurred. Please contact us.';
$string['istestmode'] = 'Test mode';
$string['maxcost'] = 'Maximium cost';
$string['password'] = 'Password';
$string['password_error'] = 'Invalid payment password';
$string['password_help'] = 'Using this password you can bypass the payback process. It can be useful when it is not possible to make a payment.';
$string['password_success'] = 'Paymemt password accepted';
$string['password_text'] = 'If you are unable to make a payment, then ask your curator for a password and enter it.';
$string['passwordmode'] = 'Password';
$string['payment'] = 'Donation';
$string['payment_error'] = 'Payment Error';
$string['payment_success'] = 'Payment Successful';
$string['paymore'] = 'If you want to donate more, simply enter your amount instead of the indicated amount.';
$string['pluginname'] = 'YooKassa payment';
$string['pluginname_desc'] = 'The yookassa plugin allows you to receive payments via yookassa.';
$string['sendpaymentbutton'] = 'Send payment via yookassa!';
$string['shopid'] = 'ShopID';
$string['showduration'] = 'Show duration of training';
$string['skipmode'] = 'Can skip payment';
$string['skipmode_help'] = 'This setting allows a payment bypass button, which can be useful in public courses with optional payment.';
$string['skipmode_text'] = 'If you are not able to make a donation through the payment system, you can click on this button.';
$string['skippaymentbutton'] = 'Skip payment :(';
$string['suggest'] = 'Suggested cost';
$string['taxsystemcode'] = 'Tax type';
$string['taxsystemcode_help'] = 'Type of tax system for generating checks:<br>
1 - General taxation system<br>
2 - Simplified (STS, income)<br>
3 - Simplified (STS, income minus expenses)<br>
4 - Single tax on imputed income (UTII)<br>
5 - Unified Agricultural Tax (UST)<br>
6 - Patent taxation system';
$string['usedetails'] = 'Make it collapsible';
$string['usedetails_help'] = 'Display a button or password in a collapsed block.';
$string['usedetails_text'] = 'Click here if you are unable to donate.';
$string['vatcode'] = 'VAT rate';
$string['vatcode_help'] = 'VAT rate according to YooKass documentation.';

/* Payment systems */
$string['paymentmethod'] = 'Payment method';
$string['paymentmethod_help'] = 'Sets the payment method. Make sure the method you choose is supported by your store.';
$string['yookassa'] = 'YooKassa (all methods)';
$string['wallet'] = 'YooMoney wallet';
$string['plastic'] = 'VISA, MasterCard, MIR';
$string['sbp'] = 'SBP (QR-code)';

$string['privacy:metadata'] = 'The yookassa plugin store some personal data.';
$string['privacy:metadata:paygw_yookassa:paygw_yookassa'] = 'Store some data';
$string['privacy:metadata:paygw_yookassa:shopid'] = 'Shopid';
$string['privacy:metadata:paygw_yookassa:apikey'] = 'ApiKey';
$string['privacy:metadata:paygw_yookassa:email'] = 'Email';
$string['privacy:metadata:paygw_yookassa:yookassa_plus'] = 'Send json data';
$string['privacy:metadata:paygw_yookassa:invoiceid'] = 'Invoice id';
$string['privacy:metadata:paygw_yookassa:courceid'] = 'Cource id';
$string['privacy:metadata:paygw_yookassa:groupnames'] = 'Group names';
$string['privacy:metadata:paygw_yookassa:success'] = 'Status';

$string['messagesubject'] = 'Payment notification ({$a})';

$string['message_success_completed'] = 'Hello {$a->firstname},
You transaction of payment id {$a->orderid} with cost of {$a->fee} {$a->currency} for "{$a->description}" is successfully completed.
If the item is not accessable please contact the administrator.';

$string['message_recurrent_created'] = 'Hello, {$a->firstname}!
Regular payment No. {$a->orderid} at {$a->localizedcost} is ready for payment.
You can disable regular payments in the Reports (payment) section in your personal profile {$a->$url}/user/profile.php';

$string['message_success_recurrent'] = 'Hello, {$a->firstname}!
Your regular payment transaction No. {$a->orderid} for {$a->localizedcost} has been successfully created. Thank you for your donation.
You can disable regular payments in the Reports (payment) section in your personal profile {$a->$url}/user/profile.php';

$string['message_recurrent_error'] = 'Hello, {$a->firstname}!
Regular payment No. {$a->orderid} for {$a->localizedcost} completed with an error.
The subscription has been disabled, please make a new payment to renew your subscription.';

$string['message_recurrent_notify'] = 'Hello, {$a->name}!
We remind you that the date of regular payment No. {$a->orderid} for {$a->localizedcost} is obtained.
Please ensure that the specified amount is available in your account, otherwise the subscription will not be renewed.
You can disable regular payments in the Reports (payments) section in your personal profile {$a->url}/user/profile.php';

$string['message_invoice_created'] = 'Hello {$a->firstname}!
Your payment link {$a->orderid} to {$a->fee} {$a->currency} has been successfully created.
You can pay it within an hour.';

$string['messageprovider:payment_receipt'] = 'Payment receipt';

$string['fixcost'] = 'Fixed price mode';
$string['fixcost_help'] = 'Disables the ability for students to pay with an arbitrary amount.';
$string['maxcosterror'] = 'The maximum price must be higher than the recommended price';

$string['recurrent'] = 'Enable recurring payments';
$string['recurrent_help'] = 'Enable recurring payments';
$string['recurrentperiod'] = 'Recurring payment frequency';
$string['recurrentperiod_help'] = 'It is advisable to specify the frequency no more than once a day. This involves executing the corresponding regular task in the task scheduler. More than once a day - only for tests.';
$string['recurrentperioderror'] = 'Specify the frequency';
$string['recurrentday'] = 'Day of payments';
$string['recurrentday_help'] = 'Sets the day of the month on which the next debits will occur. If not set, payments will be made according to a cyclic schedule';

$string['recurrentcost'] = 'Recurring payment cost';
$string['recurrentcost_help'] = 'Specify what price to charge when making a recurring payment:<br>
Paid - the one that was specified by the user when creating a regular payment.<br>
Item cost - the one specified in the settings of the payment module or course.<br>
Recommended - taken from the settings of this interface.';
$string['recurrentcost1'] = 'Paid';
$string['recurrentcost2'] = 'Item cost';
$string['recurrentcost3'] = 'Recommended price';
$string['suggesterror'] = 'Suggested price must be for recurring payment enabled';

$string['sendlinkmsg'] = 'Send payment link by email';
$string['sendlinkmsg_help'] = 'If enabled, a link to the invoice for payment will be sent to the users email.';

$string['savedebugdata'] = 'Save debug log';
$string['savedebugdata_help'] = 'Bank request and response data will be saved in {dataroot}/payment.log';

$string['noreportplugin'] = '<font color=red>The report_payments plugin is not installed, you will not be able to cancel recurring payments.</font>';

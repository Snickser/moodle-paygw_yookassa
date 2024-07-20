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

/** Notifications for paygw_yookassa.
 *
 * @package    paygw_yookassa
 * @copyright  2024 Alex Orlov <snickser@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace paygw_yookassa;

/** Notifications class.
 *
 * Handle notifications for users about their transactions.
 *
 * @package    paygw_yookassa
 * @copyright  2024 Alex Orlov <snickser@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class notifications {
    /**
     * Function that handle the notifications about transactions using yookassa payment gateway
     * and all kinds of responses.
     *
     * this function sending the message to the user and return the id of the message if needed
     * or false in case of error.
     *
     * @param int $userid
     * @param float $fee
     * @param string $currency
     * @param int $orderid
     * @param string $type
     * @param string $nextpay
     * @return int|false
     */
    public static function notify($userid, $fee, $currency, $orderid, $type = '', $nextpay = '') {
        global $DB, $CFG;

        // Get the user object for messaging and fullname.
        $user = \core_user::get_user($userid);
        if (empty($user) || isguestuser($user) || !empty($user->deleted)) {
            return false;
        }

        // Set the object wiht all informations to notify the user.
        $a = (object)[
            'fee'       => $fee, // The original cost.
            'currency'  => $currency,
            'orderid'   => $orderid,
            'firstname' => $user->firstname,
            'fullname'  => fullname($user),
            'url'       => $CFG->wwwroot,
            'nextpay'   => $nextpay,
            'localizedcost' => \core_payment\helper::get_cost_as_string($fee, $currency),
        ];

        $message = new \core\message\message();
        $message->component = 'paygw_yookassa';
        $message->name      = 'payment_receipt'; // The notification name from message.php.
        $message->userfrom  = \core_user::get_noreply_user(); // If the message is 'from' a specific user you can set them here.
        $message->userto    = $user;
        $message->subject   = get_string('messagesubject', 'paygw_yookassa', $type);
        switch ($type) {
            case 'Success completed':
                $messagebody = get_string('message_success_completed', 'paygw_yookassa', $a);
                break;
            case 'Success recurrent':
                $messagebody = get_string('message_success_recurrent', 'paygw_yookassa', $a);
                break;
            case 'Recurrent created':
                $messagebody = get_string('message_recurrent_created', 'paygw_yookassa', $a);
                break;
            case 'Recurrent error':
                $messagebody = get_string('message_recurrent_error', 'paygw_yookassa', $a);
                break;
            case 'Recurrent notify':
                $messagebody = get_string('message_recurrent_notify', 'paygw_yookassa', $a);
                break;
            case 'Invoice created':
                $messagebody = get_string('message_invoice_created', 'paygw_yookassa', $a);
                break;
            default:
                $messagebody = 'null';
        }

        $message->fullmessage       = $messagebody;
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml   = "<p>$messagebody</p>";
        $message->notification      = 1; // Because this is a notification generated from Moodle, not a user-to-user message.
        $message->contexturl        = ''; // A relevant URL for the notification.
        $message->contexturlname    = ''; // Link title explaining where users get to for the contexturl.
        $content = ['*' => ['header' => '', 'footer' => '']]; // Extra content for specific processor.
        $message->set_additional_content('email', $content);

        // Actually send the message.
        $messageid = message_send($message);

        return $messageid;
    }
}

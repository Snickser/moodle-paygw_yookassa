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
 * This module is responsible for PayNL content in the gateways modal.
 *
 * @module     paygw_yookassa/gateways_modal
 * @copyright  2024 Alex Orlov <snickser@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Templates from 'core/templates';
import Modal from 'core/modal';

/**
 * Show modal with the PayNL placeholder.
 *
 * @returns {Promise}
 */

const showModalWithPlaceholder = async() => {
    const modal = await Modal.create({
        body: await Templates.render('paygw_yookassa/button_placeholder', {}),
        show: true,
        removeOnClose: true,
    });
    modal.destroy();
};


/**
 * Process.
 *
 * @param {String} component
 * @param {String} paymentArea
 * @param {String} itemId
 * @param {String} description
 * @returns {Promise<>}
 */
export const process = (component, paymentArea, itemId, description) => {
    return showModalWithPlaceholder()
        .then(() => {
            location.href = M.cfg.wwwroot + '/payment/gateway/yookassa/method.php?' +
                'component=' + component +
                '&paymentarea=' + paymentArea +
                '&itemid=' + itemId +
                '&description=' + description;
            return new Promise(() => null);
        });
};

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
 * Local language pack from https://study.bhuri.ru
 *
 * @package    paygw_yookassa
 * @subpackage yookassa
 * @copyright  2024 Alex Orlov <snickser@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['abouttopay'] = 'Вы собираетесь пожертвовать на';
$string['apikey'] = 'API Key';
$string['callback_help'] = 'Скопируйте эту строку и вставьте в "HTTP-уведомления" в настройках магазина в ЮKassa, и включите там уведомления "payment.succeeded" и "payment.canceled".';
$string['callback_url'] = 'URL для уведомлений:';
$string['cost'] = 'Стоимость записи';
$string['currency'] = 'Валюта';
$string['fixdesc'] = 'Фиксированный комментарий платежа';
$string['fixdesc_help'] = 'Эта настройка устанавливает фиксированный комментарий для всех платежей, и отключает отображение описания комментария на странице платежа.';
$string['gatewaydescription'] = 'ЮKassa — авторизованный платежный шлюз для обработки транзакций по кредитным картам.';
$string['istestmode'] = 'Тестовый режим';
$string['maxcost'] = 'Максимальная цена';
$string['password'] = 'Резервный пароль';
$string['password_error'] = 'Введён неверный платёжный пароль';
$string['password_help'] = 'С помощью этого пароля можно обойти процесс отплаты. Может быть полезен когда нет возможности произвести оплату.';
$string['password_success'] = 'Платёжный пароль принят';
$string['password_text'] = 'Если у вас нет возможности сделать пожертвование, то попросите у вашего куратора пароль и введите его.';
$string['passwordmode'] = 'Разрешить ввод резервного пароля';
$string['payment'] = 'Пожертвование';
$string['payment_error'] = 'Ошибка оплаты';
$string['payment_success'] = 'Оплата успешно произведена';
$string['paymentserver'] = 'URL сервера оплаты';
$string['paymore'] = 'Если вы хотите пожертвовать больше, то просто впишите свою сумму вместо указанной.';
$string['pluginname'] = 'Платежи ЮKassa';
$string['pluginname_desc'] = 'Плагин yookassa позволяет получать платежи через yookassa.';
$string['sendpaymentbutton'] = 'Пожертвовать!';
$string['shopid'] = 'Идентификатор магазина';
$string['showduration'] = 'Показывать длительность обучения на странице';
$string['skipmode'] = 'Показать кнопку обхода платежа';
$string['skipmode_help'] = 'Эта настройка разрешает кнопку обхода платежа, может быть полезна в публичных курсах с необязательной оплатой.';
$string['skipmode_text'] = 'Если вы не имеете возможности совершить пожертвование через платёжную систему то можете нажать на эту кнопку.';
$string['skippaymentbutton'] = 'Не имею :(';
$string['suggest'] = 'Рекомендуемая цена';
$string['taxsystemcode'] = 'Тип налогообложения';
$string['taxsystemcode_help'] = 'Тип системы налогообложения для формирования чеков:<br>
1 - Общая система налогообложения<br>
2 - Упрощенная (УСН, доходы)<br>
3 - Упрощенная (УСН, доходы минус расходы)<br>
4 - Единый налог на вмененный доход (ЕНВД)<br>
5 - Единый сельскохозяйственный налог (ЕСН)<br>
6 - Патентная система налогообложения';
$string['usedetails'] = 'Показывать свёрнутым';
$string['usedetails_help'] = 'Прячет кнопку или пароль под сворачиваемый блок, если они включены.';
$string['usedetails_text'] = 'Нажмите тут если у вас нет возможности совершить пожертвование';
$string['vatcode'] = 'Ставка НДС';
$string['vatcode_help'] = 'Ставка НДС согласно API документации ЮКасса.';

/* Платежные системы */
$string['paymentmethod'] = 'Способ оплаты';
$string['paymentmethod_help'] = 'Устанавливает способ оплаты. Убедитесь, что выбранный метод поддерживается вашим магазином.';
$string['yookassa'] = 'ЮKassa (все доступные)';
$string['wallet'] = 'ЮMoney кошелёк';
$string['plastic'] = 'VISA, MasterCard, МИР';
$string['sbp'] = 'СБП (QR-код)';

$string['messagesubject'] = 'Уведомление о платеже ({$a})';

$string['message_success_completed'] = 'Здравствуйте, {$a->firstname}!
Платёжная транзакция № {$a->orderid} на {$a->localizedcost} успешно завершена. Спасибо за ваше пожертвование.
Если элемент курса недоступен, обратитесь в техподдержку сайта.';

$string['message_success_recurrent'] = 'Здравствуйте, {$a->firstname}!
Регулярный платёж № {$a->orderid} на {$a->localizedcost} успешно создан и оплачен. Спасибо за ваше пожертвование.
Следующий автоматический платёж назначен на {$a->nextpay}.
Отключить регулярные платежи можно в разделе Отчёты (оплата) в личном профиле {$a->url}/user/profile.php';

$string['message_recurrent_created'] = 'Здравствуйте, {$a->firstname}!
Регулярный платёж № {$a->orderid} на {$a->localizedcost} создан и передан в банк.
Отключить регулярные платежи можно в разделе Отчёты (оплата) в личном профиле {$a->url}/user/profile.php';

$string['message_recurrent_error'] = 'Здравствуйте, {$a->firstname}!
Регулярный платёж № {$a->orderid} на {$a->localizedcost} завершился с ошибкой.
Подписка будет отключена, для возобновления подписки произведите новую оплату.';

$string['message_recurrent_notify'] = 'Здравствуйте, {$a->firstname}!
Напоминаем о том, что приближается дата регулярного платежа № {$a->orderid} на {$a->localizedcost}.
Пожалуйста, обеспечьте наличие указанной суммы на счёте, иначе подписка не будет продлена.
Отключить регулярные платежи можно в разделе Отчёты (оплата) в личном профиле {$a->url}/user/profile.php';

$string['message_invoice_created'] = 'Здравствуйте, {$a->firstname}!
Платёжная ссылка {$a->orderid} на {$a->localizedcost} успешно создана.
Вы можете совершить платёж по ней в течении часа.';

$string['fixcost'] = 'Режим фиксированной цены';
$string['fixcost_help'] = 'Отключает для студентов возможность оплаты произвольной суммой.';
$string['maxcosterror'] = 'Максимальная цена должна быть выше рекомендуемой цены';

$string['recurrent'] = 'Включить регулярные платежи';
$string['recurrent_help'] = 'Регулярные (рекуррентные) платежи исполняются по таймеру без участия студента, данные первого платежа сохраняются на стороне банка и используются повторно, с некоторой периодичностью.';
$string['recurrentperiod'] = 'Периодичность регулярного платежа';
$string['recurrentperioderror'] = 'Укажите периодичность. Чаще чем раз в день - только для тестов!';

$string['recurrentcost'] = 'Стоимость регулярного платежа';
$string['recurrentcost_help'] = 'Указывает какую цену брать при проведении регулярного платежа:<br>
Уплаченная - та, что была указана пользователем при создании регулярного платежа.<br>
Стоимость элемента - та, которая указана в настройках платёжного модуля или курса.<br>
Рекумендуемая - берётся из настроек этого интерфейса.';
$string['recurrentcost1'] = 'Уплаченная';
$string['recurrentcost2'] = 'Стоимость элемента';
$string['recurrentcost3'] = 'Рекомендуемая цена';
$string['suggesterror'] = 'Рекомендуемая цена должна быть указана для включенного регулярного платежа';

$string['sendlinkmsg'] = 'Отправлять ссылку оплаты на почту';
$string['sendlinkmsg_help'] = 'Если включено, то ссылка на счёт для оплаты будет отправляться на почту пользователя.';

$string['savedebugdata'] = 'Сохранять debug лог';
$string['savedebugdata_help'] = 'Данные запросов и ответов банка будут сохраняться в /tmp/xxxx';

$string['noreportplugin'] = '<font color=red>Не установлен report_payments плагин, вы не сможете отменить регулярные платежи.</font>';

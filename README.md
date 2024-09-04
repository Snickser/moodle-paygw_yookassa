# YooKassa payment gateway plugin for Moodle.

[![](https://img.shields.io/github/v/release/Snickser/moodle-paygw_yookassa.svg)](https://github.com/Snickser/moodle-paygw_yookassa/releases)
[![Build Status](https://github.com/Snickser/moodle-paygw_yookassa/actions/workflows/moodle-ci.yml/badge.svg)](https://github.com/Snickser/moodle-paygw_yookassa/actions/workflows/moodle-ci.yml)

![alt text](https://raw.githubusercontent.com/Snickser/moodle-paygw_yookassa/809333a08256b99ff3a7143ce2fbc0ee9fba6b24/pix/img.svg)

https://yookassa.ru

## Возможности

+ Можно использовать пароль или кнопку для обхода платежа.
+ Сохраняет в базе номер курса и название группы студента.
+ Можно указать рекомендуемую цену, ограничить максимальную цену, или включить режим фиксированной цены.
+ Отображение продолжительности обучения (для enrol_fee и mod_gwpaymets), если она установлена.
+ Поддержка пароля из модуля курса (mod_gwpaymets).
+ Оповещение пользователя при успешном платеже.
+ Рекуррентные платежи (только совместно с моим report_payments).

## Рекомендации

+ Moodle 4.3+
+ Для записи в курс используйте мой пропатченный плагин "Зачисление за оплату" [enrol_fee](https://github.com/Snickser/moodle-enrol_fee/tree/dev).
+ Для контрольного задания используйте пропатченный мной плагин по ссылке [mod_gwpayments](https://github.com/Snickser/moodle-mod_gwpayments/tree/dev).
+ Для ограничения доступности используйте пропатченный мной плагин по ссылке [availability_gwpayments](https://github.com/Snickser/moodle-availability_gwpayments/tree/dev).
+ Плагин просмотра отчётов и отключения регулярных платежей [report_payments](https://github.com/Snickser/moodle-report_payments/tree/dev).

## INSTALLATION

Download the latest **paygw_yookassa.zip** and unzip the contents into the **/payment/gateway** directory. Or upload it from Moodle plugins adminnistration interface.<br>

1. Install the plugin
2. Enable the yookassa payment gateway
3. Create a new payment account
4. Configure the payment account against the yookassa gateway using your pay ID
5. Enable the 'Enrolment on Payment' enrolment method
6. Add the 'Enrolment on Payment' method to your chosen course
7. Set the payment account, enrolment fee, and currency

This plugin supports only basic functionality, but everything changes someday...

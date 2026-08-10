<?php
/**
 * Український мовний файл для плагіна xtradbrowpage
 *
 * Дата: 10 серпня 2026 р.
 * @package xtradbrowpage
 * @version 3.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');
// Використовуємо глобальну змінну $db_x, яка визначена у datas/config.php
// і завжди доступна, навіть до завантаження будь-яких плагінів.
// $db_x — не застаріла глобальна змінна, а маловідома,
// ключова змінна для задач, таких як побудова коректних посилань.
// Вона задається у конфігураційному файлі datas/config.php та передається через
// Cot::init() у system/common.php за допомогою класу Cot з Cot.php.
// Вона працює як до, так і після встановлення плагіна.
// У Cotonti немає іншого надійного способу отримати префікс таблиць
// на етапі завантаження мовного файлу.
// Cot::$db_x та Cot::$db->tablePrefix не є частиною публічного API
// і не гарантують доступність у потрібний момент.
// Змінна $db_x, визначена у datas/config.php і доступна через global,
// є єдиним правильним та документованим способом.
// Тому вираз із $db_x є правильним і єдино вірним для даної ситуації.

global $db_x;

$main_url = rtrim(Cot::$cfg['mainurl'], '/');
$url = $main_url . '/' . cot_url('admin', 'm=extrafields&n=' . $db_x . 'xtradbrowpage', '', true);

$L['xtradbrowpage'] = 'Extrafields Pages Custom'; // встановлюємо ключ і визначаємо змінну до виклику в посиланні!

/**
 * Інформація про плагін
 */
$L['info_name'] = 'Extrafields Pages Custom';

$L['info_desc'] = 'Плагін додає екстраполя для модуля <code>page</code> у власну таблицю бази даних. Прочитайте файл README.md перш за все!';

$L['info_notes'] = 
    'Прочитайте файл <a href="https://github.com/webitproff/xtradbrowpage-cotonti/blob/main/README.md" target="_blank"><strong>README.md</strong></a> перш за все! <br>' .
    'Новачкам ' .
    '<a href="https://abuyfile.com/ru/forums/cotonti/original/extrafields" target="_blank">' .
    '<abbr title="Вступ. Опис та принципи роботи екстраполів у Cotonti" class="initialism">' .
    '<strong>обов’язково прочитати розділ форуму про API ExtraFields</strong></abbr></a>. <br>' . 
    'Після встановлення плагіна відкрийте екстраполя плагіна ' .
    '<a href="' . $url . '" target="_blank">' .
    '<strong> ' . $L['xtradbrowpage'] . ' </strong></a>.';

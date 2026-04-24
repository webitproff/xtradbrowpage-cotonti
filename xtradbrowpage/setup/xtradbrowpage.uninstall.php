<?php
/**
 * xtradbrowpage.uninstall.php – Полное удаление данных плагина при деинсталляции
 *
 * Удаляет:
 * - Все записи в таблице cot_extra_fields, относящиеся к таблице $db_xtradbrowpage
 * - Саму таблицу $db_xtradbrowpage (DROP TABLE IF EXISTS)
 *
 * @package xtradbrowpage
 * @version 2.7.8
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

// Подключаем файл плагина для регистрации глобальных переменных ($db_xtradbrowpage)
require_once cot_incfile('xtradbrowpage', 'plug');

global $db, $db_extra_fields, $db_xtradbrowpage;

// 1. Удаляем все определения экстраполей для нашей таблицы
$db->delete($db_extra_fields, "field_location = ?", [$db_xtradbrowpage]);

// 2. Удаляем саму таблицу (на случай, если SQL-файл не сработал или префикс отличается)
$db->query("DROP TABLE IF EXISTS `{$db_xtradbrowpage}`");
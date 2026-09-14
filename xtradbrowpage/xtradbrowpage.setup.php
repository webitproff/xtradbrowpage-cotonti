<?php
/* ====================
[BEGIN_COT_EXT]
Code=xtradbrowpage
Name=Custom Extrafields for Pages
Description=Adds custom extrafields to individual pages stored in separate table
Version=3.0.0
Date=2026-08-13
Author=webitproff
Copyright=Copyright (c) 2026 webitproff | https://github.com/webitproff
Notes=BSD License
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=
Requires_modules=page
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
xtradbrowpage_i18n_use=01:radio::1:Мультиязычность полей активировать и использовать
xtradbrowpage_i18n_lang_code_default=02:string::ru:Код основного языка сайта (должен совпадать с <code>$cfg['defaultlang']</code>)
xtradbrowpage_i18n_lang_code_first=03:string::en:Код первого дополнительного языка
xtradbrowpage_i18n_lang_code_first_use=04:radio::1:Использовать первый дополнительный язык
xtradbrowpage_i18n_lang_code_second=05:string::ua:Код второго дополнительного языка
xtradbrowpage_i18n_lang_code_second_use=06:radio::0:Использовать второй дополнительный язык
perpage=07:string::5:Items per page in admin list
xtradbrowpage_showallitems=08:radio::1:Show all items (even without extrafields) in admin list
[END_COT_EXT_CONFIG]
==================== */

defined('COT_CODE') or die('Wrong URL');



/**
 * xtradbrowpage.setup.php - Register data in $db_core and $db_config. Setup & Config File for the Plugin xtradbrowpage
 *
 * xtradbrowpage plugin for Cotonti v1.+ (https://github.com/Cotonti/Cotonti), PHP 8.5+, MySQL 8.4
 * Filename: xtradbrowpage.setup.php
 *
 * Date: Aug 10Th, 2026
 * @package xtradbrowpage
 * @version 3.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

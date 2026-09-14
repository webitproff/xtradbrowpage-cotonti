<?php
/**
 * Russian Language File for xtradbrowpage Plugin
 *
 * Date: Aug 10Th, 2026
 * @package xtradbrowpage
 * @version 3.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');
// использовать глобальную переменную $db_x, которая определена в datas/config.php 
// и доступна абсолютно всегда, ещё до загрузки любых плагинов 
// $db_x — это не устаревшая глобальная переменная, а малоизвестная, 
// ключевая переменная для например таких задач для корректно ссылки.
// задаётся в конфиге datas/config.php и пробрасывается через 
// Cot::init() в system/common.php используя class Cot из Cot.php . 
// Она работает и до установки плагина, и после.
// В Cotonti нет других надёжных способов получить префикс таблиц на этапе загрузки языкового файла. 
// Cot::$db_x и Cot::$db->tablePrefix не являются частью публичного API и не гарантируют доступность в нужный момент. 
// Переменная $db_x, определённая в datas/config.php и доступная через global, — это единственный корректный и документированный способ. 
// Поэтому выражение с $db_x является правильным и единственно верным для данной ситуации.

global $db_x;

$main_url = rtrim(Cot::$cfg['mainurl'], '/');
$url = $main_url . '/' . cot_url('admin', 'm=extrafields&n=' . $db_x . 'xtradbrowpage', '', true);

// устанавливаем ключ и определяем перевенную до вызова в ссылке в $L['info_notes']!
$L['xtradbrowpage'] = 'Extrafields Pages Custom'; 


// Plugin configuration


/**
 * Plugin Info
 */
$L['info_name'] = 'Extrafields Pages Custom';

$L['info_desc'] = 'Плагин добавляет экстраполя для модуля <code>page</code> в свою собственную таблицу БД. Файл README.md читать прежде всего!';

$L['info_notes'] = 
    'Файл <a href="https://github.com/webitproff/xtradbrowpage-cotonti/blob/main/README.md" target="_blank"><strong>README.md</strong></a> читать прежде всего! <br>' .
    'Новичкам ' .
    '<a href="https://abuyfile.com/ru/forums/cotonti/original/extrafields" target="_blank">' .
    '<abbr title="Введение. Описание и принципы работы экстраполей в Cotonti" class="initialism">' .
    '<strong>обязательно читать раздел форума об API ExtraFields</strong></abbr></a>. <br>' . 
    'После установки плагина, открыть экстраполя плагина ' .
    '<a href="' . $url . '" target="_blank">' .
    '<strong> ' . $L['xtradbrowpage'] . ' </strong></a>.';


// Настройки плагина
$L['cfg_xtradbrowpage_i18n_use'] = 'Мультиязычность полей активировать и использовать';
$L['cfg_xtradbrowpage_i18n_lang_code_default'] = 'Код основного языка сайта';
$L['cfg_xtradbrowpage_i18n_lang_code_first'] = 'Код первого дополнительного языка';
$L['cfg_xtradbrowpage_i18n_lang_code_first_use'] = 'Использовать первый дополнительный язык';
$L['cfg_xtradbrowpage_i18n_lang_code_second'] = 'Код второго дополнительного языка';
$L['cfg_xtradbrowpage_i18n_lang_code_second_use'] = 'Использовать второй дополнительный язык';
$L['cfg_perpage'] = 'Элементов на страницу';
$L['cfg_xtradbrowpage_showallitems'] = 'Показывать все страницы (даже без экстраполей)';

// Интерфейс админки
$L['xtradbrowpage_name'] = 'Extrafields Pages Custom';
$L['xtradbrowpage_tab_stats'] = 'Статистика';
$L['xtradbrowpage_tab_edit'] = 'Редактировать';
$L['xtradbrowpage_tab_i18n'] = 'Редактировать + Переводы';
$L['xtradbrowpage_stats_total_pages'] = 'Всего страниц';
$L['xtradbrowpage_stats_xtra_rows'] = 'Записей в xtradbrowpage';
$L['xtradbrowpage_stats_filled'] = 'Заполненных записей';
$L['xtradbrowpage_extrafields_info'] = 'Параметры экстраполей';
$L['xtradbrowpage_field_name'] = 'Имя поля';
$L['xtradbrowpage_field_type'] = 'Тип';
$L['xtradbrowpage_field_description'] = 'Описание';
$L['xtradbrowpage_field_variants'] = 'Варианты';
$L['xtradbrowpage_field_params'] = 'Параметры';
$L['xtradbrowpage_field_default'] = 'По умолчанию';
$L['xtradbrowpage_field_required'] = 'Обязательное';
$L['xtradbrowpage_field_enabled'] = 'Включено';
$L['xtradbrowpage_no_extrafields'] = 'Нет зарегистрированных экстраполей';
$L['xtradbrowpage_search_sq'] = 'Поиск по тексту';
$L['xtradbrowpage_filter_id'] = 'ID страницы';
$L['xtradbrowpage_search_cat'] = 'Категория';
$L['xtradbrowpage_search_in_title'] = 'Заголовок';
$L['xtradbrowpage_search_in_desc'] = 'Описание';
$L['xtradbrowpage_search_in_alias'] = 'Алиас';
$L['xtradbrowpage_search_btn'] = 'Фильтр';
$L['xtradbrowpage_search_reset'] = 'Сброс';
$L['xtradbrowpage_page_title'] = 'Заголовок страницы';
$L['xtradbrowpage_no_records'] = 'Нет страниц';
$L['xtradbrowpage_i18n_active'] = 'Мультиязычность включена';
$L['xtradbrowpage_i18n_disabled'] = 'Мультиязычность отключена';
$L['xtradbrowpage_updated'] = 'Обновлено записей: %d';
$L['xtradbrowpage_search_result_msg'] = 'Найдено %s по запросу %s';
$L['xtradbrowpage_search_result_none'] = 'Ничего не найдено по запросу %s';
$L['xtradbrowpage_search_declen'] = 'записей,запись,записи';

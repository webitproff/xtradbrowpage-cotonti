<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=pagetags.main
[END_COT_EXT]
==================== */

/**
 * Overrides page tags in cot_generate_pagetags() function
 * Теги для использования в cot_generate_pagetags() (списки, новости, etc.): plugins/xtradbrowpage/xtradbrowpage.pagetags.php
 * Хук pagetags.main. Добавляет в общий массив тегов переменные {PAGE_XTRA_ИМЯПОЛЯ} и т.д.
 * 
 * Date: Apr 25Th, 2026
 * @package xtradbrowpage
 * @version 2.7.8
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 * @see cot_generate_pagetags()
 * Хук pagetags.main вызывается внутри функции cot_generate_pagetags(). 
 * В этой функции локально определена переменная $temp_array, и когда через include подключается ваш файл, 
 * он выполняется в том же пространстве имён функции – поэтому $temp_array доступна напрямую, без объявления global.
 * @var array<string, mixed> $page_data
 */
 
/* 
 * Список статей (page.list.tpl) – через pagetags
 * 
 * Теги будут иметь префикс, заданный в cot_generate_pagetags(). Обычно это PAGE_, поэтому:
 * 
 * <!-- IF {LIST_ROW_XTRA_EVENT_NAME} -->
 *     <small>{LIST_ROW_XTRA_EVENT_NAME_TITLE}: {LIST_ROW_XTRA_EVENT_NAME}</small>
 * <!-- ENDIF -->
*/

defined('COT_CODE') or die('Wrong URL.');
require_once cot_incfile('xtradbrowpage', 'plug');

$extrafields = xtradbrowpage_getExtrafields();
if (!empty($extrafields) && !empty($page_data['page_id'])) {
    $xtra_data = xtradbrowpage_load($page_data['page_id']);
    if ($xtra_data) {
        foreach ($extrafields as $exfld) {
            $tag = 'XTRA_' . strtoupper($exfld['field_name']);
            $value = $xtra_data[$exfld['field_name']] ?? null;
            $temp_array[$tag] = cot_build_extrafields_data('xtra', $exfld, $value, $page_data['page_parser']);
            $temp_array[$tag . '_TITLE'] = cot_extrafield_title($exfld, 'xtra_');
            $temp_array[$tag . '_VALUE'] = $value;
        }
    } else {
        // Сброс тегов, чтобы избежать "наследования" значений в списках
        foreach ($extrafields as $exfld) {
            $tag = 'XTRA_' . strtoupper($exfld['field_name']);
            $temp_array[$tag] = '';
            $temp_array[$tag . '_TITLE'] = '';
            $temp_array[$tag . '_VALUE'] = '';
        }
    }
}
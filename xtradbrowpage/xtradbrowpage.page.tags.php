<?php
/* ====================
[BEGIN_COT_EXT] 
Hooks=page.tags
[END_COT_EXT]
==================== */

/**
 * вывод на странице просмотра: plugins/xtradbrowpage/xtradbrowpage.page.tags.php
 * Хук page.tags. Позволяет вывести все поля через блок <!-- BEGIN: XTRA_EXTRAFLD -->, 
 * а также назначает индивидуальные теги {XTRA_ИМЯПОЛЯ}
 *
 * Date: Apr 25Th, 2026
 * @package xtradbrowpage
 * @version 2.7.8
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */
/* 
 * Страница просмотра (page.tpl)
 * 
 * Динамический вывод всех полей:
 * 
 * <!-- BEGIN: XTRA_EXTRAFLD -->
 * <div class="extrafield">
 *     <strong>{XTRA_EXTRAFIELD_TITLE}:</strong>
 *     <span>{XTRA_EXTRAFIELD_VALUE}</span>
 * </div>
 * <!-- END: XTRA_EXTRAFLD -->
 * 
 * Ручной (индивидуальный) вывод конкретного поля:
 * 
 * <!-- IF {XTRA_EVENT_NAME} -->
 *     <p>{XTRA_EVENT_NAME_TITLE}: {XTRA_EVENT_NAME}</p>
 * <!-- ENDIF -->
 * 
 */
 
 
defined('COT_CODE') or die('Wrong URL.');
require_once cot_incfile('xtradbrowpage', 'plug');

if (!empty($pag['page_id'])) {
    $extrafields = xtradbrowpage_getExtrafields();
    if (!empty($extrafields)) {
        $xtra_data = xtradbrowpage_load($pag['page_id']);
        if ($xtra_data) {
            foreach ($extrafields as $exfld) {
                $tag = mb_strtoupper($exfld['field_name']);
                $value = $xtra_data[$exfld['field_name']] ?? null;

                $t->assign([
                    'XTRA_' . $tag             => cot_build_extrafields_data('xtra', $exfld, $value, $pag['page_parser']),
                    'XTRA_' . $tag . '_TITLE'  => cot_extrafield_title($exfld, 'xtra_'),
                    'XTRA_' . $tag . '_VALUE'  => $value,
                    'XTRA_EXTRAFIELD_TITLE'    => cot_extrafield_title($exfld, 'xtra_'),
                    'XTRA_EXTRAFIELD_VALUE'    => cot_build_extrafields_data('xtra', $exfld, $value, $pag['page_parser']),
                ]);
                $t->parse('MAIN.XTRA_EXTRAFLD');
            }
        } else {
            // Очистка, если данных нет
            foreach ($extrafields as $exfld) {
                $tag = mb_strtoupper($exfld['field_name']);
                $t->assign([
                    'XTRA_' . $tag => '',
                    'XTRA_' . $tag . '_TITLE' => '',
                    'XTRA_' . $tag . '_VALUE' => '',
                    'XTRA_EXTRAFIELD_TITLE'   => '',
                    'XTRA_EXTRAFIELD_VALUE'   => '',
                ]);
                $t->parse('MAIN.XTRA_EXTRAFLD');
            }
        }
    }
}
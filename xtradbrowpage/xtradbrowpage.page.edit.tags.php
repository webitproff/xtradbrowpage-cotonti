<?php
/* ====================
  [BEGIN_COT_EXT]
  Hooks=page.edit.tags
  [END_COT_EXT]
==================== */

/**
 * Вывод полей в форме редактирования: plugins/xtradbrowpage/xtradbrowpage.page.edit.tags.php
 * Хук page.edit.tags. Отображает все extrafields с их текущими значениями (если есть) в форме редактирования страницы.
 *
 * Date: Apr 25Th, 2026
 * @package xtradbrowpage
 * @version 2.7.8
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

/* 
 * Форма редактирования страницы (page.edit.tpl)
 * 
 * <!-- BEGIN: XTRA_EXTRAFLD -->
 * <div class="form-group">
 *     <label>{PAGEEDIT_FORM_XTRA_EXTRAFLD_TITLE}</label>
 *     {PAGEEDIT_FORM_XTRA_EXTRAFLD}
 * </div>
 * <!-- END: XTRA_EXTRAFLD -->
 * 
 */
 
defined('COT_CODE') or die('Wrong URL.');
require_once cot_incfile('xtradbrowpage', 'plug');

$extrafields = xtradbrowpage_getExtrafields();
if (!empty($extrafields) && isset($pag['page_id'])) {
    $xtra_data = xtradbrowpage_load($pag['page_id']);
    foreach ($extrafields as $exfld) {
        $fieldName = 'rxtra_' . $exfld['field_name'];
        $value = $xtra_data[$exfld['field_name']] ?? null;
        $element = cot_build_extrafields($fieldName, $exfld, $value);
        $title = cot_extrafield_title($exfld, 'xtra_');

        $t->assign([
            'PAGEEDIT_FORM_XTRA_' . strtoupper($exfld['field_name'])         => $element,
            'PAGEEDIT_FORM_XTRA_' . strtoupper($exfld['field_name']) . '_TITLE' => $title,
            'PAGEEDIT_FORM_XTRA_EXTRAFLD'                                    => $element,
            'PAGEEDIT_FORM_XTRA_EXTRAFLD_TITLE'                              => $title,
        ]);
        $t->parse('MAIN.XTRA_EXTRAFLD');
    }
}
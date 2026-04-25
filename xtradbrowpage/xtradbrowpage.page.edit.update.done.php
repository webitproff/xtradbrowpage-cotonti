<?php
/* ====================
  [BEGIN_COT_EXT]
  Hooks=page.edit.update.done
  [END_COT_EXT]
==================== */

/**
 * Сохранение данных после обновления страницы: plugins/xtradbrowpage/xtradbrowpage.page.edit.update.done.php
 * Хук page.edit.update.done. Вызывается после успешного обновления страницы. Сохраняет значения extrafields в cot_xtradbrowpage.
 *
 * Date: Apr 25Th, 2026
 * @package xtradbrowpage
 * @version 2.7.8
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL.');
require_once cot_incfile('xtradbrowpage', 'plug');

if (isset($id) && $id > 0) {
    $extrafields = xtradbrowpage_getExtrafields();
    if (!empty($extrafields)) {
        $xtra_data = xtradbrowpage_load($id) ?: [];
        $data = [];
        foreach ($extrafields as $exfld) {
            $fieldName = $exfld['field_name'];
            $inputName = 'rxtra_' . $fieldName;
            $oldValue = $xtra_data[$fieldName] ?? '';
            $data[$fieldName] = cot_import_extrafields($inputName, $exfld, 'P', $oldValue, 'xtra_');
        }
        xtradbrowpage_save($id, $data);
        // === ВАЖНО: перемещаем загруженные файлы в целевую папку ===
        cot_extrafield_movefiles();
    }
}

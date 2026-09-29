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
 * Date: Sep 29Th, 2026
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
			$oldValue  = $xtra_data[$fieldName] ?? '';

			// Импортируем только если поле реально пришло в запросе:
			// - обычные поля/чекбоксы/чеклистбоксы/селекты -> $_POST
			// - файловые поля -> либо $_POST (удаление), либо $_FILES (загрузка)
			if (
				isset($_POST[$inputName]) ||
				isset($_POST['rdel_' . $inputName]) ||
				(isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] !== UPLOAD_ERR_NO_FILE)
			) {
				$data[$fieldName] = cot_import_extrafields($inputName, $exfld, 'P', $oldValue, 'xtra_');
			} else {
				// Поле не отправлено — сохраняем прежнее значение, не трогаем ядро
				$data[$fieldName] = $oldValue;
			}
		}
        xtradbrowpage_save($id, $data);
        // === ВАЖНО: перемещаем загруженные файлы в целевую папку ===
        cot_extrafield_movefiles();
    }
}

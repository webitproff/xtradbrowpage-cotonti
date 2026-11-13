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
 * Date: Nov 13Th, 2026
 * @package xtradbrowpage
 * @version 2.7.9
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

		$hasSubmittedExtrafield = false;

		// Проверяем, пришло ли хотя бы одно дополнительное поле.
		foreach ($extrafields as $exfld) {
			$inputName = 'rxtra_' . $exfld['field_name'];

			if (
				isset($_POST[$inputName]) ||
				isset($_POST['rdel_' . $inputName]) ||
				(isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] !== UPLOAD_ERR_NO_FILE)
			) {
				$hasSubmittedExtrafield = true;
				break;
			}
		}

		foreach ($extrafields as $exfld) {
			$fieldName = $exfld['field_name'];
			$fieldType = $exfld['field_type'] ?? '';
			$inputName = 'rxtra_' . $fieldName;
			$oldValue  = $xtra_data[$fieldName] ?? '';

			if (
				isset($_POST[$inputName]) ||
				isset($_POST['rdel_' . $inputName]) ||
				(isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] !== UPLOAD_ERR_NO_FILE)
			) {
				$data[$fieldName] = cot_import_extrafields(
					$inputName,
					$exfld,
					'P',
					$oldValue,
					'xtra_'
				);
			} elseif ($fieldType === 'checkbox' && $hasSubmittedExtrafield) {
				// Checkbox не передаётся в POST, если он снят.
				$data[$fieldName] = 0;
			}
		}
		if (!empty($data)) {
			xtradbrowpage_save($id, $data);
		}
        // === ВАЖНО: перемещаем загруженные файлы в целевую папку ===
        cot_extrafield_movefiles();
    }
}

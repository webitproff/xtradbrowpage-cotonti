<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=pagemassedit.massedit.loop
Order=10
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL');
require_once cot_incfile('xtradbrowpage', 'plug');

$extrafields = xtradbrowpage_getExtrafields();
if (!empty($extrafields)) {
    foreach ($extrafields as $exfld) {
        $fname = $exfld['field_name'];
        $value = $row[$fname] ?? null;
        $inputName = 'rxtra_' . $fname . '[' . $id . ']';
        $fieldHtml = cot_build_extrafields($inputName, $exfld, $value);
        $t->assign('XTRA_COLUMN_HTML', $fieldHtml);
        $t->parse('MAIN.MANAGE_ROW.XTRA_COLUMN');
    }
}
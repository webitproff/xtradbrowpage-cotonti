<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=pagemassedit.massedit.save
Order=10
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');
require_once cot_incfile('xtradbrowpage', 'plug');

if (!empty($ids)) {
    $extrafields = xtradbrowpage_getExtrafields();
    if (empty($extrafields)) return;

    foreach ($ids as $id) {
        $id = (int)$id;
        $xtra_data = xtradbrowpage_load($id) ?: [];
        $data = [];
        $changed = false;

        foreach ($extrafields as $exfld) {
            $fname    = $exfld['field_name'];
            $postKey  = 'rxtra_' . $fname;
            $oldValue = $xtra_data[$fname] ?? '';
            $newValue = $oldValue;

            if ($exfld['field_type'] == 'checkbox') {
                // Чекбокс не отправляется, если снят – считаем, что 0
                $newValue = isset($_POST[$postKey][$id]) ? 1 : 0;
            } elseif (isset($_POST[$postKey][$id])) {
                $raw = $_POST[$postKey][$id];

                switch ($exfld['field_type']) {
                    case 'checklistbox':
                        if (is_array($raw)) {
                            unset($raw['nullval']);
                            $newValue = implode(',', $raw);
                        } else {
                            $newValue = '';
                        }
                        break;

                    case 'datetime':
                        if (is_array($raw)) {
                            $year   = isset($raw['year'])   ? (int)$raw['year']   : 0;
                            $month  = isset($raw['month'])  ? (int)$raw['month']  : 0;
                            $day    = isset($raw['day'])    ? (int)$raw['day']    : 0;
                            $hour   = isset($raw['hour'])   ? (int)$raw['hour']   : 0;
                            $minute = isset($raw['minute']) ? (int)$raw['minute'] : 0;
                            if ($year && $month && $day) {
                                $newValue = mktime($hour, $minute, 0, $month, $day, $year);
                            } else {
                                $newValue = 0;
                            }
                        } else {
                            $newValue = (int)$raw;
                        }
                        break;

                    default:
                        $newValue = is_array($raw) ? implode(',', $raw) : trim($raw);
                        break;
                }
            } else {
                // поле не отправлено – для обычных полей оставляем старое значение
                $newValue = $oldValue;
            }

            $data[$fname] = $newValue;
            if ($newValue != $oldValue) {
                $changed = true;
            }
        }

        if ($changed) {
            xtradbrowpage_save($id, $data);
        }
    }

    cot_extrafield_movefiles();
}
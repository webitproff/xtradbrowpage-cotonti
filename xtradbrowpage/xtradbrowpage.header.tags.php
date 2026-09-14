<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=header.tags
[END_COT_EXT]
==================== */

/**
 * Вывод в «шапке» сайта: plugins/xtradbrowpage/xtradbrowpage.header.tags.php
 * Хук header.tags. Позволяет использовать теги {XTRA_HEADER_ИМЯПОЛЯ} для SEO-тегов и других элементов <head>.
 * Пример вывода:
 * <!-- IF {XTRA_HEADER_EVENT_NAME} -->
 * <meta name="event" content="{XTRA_HEADER_EVENT_NAME}" />
 * <!-- ENDIF -->
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

if ($env['ext'] == 'page' && isset($id) && $id > 0) {
    $extrafields = xtradbrowpage_getExtrafields();
    if (!empty($extrafields)) {
        $xtra_data = xtradbrowpage_load($id);
        $parser = Cot::$cfg['page']['parser'];
        if ($xtra_data) {
            $page_info = Cot::$db->query("SELECT page_parser FROM " . Cot::$db->pages . " WHERE page_id = ?", [$id])->fetch();
            if ($page_info) {
                $parser = $page_info['page_parser'] ?? $parser;
            }
        }
        foreach ($extrafields as $exfld) {
            $tag = 'XTRA_HEADER_' . strtoupper($exfld['field_name']);
            $value = $xtra_data[$exfld['field_name']] ?? '';
            $t->assign([
                $tag => htmlspecialchars(cot_build_extrafields_data('xtra', $exfld, $value, $parser), ENT_QUOTES, 'UTF-8'),
                $tag . '_TITLE' => cot_extrafield_title($exfld, 'xtra_'),
                $tag . '_VALUE' => $value,
            ]);
        }
    }
}
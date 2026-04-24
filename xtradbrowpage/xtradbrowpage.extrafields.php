<?php
/* ====================
  [BEGIN_COT_EXT]
  Hooks=admin.extrafields.first
  [END_COT_EXT]
==================== */

/**
 * xtradbrowpage Plugin
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

$extra_whitelist[$db_xtradbrowpage] = [
    'name'    => $db_xtradbrowpage,
    'caption' => $L['xtradbrowpage'],
    'type'    => 'plug',
    'code'    => 'xtradbrowpage',
    'tags'    => [
        'page.edit.tpl' => '{PAGEEDIT_FORM_XTRA_XXXXX}, {PAGEEDIT_FORM_XTRA_XXXXX_TITLE}',
        'page.tpl'      => '{XTRA_XXXXX}, {XTRA_XXXXX_TITLE}',
    ]
];
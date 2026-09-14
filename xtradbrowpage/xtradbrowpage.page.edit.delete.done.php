<?php
/* ====================
  [BEGIN_COT_EXT]
  Hooks=page.edit.delete.done
  [END_COT_EXT]
==================== */

/**
 * Удаление связанных данных при удалении страницы: plugins/xtradbrowpage/xtradbrowpage.page.edit.delete.done.php
 * Хук page.edit.delete.done.
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
    Cot::$db->delete(Cot::$db->xtradbrowpage, "itempagid = ?", [$id]);
}
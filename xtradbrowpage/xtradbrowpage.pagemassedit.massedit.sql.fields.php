<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=pagemassedit.massedit.sql.fields
Order=10
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL');
require_once cot_incfile('xtradbrowpage', 'plug');

// Добавляем все колонки таблицы xtradbrowpage в общий список полей
$selectFields[] = Cot::$db->xtradbrowpage . '.*';
// Сообщаем pagemassedit, что нужен LEFT JOIN с этой таблицей
$needXtraJoin = Cot::$db->xtradbrowpage;
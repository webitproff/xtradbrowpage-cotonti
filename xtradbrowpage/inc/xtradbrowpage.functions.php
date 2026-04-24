<?php
/**
 * xtradbrowpage API
 * Функции плагина: plugins/xtradbrowpage/inc/xtradbrowpage.functions.php
 * Date: Apr 25Th, 2026
 * @package xtradbrowpage
 * @version 2.7.8
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_langfile('xtradbrowpage', 'plug');

require_once cot_incfile('extrafields');
Cot::$db->registerTable('xtradbrowpage');

/**
 * Возвращает массив зарегистрированных extrafields для таблицы cot_xtradbrowpage
 */
function xtradbrowpage_getExtrafields() {
    return Cot::$extrafields[Cot::$db->xtradbrowpage] ?? [];
}

/**
 * Загружает запись из cot_xtradbrowpage по itempagid
 * @param int $page_id ID страницы из модуля Pages
 * @return array|null
 */
function xtradbrowpage_load($page_id) {
    $res = Cot::$db->query("SELECT * FROM " . Cot::$db->xtradbrowpage . " WHERE itempagid = ?", [$page_id]);
    return $res->fetch();
}

/**
 * Сохраняет данные (INSERT или UPDATE) в таблицу cot_xtradbrowpage
 * @param int $page_id ID страницы из модуля Pages
 * @param array $data Ассоциативный массив с именами полей extrafields (без префикса `ipage_` или `page_`)
 */
function xtradbrowpage_save($page_id, $data) {
    $exists = Cot::$db->query(
        "SELECT COUNT(*) FROM " . Cot::$db->xtradbrowpage . " WHERE itempagid = ?",
        [$page_id]
    )->fetchColumn() > 0;

    if ($exists) {
        Cot::$db->update(Cot::$db->xtradbrowpage, $data, "itempagid = ?", [$page_id]);
    } else {
        $data['itempagid'] = $page_id;
        Cot::$db->insert(Cot::$db->xtradbrowpage, $data);
    }
}
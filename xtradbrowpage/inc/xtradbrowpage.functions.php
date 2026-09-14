<?php
/**
 * центральные функции плагина (v3.0.0 с поддержкой i18n)
 *
 * Файл:     plugins/xtradbrowpage/inc/xtradbrowpage.functions.php
 * Назначение: предоставляет базовые операции чтения/записи в таблицу `cot_xtradbrowpage`,
 *            а также инициализирует глобальную переменную таблицы и подключает
 *            языковые файлы и API экстраполей.
 *
 * Важные замечания по архитектуре:
 *   - Таблица `cot_xtradbrowpage` намеренно создана с первичным ключом `itempagid`.
 *     Это гарантирует, что при вызове `cot_extrafield_add()` для этой таблицы Cotonti НЕ
 *     добавляет префикс к именам создаваемых колонок. Физические имена колонок
 *     точно совпадают с именами экстраполей (`event_name`, `interests` и т.д.).
 *   - Связь с элементом модуля Pages осуществляется через значение `itempagid`, которое всегда равно
 *     `page_id` из таблицы `cot_pages`. Благодаря ON DELETE CASCADE удаление страницы
 *     автоматически удаляет строку из нашей таблицы на уровне БД.
 *
 * Custom Extrafields for Pages plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4
 *
 * Date: Aug 13, 2026
 * @package xtradbrowpage
 * @version 3.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

// Подключаем языковой файл плагина и стандартный API экстраполей
require_once cot_langfile('xtradbrowpage', 'plug');
require_once cot_incfile('page', 'module'); 
require_once cot_incfile('extrafields');

// Регистрируем таблицы в реестре Cotonti
Cot::$db->registerTable('xtradbrowpage');
Cot::$db->registerTable('xtradbrowpage_i18n');

/**
 * Возвращает массив зарегистрированных экстраполей для таблицы cot_xtradbrowpage
 *
 * @return array Ассоциативный массив, где ключ — имя поля, значение — конфигурация поля.
 */
function xtradbrowpage_getExtrafields()
{
    return Cot::$extrafields[Cot::$db->xtradbrowpage] ?? [];
}

/**
 * Загружает запись дополнительных полей страницы из таблицы cot_xtradbrowpage
 *
 * @param int $page_id ID страницы из модуля Pages (равен `itempagid`)
 * @return array|null Ассоциативный массив всех полей строки или null, если запись не найдена.
 */
function xtradbrowpage_load($page_id)
{
    $res = Cot::$db->query(
        "SELECT * FROM " . Cot::$db->xtradbrowpage . " WHERE itempagid = ?",
        [$page_id]
    );
    return $res->fetch();
}

/**
 * Сохраняет (INSERT или UPDATE) запись дополнительных полей страницы
 *
 * Логика работы:
 *   1. Нормализует значения: если поле содержит массив, преобразует его в строку через запятую.
 *   2. Проверяет, существует ли уже запись для данного `$page_id`.
 *   3. Если существует — выполняет UPDATE по первичному ключу `itempagid`.
 *   4. Если не существует — выполняет INSERT, вручную задавая значение `itempagid`.
 *
 * @param int   $page_id ID страницы (будет записан в колонку `itempagid`)
 * @param array $data    Ассоциативный массив значений экстраполей
 */
function xtradbrowpage_save($page_id, $data)
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = implode(',', $value);
        }
    }

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

/**
 * Загружает перевод значения экстраполя для указанного языка
 *
 * @param int    $page_id   ID страницы (itempagid)
 * @param string $fieldName Имя экстраполя
 * @param string $lang      Двухбуквенный код языка
 * @return string|null      Перевод или null
 */
function xtradbrowpage_i18n_load($page_id, $fieldName, $lang)
{
    return Cot::$db->query(
        "SELECT value FROM " . Cot::$db->xtradbrowpage_i18n . " WHERE itempagid = ? AND field_name = ? AND lang = ?",
        [$page_id, $fieldName, $lang]
    )->fetchColumn() ?: null;
}

/**
 * Сохраняет или удаляет перевод значения экстраполя для конкретного языка
 * Если $value === null или '', запись удаляется.
 *
 * @param int    $page_id   ID страницы
 * @param string $fieldName Имя экстраполя
 * @param string $lang      Двухбуквенный код языка
 * @param mixed  $value     Значение перевода
 */
function xtradbrowpage_i18n_save($page_id, $fieldName, $lang, $value)
{
    if ($value === null || $value === '') {
        Cot::$db->delete(Cot::$db->xtradbrowpage_i18n, "itempagid = ? AND field_name = ? AND lang = ?", [$page_id, $fieldName, $lang]);
    } else {
        Cot::$db->query(
            "INSERT INTO " . Cot::$db->xtradbrowpage_i18n . " (itempagid, field_name, lang, value)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE value = VALUES(value)",
            [$page_id, $fieldName, $lang, $value]
        );
    }
}

/**
 * Возвращает значение экстраполя с учётом мультиязычного перевода
 *
 * @param int    $page_id       ID страницы
 * @param string $fieldName     Имя экстраполя
 * @param mixed  $originalValue Исходное значение (из основной таблицы)
 * @return mixed Значение на нужном языке или оригинал
 */
function xtradbrowpage_i18n_get_value($page_id, $fieldName, $originalValue)
{
    if (empty(Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_use'])) {
        return $originalValue;
    }

    $defaultLang = !empty(Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_default'])
        ? Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_default']
        : Cot::$cfg['defaultlang'];

    $currentLang = Cot::$usr['lang'] ?? $defaultLang;

    if ($currentLang !== $defaultLang) {
        $translated = xtradbrowpage_i18n_load($page_id, $fieldName, $currentLang);
        return $translated !== null ? $translated : $originalValue;
    }

    if ($originalValue !== null && $originalValue !== '') {
        return $originalValue;
    }

    $activeLangs = [];
    $firstCode  = Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_first'] ?? '';
    $firstUse   = Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_first_use'] ?? 0;
    $secondCode = Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_second'] ?? '';
    $secondUse  = Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_second_use'] ?? 0;

    if ($firstUse && !empty($firstCode) && $firstCode !== $defaultLang) {
        $activeLangs[] = $firstCode;
    }
    if ($secondUse && !empty($secondCode) && $secondCode !== $defaultLang) {
        $activeLangs[] = $secondCode;
    }

    foreach ($activeLangs as $lang) {
        $fallback = xtradbrowpage_i18n_load($page_id, $fieldName, $lang);
        if ($fallback !== null && $fallback !== '') {
            return $fallback;
        }
    }

    return $originalValue;
}
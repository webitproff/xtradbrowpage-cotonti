<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * Admin panel for xtradbrowpage – Statistics, Edit extrafields, Edit with i18n
 *
 * Filename: plugins/xtradbrowpage/xtradbrowpage.admin.php
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

require_once cot_langfile('xtradbrowpage', 'plug');
require_once cot_incfile('xtradbrowpage', 'plug');
require_once cot_incfile('page', 'module');
require_once cot_incfile('extrafields');

$tab = cot_import('tab', 'G', 'ALP') ?: 'stats';
$a   = cot_import('a',   'G', 'ALP');

$t = new XTemplate(cot_tplfile('xtradbrowpage.admin', 'plug', true));

$t->assign([
    'TAB_STATS_ACTIVE' => $tab === 'stats' ? 'active' : '',
    'TAB_EDIT_ACTIVE'  => $tab === 'edit'  ? 'active' : '',
    'TAB_I18N_ACTIVE'  => $tab === 'i18n'  ? 'active' : '',
    'URL_STATS'        => cot_url('admin', ['m'=>'other','p'=>'xtradbrowpage','tab'=>'stats']),
    'URL_EDIT'         => cot_url('admin', ['m'=>'other','p'=>'xtradbrowpage','tab'=>'edit']),
    'URL_I18N'         => cot_url('admin', ['m'=>'other','p'=>'xtradbrowpage','tab'=>'i18n']),
]);

$perPage = (int) (Cot::$cfg['plugin']['xtradbrowpage']['perpage'] ?? 20);
if ($perPage < 1) $perPage = 20;

/* ========== ВКЛАДКА СТАТИСТИКА ========== */
if ($tab === 'stats') {
    $totalPages = Cot::$db->query(
        "SELECT COUNT(*) FROM $db_pages WHERE page_id > 0"
    )->fetchColumn();

    $totalXtraRows = Cot::$db->query(
        "SELECT COUNT(*) FROM " . Cot::$db->xtradbrowpage
    )->fetchColumn();

    $extrafields = xtradbrowpage_getExtrafields();
    $filledCount = 0;
    if (!empty($extrafields)) {
        $nonEmpty = [];
        foreach ($extrafields as $exfld) {
            $fname = $exfld['field_name'];
            $nonEmpty[] = "$fname IS NOT NULL AND $fname != ''";
        }
        if (!empty($nonEmpty)) {
            $where = implode(' OR ', $nonEmpty);
            $filledCount = Cot::$db->query(
                "SELECT COUNT(*) FROM " . Cot::$db->xtradbrowpage . " WHERE $where"
            )->fetchColumn();
        }
    }

    $t->assign([
        'STATS_TOTAL_PAGES'     => $totalPages,
        'STATS_TOTAL_XTRA_ROWS' => $totalXtraRows,
        'STATS_FILLED_COUNT'    => $filledCount,
    ]);

    if (!empty($extrafields)) {
        foreach ($extrafields as $exfld) {
            $t->assign([
                'FIELD_NAME'        => htmlspecialchars($exfld['field_name']),
                'FIELD_TYPE'        => htmlspecialchars($exfld['field_type']),
                'FIELD_DESCRIPTION' => htmlspecialchars($exfld['field_description'] ?? ''),
                'FIELD_VARIANTS'    => htmlspecialchars($exfld['field_variants'] ?? ''),
                'FIELD_PARAMS'      => htmlspecialchars($exfld['field_params'] ?? ''),
                'FIELD_DEFAULT'     => htmlspecialchars($exfld['field_default'] ?? ''),
                'FIELD_REQUIRED'    => $exfld['field_required'] ? Cot::$L['Yes'] : Cot::$L['No'],
                'FIELD_ENABLED'     => $exfld['field_enabled'] ? Cot::$L['Yes'] : Cot::$L['No'],
            ]);
            $t->parse('MAIN.STATS_EXTRAFIELDS_ROW');
        }
    } else {
        $t->parse('MAIN.STATS_NO_EXTRAFIELDS');
    }
}

/* ========== ВКЛАДКА РЕДАКТИРОВАНИЕ (основные данные) ========== */
if ($tab === 'edit') {
    list($pg, $d, $durl) = cot_import_pagenav('d', $perPage);

    // Параметры поиска – из POST при сохранении, иначе из GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sq        = cot_import('sq', 'P', 'TXT');
        $c         = cot_import('c', 'P', 'TXT');
        $search_in = cot_import('search_in', 'P', 'ALP');
        $filter_id = cot_import('filter_id', 'P', 'INT');
    } else {
        $sq        = cot_import('sq', 'G', 'TXT');
        $c         = cot_import('c', 'G', 'TXT');
        $search_in = cot_import('search_in', 'G', 'ALP', 8);
        $filter_id = cot_import('filter_id', 'G', 'INT');
    }
    $sq = ($sq !== null) ? trim($sq) : '';
    if (!in_array($search_in, ['title', 'desc', 'alias'])) {
        $search_in = 'title';
    }

    // Все параметры сохраняем в URL
    $urlParams = [
        'm'         => 'other',
        'p'         => 'xtradbrowpage',
        'tab'       => 'edit',
        'sq'        => $sq,
        'c'         => $c,
        'search_in' => $search_in,
        'filter_id' => $filter_id,
    ];

    $showAllItems = !empty(Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_showallitems']);

    // ========================
    // Сохранение изменений
    // ========================
    if ($a === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : [];
        $updatedCount = 0;

        foreach ($ids as $id) {
            $oldData = xtradbrowpage_load($id);
            $isNewRecord = empty($oldData);
            if ($isNewRecord) {
                $oldData = [];
            }

            $data = [];
            $changed = false;
            $extrafields = xtradbrowpage_getExtrafields();

            foreach ($extrafields as $exfld) {
                $fname = $exfld['field_name'];
                $postKey = 'rxtra_' . $fname;

                // Определяем старое значение
                if ($isNewRecord) {
                    switch ($exfld['field_type']) {
                        case 'checkbox':
                            $oldValue = 0;
                            break;
                        case 'datetime':
                            $oldValue = 0;
                            break;
                        case 'select':
                            // Для select форма автоматически подставляет дефолт при пустом значении
                            $oldValue = $exfld['field_default'] ?? '';
                            break;
                        default:
                            $oldValue = '';
                            break;
                    }
                } else {
                    $oldValue = $oldData[$fname] ?? '';
                }

                $newValue = $oldValue; // по умолчанию не меняем
                $isPosted = isset($_POST[$postKey][$id]);

                // Обработка в зависимости от типа
                if ($exfld['field_type'] == 'checkbox') {
                    $newValue = $isPosted ? 1 : 0;
                } elseif ($exfld['field_type'] == 'checklistbox') {
                    if ($isPosted && is_array($_POST[$postKey][$id])) {
                        $raw = $_POST[$postKey][$id];
                        unset($raw['nullval']);
                        $filtered = array_filter($raw, 'strlen');
                        sort($filtered);
                        $newValue = implode(',', $filtered);
                    } else {
                        $newValue = '';
                    }
                } elseif ($exfld['field_type'] == 'datetime') {
                    if ($isPosted && is_array($_POST[$postKey][$id])) {
                        $raw = $_POST[$postKey][$id];
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
                    } elseif ($isPosted) {
                        $newValue = (int)$_POST[$postKey][$id];
                    }
                } else {
                    if ($isPosted) {
                        $raw = $_POST[$postKey][$id];
                        $newValue = is_array($raw) ? implode(',', $raw) : trim($raw);
                    }
                }

                // Сравнение с учётом типа
                if ($exfld['field_type'] == 'checklistbox') {
                    $newCompare = $newValue;
                    if (!empty($newCompare)) {
                        $newArray = explode(',', $newCompare);
                        sort($newArray);
                        $newCompare = implode(',', $newArray);
                    }
                    $oldCompare = $oldValue;
                    if (!empty($oldCompare)) {
                        $oldArray = explode(',', $oldCompare);
                        sort($oldArray);
                        $oldCompare = implode(',', $oldArray);
                    }
                    if ($newCompare != $oldCompare) {
                        $data[$fname] = $newValue;
                        $changed = true;
                    }
                } elseif ($exfld['field_type'] == 'datetime') {
                    $newCompare = is_numeric($newValue) ? date('Y-m-d H:i', (int)$newValue) : $newValue;
                    $oldCompare = is_numeric($oldValue) ? date('Y-m-d H:i', (int)$oldValue) : $oldValue;
                    if ($newCompare != $oldCompare) {
                        $data[$fname] = $newValue;
                        $changed = true;
                    }
                } else {
                    if ($newValue != $oldValue) {
                        $data[$fname] = $newValue;
                        $changed = true;
                    }
                }
            }

            if ($changed) {
                xtradbrowpage_save($id, $data);
                $updatedCount++;
            }
        }

        $msg = '';
        if ($updatedCount > 0) {
            $msg .= sprintf(Cot::$L['xtradbrowpage_updated'], $updatedCount);
        }
        if (!empty($msg)) {
            cot_message($msg);
        }

        $backUrl = cot_url('admin', array_merge($urlParams, ['d' => $durl]));
        $backUrl = str_replace('&amp;', '&', $backUrl);
        cot_redirect($backUrl);
    }

    // ========================
    // WHERE для страниц
    // ========================
    $sqlwhere = "p.page_id > 0";
    $params = [];

    if (!empty($sq)) {
        $sq_escaped = "%$sq%";
        if ($search_in == 'title') {
            $sqlwhere .= " AND p.page_title LIKE :sq";
        } elseif ($search_in == 'desc') {
            $sqlwhere .= " AND (p.page_title LIKE :sq OR p.page_desc LIKE :sq)";
        } elseif ($search_in == 'alias') {
            $sqlwhere .= " AND p.page_alias LIKE :sq";
        }
        $params['sq'] = $sq_escaped;
    }

    if (!empty($filter_id)) {
        $sqlwhere .= " AND p.page_id = :fid";
        $params['fid'] = $filter_id;
    }

    if (!empty($c)) {
        $catsub = cot_structure_children('page', $c);
        if (!empty($catsub)) {
            $sqlwhere .= " AND p.page_cat IN ('" . implode("','", $catsub) . "')";
        }
    }

    // Подсчёт количества записей
    if ($showAllItems) {
        $total = Cot::$db->query(
            "SELECT COUNT(*) FROM $db_pages AS p WHERE $sqlwhere", $params
        )->fetchColumn();
    } else {
        $total = Cot::$db->query(
            "SELECT COUNT(*) FROM " . Cot::$db->xtradbrowpage . " AS t
             LEFT JOIN $db_pages AS p ON p.page_id = t.itempagid
             WHERE $sqlwhere", $params
        )->fetchColumn();
    }

    $searchMsg = '';
    if (!empty($sq) || !empty($filter_id)) {
        $totalFound = (int)$total;
        $queryDesc = [];
        if (!empty($sq)) $queryDesc[] = '«'.htmlspecialchars($sq).'»';
        if (!empty($filter_id)) $queryDesc[] = 'ID '.$filter_id;
        if ($totalFound > 0) {
            $searchMsg = sprintf(Cot::$L['xtradbrowpage_search_result_msg'], cot_declension($totalFound, Cot::$L['xtradbrowpage_search_declen']), implode(', ', $queryDesc));
        } else {
            $searchMsg = sprintf(Cot::$L['xtradbrowpage_search_result_none'], implode(', ', $queryDesc));
        }
    }

    // Получение данных
    if ($showAllItems) {
        $items = Cot::$db->query(
            "SELECT p.page_id, p.page_title, p.page_alias, p.page_cat, t.*
             FROM $db_pages AS p
             LEFT JOIN " . Cot::$db->xtradbrowpage . " AS t ON t.itempagid = p.page_id
             WHERE $sqlwhere
             ORDER BY p.page_id DESC
             LIMIT $d, $perPage",
            $params
        )->fetchAll();
    } else {
        $items = Cot::$db->query(
            "SELECT t.*, p.page_title, p.page_alias, p.page_cat
             FROM " . Cot::$db->xtradbrowpage . " AS t
             LEFT JOIN $db_pages AS p ON p.page_id = t.itempagid
             WHERE $sqlwhere
             ORDER BY t.itempagid DESC
             LIMIT $d, $perPage",
            $params
        )->fetchAll();
    }

    $t->assign([
        'SEARCH_ACTION_URL'       => cot_url('admin'),
        'SEARCH_SQ'               => cot_inputbox('text', 'sq', !empty($sq) ? htmlspecialchars($sq) : '', 'class="form-control" autofocus'),
        'SEARCH_CAT'              => cot_selectbox_structure('page', $c, 'c', Cot::$L['All']),
        'SEARCH_CAT_SELECT2'      => cot_selectbox_structure('page', $c, 'c', Cot::$L['All']),
        'SEARCH_FILTER_ID'        => cot_inputbox('number', 'filter_id', !empty($filter_id) ? $filter_id : '', 'class="form-control" placeholder="ID"'),
        'SEARCH_RESULT_MSG'       => $searchMsg,
        'SEARCH_IN_TITLE_CHECKED' => ($search_in == 'title') ? 'checked="checked"' : '',
        'SEARCH_IN_DESC_CHECKED'  => ($search_in == 'desc')  ? 'checked="checked"' : '',
        'SEARCH_IN_ALIAS_CHECKED' => ($search_in == 'alias') ? 'checked="checked"' : '',
        'SEARCH_SQ_VALUE'         => htmlspecialchars($sq ?? ''),
        'SEARCH_C_VALUE'          => htmlspecialchars($c ?? ''),
        'SEARCH_IN_VALUE'         => htmlspecialchars($search_in ?? ''),
        'SEARCH_FILTER_ID_VALUE'  => htmlspecialchars($filter_id ?? ''),
    ]);

    $extrafields = xtradbrowpage_getExtrafields();
    if (!empty($extrafields)) {
        foreach ($extrafields as $exfld) {
            $t->assign('FIELD_HEADER_TITLE', htmlspecialchars(cot_extrafield_title($exfld, 'xtra_')));
            $t->parse('MAIN.EDIT_FIELDS_HEADER');
        }
    }

    if (!empty($items)) {
        foreach ($items as $row) {
            $id = $row['page_id'] ?? $row['itempagid'];
            $itemUrl = cot_url('page', 'c=' . $row['page_cat'] . '&id=' . $id);
            $t->assign([
                'MANAGE_ID'    => $id,
                'MANAGE_TITLE' => htmlspecialchars($row['page_title'] ?? ''),
                'MANAGE_URL'   => $itemUrl,
            ]);

            foreach ($extrafields as $exfld) {
                $fname = $exfld['field_name'];
                // Для новых записей значение пустое
                $value = $row[$fname] ?? '';
                $inputName = 'rxtra_' . $fname . '[' . $id . ']';
                $fieldHtml = cot_build_extrafields($inputName, $exfld, $value);
                $t->assign('FIELD_HTML', $fieldHtml);
                $t->parse('MAIN.EDIT_ROW.FIELD_CELL');
            }
            $t->parse('MAIN.EDIT_ROW');
        }
    } else {
        $t->parse('MAIN.EDIT_EMPTY');
    }

    $pagenav = cot_pagenav('admin', $urlParams, $d, $total, $perPage, 'd');
    $t->assign(cot_generatePaginationTags($pagenav));
    $t->assign('EDIT_FORM_URL', cot_url('admin', ['m'=>'other','p'=>'xtradbrowpage','tab'=>'edit','a'=>'update','d'=>$durl]));
}

/* ========== ВКЛАДКА РЕДАКТИРОВАНИЕ С ПЕРЕВОДАМИ (i18n) ========== */
if ($tab === 'i18n') {
    list($pg, $d, $durl) = cot_import_pagenav('d', $perPage);

    // Параметры поиска – из POST при сохранении, иначе из GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sq        = cot_import('sq', 'P', 'TXT');
        $c         = cot_import('c', 'P', 'TXT');
        $search_in = cot_import('search_in', 'P', 'ALP');
        $filter_id = cot_import('filter_id', 'P', 'INT');
    } else {
        $sq        = cot_import('sq', 'G', 'TXT');
        $c         = cot_import('c', 'G', 'TXT');
        $search_in = cot_import('search_in', 'G', 'ALP', 8);
        $filter_id = cot_import('filter_id', 'G', 'INT');
    }
    $sq = ($sq !== null) ? trim($sq) : '';
    if (!in_array($search_in, ['title', 'desc', 'alias'])) {
        $search_in = 'title';
    }

    $urlParams = [
        'm'         => 'other',
        'p'         => 'xtradbrowpage',
        'tab'       => 'i18n',
        'sq'        => $sq,
        'c'         => $c,
        'search_in' => $search_in,
        'filter_id' => $filter_id,
    ];

    $showAllItems = !empty(Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_showallitems']);

    // Языковые настройки
    $i18nActive = !empty(Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_use']);
    $activeLangs = [];
    $langDefault = !empty(Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_default'])
        ? Cot::$cfg['plugin']['xtradbrowpage']['xtradbrowpage_i18n_lang_code_default']
        : Cot::$cfg['defaultlang'];

    if ($i18nActive) {
        $pairs = [
            ['use' => 'xtradbrowpage_i18n_lang_code_first_use',  'code' => 'xtradbrowpage_i18n_lang_code_first'],
            ['use' => 'xtradbrowpage_i18n_lang_code_second_use', 'code' => 'xtradbrowpage_i18n_lang_code_second'],
            ['use' => 'xtradbrowpage_i18n_lang_code_third_use',  'code' => 'xtradbrowpage_i18n_lang_code_third'],
        ];
        foreach ($pairs as $pair) {
            if (!empty(Cot::$cfg['plugin']['xtradbrowpage'][$pair['use']])
                && !empty(Cot::$cfg['plugin']['xtradbrowpage'][$pair['code']])) {
                $lang = Cot::$cfg['plugin']['xtradbrowpage'][$pair['code']];
                if ($lang !== $langDefault) {
                    $activeLangs[] = $lang;
                }
            }
        }
    }

    // Типы полей, которые можно редактировать
    $i18nEditableTypes = ['input', 'textarea'];

    // Загружаем файл стран
    $userLang = !empty(Cot::$usr['lang']) ? Cot::$usr['lang'] : Cot::$cfg['defaultlang'];
    $countryFile = $_SERVER['DOCUMENT_ROOT'] . '/lang/' . $userLang . '/countries.' . $userLang . '.lang.php';
    if (file_exists($countryFile)) {
        include $countryFile;
    }

    // Функция форматирования значения для просмотра
    $formatValue = function($exfld, $value) {
        if ($value === null || $value === '') {
            return '';
        }
        switch ($exfld['field_type']) {
            case 'checkbox':
                return $value ? Cot::$L['Yes'] : Cot::$L['No'];
            case 'datetime':
                if ((int)$value === 0) {
                    return '';
                }
                return cot_date('datetime_medium', (int)$value);
            case 'country':
                global $cot_countries;
                return isset($cot_countries[$value]) ? $cot_countries[$value] : htmlspecialchars($value);
            case 'checklistbox':
                $parts = explode(',', $value);
                $localized = [];
                foreach ($parts as $part) {
                    $part = trim($part);
                    if ($part === '') continue;
                    $langKey = $exfld['field_name'] . '_' . $part;
                    $localized[] = isset(Cot::$L[$langKey]) ? Cot::$L[$langKey] : $part;
                }
                return htmlspecialchars(implode(', ', $localized));
            default:
                $langKey = $exfld['field_name'] . '_' . $value;
                if (isset(Cot::$L[$langKey])) {
                    return Cot::$L[$langKey];
                }
                return htmlspecialchars($value);
        }
    };

    // ========================
    // Сохранение изменений
    // ========================
    if ($a === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $ids = isset($_POST['ids']) ? array_map('intval', $_POST['ids']) : [];
        $updatedCount = 0;

        foreach ($ids as $id) {
            $oldData = xtradbrowpage_load($id);
            $isNewRecord = empty($oldData);
            if ($isNewRecord) {
                $oldData = [];
            }

            $data = [];
            $changed = false;
            $extrafields = xtradbrowpage_getExtrafields();

            // Обрабатываем только input и textarea
            foreach ($extrafields as $exfld) {
                if (!in_array($exfld['field_type'], $i18nEditableTypes)) {
                    continue;
                }

                $fname = $exfld['field_name'];
                $postKey = 'rxtra_' . $fname;

                if ($isNewRecord) {
                    $oldValue = $exfld['field_default'] ?? '';
                } else {
                    $oldValue = $oldData[$fname] ?? '';
                }

                $newValue = $oldValue;
                if (isset($_POST[$postKey][$id])) {
                    $raw = $_POST[$postKey][$id];
                    $newValue = is_array($raw) ? implode(',', $raw) : trim($raw);
                }

                if ($newValue != $oldValue) {
                    $data[$fname] = $newValue;
                    $changed = true;
                }
            }

            if ($changed) {
                xtradbrowpage_save($id, $data);
                $updatedCount++;
            }

            // Переводы
            if ($i18nActive && !empty($activeLangs)) {
                $i18nChanged = false;
                foreach ($extrafields as $exfld) {
                    if (!in_array($exfld['field_type'], $i18nEditableTypes)) {
                        continue;
                    }
                    $fname = $exfld['field_name'];
                    foreach ($activeLangs as $lang) {
                        if ($lang === $langDefault) {
                            continue;
                        }
                        $i18nKey = 'rxtra_' . $fname . '_' . $lang;
                        if (isset($_POST[$i18nKey][$id])) {
                            $newI18nValue = trim($_POST[$i18nKey][$id]);
                            $oldI18nValue = function_exists('xtradbrowpage_i18n_load')
                                ? xtradbrowpage_i18n_load($id, $fname, $lang)
                                : '';
                            if ($newI18nValue != $oldI18nValue) {
                                if (function_exists('xtradbrowpage_i18n_save')) {
                                    xtradbrowpage_i18n_save($id, $fname, $lang, $newI18nValue);
                                }
                                $i18nChanged = true;
                            }
                        }
                    }
                }
                if ($i18nChanged && !$changed) {
                    $updatedCount++;
                }
            }
        }

        $msg = '';
        if ($updatedCount > 0) {
            $msg .= sprintf(Cot::$L['xtradbrowpage_updated'], $updatedCount);
        }
        if (!empty($msg)) {
            cot_message($msg);
        }

        $backUrl = cot_url('admin', array_merge($urlParams, ['d' => $durl]));
        $backUrl = str_replace('&amp;', '&', $backUrl);
        cot_redirect($backUrl);
    }

    // ========================
    // WHERE для страниц
    // ========================
    $sqlwhere = "p.page_id > 0";
    $params = [];

    if (!empty($sq)) {
        $sq_escaped = "%$sq%";
        if ($search_in == 'title') {
            $sqlwhere .= " AND p.page_title LIKE :sq";
        } elseif ($search_in == 'desc') {
            $sqlwhere .= " AND (p.page_title LIKE :sq OR p.page_desc LIKE :sq)";
        } elseif ($search_in == 'alias') {
            $sqlwhere .= " AND p.page_alias LIKE :sq";
        }
        $params['sq'] = $sq_escaped;
    }

    if (!empty($filter_id)) {
        $sqlwhere .= " AND p.page_id = :fid";
        $params['fid'] = $filter_id;
    }

    if (!empty($c)) {
        $catsub = cot_structure_children('page', $c);
        if (!empty($catsub)) {
            $sqlwhere .= " AND p.page_cat IN ('" . implode("','", $catsub) . "')";
        }
    }

    // Подсчёт
    if ($showAllItems) {
        $total = Cot::$db->query(
            "SELECT COUNT(*) FROM $db_pages AS p WHERE $sqlwhere", $params
        )->fetchColumn();
    } else {
        $total = Cot::$db->query(
            "SELECT COUNT(*) FROM " . Cot::$db->xtradbrowpage . " AS t
             LEFT JOIN $db_pages AS p ON p.page_id = t.itempagid
             WHERE $sqlwhere", $params
        )->fetchColumn();
    }

    $searchMsg = '';
    if (!empty($sq) || !empty($filter_id)) {
        $totalFound = (int)$total;
        $queryDesc = [];
        if (!empty($sq)) $queryDesc[] = '«'.htmlspecialchars($sq).'»';
        if (!empty($filter_id)) $queryDesc[] = 'ID '.$filter_id;
        if ($totalFound > 0) {
            $searchMsg = sprintf(Cot::$L['xtradbrowpage_search_result_msg'], cot_declension($totalFound, Cot::$L['xtradbrowpage_search_declen']), implode(', ', $queryDesc));
        } else {
            $searchMsg = sprintf(Cot::$L['xtradbrowpage_search_result_none'], implode(', ', $queryDesc));
        }
    }

    // Получение данных
    if ($showAllItems) {
        $items = Cot::$db->query(
            "SELECT p.page_id, p.page_title, p.page_alias, p.page_cat, t.*
             FROM $db_pages AS p
             LEFT JOIN " . Cot::$db->xtradbrowpage . " AS t ON t.itempagid = p.page_id
             WHERE $sqlwhere
             ORDER BY p.page_id DESC
             LIMIT $d, $perPage",
            $params
        )->fetchAll();
    } else {
        $items = Cot::$db->query(
            "SELECT t.*, p.page_title, p.page_alias, p.page_cat
             FROM " . Cot::$db->xtradbrowpage . " AS t
             LEFT JOIN $db_pages AS p ON p.page_id = t.itempagid
             WHERE $sqlwhere
             ORDER BY t.itempagid DESC
             LIMIT $d, $perPage",
            $params
        )->fetchAll();
    }

    $t->assign([
        'SEARCH_ACTION_URL'       => cot_url('admin'),
        'SEARCH_SQ'               => cot_inputbox('text', 'sq', !empty($sq) ? htmlspecialchars($sq) : '', 'class="form-control" autofocus'),
        'SEARCH_CAT'              => cot_selectbox_structure('page', $c, 'c', Cot::$L['All']),
        'SEARCH_CAT_SELECT2'      => cot_selectbox_structure('page', $c, 'c', Cot::$L['All']),
        'SEARCH_FILTER_ID'        => cot_inputbox('number', 'filter_id', !empty($filter_id) ? $filter_id : '', 'class="form-control" placeholder="ID"'),
        'SEARCH_RESULT_MSG'       => $searchMsg,
        'SEARCH_IN_TITLE_CHECKED' => ($search_in == 'title') ? 'checked="checked"' : '',
        'SEARCH_IN_DESC_CHECKED'  => ($search_in == 'desc')  ? 'checked="checked"' : '',
        'SEARCH_IN_ALIAS_CHECKED' => ($search_in == 'alias') ? 'checked="checked"' : '',
        'I18N_ACTIVE'             => $i18nActive,
        'SEARCH_SQ_VALUE'         => htmlspecialchars($sq ?? ''),
        'SEARCH_C_VALUE'          => htmlspecialchars($c ?? ''),
        'SEARCH_IN_VALUE'         => htmlspecialchars($search_in ?? ''),
        'SEARCH_FILTER_ID_VALUE'  => htmlspecialchars($filter_id ?? ''),
    ]);

    $extrafields = xtradbrowpage_getExtrafields();
    if (!empty($extrafields)) {
        foreach ($extrafields as $exfld) {
            $t->assign('FIELD_HEADER_TITLE', htmlspecialchars(cot_extrafield_title($exfld, 'xtra_')));
            if ($i18nActive && in_array($exfld['field_type'], $i18nEditableTypes)) {
                foreach ($activeLangs as $lang) {
                    $t->assign('LANG_HEADER', strtoupper($lang));
                    $t->parse('MAIN.I18N_FIELDS_HEADER.LANG_HEADER_ROW');
                }
            }
            $t->parse('MAIN.I18N_FIELDS_HEADER');
        }
    }

    if (!empty($items)) {
        foreach ($items as $row) {
            $id = $row['page_id'] ?? $row['itempagid'];
            $itemUrl = cot_url('page', 'c=' . $row['page_cat'] . '&id=' . $id);
            $t->assign([
                'MANAGE_ID'    => $id,
                'MANAGE_TITLE' => htmlspecialchars($row['page_title'] ?? ''),
                'MANAGE_URL'   => $itemUrl,
            ]);

            foreach ($extrafields as $exfld) {
                $fname = $exfld['field_name'];
                $isEditable = in_array($exfld['field_type'], $i18nEditableTypes);
                $isNew = (!isset($row['itempagid']) || $row['itempagid'] === null);

                if ($isNew) {
                    $value = $isEditable ? ($exfld['field_default'] ?? '') : '';
                } else {
                    $value = $row[$fname] ?? '';
                }

                if ($isEditable) {
                    $inputName = 'rxtra_' . $fname . '[' . $id . ']';
                    $fieldHtml = cot_build_extrafields($inputName, $exfld, $value);
                } else {
                    $fieldHtml = $formatValue($exfld, $value);
                }
                $t->assign('FIELD_HTML', $fieldHtml);

                if ($i18nActive && $isEditable) {
                    foreach ($activeLangs as $lang) {
                        $langValue = function_exists('xtradbrowpage_i18n_load')
                            ? xtradbrowpage_i18n_load($id, $fname, $lang)
                            : '';
                        $langInputName = 'rxtra_' . $fname . '_' . $lang . '[' . $id . ']';
                        $langHtml = cot_inputbox('text', $langInputName, $langValue, 'class="form-control form-control-sm"');
                        $t->assign('LANG_FIELD', $langHtml);
                        $t->parse('MAIN.I18N_ROW.FIELD_CELL.LANG_ROW');
                    }
                }
                $t->parse('MAIN.I18N_ROW.FIELD_CELL');
            }
            $t->parse('MAIN.I18N_ROW');
        }
    } else {
        $t->parse('MAIN.I18N_EMPTY');
    }

    $pagenav = cot_pagenav('admin', $urlParams, $d, $total, $perPage, 'd');
    $t->assign(cot_generatePaginationTags($pagenav));
    $t->assign('I18N_FORM_URL', cot_url('admin', ['m'=>'other','p'=>'xtradbrowpage','tab'=>'i18n','a'=>'update','d'=>$durl]));
}

cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');
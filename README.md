# The Plugin Custom Extrafields for Pages module of Cotonti CMF, PHP 8.5+, MySQL 8.4

## The plugin "xtradbrowpage" changes the storage strategy: a physically independent cot_xtradbrowpage table is created for all extrapoles registered through it.

[![Version](https://img.shields.io/badge/version-3.0.0-green.svg)](https://github.com/webitproff/xtradbrowpage-cotonti/releases)
[![Cotonti Compatibility](https://img.shields.io/badge/Cotonti-v.1-orange.svg)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.5-purple.svg)](https://www.php.net/releases/8_5_0.php)
[![MySQL](https://img.shields.io/badge/MySQL-8.4-blue.svg)](https://www.mysql.com/)
[![Bootstrap v5.3.8](https://img.shields.io/badge/Bootstrap-v5.3.8-blueviolet.svg)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/license-BSD-blue.svg)](https://github.com/webitproff/xtradbrowpage-cotonti/blob/main/LICENSE)

---
> On August 10, 2026, the xtradbrowpage plugin gained **[integration](https://github.com/webitproff/pagemassedit-cotonti/blob/main/INTEGRATION.md)** with the mass page editing plugin — **["Page Mass Edit"](https://github.com/webitproff/pagemassedit-cotonti/tree/main)**
---


<img width="1536" height="1024" alt="The Plugin Custom Extrafields for Pages module of Cotonti CMF" src="https://github.com/user-attachments/assets/c76cffa4-16ad-44b2-ae02-e0ab671db16f" />




### What the Plugin Does

*   Creates a separate table `cot_xtradbrowpage` to store extra fields for pages. Each record is linked to a page via `itempagid = page_id` with cascading delete.
*   Registers the `cot_xtradbrowpage` location in the admin panel under "Extensions → Extrafields".
*   When editing a page, automatically outputs all active extra fields of this location (form fields with the `rxtra_` prefix, tags `{PAGEEDIT_FORM_XTRA_FIELDNAME}`). Saves them on page update.
*   On the page view, provides tags `{XTRA_FIELDNAME}`, `{XTRA_FIELDNAME_TITLE}`, `{XTRA_FIELDNAME_VALUE}`, and a block `<!-- BEGIN: XTRA_EXTRAFLD -->` to loop through all fields.
*   In page lists (via `cot_generate_pagetags()`), tags with the prefix `{LIST_ROW_XTRA_...}` are available.
*   In the `<head>` section (`header.tags` hook), you can use `{XTRA_HEADER_FIELDNAME}`.
*   When a page is deleted, the corresponding record in `cot_xtradbrowpage` is removed either by cascade or explicitly (`page.edit.delete.done` hook).
*   Provides three API functions for manual operations.

---
## 🎯 [**Читать на русском и смотреть в маркетплейсе расширений**](https://abuyfile.com/ru/market/cotonti/xtra-db-row-page)   

10 августа 2026 г плагину xtradbrowpage добавлена **[интеграция](https://github.com/webitproff/pagemassedit-cotonti/blob/main/INTEGRATION.md)** с плагином массового редактирования статей, - **["Page Mass Edit"](https://github.com/webitproff/pagemassedit-cotonti/tree/main)**
---
### Requirements

*   Cotonti 1+
*   PHP 8.5+
*   The "page" module must be installed

 [**permanent link to the current plugin source code on GitHub**](https://github.com/webitproff/xtradbrowpage-cotonti) 

### Installation

1.  Copy the plugin folder to `/plugins/xtradbrowpage`.
2.  In the admin panel, go to "Extensions → Plugins" and click "Install".

Installation SQL (executed automatically):

```php
CREATE TABLE IF NOT EXISTS `cot_xtradbrowpage` (
    `itempagid` int UNSIGNED NOT NULL,
    PRIMARY KEY (`itempagid`),
    CONSTRAINT `fk_xtradbrowpage_pages` FOREIGN KEY (`itempagid`) REFERENCES `cot_pages` (`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Adding Extra Fields

1.  In the admin panel: "Extensions → Extrafields".
2.  Select the `cot_xtradbrowpage` location (appears after plugin installation).
3.  Create fields of the required types. Allowed types: `input`, `textarea`, `select`, `datetime`, `double`, and any others supported by the Cotonti core.

#### Example Field Set (from database dump)

|     |     |     |     |     |
| --- | --- | --- | --- | --- |
| Field Name | Type | HTML Template | Variants / Parameters | Description |
| event\_name | input | `<input class="form-control" type="text" name="{$name}" value="{$value}" maxlength="255">` |     | Event name |
| event\_description | textarea | `<textarea class="form-control" name="{$name}" rows="{$rows}" cols="{$cols}" maxlength="255">{$value}</textarea>` |     | Event description |
| event\_start | datetime | `<div class="row g-2"> ... </div>` (**[see dump](https://github.com/webitproff/xtradbrowpage-cotonti/blob/main/demo-sql-dump-only-read.sql)**) | 2024,2030,d.m.Y H:i | Event start |
| event\_ticketprice | double | `<input class="form-control" type="text" name="{$name}" value="{$value}" maxlength="255">` |     | Ticket price |
| event\_seson | select | `<select class="form-select" name="{$name}">{$options}</select>` | unknown,winter,summer,autumn,spring | Season |

### Templates and Tags

#### 1\. Edit Form (page.edit.tpl)

Block for automatic output of all fields:

```php
<!-- BEGIN: XTRA_EXTRAFLD -->
<div class="form-group">
    <label>{PAGEEDIT_FORM_XTRA_EXTRAFLD_TITLE}</label>
    {PAGEEDIT_FORM_XTRA_EXTRAFLD}
</div>
<!-- END: XTRA_EXTRAFLD -->
```

Individual fields (example for event\_name):

```php
<div class="mb-3">
    <label>{PAGEEDIT_FORM_XTRA_EVENT_NAME_TITLE}</label>
    {PAGEEDIT_FORM_XTRA_EVENT_NAME}
</div>
```

#### 2\. View Page (page.tpl)

Block for all fields:

```php
<!-- BEGIN: XTRA_EXTRAFLD -->
<div class="extrafield">
    <strong>{XTRA_EXTRAFIELD_TITLE}:</strong>
    <span>{XTRA_EXTRAFIELD_VALUE}</span>
</div>
<!-- END: XTRA_EXTRAFLD -->
```

Individual field:

```php
<!-- IF {XTRA_EVENT_START} -->
    <p>{XTRA_EVENT_START_TITLE}: {XTRA_EVENT_START}</p>
<!-- ENDIF -->
```

Values are formatted according to the field type (datetime will be formatted as `d.m.Y H:i`).

#### 3\. Page Lists (page.list.tpl, news.tpl, etc.)

Use tags `{LIST_ROW_XTRA_FIELDNAME}`, e.g.:

```php
<!-- IF {LIST_ROW_XTRA_EVENT_NAME} -->
    <small>{LIST_ROW_XTRA_EVENT_NAME_TITLE}: {LIST_ROW_XTRA_EVENT_NAME}</small>
<!-- ENDIF -->
```

These tags are added via the `pagetags.main` hook inside the `cot_generate_pagetags()` function.

#### 4\. Head Section (header.tpl)

```php
<!-- IF {XTRA_HEADER_EVENT_NAME} -->
    <meta name="event" content="{XTRA_HEADER_EVENT_NAME}">
<!-- ENDIF -->
```

### API for Developers

Functions are located in `/plugins/xtradbrowpage/inc/xtradbrowpage.functions.php`.

#### Get all registered fields

```php
$fields = xtradbrowpage_getExtrafields(); // array from Cot::$extrafields['cot_xtradbrowpage']
```

#### Load record for a page

```php
$data = xtradbrowpage_load($page_id); // returns an associative array with field_name keys
```

#### Save (INSERT or UPDATE)

```php
$values = [
    'event_name' => 'New event',
    'event_start' => 1715011200, // UNIX timestamp
    'event_ticketprice' => 100.50
];
xtradbrowpage_save($page_id, $values);
```

### Plugin Hooks and Their Purpose

|     |     |     |
| --- | --- | --- |
| Hook | File | Action |
| admin.extrafields.first | xtradbrowpage.admin.extrafields.first.php | Adds the location to the extra fields whitelist |
| header.tags | xtradbrowpage.header.tags.php | Assigns XTRA\_HEADER\_\* tags for the current page |
| page.edit.tags | xtradbrowpage.page.edit.tags.php | Outputs fields in the edit form and parses the XTRA\_EXTRAFLD block |
| page.edit.update.done | xtradbrowpage.page.edit.update.done.php | Saves the submitted values to cot\_xtradbrowpage |
| page.edit.delete.done | xtradbrowpage.page.edit.delete.done.php | Explicitly deletes the record for the page |
| page.tags | xtradbrowpage.page.tags.php | Assigns tags for the view page, parses the XTRA\_EXTRAFLD block |
| pagetags.main | xtradbrowpage.pagetags.php | Adds LIST\_ROW\_XTRA\_\* tags to $temp\_array |

### Practical Example: Creating Events

#### 1\. Adding Fields

Create fields through the admin panel: event\_name, event\_description, event\_start, event\_ticketprice, event\_seson with the parameters from the table above.

#### 2\. Edit Template

```php
<!-- In page.edit.tpl -->
<div class="card mt-3">
    <div class="card-header">Event Parameters</div>
    <div class="card-body">
        <!-- BEGIN: XTRA_EXTRAFLD -->
        <div class="mb-3">
            <label class="form-label">{PAGEEDIT_FORM_XTRA_EXTRAFLD_TITLE}</label>
            {PAGEEDIT_FORM_XTRA_EXTRAFLD}
        </div>
        <!-- END: XTRA_EXTRAFLD -->
    </div>
</div>
```

#### 3\. View Template

```php
<!-- In page.tpl -->
<!-- IF {XTRA_EVENT_NAME} -->
<div class="event-details">
    <h2>{XTRA_EVENT_NAME}</h2>
    <!-- BEGIN: XTRA_EXTRAFLD -->
    <p><strong>{XTRA_EXTRAFIELD_TITLE}:</strong> {XTRA_EXTRAFIELD_VALUE}</p>
    <!-- END: XTRA_EXTRAFLD -->
</div>
<!-- ENDIF -->
```

#### 4\. List Output

```php
<!-- In page.list.tpl inside LIST_ROWS block -->
<div class="list-item">
    <a href="{LIST_ROW_URL}">{LIST_ROW_TITLE}</a>
    <!-- IF {LIST_ROW_XTRA_EVENT_START} -->
        <span class="date">{LIST_ROW_XTRA_EVENT_START}</span>
    <!-- ENDIF -->
</div>
```

### Field Type Specifics

*   **datetime**: stored as int (UNIX timestamp). In the edit form, dropdowns are provided. On output, formatted according to the `field_params` (e.g., `d.m.Y H:i`).
*   **select**: comma-separated values in `field_variants`; the selected value is saved as a string.
*   **double**: saved as a floating-point number.
*   All fields are escaped on output via `cot_build_extrafields_data()` and `htmlspecialchars()`.

### Notes

*   The plugin does not add columns to the `cot_pages` table.
*   If a page is created without immediately saving extra fields, the record in `cot_xtradbrowpage` will appear on the first update of the page via the edit form.
*   For bulk data insertion, use the `xtradbrowpage_save()` API function.
*   Cache clearing is not required, but if you encounter display issues, clear the cache in the admin panel.

## HELP & SUPPORT

*  **[Forum Topic](https://abuyfile.com/ru/forums/cotonti/original/extrafields/topic205)**
*  **[Forum SECTION](https://abuyfile.com/ru/forums/cotonti/original/extrafields)**


### It may also be interesting and useful to you:

*  **[Integrating Extrafields into the I18n plugin in Cotonti CMF](https://abuyfile.com/en/cotonti/authorial-plugins/integraciya-extrafields-v-plagin-i18n-v-cotonti-cmf)**

*  **[Localization (multilingualism) of Extra-fields. Instruction manual](https://abuyfile.com/en/cotonti/reading/lokalizaciya-ekstrapolej-v-cotonti-poshagovaya-polnaya-instrukciya-na-primere-polya-status-tovara)**

#### Similar Extensions

 -  **["Page Mass Edit"](https://github.com/webitproff/pagemassedit-cotonti/tree/main)**  
 -  **["Extrafields Market Custom"](https://github.com/webitproff/xtradbrowmarket-cotonti)**  
 -  **["Extrafields Users Custom"](https://github.com/webitproff/xtradbrowusers-cotonti)**  
 -  **["Extensions Filter for Cotonti"](https://github.com/webitproff/extensionfilter-cotonti)**  
 -  **["Drag & Drop Structure"](https://github.com/webitproff/dragstructure-cotonti)**  
 

___  
---  
> RUSSIAN
---  



# Плагин Custom Extrafields for Pages для Cotonti CMF, PHP 8.5+, MySQL 8.4

Плагин `xtradbrowpage` изменяет стратегию хранения данных: для всех дополнительных полей, зарегистрированных через него, создаётся физически отдельная таблица `cot_xtradbrowpage`.

**Версия:** 3.1.0\
**Совместимость:** Cotonti v.1\
**PHP:** 8.5+\
**MySQL:** 8.4\
**Bootstrap:** v5.3.8\
**Лицензия:** BSD

---

10 августа 2026 года плагин `xtradbrowpage` получил интеграцию с плагином массового редактирования статей — **Page Mass Edit**.

## Что делает плагин

- Создаёт отдельную таблицу `cot_xtradbrowpage` для хранения дополнительных полей страниц. Каждая запись связывается со страницей через `itempagid = page_id` с каскадным удалением.
- Регистрирует расположение `cot_xtradbrowpage` в административной панели в разделе «Расширения → Дополнительные поля».
- При редактировании страницы автоматически выводит все активные дополнительные поля этого расположения (поля формы с префиксом `rxtra_`, теги `{PAGEEDIT_FORM_XTRA_FIELDNAME}`). Сохраняет их при обновлении страницы.
- При просмотре страницы предоставляет теги `{XTRA_FIELDNAME}`, `{XTRA_FIELDNAME_TITLE}`, `{XTRA_FIELDNAME_VALUE}`, а также блок `<!-- BEGIN: XTRA_EXTRAFLD -->` для перебора всех полей.
- В списках страниц (через `cot_generate_pagetags()`) становятся доступны теги с префиксом `{LIST_ROW_XTRA_...}`.
- В секции `<head>` (хук `header.tags`) можно использовать тег `{XTRA_HEADER_FIELDNAME}`.
- При удалении страницы соответствующая запись в `cot_xtradbrowpage` удаляется автоматически через каскадное удаление или явно через хук `page.edit.delete.done`.
- Предоставляет три API-функции для ручной работы с данными.

---

## Требования

- Cotonti 1+
- PHP 8.5+
- Установленный модуль `page`

[**Постоянная ссылка на актуальный исходный код плагина на GitHub**](https://github.com/webitproff/xtradbrowpage-cotonti)

## Установка

1. Скопируйте папку плагина в `/plugins/xtradbrowpage`.
2. В административной панели перейдите в «Расширения → Плагины» и нажмите «Установить».

SQL-запрос для установки (выполняется автоматически):

```
CREATE TABLE IF NOT EXISTS `cot_xtradbrowpage` (
    `itempagid` int UNSIGNED NOT NULL,
    PRIMARY KEY (`itempagid`),
    CONSTRAINT `fk_xtradbrowpage_pages` FOREIGN KEY (`itempagid`) REFERENCES `cot_pages` (`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Добавление дополнительных полей

1. В административной панели откройте «Расширения → Дополнительные поля».
2. Выберите расположение `cot_xtradbrowpage` (оно появится после установки плагина).
3. Создайте поля нужных типов. Допустимые типы: `input`, `textarea`, `select`, `datetime`, `double` и любые другие типы, поддерживаемые ядром Cotonti.

### Пример набора полей (из дампа базы данных)

| Название поля       | Тип      | HTML-шаблон                                                                                                                                | Варианты / параметры                  | Описание                    |
| ------------------- | -------- | ------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------- | --------------------------- |
| `event_name`        | input    | `<input class="form-control" type="text" name="{$name}" value="{$value}" maxlength="255">`                                                 | —                                     | Название события            |
| `event_description` | textarea | `<textarea class="form-control" name="{$name}" rows="{$rows}" cols="{$cols}" maxlength="255">{$value}</textarea>`                          | —                                     | Описание события            |
| `event_start`       | datetime | `<div class="row g-2"> ... </div>` ([см. дамп](https://github.com/webitproff/xtradbrowpage-cotonti/blob/main/demo-sql-dump-only-read.sql)) | `2024,2030,d.m.Y H:i`                 | Дата и время начала события |
| `event_ticketprice` | double   | `<input class="form-control" type="text" name="{$name}" value="{$value}" maxlength="255">`                                                 | —                                     | Стоимость билета            |
| `event_seson`       | select   | `<select class="form-select" name="{$name}">{$options}</select>`                                                                           | `unknown,winter,summer,autumn,spring` | Сезон                       |

## Шаблоны и теги

### 1. Форма редактирования (`page.edit.tpl`)

Блок для автоматического вывода всех полей:

```
<!-- BEGIN: XTRA_EXTRAFLD -->
<div class="form-group">
    <label>{PAGEEDIT_FORM_XTRA_EXTRAFLD_TITLE}</label>
    {PAGEEDIT_FORM_XTRA_EXTRAFLD}
</div>
<!-- END: XTRA_EXTRAFLD -->
```

Отдельные поля (пример для `event_name`):

```
<div class="mb-3">
    <label>{PAGEEDIT_FORM_XTRA_EVENT_NAME_TITLE}</label>
    {PAGEEDIT_FORM_XTRA_EVENT_NAME}
</div>
```

### 2. Просмотр страницы (`page.tpl`)

Блок для вывода всех полей:

```
<!-- BEGIN: XTRA_EXTRAFLD -->
<div class="extrafield">
    <strong>{XTRA_EXTRAFIELD_TITLE}:</strong>
    <span>{XTRA_EXTRAFIELD_VALUE}</span>
</div>
<!-- END: XTRA_EXTRAFLD -->
```

Отдельное поле:

```
<!-- IF {XTRA_EVENT_START} -->
    <p>{XTRA_EVENT_START_TITLE}: {XTRA_EVENT_START}</p>
<!-- ENDIF -->
```

Значения форматируются в соответствии с типом поля (например, `datetime` будет отформатирован как `d.m.Y H:i`).

### 3. Списки страниц (`page.list.tpl`, `news.tpl` и т. д.)

Используйте теги `{LIST_ROW_XTRA_FIELDNAME}`, например:

```
<!-- IF {LIST_ROW_XTRA_EVENT_NAME} -->
    <small>{LIST_ROW_XTRA_EVENT_NAME_TITLE}: {LIST_ROW_XTRA_EVENT_NAME}</small>
<!-- ENDIF -->
```

Эти теги добавляются через хук `pagetags.main` внутри функции `cot_generate_pagetags()`.

### 4. Секция Head (`header.tpl`)

```
<!-- IF {XTRA_HEADER_EVENT_NAME} -->
    <meta name="event" content="{XTRA_HEADER_EVENT_NAME}">
<!-- ENDIF -->
```

## API для разработчиков

Функции находятся в файле `/plugins/xtradbrowpage/inc/xtradbrowpage.functions.php`.

### Получение всех зарегистрированных полей

```
$fields = xtradbrowpage_getExtrafields(); // массив из Cot::$extrafields['cot_xtradbrowpage']
```

### Загрузка записи для страницы

```
$data = xtradbrowpage_load($page_id); // возвращает ассоциативный массив с ключами field_name
```

### Сохранение (INSERT или UPDATE)

```
$values = [
    'event_name' => 'New event',
    'event_start' => 1715011200, // временная метка UNIX
    'event_ticketprice' => 100.50
];

xtradbrowpage_save($page_id, $values);
```

## Хуки плагина и их назначение

| Хук                       | Файл                                        | Действие                                                                  |
| ------------------------- | ------------------------------------------- | ------------------------------------------------------------------------- |
| `admin.extrafields.first` | `xtradbrowpage.admin.extrafields.first.php` | Добавляет расположение в список разрешённых дополнительных полей          |
| `header.tags`             | `xtradbrowpage.header.tags.php`             | Назначает теги `XTRA_HEADER_*` для текущей страницы                       |
| `page.edit.tags`          | `xtradbrowpage.page.edit.tags.php`          | Выводит поля в форме редактирования и обрабатывает блок `XTRA_EXTRAFLD`   |
| `page.edit.update.done`   | `xtradbrowpage.page.edit.update.done.php`   | Сохраняет отправленные значения в `cot_xtradbrowpage`                     |
| `page.edit.delete.done`   | `xtradbrowpage.page.edit.delete.done.php`   | Явно удаляет запись страницы                                              |
| `page.tags`               | `xtradbrowpage.page.tags.php`               | Назначает теги для страницы просмотра и обрабатывает блок `XTRA_EXTRAFLD` |
| `pagetags.main`           | `xtradbrowpage.pagetags.php`                | Добавляет теги `LIST_ROW_XTRA_*` в `$temp_array`                          |

## Практический пример: создание событий

### 1. Добавление полей

Создайте через административную панель поля: `event_name`, `event_description`, `event_start`, `event_ticketprice`, `event_seson`, используя параметры из таблицы выше.

### 2. Шаблон редактирования

```
<!-- В page.edit.tpl -->
<div class="card mt-3">
    <div class="card-header">Параметры события</div>
    <div class="card-body">
        <!-- BEGIN: XTRA_EXTRAFLD -->
        <div class="mb-3">
            <label class="form-label">{PAGEEDIT_FORM_XTRA_EXTRAFLD_TITLE}</label>
            {PAGEEDIT_FORM_XTRA_EXTRAFLD}
        </div>
        <!-- END: XTRA_EXTRAFLD -->
    </div>
</div>
```

### 3. Шаблон просмотра

```
<!-- В page.tpl -->
<!-- IF {XTRA_EVENT_NAME} -->
<div class="event-details">
    <h2>{XTRA_EVENT_NAME}</h2>
    <!-- BEGIN: XTRA_EXTRAFLD -->
    <p><strong>{XTRA_EXTRAFIELD_TITLE}:</strong> {XTRA_EXTRAFIELD_VALUE}</p>
    <!-- END: XTRA_EXTRAFLD -->
</div>
<!-- ENDIF -->
```

### 4. Вывод в списке

```
<!-- В page.list.tpl внутри блока LIST_ROWS -->
<div class="list-item">
    <a href="{LIST_ROW_URL}">{LIST_ROW_TITLE}</a>
    <!-- IF {LIST_ROW_XTRA_EVENT_START} -->
        <span class="date">{LIST_ROW_XTRA_EVENT_START}</span>
    <!-- ENDIF -->
</div>
```

## Особенности типов полей

- **datetime**: хранится как целое число (временная метка UNIX). В форме редактирования предоставляются выпадающие списки. При выводе значение форматируется согласно параметру `field_params` (например, `d.m.Y H:i`).
- **select**: варианты задаются через значения, разделённые запятыми, в `field_variants`; выбранное значение сохраняется как строка.
- **double**: сохраняется как число с плавающей точкой.
- Все поля экранируются при выводе через `cot_build_extrafields_data()` и `htmlspecialchars()`.

## Примечания

- Плагин не добавляет столбцы в таблицу `cot_pages`.
- Если страница создана без немедленного сохранения дополнительных полей, запись в `cot_xtradbrowpage` появится при первом обновлении страницы через форму редактирования.
- Для массовой вставки данных используйте API-функцию `xtradbrowpage_save()`.
- Очистка кэша не требуется, но если возникнут проблемы с отображением, очистите кэш в административной панели.

## Помощь и поддержка

- [Тема на форуме](https://abuyfile.com/ru/forums/cotonti/original/extrafields/topic205)
- [Раздел форума](https://abuyfile.com/ru/forums/cotonti/original/extrafields)

## Возможно, вам также будет интересно

- [Интеграция дополнительных полей в плагин I18n в Cotonti CMF](https://abuyfile.com/en/cotonti/authorial-plugins/integraciya-extrafields-v-plagin-i18n-v-cotonti-cmf)
- [Локализация дополнительных полей. Пошаговое руководство](https://abuyfile.com/en/cotonti/reading/lokalizaciya-ekstrapolej-v-cotonti-poshagovaya-polnaya-instrukciya-na-primere-polya-status-tovara)

#### Похожижие расширения

 -  **["Page Mass Edit"](https://github.com/webitproff/pagemassedit-cotonti/tree/main)**  
 -  **["Extrafields Market Custom"](https://github.com/webitproff/xtradbrowmarket-cotonti)**  
 -  **["Extrafields Users Custom"](https://github.com/webitproff/xtradbrowusers-cotonti)**  
 -  **["Extensions Filter for Cotonti"](https://github.com/webitproff/extensionfilter-cotonti)**  
 -  **["Drag & Drop Structure"](https://github.com/webitproff/dragstructure-cotonti)**  
 

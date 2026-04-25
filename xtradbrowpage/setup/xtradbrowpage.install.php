<?php
/**
 * xtradbrowpage.install.php – Демонстрационные экстраполя при первичной установке
 *
 * Создаёт для таблицы cot_xtradbrowpage полный комплект демо-полей всех типов,
 * поддерживаемых Cotonti Extrafields. Каждое поле сразу готово к работе, а
 * в админке можно посмотреть живые примеры оформления.
 * Пояснения и поддержка по вопросам в файле https://abuyfile.com/ru/forums/cotonti/original/extrafields/topic206
 *
 * @package xtradbrowpage
 * @version 2.7.8
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');
require_once cot_incfile('xtradbrowpage', 'plug');

global $db_xtradbrowpage;

// ====================================================================
// 1. Простое текстовое поле (input) — Название события
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица-владелец
    'event_name',                        // 2. Имя поля в БД и в шаблонах
    'input',                             // 3. Тип extrafield (input — текст)
    '<input class="form-control" type="text" name="{$name}" value="{$value}" maxlength="255" />', // 4. HTML поля в форме
    '',                                  // 5. Варианты (не нужны)
    'Новое событие',                     // 6. Значение по умолчанию
    0,                                   // 7. Обязательное? 0 — нет
    'HTML',                              // 8. Парсер для вывода
    'Название события'                   // 9. Описание в админке
);

// ====================================================================
// 2. Многострочный текст (textarea) — Описание программы события
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'event_description',                 // 2. Имя поля
    'textarea',                          // 3. Тип
    '<textarea class="form-control" name="{$name}" rows="5" cols="40">{$value}</textarea>', // 4. HTML
    '',                                  // 5. Варианты
    'Введите описание события...',       // 6. Значение по умолчанию
    0,                                   // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Описание программы события'         // 9. Описание
);

// ====================================================================
// 3. Дата и время (datetime) — Начало события
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'event_start',                       // 2. Имя поля
    'datetime',                          // 3. Тип
    '<div class="row g-2">
        <div class="col-2">{$day}</div>
        <div class="col-3">{$month}</div>
        <div class="col-2">{$year}</div>
        <div class="col-2">{$hour}</div>
        <div class="col-1 text-center">:</div>
        <div class="col-2">{$minute}</div>
    </div>',                             // 4. HTML (день, месяц, год, час, минуты)
    '',                                  // 5. Варианты
    '1714060800',                        // 6. Значение по умолчанию (timestamp 26.04.2024 00:00)
    0,                                   // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Начало события',                    // 9. Описание
    '2024,2030,d.m.Y H:i'                // 10. Параметры: диапазон лет и формат
);

// ====================================================================
// 4. Число с плавающей точкой (double) — Стоимость билета
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'event_ticketprice',                 // 2. Имя поля
    'double',                            // 3. Тип
    '<input class="form-control" type="text" name="{$name}" value="{$value}" />', // 4. HTML
    '',                                  // 5. Варианты
    '0',                                 // 6. Значение по умолчанию
    0,                                   // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Стоимость билета'                   // 9. Описание
);

// ====================================================================
// 5. Выпадающий список (select) — Сезон
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'event_seson',                       // 2. Имя поля
    'select',                            // 3. Тип
    '<select class="form-select" name="{$name}">{$options}</select>', // 4. HTML
    'unknown,winter,summer,autumn,spring', // 5. Варианты списка
    'unknown',                           // 6. Значение по умолчанию
    0,                                   // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Сезон'                              // 9. Описание
);

// ====================================================================
// 6. Целое число (inputint) — Пример целого числа
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_int',                          // 2. Имя поля
    'inputint',                          // 3. Тип — целое число
    '<input class="form-control" type="number" name="{$name}" value="{$value}" />', // 4. HTML
    '',                                  // 5. Варианты (не нужны)
    '0',                                 // 6. Значение по умолчанию
    false,                               // 7. Обязательное? — нет
    'HTML',                              // 8. Парсер
    'Пример целого числа (inputint)',    // 9. Описание
    '',                                  // 10. Параметры (не нужны)
    1,                                   // 11. Включено (1 = да)
    false,                               // 12. Пропустить ALTER TABLE (false = выполнить)
    'INT UNSIGNED NOT NULL DEFAULT 0'    // 13. Кастомный SQL-тип для БД
);

// ====================================================================
// 7. Число с плавающей точкой (double) — Демо double
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_double',                       // 2. Имя поля
    'double',                            // 3. Тип
    '<input class="form-control" type="text" name="{$name}" value="{$value}" />', // 4. HTML
    '',                                  // 5. Варианты
    '0.00',                              // 6. Значение по умолчанию
    false,                               // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Пример числа с плавающей точкой (double)', // 9. Описание
    '',                                  // 10. Параметры
    1,                                   // 11. Включено
    false,                               // 12. Выполнить ALTER TABLE
    'DOUBLE NOT NULL DEFAULT 0'          // 13. Кастомный SQL-тип
);

// ====================================================================
// 8. Выпадающий список (select) — Демо select
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_select',                       // 2. Имя поля
    'select',                            // 3. Тип
    '<select class="form-select" name="{$name}">{$options}</select>', // 4. HTML
    'Option 1,Option 2,Option 3',       // 5. Варианты
    'Option 1',                          // 6. Значение по умолчанию
    false,                               // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Пример выпадающего списка (select)' // 9. Описание
);

// ====================================================================
// 9. Флажок (checkbox) — Пример checkbox
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_checkbox',                     // 2. Имя поля
    'checkbox',                          // 3. Тип
    '<div class="form-check">
       <input class="form-check-input" type="checkbox" name="{$name}" value="1" {$checked} />
       <label class="form-check-label">Включено</label>
     </div>',                            // 4. HTML
    '',                                  // 5. Варианты (не нужны)
    '0',                                 // 6. По умолчанию — выключен
    false,                               // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Пример флажка (checkbox)'           // 9. Описание
);

// ====================================================================
// 10. Радиокнопки (radio) — Исправленный шаблон с {$value} и {$title}
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_radio',                        // 2. Имя поля
    'radio',                             // 3. Тип
    '<div class="form-check">
       <input class="form-check-input" type="radio" name="{$name}" value="{$value}" {$checked} />
       <label class="form-check-label">{$title}</label>
     </div>',                            // 4. HTML (!!!{$value} и {$title}, а не {$variant})
    'Yes,No',                            // 5. Варианты (с такими же именами, без перевода)
    'No',                                // 6. По умолчанию
    false,                               // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Пример радиокнопок (radio)'         // 9. Описание
);

// ====================================================================
// 11. Дата/время (datetime) — Демо datetime
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_datetime',                     // 2. Имя поля
    'datetime',                          // 3. Тип
    '<div class="row g-2">
      <div class="col-2">{$day}</div>
      <div class="col-3">{$month}</div>
      <div class="col-2">{$year}</div>
      <div class="col-2">{$hour}</div>
      <div class="col-1 text-center">:</div>
      <div class="col-2">{$minute}</div>
    </div>',                             // 4. HTML
    '',                                  // 5. Варианты
    '1714060800',                        // 6. Значение по умолчанию (26.04.2024)
    false,                               // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Пример выбора даты и времени (datetime)', // 9. Описание
    '2024,2030,d.m.Y H:i'                // 10. Параметры
);

// ====================================================================
// 12. Загрузка файла (file) — Пример file
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_file',                         // 2. Имя поля
    'file',                              // 3. Тип
    '<div class="list-group mb-3">
       <div class="list-group-item list-group-item-secondary">
         <strong>Текущий файл:</strong> <span class="text-primary">{$value}</span>
       </div>
       <div class="list-group-item">
         <label class="form-label">Заменить файл</label>
         <input type="file" class="form-control" name="{$name}" {$attrs}>
       </div>
       <div class="list-group-item list-group-item-danger">
         <div class="form-check">
           <input class="form-check-input" type="checkbox" name="{$delname}" value="1" id="delete_{$name}">
           <label class="form-check-label text-danger" for="delete_{$name}">
             Удалить текущий файл
           </label>
         </div>
       </div>
     </div>',                            // 4. HTML
    'jpg,png,pdf,zip',                   // 5. Разрешённые расширения ставим 'jpg,png,pdf,zip' и достаточно
    '',                                  // 6. По умолчанию (файла нет)
    false,                               // 7. Необязательное
    '',                                  // 8. Парсер (не нужен)
    'Пример загрузки файла (file)',      // 9. Описание
    'datas/exflds/xtradbrowpage'         // 10. Папка для сохранения
);

// ====================================================================
// 13. Страна (country) — Пример выбора страны
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_country',                      // 2. Имя поля
    'country',                           // 3. Тип
    '<select class="form-select" name="{$name}" size="1">
       <option value="">Выберите страну</option>{$options}
     </select>',                         // 4. HTML
    '',                                  // 5. Варианты (автоматически)
    'ae',                                // 6. Значение по умолчанию (указывать в нижнем регистре из двух символов)
    false,                               // 7. Необязательное
    '',                                  // 8. Парсер
    'Пример выбора страны (country)'     // 9. Описание
);

// ====================================================================
// 14. Ползунок (range) — Рабочий пример как выпадающий список (API использует select!)
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_range',                        // 2. Имя поля
    'range',                             // 3. Тип (в Cotonti это select с числами)
    '<select class="form-select" name="{$name}">{$options}</select>', // 4. HTML как селект
    '',                                  // 5. Варианты (генерируются автоматически)
    '50',                                // 6. Значение по умолчанию
    false,                               // 7. Необязательное
    '',                                  // 8. Парсер
    'Пример ползунка (range) — выбор числа', // 9. Описание
    '0,100'                              // 10. Параметры min,max (обязательно!)
);

// ====================================================================
// 15. Список с множественным выбором (checklistbox) — корректный шаблон
// ====================================================================
cot_extrafield_add(
    $db_xtradbrowpage,                  // 1. Таблица
    'demo_checklistbox',                 // 2. Имя поля
    'checklistbox',                      // 3. Тип
    '<div class="form-check">
       <input class="form-check-input" type="checkbox" name="{$name}" value="{$value}" {$checked} />
       <label class="form-check-label">{$title}</label>
     </div>',                            // 4. HTML (один чекбокс, API повторит для каждого варианта)
    'Опция 1,Опция 2,Опция 3',          // 5. Варианты
    'Опция 1',                           // 6. Значение по умолчанию (можно "Опция 1,Опция 3")
    false,                               // 7. Необязательное
    'HTML',                              // 8. Парсер
    'Пример checklistbox (checklistbox)' // 9. Описание
);

// ====================================================================
// Создание папки для файлов экстраполей
// ====================================================================
if (!is_dir('datas/exflds/xtradbrowpage')) {
    mkdir('datas/exflds/xtradbrowpage', 0777, true); // Создаём папку рекурсивно

    // Защита от прямого доступа
    file_put_contents('datas/exflds/xtradbrowpage/.htaccess', "Order deny,allow\nDeny from all");
}
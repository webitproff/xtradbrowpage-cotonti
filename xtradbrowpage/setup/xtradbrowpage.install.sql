-- plugins/xtradbrowpage/setup/xtradbrowpage.install.sql
-- Установочный файл: plugins/xtradbrowpage/setup/xtradbrowpage.install.sql
-- Создаёт таблицу только с itempagid – все остальные столбцы будут добавлены через API Extrafields.

-- xtradbrowpage
CREATE TABLE IF NOT EXISTS `cot_xtradbrowpage` (
    `itempagid` int UNSIGNED NOT NULL,
    PRIMARY KEY (`itempagid`),
    CONSTRAINT `fk_xtradbrowpage_pages` FOREIGN KEY (`itempagid`) REFERENCES `cot_pages` (`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица переводов
CREATE TABLE IF NOT EXISTS `cot_xtradbrowpage_i18n` (
    `itempagid` int UNSIGNED NOT NULL,
    `field_name` varchar(255) NOT NULL,
    `lang` char(2) NOT NULL DEFAULT 'en',
    `value` text,
    PRIMARY KEY (`itempagid`, `field_name`, `lang`),
    CONSTRAINT `fk_xtradbrowpage_i18n` FOREIGN KEY (`itempagid`) REFERENCES `cot_xtradbrowpage` (`itempagid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
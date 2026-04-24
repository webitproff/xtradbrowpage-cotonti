-- plugins/xtradbrowpage/setup/xtradbrowpage.install.sql
-- Установочный файл: plugins/xtradbrowpage/setup/xtradbrowpage.install.sql
-- Создаёт таблицу только с itempagid – все остальные столбцы будут добавлены через API Extrafields.

-- xtradbrowpage
CREATE TABLE IF NOT EXISTS `cot_xtradbrowpage` (
    `itempagid` int UNSIGNED NOT NULL,
    PRIMARY KEY (`itempagid`),
    CONSTRAINT `fk_xtradbrowpage_pages` FOREIGN KEY (`itempagid`) REFERENCES `cot_pages` (`page_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
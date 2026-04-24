-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Апр 24 2026 г., 15:18
-- Версия сервера: 8.4.7
-- Версия PHP: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cotontilocal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cot_extra_fields`
--

CREATE TABLE `cot_extra_fields` (
  `field_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_html` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `field_variants` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `field_params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `field_default` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `field_required` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `field_enabled` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `field_parse` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'HTML',
  `field_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cot_extra_fields`
--

INSERT INTO `cot_extra_fields` (`field_location`, `field_name`, `field_type`, `field_html`, `field_variants`, `field_params`, `field_default`, `field_required`, `field_enabled`, `field_parse`, `field_description`) VALUES
('cot_market', 'product_status', 'select', '<select class=\"form-select\" name=\"{$name}\">{$options}</select>', 'unknown,instock,outofstock,ending,onorder', '', 'unknown', 0, 1, 'HTML', 'Статус наличия товара'),
('cot_market', 'forum_link', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />{$error}', '', '', '', 0, 1, 'HTML', 'Тема на форуме'),
('cot_market', 'youtube_id', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />{$error}', '', '', '', 0, 1, 'HTML', 'Видео YouTube'),
('cot_pages', 'youtube_id', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />{$error}', '', '', '', 0, 1, 'HTML', 'Видео YouTube'),
('cot_pages', 'forum_link', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />{$error}', '', '', '', 0, 1, 'HTML', 'Тема на форуме'),
('cot_users', 'firstname', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />{$error}', '', '', '', 0, 1, 'HTML', 'firstname'),
('cot_users', 'lastname', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />{$error}', '', '', '', 0, 1, 'HTML', 'lastname'),
('cot_forum_topics', 'youtube_id', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />', '', '', '', 0, 1, 'HTML', 'Video'),
('cot_i18n_pages', 'meta_title', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />', '', '', '', 0, 1, 'HTML', 'Мета-заголовк'),
('cot_i18n_pages', 'meta_description', 'textarea', '<textarea class=\"form-control\" name=\"{$name}\" rows=\"{$rows}\" cols=\"{$cols}\" maxlength=\"255\">{$value}</textarea>', '', '', '', 0, 1, 'HTML', 'Мета описание'),
('cot_xtradbrowpage', 'event_name', 'input', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />', '', '', '', 0, 1, 'HTML', 'Название события'),
('cot_xtradbrowpage', 'event_description', 'textarea', '<textarea class=\"form-control\" name=\"{$name}\" rows=\"{$rows}\" cols=\"{$cols}\"  maxlength=\"255\">{$value}</textarea>', '', '', '', 0, 1, 'HTML', 'Описание программы события'),
('cot_xtradbrowpage', 'event_start', 'datetime', '<div class=\"row g-2\">\r\n    <div class=\"col-2\">{$day}</div>\r\n    <div class=\"col-3\">{$month}</div>\r\n    <div class=\"col-2\">{$year}</div>\r\n    <div class=\"col-2\">{$hour}</div>\r\n    <div class=\"col-1 text-center\">:</div>\r\n    <div class=\"col-2\">{$minute}</div>\r\n</div>', '', '2024,2030,d.m.Y H:i', '', 0, 1, 'HTML', 'Начало события'),
('cot_xtradbrowpage', 'event_ticketprice', 'double', '<input class=\"form-control\" type=\"text\" name=\"{$name}\" value=\"{$value}\"  maxlength=\"255\" />', '', '', '', 0, 1, 'HTML', 'стоимость билета'),
('cot_xtradbrowpage', 'event_seson', 'select', '<select class=\"form-select\" name=\"{$name}\">{$options}</select>', 'unknown,winter,summer,autumn,spring', '', 'unknown', 0, 1, 'HTML', 'Сезон');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cot_extra_fields`
--
ALTER TABLE `cot_extra_fields`
  ADD KEY `field_location` (`field_location`),
  ADD KEY `field_name` (`field_name`);
COMMIT;


--
-- Структура таблицы `cot_xtradbrowpage`
--

CREATE TABLE `cot_xtradbrowpage` (
  `itempagid` int UNSIGNED NOT NULL,
  `event_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `event_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `event_start` int DEFAULT '0',
  `event_ticketprice` double DEFAULT '0',
  `event_seson` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cot_xtradbrowpage`
--

INSERT INTO `cot_xtradbrowpage` (`itempagid`, `event_name`, `event_description`, `event_start`, `event_ticketprice`, `event_seson`) VALUES
(60, 'ИИ модели 2026 года', 'Выставка достижений человека, сделанных при помощи различных ИИ моделей в 2026 году', 1787062200, 55, 'summer'),
(62, 'Презентация плагина', 'Будет много вкусного. приглашаем всех. вход свободный, но есть VIP-зона', 1781086200, 25, '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cot_xtradbrowpage`
--
ALTER TABLE `cot_xtradbrowpage`
  ADD PRIMARY KEY (`itempagid`);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cot_xtradbrowpage`
--
ALTER TABLE `cot_xtradbrowpage`
  ADD CONSTRAINT `fk_xtradbrowpage_pages` FOREIGN KEY (`itempagid`) REFERENCES `cot_pages` (`page_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


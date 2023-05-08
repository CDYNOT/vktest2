-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Май 08 2023 г., 18:49
-- Версия сервера: 5.7.21-20-beget-5.7.21-20-1-log
-- Версия PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cdynot_vktest2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--
-- Создание: Май 08 2023 г., 15:39
-- Последнее обновление: Май 08 2023 г., 15:46
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name_id` int(11) NOT NULL,
  `auth` tinyint(1) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `name_id`, `auth`, `ip`, `date`) VALUES
(1, 1, 1, '178.155.29.8', '2023-05-06 18:40:11'),
(2, 2, 0, '178.155.29.8', '2023-05-06 18:40:13'),
(3, 3, 0, '178.155.29.8', '2023-05-06 18:40:16'),
(4, 4, 1, '178.155.29.8', '2023-05-06 18:40:19'),
(5, 5, 0, '178.155.29.8', '2023-05-06 18:40:22'),
(6, 6, 1, '178.155.29.8', '2023-05-06 18:40:26'),
(7, 7, 1, '178.155.29.8', '2023-05-07 18:40:29'),
(8, 8, 0, '178.155.29.8', '2023-05-07 18:40:35'),
(9, 2, 1, '147.107.220.114', '2023-05-07 18:42:50'),
(10, 3, 1, '147.107.220.114', '2023-05-07 18:43:02'),
(11, 4, 1, '147.107.220.114', '2023-05-07 18:43:06'),
(12, 7, 1, '147.107.220.114', '2023-05-07 18:43:08'),
(13, 1, 0, '98.124.157.165', '2023-05-08 18:43:15'),
(14, 3, 0, '98.124.157.165', '2023-05-08 18:43:18'),
(15, 6, 0, '98.124.157.165', '2023-05-08 18:43:20'),
(16, 8, 0, '98.124.157.165', '2023-05-08 18:43:22'),
(17, 2, 0, '98.124.157.165', '2023-05-08 18:43:58'),
(18, 2, 0, '98.124.157.165', '2023-05-08 18:44:00'),
(19, 2, 0, '98.124.157.165', '2023-05-08 18:44:02');

-- --------------------------------------------------------

--
-- Структура таблицы `events_name`
--
-- Создание: Май 05 2023 г., 11:18
-- Последнее обновление: Май 05 2023 г., 10:53
--

DROP TABLE IF EXISTS `events_name`;
CREATE TABLE `events_name` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `events_name`
--

INSERT INTO `events_name` (`id`, `name`) VALUES
(1, 'view'),
(2, 'click'),
(3, 'open'),
(4, 'delete'),
(5, 'post'),
(6, 'rename'),
(7, 'like'),
(8, 'reaction');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`name_id`);

--
-- Индексы таблицы `events_name`
--
ALTER TABLE `events_name`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `events_name`
--
ALTER TABLE `events_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`name_id`) REFERENCES `events_name` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

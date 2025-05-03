-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: phone-contacts-mysql
-- 生成日時: 2025 年 5 月 03 日 08:01
-- サーバのバージョン： 8.0.31
-- PHP のバージョン: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `phone-contacts`
--
CREATE DATABASE IF NOT EXISTS `phone-contacts` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `phone-contacts`;

-- --------------------------------------------------------

--
-- テーブルの構造 `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '名前',
  `kana` varchar(255) DEFAULT NULL COMMENT 'ふりがな',
  `birthday` date DEFAULT NULL COMMENT '生年月日',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `created` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `mails`
--

CREATE TABLE `mails` (
  `id` int NOT NULL COMMENT 'ID',
  `contact_id` int NOT NULL COMMENT 'contacts.id',
  `mail` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `created` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `phones`
--

CREATE TABLE `phones` (
  `id` int NOT NULL COMMENT 'ID',
  `contact_id` int NOT NULL COMMENT 'contacts.id',
  `number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '電話番号',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `created` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `mails`
--
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- テーブルのインデックス `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- テーブルの AUTO_INCREMENT `mails`
--
ALTER TABLE `mails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- テーブルの AUTO_INCREMENT `phones`
--
ALTER TABLE `phones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

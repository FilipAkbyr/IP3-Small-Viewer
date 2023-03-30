-- Adminer 4.7.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

/* Tento  řádek smažte, pokud chcete jen obnovit data */
CREATE DATABASE `ip_3` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci */;


USE `ip_3`;

DROP TABLE IF EXISTS `room`;
CREATE TABLE `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `no` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci DEFAULT NULL,
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `no` (`no`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `room` (`room_id`, `no`, `name`, `phone`) VALUES
(1,	'101',	'Ředitelna',	'2292'),
(2,	'102',	'Kuchyňka',	'2293'),
(3,	'104',	'Zasedací místnost',	'2294'),
(4,	'201',	'Xerox',	'2296'),
(5,	'202',	'Ekonomické',	'2295'),
(6,	'203',	'Toalety',	NULL),
(7,	'001',	'Dílna',	'2241'),
(8,	'002',	'Sklad',	'2243'),
(11,	'003',	'Šatna',	NULL);


DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `job` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `wage` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `admin` boolean NOT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `room` (`room`),
  CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`room`) REFERENCES `room` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `employee` (`employee_id`, `name`, `surname`, `job`, `wage`, `room`, `login`, `pass`, `admin`) VALUES
(1,	'František',	'Netěsný',	'ředitel',	65000,	1, 'frantisek', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', true),
(3,	'Alena',	'Netěsná',	'ekonomka',	42000,	5, 'alena', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', false),
(4,	'Jiřina',	'Hamáčková',	'ekonomka',	32000,	5, 'jirina', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', false),
(5,	'Filip',	'Rybka',	'ředitel',	65000,	1, 'Admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', true),
(6,	'Pepa',	'Smítko',	'ředitelka',	65000,	1, 'User', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', false);



DROP TABLE IF EXISTS `key`;
CREATE TABLE `key` (
  `key_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  PRIMARY KEY (`key_id`),
  UNIQUE KEY `employee_room` (`employee`,`room`),
  KEY `room` (`room`),
  CONSTRAINT `key_ibfk_1` FOREIGN KEY (`employee`) REFERENCES `employee` (`employee_id`),
  CONSTRAINT `key_ibfk_2` FOREIGN KEY (`room`) REFERENCES `room` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `key` (`key_id`, `employee`, `room`) VALUES
(1,	1,	1),
(19,	1,	2),
(20,	1,	3),
(21,	1,	4),
(22,	1,	5),
(23,	1,	6),
(16,	1,	7),
(17,	1,	8),
(18,	1,	11),
(46,	3,	1),
(47,	3,	2),
(35,	3,	6),
(48,	4,	2),
(36,	4,	6);
-- 2020-10-07 08:20:27

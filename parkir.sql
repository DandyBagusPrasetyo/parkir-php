-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for parkir
CREATE DATABASE IF NOT EXISTS `parkir` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `parkir`;

-- Dumping structure for table parkir.members
CREATE TABLE IF NOT EXISTS `members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `vehicle_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_model` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_color` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `card_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table parkir.members: ~2 rows (approximately)
REPLACE INTO `members` (`id`, `name`, `phone`, `address`, `vehicle_type`, `vehicle_model`, `vehicle_color`, `vehicle_number`, `card_code`) VALUES
	(1, 'Prasetyo Bagus Dandy', '202143500359', 'Kelapa Gading', 'Mobil', 'Toyota Fortuner', 'HITAM', 'B3012USH', 'MBL-B3012USH'),
	(2, 'Nofal Kristanto', '08828129921', 'Lubang Buaya', 'Sepeda Motor', 'Honda Vario 125', 'PUTIH', 'B3290TAS', 'MTR-B3290TAS');

-- Dumping structure for table parkir.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_number` int DEFAULT NULL,
  `member_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `vehicle_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vehicle_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_transactions_members` (`member_id`),
  KEY `FK_transactions_users` (`user_id`),
  CONSTRAINT `FK_transactions_members` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  CONSTRAINT `FK_transactions_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table parkir.transactions: ~15 rows (approximately)
REPLACE INTO `transactions` (`id`, `ticket_number`, `member_id`, `user_id`, `vehicle_type`, `vehicle_number`, `date`, `time`, `status`, `payment`, `price`) VALUES
	(1, NULL, 1, 1, NULL, 'B3012USH', '2024-06-18', '08:48:47', 'IN', 'Cash', 0),
	(2, NULL, 1, 1, NULL, 'B3012USH', '2024-06-18', '08:48:49', 'OUT', 'Cash', 0),
	(3, 3084857, NULL, 1, NULL, '', '2024-06-26', '14:09:36', 'OUT', 'Cash', 990000),
	(4, 4084902, NULL, 1, NULL, '', '2024-06-26', '14:10:06', 'OUT', 'Cash', 594000),
	(5, 5135350, NULL, 1, NULL, '', '2024-06-26', '14:10:22', 'OUT', 'Cash', 3000),
	(6, 6135602, NULL, 1, NULL, '', '2024-06-26', '14:11:05', 'OUT', 'Cash', 3000),
	(7, 7135816, NULL, 1, NULL, 'B832ras', '2024-06-26', '13:58:16', 'IN', 'Cash', 3000),
	(8, 8135828, NULL, 1, NULL, '', '2024-06-26', '13:58:28', 'IN', 'Cash', 3000),
	(9, 9135841, NULL, 1, NULL, '', '2024-06-26', '13:58:41', 'IN', 'Cash', 3000),
	(10, 10140049, NULL, 1, NULL, '', '2024-06-26', '14:01:02', 'OUT', 'Cash', 3000),
	(11, 11140335, NULL, 1, NULL, '', '2024-06-26', '14:03:38', 'OUT', 'Cash', 3000),
	(12, 12140604, NULL, 1, NULL, '', '2024-06-26', '14:06:07', 'OUT', 'Cash', 3000),
	(13, 13140750, NULL, 1, NULL, '', '2024-06-26', '14:07:53', 'OUT', 'Cash', 5000),
	(14, 14140902, NULL, 1, NULL, '', '2024-06-26', '14:09:15', 'OUT', 'Cash', 3000),
	(15, 15141214, NULL, 1, NULL, '', '2024-06-26', '14:12:18', 'OUT', 'Cash', 5000),
	(16, 16141357, NULL, 1, NULL, '', '2024-06-26', '14:14:01', 'OUT', 'Cash', 3000),
	(17, 17141722, NULL, 1, 'Mobil', '', '2024-06-26', '14:17:25', 'OUT', 'Cash', 5000),
	(18, 18142733, NULL, 1, 'Mobil', '', '2024-06-26', '14:27:37', 'OUT', 'Cash', 5000),
	(19, 19142830, NULL, 1, 'Sepeda Motor', '', '2024-06-26', '14:28:33', 'OUT', 'Cash', 3000),
	(20, 20142859, NULL, 1, 'Sepeda Motor', '', '2024-06-26', '14:29:02', 'OUT', 'Cash', 3000),
	(21, 21143918, NULL, 1, 'Sepeda Motor', '', '2024-06-26', '14:39:20', 'OUT', 'Cash', 3000),
	(22, 22145404, NULL, 1, 'Sepeda Motor', '', '2024-06-26', '14:54:07', 'OUT', 'Cash', 3000),
	(23, 23145412, NULL, 1, 'Sepeda Motor', 'asd', '2024-06-26', '14:54:17', 'OUT', 'Cash', 3000),
	(24, 24145553, NULL, 1, 'Sepeda Motor', 'sadwa', '2024-06-26', '14:55:56', 'OUT', 'Cash', 3000),
	(25, 25145608, NULL, 1, 'Mobil', 'asda', '2024-06-26', '14:56:50', 'OUT', 'Cash', 5000),
	(26, 26145827, NULL, 1, 'Mobil', '', '2024-06-26', '14:58:35', 'OUT', 'Cash', 5000),
	(27, 27145856, NULL, 1, 'Mobil', 'asa', '2024-06-26', '14:59:01', 'OUT', 'Cash', 5000),
	(28, 28150453, NULL, 1, 'Sepeda Motor', 'asw', '2024-06-26', '15:05:00', 'OUT', 'Cash', 3000),
	(29, 29150701, NULL, 1, 'Sepeda Motor', '', '2024-06-26', '15:07:03', 'OUT', 'Cash', 3000),
	(30, 30150921, NULL, 1, 'Sepeda Motor', '', '2024-06-26', '15:09:24', 'OUT', 'Cash', 3000);

-- Dumping structure for table parkir.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table parkir.users: ~3 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`) VALUES
	(1, 'Dandy Bagus Prasetyo', 'dandy@gmail.com', '$2y$10$LFD0.A8GI8G/85X38.JR8.WXm3KwfCG9Ka.c2URRRd6XoIJK/gB8K', '202143500359', 'management'),
	(2, 'Bagus', 'bagus@gmail.com', '$2y$10$u.AEUkmPtY3GKbKqPmHmEedUM6p2hSiTzmd3a1RrdV4eSyeZueh52', '202143500359', 'employee'),
	(3, 'Prasetyo', 'prasetyo@gmail.com', '$2y$10$KmYhdYojvq430DNR8Q9ctOPi5gfyde/VePVZyhedqMhqCbmSKj8Pq', '08902912131', 'employee');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

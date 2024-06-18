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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table parkir.transactions: ~4 rows (approximately)
REPLACE INTO `transactions` (`id`, `ticket_number`, `member_id`, `user_id`, `vehicle_number`, `date`, `time`, `status`, `payment`, `price`) VALUES
	(1, NULL, 1, 1, 'B3012USH', '2024-06-18', '08:48:47', 'IN', 'Cash', 0),
	(2, NULL, 1, 1, 'B3012USH', '2024-06-18', '08:48:49', 'OUT', 'Cash', 0),
	(3, 3084857, NULL, 1, '', '2024-06-18', '08:48:57', 'IN', 'Cash', 5000),
	(4, 4084902, NULL, 1, '', '2024-06-18', '08:49:02', 'IN', 'Cash', 3000);

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

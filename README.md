# Tugas Pemrograman Web PHP - Parkir XYZ

## 1. Tabel Database

- users (Menyimpan data pengguna staff parkir)
- members (Menyimpan data member parkir)
- transactions (Menyimpan data transaksi keluar/masuk)

Untuk query lebih lengkap bisa lihat pada file `parkir.sql`

## 2. Spesifikasi

- PHP versi 8.0 or later
- JavaScript Vanilla
- JQUERY
- AJAX
- BOOTSTRAP CSS
- MySQL versi 8.0 or later

## 3. Cara Install

- Install WebServer, PHP, MySQL sesuai dengan spesifikasi diatas (bisa pakai XAMPP atau semacamnya)
- Download manual atau clone menggunakan git di folder public (misal htdocs/html)<br>
  `git clone https://github.com/DandyBagusPrasetyo/parkir-php.git`
- Kemudian Buat dan Import file `parkir.sql` ke dalam database
- Sesuaikan konfigurasi koneksi mysql di file `koneksi.php`
- Buka browser dan masukan nama path (contoh http://localhost/parkir-php)

## 4. Demo / Live Preview

- https://tugas.zoinix.com

## 5. Query SQL Database (lengkap di file `parkir.sql`)

- create database

`` CREATE DATABASE `parkir`;``

`` USE `parkir`;  ``

- users tabel

`` CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
); ``

- members tabel

`` CREATE TABLE `members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL,
  `vehicle_model` varchar(50) DEFAULT NULL,
  `vehicle_color` varchar(50) DEFAULT NULL,
  `vehicle_number` varchar(50) DEFAULT NULL,
  `card_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);``

- transaction tabel

`` CREATE TABLE `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_number` int DEFAULT NULL,
  `member_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL,
  `vehicle_number` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `payment` varchar(50) DEFAULT NULL,
  `price` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_transactions_members` (`member_id`),
  KEY `FK_transactions_users` (`user_id`),
  CONSTRAINT `FK_transactions_members` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  CONSTRAINT `FK_transactions_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
); ``

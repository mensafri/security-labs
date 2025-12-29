-- Database Configuration for Security Labs
-- Import this file into phpMyAdmin or your MySQL client

CREATE DATABASE IF NOT EXISTS `praktikum_keamanan`;
USE `praktikum_keamanan`;

-- Table Structure for Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping Data for Users
-- Passwords are plain text '12345' for educational demonstrations (SQLi/Cracking)
INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin', '12345', 'admin@securitylabs.local', 'admin'),
(2, 'korban', '12345', 'korban@gmail.com', 'user');

-- Table Structure for Notes (Used in IDOR Lab)
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping Data for Notes
INSERT INTO `notes` (`id`, `user_id`, `title`, `content`) VALUES
(1, 1, 'Rahasia Admin', 'Password server adalah: Sup3rS3cur3P@ssw0rd! Jangan sampai bocor.'),
(2, 2, 'Catatan Harian', 'Hari ini saya belajar tentang IDOR. Sangat menarik!'),
(3, 2, 'To-Do List', '1. Beli kopi\n2. Kerjakan tugas Kriptografi');

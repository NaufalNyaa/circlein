-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 07:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_circlein`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 2, 'register', 'New user registered', '::1', '2025-11-26 16:34:00'),
(2, 2, 'login', 'User logged in', '::1', '2025-11-26 16:34:10'),
(3, 2, 'profile_update', 'User updated profile', '::1', '2025-11-26 16:34:41'),
(4, 2, 'create_lobby', 'Created lobby: Cari Sparing FF', '::1', '2025-11-26 16:36:13'),
(5, 2, 'profile_update', 'User updated profile', '::1', '2025-11-26 16:37:28'),
(6, 2, 'login', 'User logged in', '::1', '2025-11-26 16:51:18'),
(7, 3, 'register', 'New user registered', '::1', '2025-11-26 17:12:44'),
(8, 3, 'login', 'User logged in', '::1', '2025-11-26 17:12:58'),
(9, 3, 'join_lobby', 'Joined lobby: Cari Sparing FF', '::1', '2025-11-26 17:13:13'),
(10, 3, 'profile_update', 'User updated profile', '::1', '2025-11-26 17:14:33'),
(11, 3, 'create_lobby', 'Created lobby: Gym besok', '::1', '2025-11-26 17:18:42'),
(12, 3, 'logout', 'User logged out', '::1', '2025-11-26 17:20:32'),
(13, 2, 'login', 'User logged in', '::1', '2025-11-26 17:24:05'),
(14, 2, 'logout', 'User logged out', '::1', '2025-11-26 17:25:40'),
(15, 4, 'register', 'New user registered', '::1', '2025-11-26 17:26:16'),
(16, 2, 'login', 'User logged in', '::1', '2025-11-26 17:28:02'),
(17, 2, 'logout', 'User logged out', '::1', '2025-11-26 17:30:50'),
(18, 4, 'login', 'User logged in', '::1', '2025-11-26 17:31:03'),
(19, 4, 'profile_update', 'User updated profile', '::1', '2025-11-26 17:31:41'),
(20, 4, 'logout', 'User logged out', '::1', '2025-11-26 17:31:55'),
(21, 2, 'login', 'User logged in', '::1', '2025-11-26 17:32:27'),
(22, 2, 'create_lobby', 'Created lobby: Cari temen ngopi bareng', '::1', '2025-11-26 17:33:57'),
(23, 2, 'join_lobby', 'Joined lobby: Gym besok', '::1', '2025-11-26 17:34:14'),
(24, 2, 'logout', 'User logged out', '::1', '2025-11-26 17:34:20'),
(25, 4, 'login', 'User logged in', '::1', '2025-11-26 17:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `lobbies`
--

CREATE TABLE `lobbies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `judul_aktivitas` varchar(255) NOT NULL,
  `kategori` enum('Game','Olahraga','Belajar','Hangout','Kompetisi') NOT NULL,
  `game_type` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `waktu_kumpul` datetime DEFAULT NULL,
  `max_players` int(11) DEFAULT 5,
  `current_players` int(11) DEFAULT 1,
  `status` enum('Open','Full','Closed') DEFAULT 'Open',
  `featured` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lobbies`
--

INSERT INTO `lobbies` (`id`, `user_id`, `judul_aktivitas`, `kategori`, `game_type`, `deskripsi`, `lokasi`, `waktu_kumpul`, `max_players`, `current_players`, `status`, `featured`, `views`, `created_at`, `updated_at`) VALUES
(1, 2, 'Cari Sparing FF', 'Game', 'Free Fire', 'bebas sampingan, no cheat + on live tiktok, langgar denda 12jt', 'Kragilan, Serang Banten', '2025-11-29 23:35:00', 4, 2, 'Open', 0, 8, '2025-11-26 16:36:13', '2025-11-26 17:32:59'),
(2, 3, 'Gym besok', 'Olahraga', '', 'cari partner gym sekitar kampus uin banten', 'Kampus 2 uin sultan maulana hasanuddin banten', '2025-11-28 00:18:00', 2, 2, 'Full', 1, 5, '2025-11-26 17:18:42', '2025-11-26 17:34:14'),
(3, 2, 'Cari temen ngopi bareng', 'Hangout', '', 'infokan tempat coffeshop terdekat sekitar kota serang', 'Serang, Banten', '2025-11-29 00:33:00', 10, 1, 'Open', 0, 1, '2025-11-26 17:33:57', '2025-11-26 17:33:57');

-- --------------------------------------------------------

--
-- Table structure for table `lobby_members`
--

CREATE TABLE `lobby_members` (
  `id` int(11) NOT NULL,
  `lobby_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'accepted',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lobby_members`
--

INSERT INTO `lobby_members` (`id`, `lobby_id`, `user_id`, `status`, `joined_at`) VALUES
(1, 1, 2, 'accepted', '2025-11-26 16:36:13'),
(2, 1, 3, 'accepted', '2025-11-26 17:13:13'),
(3, 2, 3, 'accepted', '2025-11-26 17:18:42'),
(4, 3, 2, 'accepted', '2025-11-26 17:33:57'),
(5, 2, 2, 'accepted', '2025-11-26 17:34:14');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('lobby_join','lobby_full','new_rating','system') NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `lobby_id` int(11) NOT NULL,
  `rater_id` int(11) NOT NULL,
  `rated_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `bio` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `rank` enum('Bronze','Silver','Gold','Platinum','Diamond') DEFAULT 'Bronze',
  `total_lobbies` int(11) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `status` enum('active','banned') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `avatar`, `bio`, `role`, `rank`, `total_lobbies`, `rating`, `status`, `created_at`, `last_login`) VALUES
(2, 'MbullGacor', 'mbull@gmail.com', '$2y$10$asVVokhQHZczb0J.C37CbeKPcAQOfHGxkqBXqnV6p8D3sp0hqcPlK', 'NAUFAL AFAF EKAYANA', '69272cc8a46e5.png', 'Seorang hengker abadi', 'user', 'Bronze', 2, 0.00, 'active', '2025-11-26 16:34:00', '2025-11-26 17:32:27'),
(3, 'BlackPanther', 'panther@gmail.com', '$2y$10$Titw/TuMvrXuZTN/ftXH7eQIcUmTrI6FwoZyK5dRm9y0bpRQ0P6/G', 'Devayana Uttara Bharatwadja', '6927357975e40.png', 'Jangan pernah menjadi bintang, tapi jadilah awan yang menutupi para bintang-bintang', 'user', 'Bronze', 1, 0.00, 'active', '2025-11-26 17:12:44', '2025-11-26 17:12:58'),
(4, 'admin', 'admin@gmail.com', '$2y$10$uyrU6YEVLuKQ6os81N/R4Ob6QegSe9Fjf3b2emfSKkXZGZn83SrrS', 'admin_mbull', '6927397d3f2b5.png', 'Pangeran Keabadian', 'admin', 'Diamond', 0, 0.00, 'active', '2025-11-26 17:26:16', '2025-11-26 17:36:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `lobbies`
--
ALTER TABLE `lobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `lobby_members`
--
ALTER TABLE `lobby_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_member` (`lobby_id`,`user_id`),
  ADD KEY `idx_lobby_id` (`lobby_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rating` (`lobby_id`,`rater_id`,`rated_id`),
  ADD KEY `idx_rated_id` (`rated_id`),
  ADD KEY `rater_id` (`rater_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `lobbies`
--
ALTER TABLE `lobbies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lobby_members`
--
ALTER TABLE `lobby_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lobbies`
--
ALTER TABLE `lobbies`
  ADD CONSTRAINT `lobbies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lobby_members`
--
ALTER TABLE `lobby_members`
  ADD CONSTRAINT `lobby_members_ibfk_1` FOREIGN KEY (`lobby_id`) REFERENCES `lobbies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lobby_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`lobby_id`) REFERENCES `lobbies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`rater_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`rated_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

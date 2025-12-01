-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql306.byetcluster.com
-- Waktu pembuatan: 01 Des 2025 pada 03.39
-- Versi server: 11.4.7-MariaDB
-- Versi PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40532494_db_circlein`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
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
-- Dumping data untuk tabel `activity_logs`
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
(25, 4, 'login', 'User logged in', '::1', '2025-11-26 17:36:10'),
(26, 5, 'register', 'New user registered', '38.225.123.209', '2025-11-27 07:17:47'),
(27, 5, 'login', 'User logged in', '38.225.123.209', '2025-11-27 07:18:00'),
(28, 6, 'register', 'New user registered', '180.243.0.17', '2025-11-27 07:19:04'),
(29, 6, 'login', 'User logged in', '180.243.0.17', '2025-11-27 07:19:20'),
(30, 6, 'join_lobby', 'Joined lobby: Cari temen ngopi bareng', '180.243.0.17', '2025-11-27 07:19:35'),
(31, NULL, 'register', 'New user registered', '203.83.45.75', '2025-11-27 07:19:45'),
(32, 5, 'profile_update', 'User updated profile', '38.225.123.209', '2025-11-27 07:20:00'),
(33, 4, 'login', 'User logged in', '203.83.45.75', '2025-11-27 07:20:53'),
(34, 6, 'profile_update', 'User updated profile', '180.243.0.17', '2025-11-27 07:22:34'),
(35, 4, 'delete_user', 'Deleted user ID: 7', '203.83.45.75', '2025-11-27 07:24:39'),
(36, 6, 'login', 'User logged in', '114.10.41.0', '2025-11-27 07:31:00'),
(37, 6, 'profile_update', 'User updated profile', '114.10.41.0', '2025-11-27 07:35:01'),
(38, 6, 'profile_update', 'User updated profile', '114.10.41.0', '2025-11-27 07:35:06'),
(39, 8, 'register', 'New user registered', '180.244.68.148', '2025-11-27 07:35:37'),
(40, 8, 'login', 'User logged in', '180.244.68.148', '2025-11-27 07:35:45'),
(41, 8, 'profile_update', 'User updated profile', '180.244.68.148', '2025-11-27 07:37:33'),
(42, 8, 'join_lobby', 'Joined lobby: Cari temen ngopi bareng', '180.244.68.148', '2025-11-27 07:37:58'),
(43, 8, 'join_lobby', 'Joined lobby: Cari Sparing FF', '180.244.68.148', '2025-11-27 07:40:36'),
(44, NULL, 'register', 'New user registered', '203.83.45.75', '2025-11-27 07:40:55'),
(45, NULL, 'login', 'User logged in', '203.83.45.75', '2025-11-27 07:41:10'),
(46, NULL, 'profile_update', 'User updated profile', '203.83.45.75', '2025-11-27 07:42:10'),
(47, 8, 'logout', 'User logged out', '180.244.68.148', '2025-11-27 07:42:47'),
(48, 8, 'login', 'User logged in', '180.244.68.148', '2025-11-27 07:43:01'),
(49, 4, 'delete_user', 'Deleted user ID: 9', '203.83.45.75', '2025-11-27 07:49:13'),
(50, 4, 'logout', 'User logged out', '203.83.45.75', '2025-11-27 07:49:48'),
(51, 3, 'login', 'User logged in', '203.83.45.75', '2025-11-27 07:50:00'),
(52, 3, 'create_lobby', 'Created lobby: Turnamen PES 2021', '203.83.45.75', '2025-11-27 08:37:14'),
(53, 3, 'login', 'User logged in', '114.8.197.35', '2025-11-27 10:38:24'),
(54, 3, 'logout', 'User logged out', '114.8.197.35', '2025-11-27 10:39:07'),
(55, 4, 'login', 'User logged in', '114.8.197.35', '2025-11-27 10:39:19'),
(56, 4, 'logout', 'User logged out', '114.8.197.35', '2025-11-27 10:40:32'),
(57, 6, 'create_lobby', 'Created lobby: Cari Sepuh Network engineering', '180.243.0.17', '2025-11-27 12:48:01'),
(58, 3, 'join_lobby', 'Joined lobby: Cari Sepuh Network engineering', '103.47.132.126', '2025-11-27 12:53:09'),
(59, 3, 'logout', 'User logged out', '103.47.132.126', '2025-11-27 12:54:34'),
(60, 4, 'login', 'User logged in', '103.47.132.126', '2025-11-27 12:54:50'),
(61, 4, 'join_lobby', 'Joined lobby: Turnamen PES 2021', '103.47.132.126', '2025-11-27 13:16:26'),
(62, 4, 'leave_lobby', 'Left lobby: Turnamen PES 2021', '103.47.132.126', '2025-11-27 13:16:35'),
(63, 4, 'change_rank', 'Changed user ID 2 rank to: Platinum', '103.47.132.126', '2025-11-27 13:17:03'),
(64, 4, 'logout', 'User logged out', '103.47.132.126', '2025-11-27 13:17:53'),
(65, 3, 'login', 'User logged in', '103.47.132.126', '2025-11-27 13:18:04'),
(66, 3, 'update_lobby', 'Updated lobby: Turnamen PES 2021', '103.47.132.126', '2025-11-27 13:19:13'),
(67, 6, 'update_lobby', 'Updated lobby: Cari Sepuh Network engineering', '180.243.0.17', '2025-11-27 13:37:33'),
(68, 6, 'update_lobby', 'Updated lobby: Cari Sepuh Network engineering', '180.243.0.17', '2025-11-27 13:37:33'),
(69, 10, 'register', 'New user registered', '180.243.0.17', '2025-11-29 02:19:00'),
(70, 10, 'login', 'User logged in', '180.243.0.17', '2025-11-29 02:19:14'),
(71, 4, 'login', 'User logged in', '110.136.46.179', '2025-11-29 16:44:09'),
(72, 4, 'change_rank', 'Changed user ID 6 rank to: Silver', '110.136.46.179', '2025-11-29 16:44:49'),
(73, 4, 'logout', 'User logged out', '110.136.46.179', '2025-11-29 16:51:49'),
(74, 4, 'login', 'User logged in', '110.136.46.179', '2025-11-29 16:54:39'),
(75, 10, 'login', 'User logged in', '180.243.0.17', '2025-11-30 02:53:36'),
(76, 10, 'profile_update', 'User updated profile', '180.243.0.17', '2025-11-30 02:54:30'),
(77, 10, 'create_lobby', 'Created lobby: Cozy Learning', '180.243.0.17', '2025-11-30 02:59:57'),
(78, 10, 'join_lobby', 'Joined lobby: Cari Sepuh Network engineering', '180.243.0.17', '2025-11-30 03:00:54'),
(79, 10, 'logout', 'User logged out', '180.243.0.17', '2025-11-30 03:01:19'),
(80, 6, 'login', 'User logged in', '180.243.0.17', '2025-11-30 03:01:30'),
(81, 6, 'logout', 'User logged out', '180.243.0.17', '2025-11-30 03:01:49'),
(82, 6, 'login', 'User logged in', '180.243.0.17', '2025-11-30 03:03:02'),
(83, 6, 'logout', 'User logged out', '180.243.0.17', '2025-11-30 03:14:25'),
(84, 4, 'login', 'User logged in', '180.243.0.17', '2025-11-30 03:14:34'),
(85, 2, 'login', 'User logged in', '103.73.193.110', '2025-12-01 03:27:28'),
(86, 2, 'update_lobby', 'Updated lobby: catur', '103.73.193.110', '2025-12-01 03:28:04'),
(87, 2, 'logout', 'User logged out', '103.73.193.110', '2025-12-01 03:28:23'),
(88, 4, 'login', 'User logged in', '103.73.193.110', '2025-12-01 03:28:34'),
(89, 4, 'change_rank', 'Changed user ID 8 rank to: Platinum', '103.73.193.110', '2025-12-01 03:28:53'),
(90, 4, 'logout', 'User logged out', '103.73.193.110', '2025-12-01 03:29:30'),
(91, 2, 'login', 'User logged in', '103.73.193.110', '2025-12-01 03:29:40'),
(92, 2, 'profile_update', 'User updated profile', '103.73.193.110', '2025-12-01 03:30:58'),
(93, 2, 'update_lobby', 'Updated lobby: ngopi Bareng Mbull', '103.73.193.110', '2025-12-01 03:31:26'),
(94, 6, 'login', 'User logged in', '180.243.11.211', '2025-12-01 03:37:31'),
(95, 2, 'join_lobby', 'Joined lobby: Cozy Learning', '103.73.193.110', '2025-12-01 07:53:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lobbies`
--

CREATE TABLE `lobbies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `judul_aktivitas` varchar(255) NOT NULL,
  `kategori` enum('Game','Olahraga','Belajar','Hangout','Kompetisi') NOT NULL,
  `game_type` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `whatsapp_group` varchar(255) DEFAULT NULL,
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
-- Dumping data untuk tabel `lobbies`
--

INSERT INTO `lobbies` (`id`, `user_id`, `judul_aktivitas`, `kategori`, `game_type`, `deskripsi`, `whatsapp_group`, `lokasi`, `waktu_kumpul`, `max_players`, `current_players`, `status`, `featured`, `views`, `created_at`, `updated_at`) VALUES
(1, 2, 'Cari Sparing FF', 'Game', 'Free Fire', 'bebas sampingan, no cheat + on live tiktok, langgar denda 12jt', NULL, 'Kragilan, Serang Banten', '2025-11-29 23:35:00', 4, 3, 'Open', 0, 16, '2025-11-26 16:36:13', '2025-11-29 11:02:39'),
(2, 3, 'Gym besok', 'Olahraga', '', 'cari partner gym sekitar kampus uin banten', NULL, 'Kampus 2 uin sultan maulana hasanuddin banten', '2025-11-28 00:18:00', 2, 2, 'Full', 0, 8, '2025-11-26 17:18:42', '2025-11-28 16:26:52'),
(3, 2, 'ngopi Bareng Mbull', 'Hangout', '', 'infokan tempat coffeshop terdekat sekitar kota serang', NULL, 'Serang, Banten', '2025-11-29 00:33:00', 10, 3, 'Open', 0, 15, '2025-11-26 17:33:57', '2025-12-01 03:31:26'),
(4, 3, 'Turnamen PES 2021', 'Kompetisi', 'Lainnya', 'Open 18 team untuk turnamen Pro Evolution Soccer 2021, season update', 'https://chat.whatsapp.com/DGLMaqM5UF3D0JnpEkAe1Q?mode=hqrc', 'Andamui, Kampus 2 uin smh banten', '2025-12-08 15:22:00', 5, 1, 'Open', 0, 8, '2025-11-27 08:37:14', '2025-11-27 13:19:13'),
(5, 6, 'Cari Sepuh Network engineering', 'Belajar', '', 'Informatika di lingkungan kampus', 'https://chat.whatsapp.com/GK5Zq9DHQKm1qBO1ESG8a4', 'Negara Kragilan', '2025-11-28 19:47:00', 5, 3, 'Open', 0, 19, '2025-11-27 12:48:01', '2025-12-01 05:09:53'),
(6, 10, 'Cozy Learning', 'Belajar', '', 'Get To Know Cozy and hidden gem, Place TO Learn', 'https://chat.whatsapp.com/IQ6cWtd83kaDcjh4JVlKx2a', 'TANGGERANG', '2026-01-30 09:58:00', 5, 2, 'Open', 0, 4, '2025-11-30 02:59:57', '2025-12-01 07:53:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lobby_chats`
--

CREATE TABLE `lobby_chats` (
  `id` int(11) NOT NULL,
  `lobby_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lobby_members`
--

CREATE TABLE `lobby_members` (
  `id` int(11) NOT NULL,
  `lobby_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'accepted',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lobby_members`
--

INSERT INTO `lobby_members` (`id`, `lobby_id`, `user_id`, `status`, `joined_at`) VALUES
(1, 1, 2, 'accepted', '2025-11-26 16:36:13'),
(2, 1, 3, 'accepted', '2025-11-26 17:13:13'),
(3, 2, 3, 'accepted', '2025-11-26 17:18:42'),
(4, 3, 2, 'accepted', '2025-11-26 17:33:57'),
(5, 2, 2, 'accepted', '2025-11-26 17:34:14'),
(6, 3, 6, 'accepted', '2025-11-27 07:19:35'),
(7, 3, 8, 'accepted', '2025-11-27 07:37:58'),
(8, 1, 8, 'accepted', '2025-11-27 07:40:36'),
(9, 4, 3, 'accepted', '2025-11-27 08:37:14'),
(10, 5, 6, 'accepted', '2025-11-27 12:48:01'),
(11, 5, 3, 'accepted', '2025-11-27 12:53:09'),
(13, 6, 10, 'accepted', '2025-11-30 02:59:57'),
(14, 5, 10, 'accepted', '2025-11-30 03:00:54'),
(15, 6, 2, 'accepted', '2025-12-01 07:53:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
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
-- Struktur dari tabel `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `lobby_id` int(11) NOT NULL,
  `rater_id` int(11) NOT NULL,
  `rated_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `search_cache`
--

CREATE TABLE `search_cache` (
  `id` int(11) NOT NULL,
  `search_key` varchar(255) NOT NULL,
  `search_results` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default-avatar.png',
  `bio` text DEFAULT NULL,
  `theme_preference` enum('dark','light') DEFAULT 'dark',
  `role` enum('user','admin') DEFAULT 'user',
  `rank` enum('Bronze','Silver','Gold','Platinum','Diamond') DEFAULT 'Bronze',
  `total_lobbies` int(11) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `status` enum('active','banned') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `avatar`, `bio`, `theme_preference`, `role`, `rank`, `total_lobbies`, `rating`, `status`, `created_at`, `last_login`, `last_activity`) VALUES
(2, 'MbullGacor', 'mbull@gmail.com', '$2y$10$asVVokhQHZczb0J.C37CbeKPcAQOfHGxkqBXqnV6p8D3sp0hqcPlK', 'NAUFAL AFAF EKAYANA', '69272cc8a46e5.png', 'Pangeran Keabadian', 'dark', 'user', 'Platinum', 2, '0.00', 'active', '2025-11-26 16:34:00', '2025-12-01 03:29:40', NULL),
(3, 'BlackPanther', 'panther@gmail.com', '$2y$10$Titw/TuMvrXuZTN/ftXH7eQIcUmTrI6FwoZyK5dRm9y0bpRQ0P6/G', 'Devayana Uttara Bharatwadja', '6927357975e40.png', 'Jangan pernah menjadi bintang, tapi jadilah awan yang menutupi para bintang-bintang', 'dark', 'user', 'Bronze', 2, '0.00', 'active', '2025-11-26 17:12:44', '2025-11-27 13:18:04', NULL),
(4, 'admin', 'admin@gmail.com', '$2y$10$uyrU6YEVLuKQ6os81N/R4Ob6QegSe9Fjf3b2emfSKkXZGZn83SrrS', 'admin_mbull', '6927397d3f2b5.png', 'Pangeran Keabadian', 'dark', 'admin', 'Diamond', 0, '0.00', 'active', '2025-11-26 17:26:16', '2025-12-01 03:28:34', NULL),
(5, 'kanjut123', 'kanjuthideng@kanjut.com', '$2y$10$ufUaRW8X/D89JwpaEsgixeZ57t3wxmo4.dKzzgx1ZJ0QiMS/uoqce', 'kanjuthideng', '6927fba01133d.png', '', 'dark', 'user', 'Bronze', 0, '0.00', 'active', '2025-11-27 07:17:47', '2025-11-27 07:18:00', NULL),
(6, 'achmad', 'maulanaachmad145@gmail.com', '$2y$10$GxivyyrN9NeeS2c.72EfNuaXA1rMyPhXYvLsZnCsSzbS2Pg.wXC62', 'brounknow', '6927ff2a16650.jpg', '', 'dark', 'user', 'Silver', 1, '0.00', 'active', '2025-11-27 07:19:04', '2025-12-01 03:37:31', NULL),
(8, 'AsepKumaar', 'salahsalah@gmail.com', '$2y$10$UYyFS8Eh//e6BihF2TLnned3OBbfoFh2sl6GkE/jquDPjHHiFWBVC', 'Daffa Aqila', '6927ffbdce444.jpeg', 'Bukan donatur', 'dark', 'user', 'Platinum', 0, '0.00', 'active', '2025-11-27 07:35:37', '2025-11-27 07:43:01', NULL),
(10, 'Kemas', 'zurothecat6@gmail.com', '$2y$10$EdxVX3IksnCOZe.4OQ.Re.FWPnnRs4So0iZ5y0OS0kFcv7sLaV2yC', 'Kemass', '692bb1e6e8f84.jpg', '', 'dark', 'user', 'Bronze', 1, '0.00', 'active', '2025-11-29 02:19:00', '2025-11-30 02:53:36', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indeks untuk tabel `lobbies`
--
ALTER TABLE `lobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_game_type` (`game_type`);

--
-- Indeks untuk tabel `lobby_chats`
--
ALTER TABLE `lobby_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lobby_id` (`lobby_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indeks untuk tabel `lobby_members`
--
ALTER TABLE `lobby_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_member` (`lobby_id`,`user_id`),
  ADD KEY `idx_lobby_id` (`lobby_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indeks untuk tabel `search_cache`
--
ALTER TABLE `search_cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `search_key` (`search_key`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT untuk tabel `lobbies`
--
ALTER TABLE `lobbies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `lobby_chats`
--
ALTER TABLE `lobby_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `lobby_members`
--
ALTER TABLE `lobby_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `search_cache`
--
ALTER TABLE `search_cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `lobbies`
--
ALTER TABLE `lobbies`
  ADD CONSTRAINT `lobbies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lobby_chats`
--
ALTER TABLE `lobby_chats`
  ADD CONSTRAINT `lobby_chats_ibfk_1` FOREIGN KEY (`lobby_id`) REFERENCES `lobbies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lobby_chats_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lobby_members`
--
ALTER TABLE `lobby_members`
  ADD CONSTRAINT `lobby_members_ibfk_1` FOREIGN KEY (`lobby_id`) REFERENCES `lobbies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lobby_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

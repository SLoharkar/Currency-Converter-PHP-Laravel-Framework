-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2024 at 05:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `authorized_ip`
--

CREATE TABLE `authorized_ip` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `authorized_ip`
--

INSERT INTO `authorized_ip` (`id`, `ip_address`) VALUES
(1, '192.168.1.1'),
(2, '10.0.0.1'),
(3, '172.16.0.1'),
(4, '192.168.0.2'),
(5, '10.1.1.1'),
(6, '127.0.0.0/24'),
(7, '192.168.1.0/24');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `rate` decimal(8,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `rate`) VALUES
(1, 'Australian Dollar', 1.5261),
(3, 'Chinese Yuan', 7.1824),
(4, 'Euro', 0.9162),
(5, 'U.K. Pound Sterling', 0.7872),
(6, 'Hong Kong Dollar', 7.7961),
(7, 'Indonesian Rupiah', 9999.9999),
(8, 'Indian Rupee', 83.9297),
(9, 'Japanese Yen', 147.0559),
(10, 'South Korean Won', 1377.6681),
(11, 'Malaysian Ringgit', 4.5015),
(12, 'New Zealand Dollar', 1.6625),
(13, 'Philippine Peso', 57.7927),
(14, 'Singapore Dollar', 1.3280),
(15, 'Thai Baht', 35.6298),
(16, 'New Taiwan Dollar ', 32.6973),
(17, 'Vietnamese Dong', 9999.9999),
(18, 'U.A.E Dirham', 3.6728),
(19, 'Bulgarian Lev', 1.7926),
(20, 'Brazilian Real', 5.6571),
(21, 'Swiss Franc', 0.8589),
(22, 'Czech Koruna', 23.1656),
(23, 'Danish Krone', 6.8373),
(24, 'Egyptian Pound', 49.2379),
(25, 'Hungarian Forint', 365.1085),
(26, 'Moldova Lei', 17.7138),
(27, 'Mexican Peso', 19.2622),
(28, 'Norwegian Krone', 10.7804),
(29, 'Polish Zloty', 3.9581),
(30, 'Romanian New Leu', 4.5613),
(31, 'Serbian Dinar', 107.3247),
(32, 'Russian Rouble', 85.9002),
(33, 'Swedish Krona', 10.4410),
(34, 'Turkish Lira', 33.5390),
(35, 'Ukrainian Hryvnia', 40.9820),
(36, 'South African Rand', 18.3178),
(37, 'Israeli New Sheqel', 3.7840),
(38, 'Jordanian Dinar', 0.7087),
(39, 'Lebanese Pound', 9999.9999),
(40, 'Chilean Peso', 953.5395),
(41, 'Icelandic Krona', 138.0542),
(42, 'Central African CFA Franc', 600.9684),
(43, 'West African CFA Franc', 600.9684),
(44, 'Bangladeshi taka', 117.4626),
(45, 'Belarussian Ruble', 3.2726),
(46, 'Pakistani Rupee', 278.6946),
(47, 'Peruvian Nuevo Sol', 3.7271),
(48, 'Saudi Riyal', 3.7545),
(49, 'Dominican Peso', 59.3756),
(50, 'Venezuelan Bolivar', 36.6132),
(51, 'Costa Rican Colón', 522.1750),
(52, 'Argentine Peso', 934.6049),
(53, 'Bolivian Boliviano', 6.8600),
(54, 'Colombian Peso', 4157.5758),
(55, 'Algerian Dinar', 134.6154),
(56, 'Haitian gourde', 131.5689),
(57, 'Panamanian Balboa', 1.0000),
(58, 'Paraguayan Guaraní', 7538.4615),
(59, 'Tunisian Dinar', 3.0762),
(60, 'Uruguayan Peso', 40.5006),
(61, 'Nigerian Naira', 1606.0200),
(62, 'Armenia Dram', 393.6778),
(63, 'Azerbaijan Manat', 1.7003),
(64, 'Georgian lari', 2.8396),
(65, 'Iraqi dinar', 1328.6195),
(66, 'Iranian rial', 9999.9999),
(67, 'Kyrgyzstan Som', 87.6746),
(68, 'Kazakhstani Tenge', 474.2097),
(69, 'Libyan Dinar', 4.9451),
(70, 'Moroccan Dirham', 10.1079),
(71, 'Tajikistan Ruble', 10.8153),
(72, 'New Turkmenistan Manat', 3.5498),
(73, 'Uzbekistan Sum', 9999.9999);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_08_07_043738_setup', 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `plain_password` varchar(255) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `remember_token`, `plain_password`, `roles`) VALUES
(1, 'admin', '$2y$12$ZgAunvi6MKpc17.qkXZ4guEulQJSOCteWJgEjVlsRvq4ytk2h4mxC', NULL, 'admin', '[\"ROLE_ADMIN\"]'),
(2, 'user', '$2y$12$Y0yXY/EIWhc5gUAucF4ut.X8UnkZN4tYVhnyQCDebQyBmpAorLH/y', NULL, 'user', '[\"ROLE_USER\"]'),
(3, 'Sam', '$2y$12$msTOtjiqm2tz/TWK7xTTr.i6ZJfGHb3UwhmwTudN6OMPtGV9Ar.GS', NULL, 'Sam', '[\"ROLE_ADMIN\"]'),
(4, 'System2', '$2y$12$DSRAgRUmoiwLqTF8URC8a.vJnt9Q9Y/PQQf0kY00FdstnBxNVG7oi', NULL, 'System2', '[\"ROLE_USER\"]'),
(6, 'Sameer', '$2y$12$I/XgFfmsjAHDeaHNZaEB2ePYyRnyI.qZxkIxvipFe3xgfNWTXsn4e', NULL, 'Sameer', '[\"ROLE_USER\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authorized_ip`
--
ALTER TABLE `authorized_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authorized_ip`
--
ALTER TABLE `authorized_ip`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

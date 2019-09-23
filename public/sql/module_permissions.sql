-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2019 at 08:49 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrisv1`
--

-- --------------------------------------------------------

--
-- Table structure for table `module_permissions`
--

CREATE TABLE IF NOT EXISTS `module_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `full` tinyint(1) NOT NULL DEFAULT 0,
  `view` tinyint(1) NOT NULL DEFAULT 0,
  `add` tinyint(1) NOT NULL DEFAULT 0,
  `edit` tinyint(1) NOT NULL DEFAULT 0,
  `delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module_permissions`
--

INSERT INTO `module_permissions` (`id`, `module_id`, `department_id`, `full`, `view`, `add`, `edit`, `delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 1, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(2, 6, 1, 0, 1, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(3, 7, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(4, 8, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(5, 9, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(6, 10, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(7, 11, 1, 0, 0, 1, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(8, 12, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(9, 13, 1, 0, 1, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(10, 14, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(11, 15, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(12, 16, 1, 0, 0, 0, 1, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(13, 17, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(14, 18, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(15, 19, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(16, 20, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(17, 21, 1, 0, 0, 1, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(18, 22, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(19, 23, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(20, 24, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(21, 25, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(22, 26, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(23, 27, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(24, 29, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(25, 30, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:26', '2019-09-16 22:49:26'),
(26, 31, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:27', '2019-09-16 22:49:27'),
(27, 32, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:27', '2019-09-16 22:49:27'),
(28, 33, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:27', '2019-09-16 22:49:27'),
(29, 34, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:27', '2019-09-16 22:49:27'),
(30, 35, 1, 1, 0, 0, 0, 0, '2019-09-16 22:49:27', '2019-09-16 22:49:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `module_permissions`
--
ALTER TABLE `module_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2019 at 12:40 PM
-- Server version: 5.7.26-0ubuntu0.18.04.1
-- PHP Version: 7.0.33-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hris_v1_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `page_roles`
--



--
-- Dumping data for table `page_roles`
--

INSERT INTO `page_roles` (`id`, `page_name`, `permissions`, `created_at`, `updated_at`, `full`, `view`, `add`, `edit`, `delete`, `module_id`) VALUES
(1, 'Dashboard', 'VIEW', '2019-09-09 23:39:03', '2019-09-09 23:39:03', 0, 1, 0, 0, 0, 1),
(2, 'Management', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:07', '2019-09-09 23:39:07', 1, 0, 0, 0, 0, 2),
(3, 'Payroll', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:12', '2019-09-09 23:39:12', 1, 0, 0, 0, 0, 3),
(4, 'Access Management', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:30', '2019-09-09 23:39:30', 1, 0, 0, 0, 0, 4),
(5, 'Settings', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:33', '2019-09-09 23:39:33', 1, 0, 0, 0, 0, 5),
(6, 'Reports', 'VIEW', '2019-09-09 23:39:37', '2019-09-09 23:39:37', 0, 1, 0, 0, 0, 6),
(7, 'Employees', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:43', '2019-09-09 23:39:43', 1, 0, 0, 0, 0, 7),
(8, 'Attendance', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:47', '2019-09-09 23:39:47', 1, 0, 0, 0, 0, 8),
(9, 'Leave Request', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:51', '2019-09-09 23:39:51', 1, 0, 0, 0, 0, 9),
(10, 'Overtime Request', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:39:56', '2019-09-09 23:39:56', 1, 0, 0, 0, 0, 10),
(11, 'Generate Payroll', 'ADD', '2019-09-09 23:40:03', '2019-09-10 00:20:07', 0, 0, 0, 0, 0, 11),
(12, 'Search Payroll', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:40:19', '2019-09-09 23:40:19', 1, 0, 0, 0, 0, 12),
(13, 'Bulk Payroll', 'VIEW', '2019-09-09 23:40:29', '2019-09-10 00:20:22', 0, 1, 0, 0, 0, 13),
(14, 'User Management', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:40:35', '2019-09-09 23:40:35', 1, 0, 0, 0, 0, 14),
(15, 'Role Management', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:40:39', '2019-09-09 23:40:39', 1, 0, 0, 0, 0, 15),
(16, 'My Company', 'EDIT', '2019-09-09 23:40:43', '2019-09-10 00:20:51', 0, 0, 0, 1, 0, 16),
(17, 'Departments', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:40:47', '2019-09-09 23:40:47', 1, 0, 0, 0, 0, 17),
(18, 'Holidays', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:40:57', '2019-09-09 23:40:57', 1, 0, 0, 0, 0, 18),
(19, 'Leaves', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:41:01', '2019-09-09 23:41:01', 1, 0, 0, 0, 0, 19),
(20, 'Employement Status', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:41:05', '2019-09-09 23:41:05', 1, 0, 0, 0, 0, 20),
(21, 'Company Bank', 'ADD', '2019-09-09 23:41:11', '2019-09-10 00:52:59', 0, 0, 0, 0, 0, 21),
(22, 'Setting', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:41:22', '2019-09-09 23:41:22', 1, 0, 0, 0, 0, 22),
(23, 'SSS', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:41:22', '2019-09-09 23:41:22', 1, 0, 0, 0, 0, 23),
(24, 'Tax', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:41:27', '2019-09-09 23:41:27', 1, 0, 0, 0, 0, 24),
(25, 'Philhealth', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:41:31', '2019-09-09 23:41:31', 1, 0, 0, 0, 0, 25),
(26, 'Pag-lbig', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-09 23:41:34', '2019-09-09 23:41:34', 1, 0, 0, 0, 0, 26),
(27, 'Payroll Settings', 'full|ADD|VIEW|EDIT|DELETE', '2019-09-10 01:27:57', '2019-09-10 01:27:57', 1, 0, 0, 0, 0, 27);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `page_roles`
--


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `page_roles`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

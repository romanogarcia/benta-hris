-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2019 at 12:39 PM
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
-- Table structure for table `modules_tables`
--


--
-- Dumping data for table `modules_tables`
--

INSERT INTO `modules_tables` (`id`, `module_name`, `module_link`, `parent`, `priority`, `menu_icon`, `status`, `created_at`, `updated_at`, `route_name`) VALUES
(1, 'Dashboard', '/home', 0, 1, '<i class=\"mdi mdi-home menu-icon\"></i>', 1, '2019-09-05 06:48:36', '2019-09-06 01:09:13', 'route(\'home\')'),
(2, 'Management', '', 0, 2, '<i class=\"mdi mdi-account-settings menu-icon\"></i>', 1, '2019-09-05 07:02:00', '2019-09-05 07:02:00', NULL),
(3, 'Payroll', '', 0, 3, '<i class=\"mdi mdi-cash-multiple menu-icon\"></i>', 1, '2019-09-05 07:03:14', '2019-09-05 07:03:14', NULL),
(4, 'Access Management', '', 0, 4, '<i class=\"mdi mdi-account-settings menu-icon\"></i>', 1, '2019-09-05 07:03:58', '2019-09-05 07:03:58', NULL),
(5, 'Settings', '', 0, 5, '<i class=\"mdi mdi-settings menu-icon\"></i>', 1, '2019-09-05 07:04:26', '2019-09-05 07:04:26', NULL),
(6, 'Reports', '/report', 0, 6, '<i class=\"mdi mdi-file-document menu-icon\"></i>', 1, '2019-09-05 07:04:58', '2019-09-05 07:21:50', 'route(\'dtr.reports\')'),
(7, 'Employees', '/employees', 2, 7, '<i class=\"mdi mdi-account-tie\"></i>', 1, '2019-09-05 07:05:48', '2019-09-06 01:09:38', 'route(\'employees.index\')'),
(8, 'Attendance', '/attendance', 2, 8, '<i class=\"mdi mdi-account-badge\"></i>', 1, '2019-09-05 07:06:41', '2019-09-06 01:10:03', 'route(\'attendance.index\')'),
(9, 'Leave Request', '/leave/filter/search', 2, 9, '<i class=\"mdi mdi-file-search-outline\"></i>', 1, '2019-09-05 07:07:30', '2019-09-06 01:10:16', 'route(\'leave.leavesearch\')'),
(10, 'Overtime Request', '/overtime/filter/search', 2, 10, '<i class=\"mdi mdi-file-document-edit-outline\"></i>', 1, '2019-09-05 07:08:16', '2019-09-06 01:10:32', 'route(\'overtime_request.search\')'),
(11, 'Generate Payroll', '/payrollledger/create', 3, 11, '<i class=\"mdi mdi-view-headline\"></i>', 1, '2019-09-05 07:09:35', '2019-09-06 01:10:54', 'route(\'payrollledger.create\')'),
(12, 'Search Payroll', '/payroll/filter/search/query', 3, 12, '<i class=\"mdi mdi-cash-usd\"></i>', 1, '2019-09-05 07:10:38', '2019-09-06 01:11:08', 'route(\'payroll.search\')'),
(13, 'Bulk Payroll', '/bulk-payroll', 3, 13, '<i class=\"mdi mdi-cash-usd\"></i>', 1, '2019-09-05 07:11:20', '2019-09-06 01:11:21', 'route(\'payroll.index\')'),
(14, 'User Management', '/users', 4, 14, '<i class=\"mdi mdi-account-key\"></i>', 1, '2019-09-05 07:12:10', '2019-09-06 01:11:35', 'route(\'users.index\')'),
(15, 'Role Management', '/roles', 4, 15, '<i class=\"mdi mdi-account-multiple-plus\"></i>', 1, '2019-09-05 07:12:56', '2019-09-06 01:11:47', 'route(\'roles.index\')'),
(16, 'My Company', '/company/1/edit', 5, 16, '<i class=\"mdi  mdi-office-building\"></i>', 1, '2019-09-05 07:13:54', '2019-09-06 01:12:04', 'route(\'company.index\')'),
(17, 'Departments', '/departments', 5, 17, '<i class=\"mdi mdi-home-modern\"></i>', 1, '2019-09-05 07:14:34', '2019-09-06 01:12:15', 'route(\'departments.index\')'),
(18, 'Holidays', '/holidays', 5, 18, '<i class=\"mdi mdi-calendar-remove\"></i>', 1, '2019-09-05 07:15:17', '2019-09-06 01:12:26', 'route(\'holidays.index\')'),
(19, 'Leaves', '/leave-list', 5, 19, '<i class=\"mdi mdi-account-remove-outline\"></i>', 1, '2019-09-05 07:16:23', '2019-09-06 01:12:36', 'route(\'leave.leave_list\')'),
(20, 'Employement Status', '/employment-status', 5, 20, '<i class=\"mdi mdi-clipboard-check-outline\"></i>', 1, '2019-09-05 07:17:02', '2019-09-05 07:17:02', NULL),
(21, 'Company Bank', '/banks', 5, 21, '<i class=\"mdi  mdi-bank\"></i>', 1, '2019-09-05 07:17:38', '2019-09-05 07:17:38', NULL),
(22, 'Setting', '/settings', 5, 22, '<i class=\"mdi mdi-settings\"></i>', 1, '2019-09-05 07:18:38', '2019-09-05 07:18:38', NULL),
(23, 'SSS', '/sss', 5, 23, '<i class=\"mdi  mdi-table-large\"></i>', 0, '2019-09-05 07:19:22', '2019-09-05 07:19:32', NULL),
(24, 'Tax', '/tax', 5, 24, '<i class=\"mdi  mdi-table-large\"></i>', 0, '2019-09-05 07:20:00', '2019-09-05 07:20:00', NULL),
(25, 'Philhealth', '/philhealth', 5, 25, '<i class=\"mdi  mdi-table-large\"></i>', 0, '2019-09-05 07:20:34', '2019-09-05 07:20:47', NULL),
(26, 'Pag-lbig', '/pagibig', 5, 26, '<i class=\"mdi  mdi-table-large\"></i>', 0, '2019-09-05 07:21:23', '2019-09-05 07:21:23', NULL),
(27, 'Payroll Settings', '/payroll_settings', 3, 27, '<i class=\"mdi mdi-settings\"></i>', 1, '2019-09-10 01:27:14', '2019-09-10 01:27:14', 'route(\'payroll.payroll_settings\')');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `modules_tables`
--


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `modules_tables`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

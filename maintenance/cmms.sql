-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2025 at 10:24 PM
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
-- Database: `cmms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `company`, `phone`, `country`, `password`) VALUES
(2, 'hussein tawil', 'husseintawil45@gmail.com', 'trust', '78875782', 'Lebanon', '$2y$10$3/uULiUV/bew1s5AMX53COeRgcozcLk.3lq6T2152QGyh6cj/Yvo.'),
(3, 'adel tarhini', 'adeltarhini@gmail.com', 'AdelCo', '7523123`', 'le', '$2y$10$2aAkA5El16vQmoZm3ESuJ.n/Tmr2Mudc12lv7rl9yns.o3GNItIqC'),
(4, 'hussein tawil', 'husseintawil884@gmail.com', 'trust', '78875782', 'Lebanon', '$2y$10$BafdJ8tTYEz0X6PKr9.DCOurR0IabLh0FAyHPIsNmqqgL5RFaFdPK');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `phone`, `email`, `created_at`, `admin_id`) VALUES
(1, 'husseintawil', '78875782', 'husseintawil45@gmail.com', '2025-07-02 09:45:28', NULL),
(2, 'hussein tawil', NULL, NULL, '2025-07-02 09:47:15', NULL),
(3, 'hussein tawil', '78875782', 'husseintawil45@gmail.com', '2025-07-03 23:41:29', NULL),
(4, 'adel tarhini', '81924123', 'adeltarhini@gmail.com', '2025-07-04 21:39:44', NULL),
(5, 'hussein tawil', '221', 'husseintawil45@gmail.com', '2025-07-05 08:41:21', 2),
(6, 'adel tarhini', '78875782', 'husseintawil45@gmail.com', '2025-07-05 08:43:14', 2),
(7, 'ali', '8123121', 'MOHAMAD@GMAIL.COM', '2025-07-05 08:46:51', 3),
(8, 'zaher', '9123831', 'adeltarhini@gmail.com', '2025-07-05 08:47:35', 3);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `role`, `admin_id`) VALUES
(1, 'pizza', 'MOHAMAD@GMAIL.COM', 'TECHN', 0),
(2, 'Ali', 'alivcjas@gmail.com', 'te', 3),
(3, ' ali saka', 'alisaka@gmail.com', 'Cleaner', 2);

-- --------------------------------------------------------

--
-- Table structure for table `spare_parts`
--

CREATE TABLE `spare_parts` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `technicians`
--

CREATE TABLE `technicians` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `type` enum('software','hardware') NOT NULL,
  `problem_description` text DEFAULT NULL,
  `accessories` text DEFAULT NULL,
  `assigned_employee_id` int(11) DEFAULT NULL,
  `status` enum('pending','in_progress','done') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `customer_id`, `device_name`, `serial_number`, `type`, `problem_description`, `accessories`, `assigned_employee_id`, `status`, `created_at`, `admin_id`) VALUES
(12, 2, 'apple', '1293194', 'software', 'sadfasfkan', 'mouse', 1, 'pending', '2025-07-04 21:58:45', 3),
(13, 4, 'lenovo', '2142414', 'software', 'nothing', 'mouse', 1, 'pending', '2025-07-04 21:59:47', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_reports`
--

CREATE TABLE `ticket_reports` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `entered_by_id` int(11) DEFAULT NULL,
  `entered_by_role` enum('admin','technician') DEFAULT 'technician',
  `problem` text DEFAULT NULL,
  `solution` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_statuses`
--

CREATE TABLE `ticket_statuses` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `role_access` enum('admin','technician','both') DEFAULT 'both',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spare_parts`
--
ALTER TABLE `spare_parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `assigned_employee_id` (`assigned_employee_id`),
  ADD KEY `fk_admin_ticket` (`admin_id`);

--
-- Indexes for table `ticket_reports`
--
ALTER TABLE `ticket_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spare_parts`
--
ALTER TABLE `spare_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `technicians`
--
ALTER TABLE `technicians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ticket_reports`
--
ALTER TABLE `ticket_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `technicians`
--
ALTER TABLE `technicians`
  ADD CONSTRAINT `technicians_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_admin_ticket` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`assigned_employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `ticket_reports`
--
ALTER TABLE `ticket_reports`
  ADD CONSTRAINT `ticket_reports_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  ADD CONSTRAINT `ticket_statuses_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

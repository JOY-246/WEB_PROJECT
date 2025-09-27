-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 01:38 PM
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
-- Database: `irms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveryman`
--

CREATE TABLE `deliveryman` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveryman`
--

INSERT INTO `deliveryman` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'irms@delivery.com', '$2y$10$N1n8tZ8jrworiW5YN7BVRumnAXdlwqLNK9YM5CwqRFRaQpyUWFEwy', NULL, '2025-09-10 18:49:45'),
(2, 'irms@staff.com', '$2y$10$3.oYu4Her/v.RIehUDxZY.ebjMJ6kfJrAJgX/Vb1vMEsMqRtfgxHu', NULL, '2025-09-13 09:42:32'),
(3, 'samin@khan.com', '$2y$10$QzZrP2jFsSDTaHME7zANceQO8UvkZd9CU.Jb1SoLRz6KQt8SgRc5C', NULL, '2025-09-13 09:59:19'),
(4, 'mas@gmail.com', '$2y$10$3k4dJ6J3a/lGIihTQgrrJO/vRgWMRKqQcgTRYP7P1FY7Po7u/s/we', NULL, '2025-09-23 17:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `deliveryman_skills_selection`
--

CREATE TABLE `deliveryman_skills_selection` (
  `id` int(11) NOT NULL,
  `delivery_email` varchar(100) NOT NULL,
  `skill` varchar(100) NOT NULL,
  `experience_years` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveryman_skills_selection`
--

INSERT INTO `deliveryman_skills_selection` (`id`, `delivery_email`, `skill`, `experience_years`, `created_at`) VALUES
(1, 'irms@delivery.com', 'Customer Support', 1, '2025-09-12 17:39:52'),
(2, 'irms@delivery.com', 'Data Entry', 1, '2025-09-12 17:39:52'),
(3, 'irms@delivery.com', 'Software Usage', 9, '2025-09-13 09:42:05'),
(4, 'irms@delivery.com', 'Time Management', 9, '2025-09-13 09:42:05'),
(5, 'irms@staff.com', 'Software Usage', 8, '2025-09-13 09:42:48'),
(6, 'irms@staff.com', 'Time Management', 8, '2025-09-13 09:42:48');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `position` enum('Staff','Manager','DeliveryMan') NOT NULL,
  `cv_file` varchar(255) NOT NULL,
  `skills` text DEFAULT NULL,
  `experience` int(11) DEFAULT 0,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `apply_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `name`, `email`, `phone`, `gender`, `position`, `cv_file`, `skills`, `experience`, `status`, `apply_date`) VALUES
(1, 'JOY SARKER', 'mas@gmail.com', '0123456789', 'Male', 'Staff', '1758707127_SRE - Ch.05 - Finalizing Requirements Development.pdf', '', 2, 'Pending', '2025-09-24 15:45:27'),
(2, 'JOY SARKER', 'mas@gmail.com', '0123456789', 'Male', 'Manager', '1758707476_SRE - Ch.04 - Documenting Requirements.pdf', NULL, NULL, 'Pending', '2025-09-24 15:51:16'),
(3, 'Samin', 'samin@khan.com', '01301907879', 'Male', 'Staff', '1758708069_SRE - Ch.05 - Finalizing Requirements Development.pdf', NULL, 0, 'Rejected', '2025-09-24 16:01:09'),
(4, 'Sarar Saqib', 'sar@gmail.com', '01301907878', 'Male', 'Staff', '1758708446_SRE - Ch.05 - Finalizing Requirements Development.pdf', NULL, 0, 'Approved', '2025-09-24 16:07:26'),
(5, 'Sarar Saqib', 'samin@khan.com', '01301907879', 'Male', 'Staff', '1758708771_SRE - Ch.05 - Finalizing Requirements Development.pdf', NULL, 0, 'Rejected', '2025-09-24 16:12:51'),
(6, 'JOY SARKER', 'mas@gmail.com', '01301907879', 'Male', 'Staff', '1758708949_SRE - Ch.02 - Requirements From Customer & Analyst Perspective.pdf', NULL, 0, 'Pending', '2025-09-24 16:15:49'),
(7, 'JOY SARKER', 'mas@gmail.com', '0123456789', 'Male', 'Staff', '1758709380_SRE - Ch.05 - Finalizing Requirements Development.pdf', NULL, 0, 'Approved', '2025-09-24 16:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'irms@manager.com', '$2y$10$CMB/qRWSBslDZyLhFxySLucgeG6rBOx/5dSzlvs9jfPcJfqfa5O1G', NULL, '2025-09-10 18:56:09'),
(2, 'samin@khan.com', '$2y$10$f8C2ZsMdlJLVM9ftwcICi.A8KY501aq8O1E4fn1LWeg6OkyIzSthu', NULL, '2025-09-15 15:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `target_role` varchar(50) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `target_role`, `is_read`, `created_at`) VALUES
(1, 'Order #3 has been confirmed by Peon.', 'pro_team', 0, '2025-09-14 10:52:46'),
(2, 'Order #3 has been confirmed by Peon.', 'pro_team', 0, '2025-09-14 10:53:05'),
(3, 'Order #3 has been confirmed by Peon.', 'pro_team', 0, '2025-09-14 10:53:13'),
(4, 'Order #4 has been confirmed by Peon.', 'pro_team', 0, '2025-09-15 10:25:04'),
(5, 'Order #2 has been confirmed by Peon.', 'pro_team', 0, '2025-09-15 15:31:29'),
(6, 'Order #1 has been confirmed by Peon.', 'pro_team', 0, '2025-09-15 15:31:32'),
(7, 'Order #5 has been confirmed by Peon.', 'pro_team', 0, '2025-09-16 11:28:05'),
(8, 'Order #4 has been confirmed by Peon.', 'pro_team', 0, '2025-09-21 20:48:17'),
(9, 'Order #6 has been confirmed by Peon.', 'pro_team', 0, '2025-09-22 11:24:05'),
(10, 'Order #8 has been confirmed by Peon.', 'pro_team', 0, '2025-09-22 11:35:08'),
(11, 'Order #9 has been confirmed by Peon.', 'pro_team', 0, '2025-09-22 11:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `peon_orders`
--

CREATE TABLE `peon_orders` (
  `id` int(11) NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','checked','confirmed','cancelled') DEFAULT 'pending',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peon_orders`
--

INSERT INTO `peon_orders` (`id`, `product_id`, `product_name`, `product_price`, `status`, `sent_at`) VALUES
(1, '01', 'Printer', 800.00, 'cancelled', '2025-09-14 09:30:46'),
(2, '02', 'Pen', 5.00, 'cancelled', '2025-09-14 09:34:06'),
(3, '04', 'AC', 10000.00, 'confirmed', '2025-09-14 10:33:45'),
(4, '02', 'Pen', 5.00, 'cancelled', '2025-09-15 10:24:43'),
(5, '03', 'Computer', 10000.00, 'confirmed', '2025-09-15 10:24:43'),
(6, '04', 'AC', 10000.00, 'confirmed', '2025-09-22 11:23:35'),
(7, '05', 'Table', 5000.00, 'cancelled', '2025-09-22 11:23:35'),
(8, '01', 'Printer', 800.00, 'cancelled', '2025-09-22 11:35:03'),
(9, '04', 'AC', 10000.00, 'confirmed', '2025-09-22 11:35:03'),
(10, '04', 'AC', 10000.00, 'pending', '2025-09-23 11:24:33'),
(11, '07', 'Laptop', 70000.00, 'pending', '2025-09-23 12:48:28'),
(12, '01', 'Printer', 800.00, 'pending', '2025-09-23 13:13:59'),
(13, '01', 'Printer', 800.00, 'pending', '2025-09-23 13:20:31'),
(14, '01', 'Printer', 800.00, 'pending', '2025-09-23 17:36:08');

-- --------------------------------------------------------

--
-- Table structure for table `procurement`
--

CREATE TABLE `procurement` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `procurement`
--

INSERT INTO `procurement` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'irms@procurement.com', '$2y$10$Fjg/V2xx99wrXCfbT1O4ze6XwgLlrMnL4lm2U9hnLAPdzupqMQ3O6', NULL, '2025-09-10 18:48:33'),
(2, 'mas@gmail.com', '$2y$10$rBgA1m9O2JpRMEr6McZYVuoXYiAndi2BLeAXqzt9217vykExEjbQO', NULL, '2025-09-14 10:36:06'),
(3, 'samin@khan.com', '$2y$10$uJli1sjKIjvn6zis5Z4CpOG5sA/GCzaUFbyAx/ZJLoZ4ZIrRbKrxq', NULL, '2025-09-15 15:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_id`, `product_name`, `product_price`, `created_at`) VALUES
(4, '01', 'Printer', 800.00, '2025-09-14 09:30:21'),
(10, '02', 'Pencil', 5.00, '2025-09-23 12:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `skill_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill_name`) VALUES
(8, 'Communication'),
(24, 'Customer Service'),
(4, 'Customer Support'),
(2, 'Data Entry'),
(1, 'Inventory Management'),
(23, 'Leadership'),
(5, 'Logistics'),
(21, 'Problem Solving'),
(3, 'Reporting'),
(6, 'Software Usage'),
(22, 'Teamwork'),
(7, 'Time Management');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `email`, `password`) VALUES
(1, 'irms@staff.com', '$2y$10$ZUCw1VzGTQuHIy9E1UKRee.Z.c/sVkYEv7G5m5yd5uUzsnTa3MGz2'),
(2, 'mas@gmail.com', '$2y$10$OR8sDaIkc/HtDDlGqunSwez9zdOCN/LQM0q.Yd5gRkAc5n/21JIre'),
(3, 'irms@staff1.com', '$2y$10$zBcQZgJJzwS9Jndk6h3xbOGDrN/nOn.T0M56VoDatFo91FiplsNkO'),
(4, 'irms@admin.com', '$2y$10$uIOkQvlCrTxUlTN8NAVazesTfHjNi2bkuo2QvgeLn2JkhHuYLf/j.'),
(5, 'samin@khan.com', '$2y$10$ZtLsVCE5EfaGoyHZ1fd1/.YAiCG7eWOQjc6OcLQJxdJP5bj13z9Cq');

-- --------------------------------------------------------

--
-- Table structure for table `staff_products`
--

CREATE TABLE `staff_products` (
  `id` int(11) NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `status` enum('new','forwarded') DEFAULT 'new',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_products`
--

INSERT INTO `staff_products` (`id`, `product_id`, `product_name`, `product_price`, `status`, `sent_at`) VALUES
(1, '02', 'Pen', 5.00, 'new', '2025-09-14 09:30:31'),
(2, '01', 'Printer', 800.00, 'new', '2025-09-14 09:30:31'),
(3, '04', 'AC', 10000.00, 'new', '2025-09-14 10:33:38'),
(4, '05', 'Table', 5000.00, 'new', '2025-09-14 10:33:38'),
(5, '02', 'Pen', 5.00, 'new', '2025-09-15 10:24:01'),
(6, '03', 'Computer', 10000.00, 'new', '2025-09-15 10:24:01'),
(7, '02', 'Pen', 5.00, 'new', '2025-09-21 19:28:04'),
(8, '03', 'Computer', 10000.00, 'new', '2025-09-21 19:28:04'),
(9, '01', 'Printer', 800.00, 'new', '2025-09-21 19:28:04'),
(10, '04', 'AC', 10000.00, 'new', '2025-09-22 11:23:19'),
(11, '05', 'Table', 5000.00, 'new', '2025-09-22 11:23:19'),
(12, '03', 'Computer', 10000.00, 'new', '2025-09-22 11:34:51'),
(13, '01', 'Printer', 800.00, 'new', '2025-09-22 11:34:51'),
(14, '04', 'AC', 10000.00, 'new', '2025-09-22 11:34:51'),
(15, '04', 'AC', 10000.00, 'new', '2025-09-23 11:23:46'),
(16, '05', 'Table', 5000.00, 'new', '2025-09-23 11:23:46'),
(17, '02', 'Pen', 5.00, 'new', '2025-09-23 11:45:18'),
(18, '01', 'Printer', 800.00, 'new', '2025-09-23 11:45:18'),
(19, '03', 'Computer', 10000.00, 'new', '2025-09-23 11:51:46'),
(20, '04', 'AC', 10000.00, 'new', '2025-09-23 11:52:22'),
(21, '04', 'AC', 10000.00, 'new', '2025-09-23 11:55:53'),
(22, '04', 'AC', 10000.00, 'new', '2025-09-23 11:56:02'),
(23, '05', 'Table', 5000.00, 'new', '2025-09-23 11:56:25'),
(24, '03', 'Computer', 10000.00, 'new', '2025-09-23 12:11:41'),
(25, '05', 'Table', 5000.00, 'new', '2025-09-23 12:12:13'),
(26, '04', 'AC', 10000.00, 'new', '2025-09-23 12:21:43'),
(27, '05', 'Table', 5000.00, 'new', '2025-09-23 12:21:43'),
(28, '04', 'AC', 10000.00, 'new', '2025-09-23 12:37:10'),
(29, '05', 'Table', 5000.00, 'new', '2025-09-23 12:37:10'),
(30, '07', 'Laptop', 70000.00, 'new', '2025-09-23 12:48:20'),
(31, '07', 'Laptop', 70000.00, 'new', '2025-09-23 12:48:36'),
(32, '08', 'Desktop', 42000.00, 'new', '2025-09-23 12:48:57'),
(33, '02', 'Pencil', 5.00, 'new', '2025-09-23 12:52:28'),
(34, '02', 'Pencil', 5.00, 'new', '2025-09-23 13:01:11'),
(35, '02', 'Pencil', 5.00, 'new', '2025-09-23 13:01:15'),
(36, '02', 'Pencil', 5.00, 'new', '2025-09-23 13:01:22'),
(37, '01', 'Printer', 800.00, 'new', '2025-09-23 13:06:39'),
(38, '01', 'Printer', 800.00, 'new', '2025-09-23 13:12:15'),
(39, '01', 'Printer', 800.00, 'new', '2025-09-23 13:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `staff_skills`
--

CREATE TABLE `staff_skills` (
  `id` int(11) NOT NULL,
  `staff_email` varchar(100) NOT NULL,
  `skill` varchar(100) NOT NULL,
  `experience_years` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_skills`
--

INSERT INTO `staff_skills` (`id`, `staff_email`, `skill`, `experience_years`, `created_at`) VALUES
(1, 'irms@staff.com', 'Customer Support,Logistics', 3, '2025-09-10 19:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `staff_skills_selection`
--

CREATE TABLE `staff_skills_selection` (
  `id` int(11) NOT NULL,
  `staff_email` varchar(100) NOT NULL,
  `skill` varchar(100) NOT NULL,
  `experience_years` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_skills_selection`
--

INSERT INTO `staff_skills_selection` (`id`, `staff_email`, `skill`, `experience_years`, `created_at`) VALUES
(1, 'irms@staff.com', 'Customer Support', 2, '2025-09-11 07:57:13'),
(2, 'irms@staff.com', 'Data Entry', 2, '2025-09-11 07:57:13'),
(5, 'irms@staff.com', 'Data Entry', 2, '2025-09-12 17:56:01'),
(6, 'irms@staff.com', 'Logistics', 2, '2025-09-12 17:56:01');

-- --------------------------------------------------------

--
-- Table structure for table `super_admin`
--

CREATE TABLE `super_admin` (
  `id` int(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `super_admin`
--

INSERT INTO `super_admin` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'irms@admin.com', '$2y$10$q9E2d5Ks1hWIjNayByXd8eDGLOdLLu9ynBC90zSHk0U8R20azn88e', '2025-09-09 17:18:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `deliveryman`
--
ALTER TABLE `deliveryman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `deliveryman_skills_selection`
--
ALTER TABLE `deliveryman_skills_selection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peon_orders`
--
ALTER TABLE `peon_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `procurement`
--
ALTER TABLE `procurement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skill_name` (`skill_name`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `staff_products`
--
ALTER TABLE `staff_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_skills`
--
ALTER TABLE `staff_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_skills_selection`
--
ALTER TABLE `staff_skills_selection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveryman`
--
ALTER TABLE `deliveryman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deliveryman_skills_selection`
--
ALTER TABLE `deliveryman_skills_selection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `peon_orders`
--
ALTER TABLE `peon_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `procurement`
--
ALTER TABLE `procurement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_products`
--
ALTER TABLE `staff_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `staff_skills`
--
ALTER TABLE `staff_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff_skills_selection`
--
ALTER TABLE `staff_skills_selection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `super_admin`
--
ALTER TABLE `super_admin`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

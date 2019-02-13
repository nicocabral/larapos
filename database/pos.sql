-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2019 at 04:09 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'CAT 1', '2019-02-09 07:26:31', '2019-02-09 07:30:00'),
(3, 'CAT 2', '2019-02-09 07:35:32', '2019-02-09 07:35:32'),
(4, 'CAT 3', '2019-02-10 07:08:04', '2019-02-10 07:08:04'),
(5, 'CAT 5', '2019-02-10 07:53:05', '2019-02-10 07:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` longtext NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `description`, `qty`, `price`, `status`, `created_at`, `updated_at`) VALUES
(6, 2, 'Sint et aut vel quo', 'Rajah Donovan', 'Quisquam ea earum at', 398, '1500.00', 'Active', '2019-02-09 06:46:02', '2019-02-10 07:50:18'),
(7, 3, 'Facilis aute non des', 'Maisie Mack', 'Id consequatur qui', 394, '130.00', 'Active', '2019-02-09 07:31:26', '2019-02-11 06:22:31'),
(8, 2, 'Libero consequatur', 'Andrew Christensen', 'Tempore obcaecati n', 837, '3000.00', 'Active', '2019-02-10 06:39:44', '2019-02-10 08:01:00'),
(9, 2, 'Sed modi nulla enim', 'Hammett Spencer', 'Dolor quis illum se', 559, '827.00', 'Active', '2019-02-10 07:42:04', '2019-02-10 07:50:18'),
(10, 4, 'Error in rerum autem', 'Maisie Herman', 'Non exercitationem v', 862, '592.00', 'Active', '2019-02-10 07:49:33', '2019-02-10 08:01:00'),
(11, 5, 'Aperiam corrupti vo', 'Yuli Russell', 'Illum duis incidunt', 914, '985.00', 'Active', '2019-02-10 07:53:18', '2019-02-10 16:20:07'),
(12, 4, 'Ut ut esse Nam dolo', 'Caleb Silva', 'Ea quia facilis sint', 923, '388.75', 'Active', '2019-02-10 07:54:09', '2019-02-10 16:20:07'),
(13, 4, 'Voluptas consequatur', 'Stacy House', 'Eum quisquam dolor d', 928, '802.25', 'Active', '2019-02-10 07:55:44', '2019-02-10 07:55:44'),
(14, 3, 'Voluptas sed id omni', 'Catherine Vaughan', 'Minus dolores labore', 252, '848.00', 'Active', '2019-02-10 07:55:56', '2019-02-10 07:55:56'),
(15, 4, 'Velit voluptatibus a', 'Jerry Levy', 'Deleniti ipsum null', 142, '680.00', 'Active', '2019-02-10 07:56:05', '2019-02-10 07:56:05'),
(16, 4, 'Ratione inventore et', 'Rogan Hale', 'Atque cupiditate vel', 218, '66.00', 'Active', '2019-02-10 07:56:16', '2019-02-10 08:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `permissions` longtext,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `total`, `paid_amount`, `date`, `status`, `created_at`, `updated_at`) VALUES
(7, '4760.00', '4760.00', '2019-02-10', 'Paid', '2019-02-09 22:03:03', '2019-02-09 22:03:03'),
(8, '520.00', '520.00', '2019-02-10', 'Paid', '2019-02-09 22:04:32', '2019-02-09 22:04:32'),
(9, '130.00', '130.00', '2019-02-10', 'Paid', '2019-02-10 05:51:38', '2019-02-10 05:51:38'),
(10, '1500.00', '1500.00', '2019-02-10', 'Paid', '2019-02-10 06:08:48', '2019-02-10 06:08:48'),
(11, '3000.00', '3000.00', '2019-02-10', 'Paid', '2019-02-10 06:40:14', '2019-02-10 06:40:14'),
(12, '3511.00', '3511.00', '2019-02-10', 'Paid', '2019-02-10 07:50:18', '2019-02-10 07:50:18'),
(13, '5033.00', '5033.00', '2019-02-10', 'Paid', '2019-02-10 08:01:00', '2019-02-10 08:01:00'),
(14, '1762.50', '1762.50', '2019-02-11', 'Paid', '2019-02-10 16:20:07', '2019-02-10 16:20:07'),
(15, '260.00', '260.00', '2019-02-11', 'Paid', '2019-02-11 06:22:31', '2019-02-11 06:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `ref_sku` varchar(255) DEFAULT NULL,
  `ref_name` varchar(255) DEFAULT NULL,
  `ref_description` varchar(255) DEFAULT NULL,
  `ref_qty` int(11) DEFAULT NULL,
  `ref_price` decimal(10,2) DEFAULT NULL,
  `ref_total` decimal(10,2) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `ref_id`, `ref_sku`, `ref_name`, `ref_description`, `ref_qty`, `ref_price`, `ref_total`, `created_at`, `updated_at`) VALUES
(7, 7, 6, 'Sint et aut vel quo', 'Rajah Donovan', 'Quisquam ea earum at', 3, '1500.00', '4500.00', '2019-02-10', NULL),
(8, 7, 7, 'Facilis aute non des', 'Maisie Mack', 'Id consequatur qui', 2, '130.00', '260.00', '2019-02-10', NULL),
(9, 8, 7, 'Facilis aute non des', 'Maisie Mack', 'Id consequatur qui', 4, '130.00', '520.00', '2019-02-10', NULL),
(10, 9, 7, 'Facilis aute non des', 'Maisie Mack', 'Id consequatur qui', 1, '130.00', '130.00', '2019-02-10', NULL),
(11, 10, 6, 'Sint et aut vel quo', 'Rajah Donovan', 'Quisquam ea earum at', 1, '1500.00', '1500.00', '2019-02-10', NULL),
(12, 11, 8, 'Libero consequatur', 'Andrew Christensen', 'Tempore obcaecati n', 1, '3000.00', '3000.00', '2019-02-10', NULL),
(13, 12, 6, 'Sint et aut vel quo', 'Rajah Donovan', 'Quisquam ea earum at', 1, '1500.00', '1500.00', '2019-02-10', NULL),
(14, 12, 9, 'Sed modi nulla enim', 'Hammett Spencer', 'Dolor quis illum se', 1, '827.00', '827.00', '2019-02-10', NULL),
(15, 12, 10, 'Error in rerum autem', 'Maisie Herman', 'Non exercitationem v', 2, '592.00', '1184.00', '2019-02-10', NULL),
(16, 13, 7, 'Facilis aute non des', 'Maisie Mack', 'Id consequatur qui', 3, '130.00', '390.00', '2019-02-10', NULL),
(17, 13, 8, 'Libero consequatur', 'Andrew Christensen', 'Tempore obcaecati n', 1, '3000.00', '3000.00', '2019-02-10', NULL),
(18, 13, 10, 'Error in rerum autem', 'Maisie Herman', 'Non exercitationem v', 1, '592.00', '592.00', '2019-02-10', NULL),
(19, 13, 11, 'Aperiam corrupti vo', 'Yuli Russell', 'Illum duis incidunt', 1, '985.00', '985.00', '2019-02-10', NULL),
(20, 13, 16, 'Ratione inventore et', 'Rogan Hale', 'Atque cupiditate vel', 1, '66.00', '66.00', '2019-02-10', NULL),
(21, 14, 11, 'Aperiam corrupti vo', 'Yuli Russell', 'Illum duis incidunt', 1, '985.00', '985.00', '2019-02-11', NULL),
(22, 14, 12, 'Ut ut esse Nam dolo', 'Caleb Silva', 'Ea quia facilis sint', 2, '388.75', '777.50', '2019-02-11', NULL),
(23, 15, 7, 'Facilis aute non des', 'Maisie Mack', 'Id consequatur qui', 2, '130.00', '260.00', '2019-02-11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(45) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `contact_number`, `status`, `created_at`, `updated_at`, `type`, `remember_token`) VALUES
(6, 'nyx.assasin', '$2y$10$qbEDBWB3B0PLGlbxXiypoOJwJjW.gYsRBexLkAtfIv7vrZ5W0L2vq', 'Nyx', 'Assasin', '+639177791994', 'Active', '2019-02-12 13:34:18', '2019-02-10 07:47:32', 'Admin', 'B3YOnaBRwpmFiC6Y7kCD6E6ZelZD7KnZqUemv3H6a7B5KkT8lxtvxPigtAkJ'),
(12, 'Kareem.Malone', '$2y$10$Vmb9CPrPgPIoEY6QVO9DUOqLg9sgqpXB.kDL61OswexHPOFS.lpc6', 'Kareem', 'Malone', '+639177791994', 'Active', '2019-02-10 12:45:47', '2019-02-10 04:45:47', NULL, 'KMpustvYzvkCGmAUSd0ybvtdNPQoX0pzIAEcTmMaZop94J7Y4vQ1LnmBv4hX'),
(13, 'Chiquita.Macdonald', '$2y$10$3MoKqnn0EN.SxdykoTITNOmoBn7svBkiAnVJsQLZTow2WAp3zcHPK', 'Chiquita', 'Macdonald', '+639177791994', 'Active', '2019-02-10 15:47:17', '2019-02-10 07:47:17', NULL, NULL),
(15, 'Garrison.Nielsen', '$2y$10$BzA71S/YlLtM27YTy71f/ey2GlRdoEuvveAg9DQyTt7ORTRdUdYNe', 'Garrison', 'Nielsen', '+639123456789', 'Active', '2019-02-10 16:00:21', '2019-02-10 08:00:21', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

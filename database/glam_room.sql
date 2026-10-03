-- phpMyAdmin SQL Dump
-- Project: The Glam Room
-- PRO204 Web Application Development
-- Database: glam_room

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glam_room`
--

-- --------------------------------------------------------
--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `admin_users`
--

ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for table `admin_users`
--

ALTER TABLE `admin_users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `enquiries`
--

ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- AUTO_INCREMENT for table `enquiries`
--

ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services`
(`id`, `service_name`, `category`, `description`, `price`, `duration`, `created_at`)
VALUES
(
  1,
  'Bridal Makeup',
  'Makeup',
  'Complete bridal makeup service for your special day.',
  180.00,
  120,
  '2026-09-09 13:47:18'
),
(
  2,
  'Party Makeup',
  'Makeup',
  'Professional makeup for parties and special occasions.',
  90.00,
  60,
  '2026-09-09 13:47:18'
),
(
  3,
  'Hair Styling',
  'Hair',
  'Professional hair styling for any occasion.',
  70.00,
  60,
  '2026-09-09 13:47:18'
),
(
  4,
  'Facial Treatment',
  'Skincare',
  'Relaxing facial treatment designed to refresh and hydrate the skin.',
  95.00,
  75,
  '2026-09-09 13:47:18'
),
(
  5,
  'Manicure',
  'Nails',
  'Professional nail shaping and manicure service.',
  45.00,
  45,
  '2026-09-09 13:47:18'
);

--
-- Indexes for table `services`
--

ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `services`
--

ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for table `enquiries`
--

ALTER TABLE `enquiries`
  ADD CONSTRAINT `enquiries_ibfk_1`
  FOREIGN KEY (`service_id`)
  REFERENCES `services` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
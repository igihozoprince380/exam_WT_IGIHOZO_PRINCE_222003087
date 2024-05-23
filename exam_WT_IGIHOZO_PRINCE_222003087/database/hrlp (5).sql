-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2024 at 07:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrlp`
--

-- --------------------------------------------------------

--
-- Table structure for table `furniture`
--

CREATE TABLE `furniture` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `subject`, `message`, `timestamp`) VALUES
(2, 7, 8, 'buy house', 'your message was sent make payment process', '2024-05-18 11:04:23');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `property_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_name`, `amount`, `payment_date`, `payment_method`, `payment_status`, `property_id`) VALUES
(6, 'ronaldo', 13000.00, '2024-05-18', 'Mobile Money', 'Pending', 8),
(7, 'ronaldo', 13000.00, '2024-05-18', 'Mobile Money', 'Pending', 8),
(8, 'ronaldo', 13000.00, '2024-05-18', 'Mobile Money', 'Pending', 8),
(9, 'ronaldo', 13000.00, '2024-05-18', 'Mobile Money', 'Pending', 8),
(10, 'ronaldo', 13000.00, '2024-05-18', 'Mobile Money', 'Pending', 8),
(11, 'ronaldo', 13000.00, '2024-05-18', 'Mobile Money', 'Pending', 8),
(12, 'ronaldo', 0.00, '2024-05-19', 'Mobile Money', 'Pending', 8),
(13, 'ronaldo', 600.00, '2024-05-19', 'Airtel Money', 'Pending', 8),
(14, 'ronaldo', 5000.00, '2024-05-30', 'Airtel Money', 'Pending', 12);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL CHECK (`discount_percentage` >= 0 and `discount_percentage` <= 100),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `Property_Type` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'available',
  `booking_status` varchar(20) DEFAULT 'available',
  `booked_by` varchar(100) DEFAULT NULL,
  `rented_by` varchar(100) NOT NULL DEFAULT '',
  `Price_Type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `Property_Type`, `Address`, `Description`, `Price`, `Photo`, `status`, `booking_status`, `booked_by`, `rented_by`, `Price_Type`) VALUES
(12, 'house', 'kimisagara', '2 bedroom|2 saloon', 5000.00, '4.JPEG', 'available', 'available', NULL, '', 'booking'),
(13, 'house', 'huye', 'gggg123', 2000.00, '4.JPEG', 'available', 'available', NULL, '', 'buy'),
(14, 'apartment', 'nyamagabe', '2 bedroom', 100000.00, '3.JPEG', 'available', 'available', NULL, '', 'booking'),
(15, 'apartment', 'huye', 'good', 100000.00, '1.JPEG', 'available', 'available', NULL, '', 'rent');

-- --------------------------------------------------------

--
-- Table structure for table `rental_listings`
--

CREATE TABLE `rental_listings` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `property_id`, `rating`, `comment`, `created_at`) VALUES
(8, 12, 3, 'bbbbb', '2024-05-22 20:16:26');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `email`, `phone`, `address`, `password`) VALUES
(11, 'kayihura prince', 'kayihura@gmail.com', '1234567', 'huye', '$2y$10$hpA..BLdhy6/a6AT6oa5DuUufNJbgGxUMSRZcdF0BASJk19IW4cGq'),
(12, 'lionel messi', 'messi@gmail.com', '0785618328', 'huye', '$2y$10$1tSpco3Q7JLNbBP1GY/DJuGExtVSRN0l3ZNYOPdSlrg1bk0ty/vIS'),
(13, 'bale', 'bale@gmail.com', '12345', 'nyamagabe', '$2y$10$1jEKArRntyHAT4L5Pg0qk.83nxwZgEh9ulHV78QVu6eRdveTsodcm');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL,
  `Role` enum('Landlord','Tenant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Username`, `Email`, `Password_Hash`, `Role`) VALUES
(3, 'pogba', 'igihozo@gmail.com', '$2y$10$z9HbBMAYBtfEc55qtDj.QOfAAiboRy12NtKrPwITfOIbaJivz4DZO', 'Tenant'),
(4, 'piokk', 'piok@gmail.com', '$2y$10$fkvFsGqJB0r1B6w.Q3/yF.PYfHDK5ZYl70OYITXlf57l6uXKI1w9y', 'Landlord'),
(5, 'piok', 'kenny@gmail.com', '$2y$10$hrKd3HU/sUZNqR0iuM70K.E1VHltEq/Z9j.2ngzvR4IRCglkxpbLO', 'Landlord'),
(6, 'jean', 'jean@gmail.com', '$2y$10$.Cxy12GMX5TZjytc8Br.OuNTIxF7fFtW0SgC.GvwcV3hmpFVHyKPG', 'Tenant'),
(7, 'messi', 'messi@gmail.com', '$2y$10$IbopeGJ7Y4Vu8MbChTSLSumRXvnmKxdytksHQAGRu8euKvDsu1bEi', 'Landlord'),
(8, 'ronaldo', 'ronaldo@gmail.com', '$2y$10$GYldBGaxYzS.uErE7dy1SOqJXw8pVhcKp/NmUR.3WiTaXUL/lFM0.', 'Tenant'),
(9, 'element', 'kayihura@gmail.com', '$2y$10$LnARqHD.CnGBbQlkAENQSeydYHIYJ0p9pV39nYmyzLajbZHg2vdia', 'Landlord'),
(10, 'neymar', 'neymar@gmail.com', '$2y$10$/ncpvp7jjaKXbGYzvZH8E.fhRXbuP1NV.nJnuOUBcoNkx9UxgR2Lu', 'Landlord'),
(11, 'piok', 'kayihura@gmail.com', '$2y$10$hpA..BLdhy6/a6AT6oa5DuUufNJbgGxUMSRZcdF0BASJk19IW4cGq', 'Tenant'),
(12, 'pogba', 'messi@gmail.com', '$2y$10$1tSpco3Q7JLNbBP1GY/DJuGExtVSRN0l3ZNYOPdSlrg1bk0ty/vIS', 'Tenant'),
(13, 'bale', 'bale@gmail.com', '$2y$10$1jEKArRntyHAT4L5Pg0qk.83nxwZgEh9ulHV78QVu6eRdveTsodcm', 'Tenant');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `furniture`
--
ALTER TABLE `furniture`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_listings`
--
ALTER TABLE `rental_listings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `furniture`
--
ALTER TABLE `furniture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rental_listings`
--
ALTER TABLE `rental_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

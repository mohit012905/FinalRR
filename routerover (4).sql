-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 05:57 PM
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
-- Database: `routerover`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` int(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `secret_key` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `phone`, `password`, `secret_key`) VALUES
(4, 'mohit', 'mohitraval2905@gmail.com', 2147483647, '$2y$10$1YOU1BkIYS595HZcX6cumeEKeGE9EUPAMzSSzrKoyCzQghgJwR3B.', 'MOHIT_2005'),
(5, 'ishii', 'ishishah@gmail.com', 2147483647, '0123456', 'MOHIT_2005'),
(6, 'ayush67', 'ayush1234@gmail.com', 1234567890, '147852', 'MOHIT_2005');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `load_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loads`
--

CREATE TABLE `loads` (
  `load_id` int(11) NOT NULL,
  `s_id` int(10) UNSIGNED DEFAULT NULL,
  `pickup` varchar(255) NOT NULL,
  `drop` varchar(255) NOT NULL,
  `material` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `vehicletype` varchar(50) NOT NULL,
  `truckbody` varchar(50) NOT NULL,
  `tyre` int(11) NOT NULL,
  `paymentmethod` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loads`
--

INSERT INTO `loads` (`load_id`, `s_id`, `pickup`, `drop`, `material`, `quantity`, `vehicletype`, `truckbody`, `tyre`, `paymentmethod`, `created_at`) VALUES
(4, 1, 'Rajasthan', 'Gujarat', 'sdsfwdq', 33, 'dafadS', 'qwdeqeds', 18, 'Online', '2025-03-18 14:10:40'),
(6, 1, 'Gandhinagar', 'Himatnagar', 'Food Grains', 12, 'Tanker', 'qwqeq', 12, 'Cash', '2025-03-18 14:40:26'),
(7, 2, 'Kolkata', 'chennai', 'Electronics', 12, 'Container Truck', 'Closed', 14, 'Online', '2025-03-18 17:19:14'),
(8, 3, 'Hydrabad', 'Surat', 'Furniture', 13, 'Mini Truck', 'Closed', 12, 'Online', '2025-03-18 18:35:02');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `s_id` int(10) UNSIGNED NOT NULL,
  `t_id` int(50) NOT NULL,
  `load_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `s_id`, `t_id`, `load_id`, `message`, `created_at`) VALUES
(73, 3, 10, 8, 'Your posted load is Accepted', '2025-04-04 16:42:49'),
(79, 1, 10, 6, 'Your posted load is Accepted', '2025-04-04 16:51:34'),
(81, 2, 10, 7, 'Your posted load is Accepted', '2025-04-04 16:57:13'),
(93, 3, 10, 8, 'Your posted load is Accepted', '2025-04-06 13:19:23'),
(94, 3, 10, 8, 'Your posted load is Accepted', '2025-04-06 14:00:37');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `s_id` int(10) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `card_number` varchar(16) DEFAULT NULL,
  `cardholder_name` varchar(100) DEFAULT NULL,
  `expiry_date` varchar(7) DEFAULT NULL,
  `cvv` varchar(4) DEFAULT NULL,
  `upi_id` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `wallet` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `s_id`, `payment_method`, `card_number`, `cardholder_name`, `expiry_date`, `cvv`, `upi_id`, `bank_name`, `wallet`, `amount`, `payment_date`) VALUES
(1, 0, 'card', '12312425241415', 'MOHIT', '2021-01', '258', NULL, NULL, NULL, 1000.00, '2025-03-18 19:20:08'),
(2, 0, 'card', '12312425241415', 'MOHIT', '2021-01', '258', NULL, NULL, NULL, 1000.00, '2025-03-18 19:21:56'),
(3, 0, 'card', '12312425241415', 'MOHIT', '2021-01', '258', NULL, NULL, NULL, 1000.00, '2025-03-18 19:22:33'),
(4, 0, 'wallet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1475.00, '2025-04-05 17:36:54'),
(5, 0, 'netbanking', NULL, NULL, NULL, NULL, NULL, 'ICICI', NULL, 1400.00, '2025-04-05 17:40:59'),
(6, 1, 'netbanking', NULL, NULL, NULL, NULL, NULL, 'SBI', NULL, 1475.00, '2025-04-06 15:18:09'),
(7, 3, 'upi', NULL, NULL, NULL, NULL, 'mjdgdiad@oksbi', NULL, NULL, 5000.00, '2025-04-06 15:18:56');

-- --------------------------------------------------------

--
-- Table structure for table `shipperprofile`
--

CREATE TABLE `shipperprofile` (
  `id` int(11) NOT NULL,
  `s_id` int(11) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipperprofile`
--

INSERT INTO `shipperprofile` (`id`, `s_id`, `profile_photo`, `fullname`, `address`, `dob`, `age`) VALUES
(1, 23, '41570eef-074f-47fd-bdb4-21be0bea7630.jpg', 'RAVAL MOHIT M.', 'rhrhedfafqaqa', '2004-11-26', 20),
(2, 24, 'admin.png', 'AYUSH CHANDRASHEKHAR SINGH', 'ZUNDAL,GANDHINAGAR', '2004-09-30', 20),
(3, 26, 'admin.png', 'tamboli', 'ssdsdsds', '2222-02-22', 196),
(4, 1, 'admin.png', 'RAVAL MOHIT M.', 'egwtwyetgw', '2005-01-29', 20),
(5, 2, 'bg-image.jpg', 'RAVAL AAYUSHI M.', 'Ahmedavbs', '2010-07-07', 14),
(6, 3, 'mohit.jpeg', 'ishii shah', 'rajasthan', '2004-11-26', 20);

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE `shippers` (
  `s_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phoneno` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shippers`
--

INSERT INTO `shippers` (`s_id`, `username`, `email`, `phoneno`, `password`) VALUES
(1, 'mohit', 'mohitraval2905@gmail.com', '1234567890', '123456'),
(2, 'AAYUSHI589', 'aayushiraval23@gmail.com', '7418529630', '123456'),
(3, 'ishii', 'ishishah@gmail.com', '7676767676', '123456'),
(4, 'hansha', 'hansha12@gmail.com', '7418529630', '147852');

-- --------------------------------------------------------

--
-- Table structure for table `transporter_profiles`
--

CREATE TABLE `transporter_profiles` (
  `id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transporter_profiles`
--

INSERT INTO `transporter_profiles` (`id`, `t_id`, `profile_photo`, `fullname`, `address`, `dob`, `age`) VALUES
(4, 8, '20220625_165943.jpg', 'RAVAL MOHIT M.', 'Vastrapur,Ahmedabad', '2005-01-29', 20),
(5, 9, 'heart.png', 'RAVAL AAYUSHI M.', 'AHMEDABAD', '2010-07-07', 14),
(6, 10, '41570eef-074f-47fd-bdb4-21be0bea7630.jpg', 'ishii shah', 'DEwal', '2004-11-26', 20),
(9, 15, 'shipdashbg.jpg', 'RAVAL AAYUSHI M.', 'Ahmedabad', '2010-07-07', 14),
(10, 16, 'mohit.jpeg', 'PATEL MONIKA', 'AHMEDABAD', '2000-02-20', 25);

-- --------------------------------------------------------

--
-- Table structure for table `truck`
--

CREATE TABLE `truck` (
  `t_id` int(50) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `phoneno` int(10) DEFAULT NULL,
  `password` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `truck`
--

INSERT INTO `truck` (`t_id`, `username`, `email`, `phoneno`, `password`) VALUES
(8, 'MOHIT', 'mohitraval90@gmail.com', 1234567890, '123456'),
(9, 'AAYUSHI', 'aayushiraval23@gmail.com', 2147483647, '123456'),
(10, 'ishii', 'ishishah@gmail.com', 2147483647, '123456'),
(15, 'AAYUSHI', 'aastha456@gmail.com', 2147483647, '123456'),
(16, 'Monika', 'monikapatel12@gmail.com', 1234567892, '123456'),
(17, 'mukesh', 'mukesh21@gmail.com', 779456130, '123456');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `t_id` int(10) NOT NULL,
  `vehicle_register_no` varchar(20) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `vehicle_tyre` int(11) NOT NULL,
  `route_permission` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `t_id`, `vehicle_register_no`, `vehicle_type`, `vehicle_tyre`, `route_permission`, `created_at`) VALUES
(10, 10, 'RJ-12-CS-3382', 'Container Truck', 14, 'Gujarat-Rajasthan', '2025-03-07 15:28:28'),
(11, 10, 'GJ-01-CS-1277', 'Pickup Van', 14, 'Uttar Pradesh-Bihar', '2025-03-09 07:56:23'),
(12, 12, 'GJ-01-CS-1258', 'Truck', 9, 'Gujarat-Rajasthan', '2025-03-18 06:29:37'),
(13, 15, 'RJ-12-CS-3385', 'Pickup Van', 12, 'Delhi-Punjab', '2025-03-18 17:16:32'),
(14, 16, 'GJ-01-CS-1288', 'Mini Truck', 12, 'All India', '2025-03-18 18:37:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `load_id` (`load_id`),
  ADD KEY `t_id` (`t_id`);

--
-- Indexes for table `loads`
--
ALTER TABLE `loads`
  ADD PRIMARY KEY (`load_id`),
  ADD KEY `s_id` (`s_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_shipper` (`s_id`),
  ADD KEY `fk_truck` (`t_id`),
  ADD KEY `fk_load` (`load_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `s_id` (`s_id`);

--
-- Indexes for table `shipperprofile`
--
ALTER TABLE `shipperprofile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shippers`
--
ALTER TABLE `shippers`
  ADD PRIMARY KEY (`s_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `transporter_profiles`
--
ALTER TABLE `transporter_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `t_id` (`t_id`);

--
-- Indexes for table `truck`
--
ALTER TABLE `truck`
  ADD PRIMARY KEY (`t_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `t_id` (`t_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loads`
--
ALTER TABLE `loads`
  MODIFY `load_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipperprofile`
--
ALTER TABLE `shipperprofile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shippers`
--
ALTER TABLE `shippers`
  MODIFY `s_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transporter_profiles`
--
ALTER TABLE `transporter_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `truck`
--
ALTER TABLE `truck`
  MODIFY `t_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`load_id`) REFERENCES `loads` (`load_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`t_id`) REFERENCES `truck` (`t_id`);

--
-- Constraints for table `loads`
--
ALTER TABLE `loads`
  ADD CONSTRAINT `loads_ibfk_1` FOREIGN KEY (`s_id`) REFERENCES `shippers` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_load` FOREIGN KEY (`load_id`) REFERENCES `loads` (`load_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_shipper` FOREIGN KEY (`s_id`) REFERENCES `shippers` (`s_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_truck` FOREIGN KEY (`t_id`) REFERENCES `truck` (`t_id`) ON DELETE CASCADE;

--
-- Constraints for table `transporter_profiles`
--
ALTER TABLE `transporter_profiles`
  ADD CONSTRAINT `transporter_profiles_ibfk_1` FOREIGN KEY (`t_id`) REFERENCES `truck` (`t_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

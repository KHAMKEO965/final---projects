-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2026 at 06:19 AM
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
-- Database: `lao_air_cargo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `airline`
--

CREATE TABLE `airline` (
  `airline_id` int(11) NOT NULL,
  `airline_name` varchar(100) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `airline`
--

INSERT INTO `airline` (`airline_id`, `airline_name`, `country`, `contact`) VALUES
(1, 'Lao Airlines', 'Laos', '021 212051'),
(2, 'Thai Airways', 'Thailand', '+66 2 356 1111'),
(3, 'pooo', 'ປະເທດໄທຍ', '65443'),
(4, 'tt', '', '65443'),
(5, 'yu', '', '65443'),
(6, 'ii', '', '65440'),
(7, 'oo', '', '65440'),
(8, 'oo', '', '65443'),
(9, 'pooo', '', '65443');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `phone`, `email`, `address`) VALUES
(3, 'ໂຊໂລ', '1325', 'khamkeosisombath@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(4, 'ແສງ', '666', 'khamkeosisombath@gmail.com', 'ບ້ານນາຄໍາ ເມືອງໄຊເສດຖາ'),
(5, 'ໂຊໂລ', '6634', 'soro@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(7, 'Khamkeo Sisombath', '666', 'khamkeosisombath@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(8, 'seekeo', '2559', 'seekeo@gmail.com', 'ບ້ານດອນກອຍ ເມືອງສີສັດຕະນາກ'),
(9, 'Khamkeo Sisombath', '6667', 'khamkeosisombath@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(10, 'ກ', '974', 'khamkeosisombath@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(11, 'Khamkeo Sisombath', '44444444ffdfddsfdsfj', 'khamkeosisombath@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(12, 'Khamkeo Sisombath rtiuertiuertieruteruterutretretr', '66666', 'khamkeosisombath@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(13, 'ສອນ', '79000', 'khamkeo@gmail.com', 'ບ້ານນາຄວາຍໄຕ້ ເມືອງໄຊເສດຖາ'),
(14, 'ອາຈານແຕ້ວ', 'ຸູ688033322467890000', 'test@gmail.com', 'hzcbmxbc,n,k v.xf /bc.b ,j,b,'),
(16, 'ເພັ', '020', '', ''),
(17, 'ເພັດ', '020789659479', 'p@gmail.com', 'ບ້ານ ໂພນທັນ ເມືອງໄຊເສດຖາ');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `document_id` int(11) NOT NULL,
  `shipment_id` int(11) DEFAULT NULL,
  `document_name` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `upload_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `flight_id` int(11) NOT NULL,
  `airline_id` int(11) DEFAULT NULL,
  `flight_number` varchar(20) NOT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `departure_time` datetime DEFAULT NULL,
  `arrival_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`flight_id`, `airline_id`, `flight_number`, `origin`, `destination`, `departure_time`, `arrival_time`) VALUES
(3, 1, '  qv-340', 'gv', 'zz', '2026-07-22 17:30:00', '2026-07-25 17:31:00'),
(4, 5, 'ົqv123', 'ວຽງຈັນ', 'ຫຼວງພະບາງ', '2026-07-21 17:30:00', '2026-07-25 17:30:00'),
(5, 6, '234', 'ວຽງຈັນ (VTE)', 'ອັດຕະປື (ATP)', '2026-07-28 21:14:00', '2026-07-31 21:14:00'),
(6, 7, '234', 'ຄຳມ່ວນ (KHM)', 'ວຽງຈັນ (VTE)', '2026-07-27 23:26:00', '2026-08-01 23:26:00'),
(7, 4, '234', 'ຊຽງຂວາງ (XKH)', 'ວຽງຈັນ (VTE)', '2026-07-28 23:40:00', '2026-07-29 23:40:00'),
(8, 1, '  qv-367', 'ຈຳປາສັກ (PKZ)', 'ຫຼວງພະບາງ (LPQ)', '2026-08-13 07:14:00', '2026-08-15 07:14:00');

-- --------------------------------------------------------

--
-- Table structure for table `package_type`
--

CREATE TABLE `package_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `weight_limit` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_type`
--

INSERT INTO `package_type` (`id`, `type_name`, `description`, `weight_limit`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ເອກະສານ', 'ເອກະສານທົ່ວໄປ', 1.00, 50000.00, 1, '2026-06-19 04:54:57', '2026-06-19 04:54:57'),
(2, 'ສິນຄ້າທົ່ວໄປ', 'ສິນຄ້າທົ່ວໄປບໍ່ມີຂໍ້ຈຳກັດ', 10.00, 100000.00, 1, '2026-06-19 04:54:57', '2026-06-19 04:54:57'),
(3, 'ສິນຄ້າພິເສດ', 'ສິນຄ້າທີ່ຕ້ອງການດູແລພິເສດ', 5.00, 200000.00, 1, '2026-06-19 04:54:57', '2026-06-19 04:54:57'),
(4, 'ນົມ', 'ຖົງ', NULL, 0.00, 1, '2026-06-19 05:12:04', '2026-06-19 05:12:04'),
(5, 'ແກັດ', 'ມ', NULL, 0.00, 1, '2026-06-19 05:12:20', '2026-06-30 11:57:14'),
(6, 'ເສື້ອ', 'ຖົງ', NULL, 0.00, 1, '2026-06-22 11:37:06', '2026-06-22 11:37:06'),
(7, 'ເຄື່ອງດື່ມ', 'ແກັດ', NULL, 0.00, 1, '2026-06-30 11:59:38', '2026-06-30 11:59:38'),
(8, 'ເສື້ອ', 'ຖົງ', NULL, 0.00, 1, '2026-07-27 14:13:19', '2026-07-27 14:13:19'),
(9, 'ເສື້ອ', 'ຖົງ', NULL, 0.00, 1, '2026-07-27 16:26:10', '2026-07-27 16:26:10'),
(10, 'ແກັດ', 'ຖົງ', NULL, 0.00, 1, '2026-07-27 16:39:55', '2026-07-27 16:39:55'),
(11, 'ນົມ', 'ຖົງ', NULL, 0.00, 1, '2026-08-05 13:03:57', '2026-08-05 13:03:57'),
(12, 'ແກັດ', '', NULL, 0.00, 1, '2026-08-13 00:21:53', '2026-08-13 00:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `shipment_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `shipment_id`, `amount`, `payment_date`) VALUES
(8, 7, 1500000.00, '2026-07-27'),
(9, 8, 6790.00, '2026-07-28'),
(10, 10, 70000.00, '2026-07-27'),
(11, 11, 555555.00, '2026-08-05'),
(12, 12, 200000.00, '2026-08-12'),
(13, 13, 3000000.00, '2026-08-13');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'ອື່ນໆ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `weight`, `quantity`, `description`, `category`) VALUES
(1, 'ເຄື່ອງເອເລັກໂຕຣນິກ', 15.50, 5, 'ໂທລະສັບ ແລະ ຄອມພິວເຕີ', 'ເຄື່ອງໃຊ້ໄຟຟ້າ'),
(2, 'ກາເຟດາວ', 50.00, 12, 'ກາເຟເມັດອໍແກນິກ', 'ເຄື່ອງບໍລິໂພກ'),
(5, 'ກາເຟດາວ', 3000.00, 90, 'ຖົງ', 'ເຄື່ອງບໍລິໂພກ'),
(7, 'ເສື້ອ', 40.00, 29, '', 'ອື່ນໆ'),
(8, 'eu', 8000.00, 66, '', 'ເຄື່ອງບໍລິໂພກ'),
(9, 'ກາເຟດາວ', 56.00, 6, '', 'ເຄື່ອງບໍລິໂພກ'),
(10, 'ກາເຟດາວ', 6.00, 5, '', 'ເຄື່ອງບໍລິໂພກ'),
(11, 'ຕູ້ເຢັນ', 13.00, 100, '', 'ເຄື່ອງໃຊ້ໄຟຟ້າ'),
(12, 'ຕູ້ຊັກຜ້າ', 15.00, 12, '', 'ເຄື່ອງໃຊ້ໄຟຟ້າ');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `schedule_id` int(11) NOT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `departure_time` datetime DEFAULT NULL,
  `arrival_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`schedule_id`, `flight_id`, `departure_time`, `arrival_time`) VALUES
(4, 4, '2026-06-23 18:38:00', '2026-07-01 18:39:00'),
(5, 5, '2026-07-28 21:15:00', '2026-07-31 21:15:00'),
(6, 6, '2026-07-28 23:27:00', '2026-07-29 23:27:00'),
(7, 7, '2026-07-28 23:41:00', '2026-07-31 23:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `shipment_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `flight_id` int(11) DEFAULT NULL,
  `shipment_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`shipment_id`, `customer_id`, `product_id`, `flight_id`, `shipment_date`, `status`) VALUES
(7, 8, 7, 5, '2026-07-28', 'ສຳເລັດແລ້ວ'),
(8, 9, 8, 6, '2026-07-28', 'ກຳລັງຮຽນຕຽມ'),
(10, 10, 2, 6, '2026-07-27', 'ສຳເລັດແລ້ວ'),
(11, 12, 5, 4, '2026-08-05', 'ສຳເລັດແລ້ວ'),
(12, 13, 7, 3, '2026-08-12', 'ກຳລັງຂົນສົ່ງ'),
(13, 17, 11, 8, '2026-08-13', 'ກຳລັງຂົນສົ່ງ'),
(14, 3, 11, 3, '2026-08-14', 'ກຳລັງຂົນສົ່ງ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'john_doe', 'john_doe'),
(3, 'admin', 'admin123'),
(4, 'staff1', 'staff123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airline`
--
ALTER TABLE `airline`
  ADD PRIMARY KEY (`airline_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `shipment_id` (`shipment_id`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`flight_id`),
  ADD KEY `airline_id` (`airline_id`);

--
-- Indexes for table `package_type`
--
ALTER TABLE `package_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `shipment_id` (`shipment_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipment_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `flight_id` (`flight_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airline`
--
ALTER TABLE `airline`
  MODIFY `airline_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `flight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `package_type`
--
ALTER TABLE `package_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`shipment_id`) REFERENCES `shipment` (`shipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flight_ibfk_1` FOREIGN KEY (`airline_id`) REFERENCES `airline` (`airline_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`shipment_id`) REFERENCES `shipment` (`shipment_id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`) ON DELETE CASCADE;

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipment_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipment_ibfk_3` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

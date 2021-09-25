-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Sep 25, 2021 at 09:10 AM
-- Server version: 8.0.26
-- PHP Version: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp`
--
CREATE DATABASE IF NOT EXISTS `erp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `erp`;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `address`, `country`) VALUES
(1, 'editedName', 'my new address', 'Still France');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `balance` float NOT NULL DEFAULT '0',
  `country` varchar(50) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `balance`, `country`) VALUES
(1, 'testName', 1234.57, 'France'),
(2, 'secondCompany', 2.34, 'secondCountry');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `birthday` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `firstDayInCompany` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `companyId` int NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `companyId` (`companyId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `country`, `birthday`, `firstDayInCompany`, `companyId`) VALUES
(1, 'editedName', 'editedCountry', '2000-01-01 00:00:00', '2000-02-02 00:00:00', 1),
(2, 'employeeName', 'employeeCountry', '1997-02-05 00:00:00', '2021-09-23 08:58:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `tax` float NOT NULL DEFAULT '0',
  `companyId` int NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `companyId` (`companyId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `stock`, `price`, `tax`, `companyId`) VALUES
(1, 'product1Name', 12, 125, 9.99, 1),
(2, 'product2Name', 10, 47.53, 2.46, 1);

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE IF NOT EXISTS `providers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `name`, `address`, `country`) VALUES
(1, 'edited1stName', 'edited1stAddress', 'edited1stCountry'),
(2, 'secondName', 'secondAddress', 'secondCountry');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `clientId` int UNSIGNED DEFAULT NULL,
  `companyId` int NOT NULL,
  `providerId` int DEFAULT NULL,
  `productId` int NOT NULL,
  `productQuantity` int UNSIGNED NOT NULL,
  `responsibleEmployeeId` int NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `clientId` (`clientId`),
  KEY `companyId` (`companyId`),
  KEY `productId` (`productId`),
  KEY `providerId` (`providerId`),
  KEY `responsibleEmployeeId` (`responsibleEmployeeId`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `clientId`, `companyId`, `providerId`, `productId`, `productQuantity`, `responsibleEmployeeId`) VALUES
(10, NULL, 1, 1, 1, 2, 1),
(12, 1, 1, NULL, 1, 1, 1),
(13, 1, 1, NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `role` enum('admin','reader') NOT NULL DEFAULT 'reader',
  `password` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `password`) VALUES
(1, 'user1', 'reader', 'pwd1'),
(2, 'user2', 'reader', 'pwd2'),
(5, 'user3', 'admin', '$2y$10$EQlEERqsxXS2D6/Qmg9BvOOVoDCfUpoFKSavAGxpHysCMAvGlkdxe');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `companyId` FOREIGN KEY (`companyId`) REFERENCES `companies` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`companyId`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`clientId`) REFERENCES `clients` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`companyId`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`providerId`) REFERENCES `providers` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transactions_ibfk_5` FOREIGN KEY (`responsibleEmployeeId`) REFERENCES `employees` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

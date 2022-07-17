-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2022 at 04:33 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agpay`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `continent_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso2_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso3_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso_numeric_code` int(11) DEFAULT NULL,
  `fips_code` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calling_code` int(11) DEFAULT NULL,
  `common_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endonym` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `demonym` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `iso_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_numeric_code` int(11) NOT NULL,
  `common_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `official_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `common_name` (`common_name`),
  ADD KEY `official_name` (`official_name`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `official_name` (`official_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

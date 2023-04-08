-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Host: db5012315885.hosting-data.io
-- Generation Time: Apr 07, 2023 at 10:37 PM
-- Server version: 10.6.10-MariaDB-1:10.6.10+maria~deb11-log
-- PHP Version: 7.0.33-0+deb9u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbs10360701`
--

-- --------------------------------------------------------

--
-- Table structure for table `exampleEvent`
--

CREATE TABLE `exampleEvent` (
  `id` int(11) NOT NULL,
  `Uname` text NOT NULL,
  `barcode` text NOT NULL,
  `checked_in` tinyint(1) NOT NULL,
  `email` text NOT NULL,
  `dateOfEvent` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `exampleEvent`
--

INSERT INTO `exampleEvent` (`id`, `Uname`, `barcode`, `checked_in`, `email`, `dateOfEvent`) VALUES
(1, 'Jake Slamm', 'ABC123', 1, 'test@gmail.com', '0000-00-00'),
(2, 'Josh', '642729e3db6c6', 0, 'abc@gmail.com', '2023-03-31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exampleEvent`
--
ALTER TABLE `exampleEvent`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exampleEvent`
--
ALTER TABLE `exampleEvent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

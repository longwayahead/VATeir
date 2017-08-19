-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 19, 2017 at 02:45 PM
-- Server version: 10.1.23-MariaDB-9+deb9u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vateir_statistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `airfields`
--

CREATE TABLE `airfields` (
  `id` tinyint(2) UNSIGNED NOT NULL,
  `icao` varchar(4) NOT NULL,
  `lat` varchar(10) NOT NULL,
  `lon` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `airfields`
--

INSERT INTO `airfields` (`id`, `icao`, `lat`, `lon`) VALUES
(1, 'EIDW', '53.421389', '-6.27'),
(2, 'EICK', '51.841389', '-8.491111'),
(3, 'EINN', '52.701978', '-8.9248170'),
(4, 'EIKY', '52.180833', '-9.523889'),
(5, 'EIWT', '53.352292', '-6.488311'),
(6, 'EIDL', '55.044167', '-8.341111'),
(7, 'EIKN', '53.910278', '-8.818611'),
(8, 'EIWF', '52.187222', '-7.086944'),
(9, 'EISG', '54.280278', '-8.599167'),
(10, 'EICM', '53.300278', '-8.941667'),
(11, 'EIBN', '51.6777', '-9.4870');

-- --------------------------------------------------------

--
-- Table structure for table `movements`
--

CREATE TABLE `movements` (
  `callsign` varchar(10) NOT NULL,
  `cid` int(11) NOT NULL,
  `logon_time` datetime NOT NULL,
  `dep` varchar(4) NOT NULL,
  `arr` varchar(4) NOT NULL,
  `dep_time` datetime DEFAULT NULL,
  `arr_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `cid` int(7) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `position` varchar(10) NOT NULL,
  `facility` tinyint(1) NOT NULL,
  `start` datetime NOT NULL,
  `finish` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airfields`
--
ALTER TABLE `airfields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movements`
--
ALTER TABLE `movements`
  ADD PRIMARY KEY (`cid`,`logon_time`,`dep`,`arr`) USING BTREE;

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`cid`,`position`,`start`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airfields`
--
ALTER TABLE `airfields`
  MODIFY `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

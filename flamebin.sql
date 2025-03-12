-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 04, 2023
-- Server version: 10.5.18-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flamebin`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `com_author` varchar(255) NOT NULL,
  `com_text` varchar(255) NOT NULL,
  `com_time` datetime NOT NULL DEFAULT current_timestamp(),
  `com_id` int(11) NOT NULL DEFAULT 0,
  `com_paste` int(11) NOT NULL DEFAULT 0,
  `com_userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `doxes`
--

CREATE TABLE `doxes` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `add` datetime NOT NULL DEFAULT current_timestamp(),
  `uid` varchar(255) NOT NULL,
  `private` int(11) NOT NULL,
  `pinned` int(11) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT 0,
  `unlisted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Table structure for table `payment_log`
--

CREATE TABLE `payment_log` (
  `Payment_ID` varchar(255) NOT NULL,
  `Order_ID` int(11) DEFAULT NULL,
  `Amount_BTC` varchar(255) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `Time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `users_uid` tinytext NOT NULL,
  `users_pwd` longtext NOT NULL,
  `users_email` tinytext NOT NULL,
  `users_rank` int(11) NOT NULL DEFAULT 0,
  `joined` datetime NOT NULL DEFAULT current_timestamp(),
  `pastes` int(11) NOT NULL DEFAULT 0,
  `profileimg` varchar(255) NOT NULL DEFAULT '/uploads/pfp/default.png',
  `profilebkg` varchar(255) NOT NULL DEFAULT '',
  `bio` varchar(255) NOT NULL DEFAULT 'No bio set.',
  `banned` int(11) NOT NULL DEFAULT 0,
  `ban_reason` varchar(255) NOT NULL DEFAULT '',
  `banned_by` varchar(255) NOT NULL DEFAULT '',
  `hex_code` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Table structure for table `flagged_pastes`
--

CREATE TABLE `flagged_pastes` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    creator_id INT,
    data LONGBLOB,
    reason VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`com_id`);

--
-- Indexes for table `doxes`
--
ALTER TABLE `doxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_log`
--
ALTER TABLE `payment_log`
  ADD PRIMARY KEY (`Payment_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`);

--
-- Indexes for table `flagged_pastes`
--
ALTER TABLE `flagged_pastes`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1436;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

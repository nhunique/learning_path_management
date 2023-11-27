-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Nov 21, 2023 at 05:12 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `learningpaths_db`
--
CREATE DATABASE IF NOT EXISTS `learningpaths_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `learningpaths_db`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Nov 21, 2023 at 04:05 PM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userID` int(50) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `email` varchar(255) UNIQUE KEY  NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `createdDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profilePhotoPath` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

-- --------------------------------------------------------
-- Table structure for table `learning_paths`
--
-- Creation: Nov 21, 2023 at 04:06 PM
--

DROP TABLE IF EXISTS `learning_paths`;
CREATE TABLE `learning_paths` (
  `pathID` int(50) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` varchar(255) UNIQUE KEY NOT NULL,
  `description` text NOT NULL,
  `createdDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `userID` int(50) UNSIGNED NOT NULL,
  `votes` int(50) NOT NULL DEFAULT 0,
  FOREIGN KEY (userID) REFERENCES users(userID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- RELATIONSHIPS FOR TABLE `learning_paths`:
--

-- --------------------------------------------------------



--
-- Table structure for table `user_vote`
--
-- Creation: Nov 21, 2023 at 04:10 PM
--
DROP TABLE IF EXISTS `user_vote`;
CREATE TABLE `user_vote` (
  `id` int(50) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `pathID` int(50) UNSIGNED NOT NULL,
  `userID` int(50) UNSIGNED NOT NULL,
  UNIQUE KEY `unique_vote` (userID, pathID),
  FOREIGN KEY (userID) REFERENCES users(userID),
  FOREIGN KEY (pathID) REFERENCES learning_paths(pathID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

--
-- Table structure for table `urls`
--
-- Creation: Nov 21, 2023 at 04:10 PM
--
DROP TABLE IF EXISTS `urls`;
CREATE TABLE `urls` (
  `urlID` int(50) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `urlTitle` varchar(255) UNIQUE KEY NOT NULL,
  `urlLink` varchar(255) NOT NULL,
  `pathID` int(50) UNSIGNED NOT NULL,
  FOREIGN KEY (pathID) REFERENCES learning_paths(pathID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

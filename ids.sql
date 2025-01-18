-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 12:14 PM
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
-- Database: `ids`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `ID` int(11) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Reputation` int(11) DEFAULT 0,
  `Role` enum('User','Admin') DEFAULT 'User',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`ID`, `UserName`, `Email`, `Password`, `Reputation`, `Role`, `CreatedAt`) VALUES
(2, 'JohnDoe', 'johndoe@example.com', '$2y$10$/5z/XiPF8xW8ZBaWueqSweDi61TBwBrjkhTmc7nW..8SUsxnFSStO', 10, 'User', '2025-01-16 22:04:13'),
(6, 'Mohamad', 'mohamadderyan445@gmail.com', '$2y$10$wH53Nsn6xL8dvUOoe5VqMOmxBc7YoQhazZhjyF/6sTB6LWES3u1Vu', 0, 'User', '2025-01-17 00:09:38'),
(7, 'Mohamad', 'mohamadderyan@gmail.com', '$2y$10$smNzvC/kEpWR.7emu42QBes5rmSOMRGchajHLvid0EFVZ3JO/Tkj.', 0, 'User', '2025-01-17 02:18:48'),
(8, 'Deryan', 'mohamadderyan5@gmail.com', '$2y$10$4j/gD2/EKzG5pLMwcE2iLucJMR/9NzhLMTwnKan8qIVItoNhVU.Ne', 0, 'User', '2025-01-17 02:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`ID`, `Name`) VALUES
(1, 'Bug Fixes'),
(2, 'Best Practices'),
(3, 'New Ideas'),
(4, 'Code Optimization'),
(5, 'Tutorials'),
(6, 'FAQs'),
(7, 'Announcements'),
(8, 'Coding Challenges'),
(9, 'Tools & Resources'),
(10, 'General Discussion'),
(11, 'Project Showcases'),
(12, 'Tips & Tricks'),
(13, 'Debugging Help'),
(14, 'Career Advice'),
(15, 'Community News');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `ID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Content` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Message` text NOT NULL,
  `IsRead` tinyint(1) DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Upvotes` int(11) DEFAULT 0,
  `Downvotes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`ID`, `UserID`, `Title`, `Description`, `ImageURL`, `CreatedAt`, `UpdatedAt`, `Upvotes`, `Downvotes`) VALUES
(24, 6, 'Fifth Post', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam et justo quis arcu mattis malesuada. Maecenas nec risus vel mauris vestibulum tristique vitae eu orci. Praesent bibendum arcu sit amet.', '6789b9c266d47-0B0EC48C-2976-4648-95B7-C415DA2DC33B_1_105_c.jpeg', '2025-01-17 02:00:34', '2025-01-17 02:00:34', 0, 0),
(25, 6, 'New Trial', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam et justo quis arcu mattis malesuada. Maecenas nec risus vel mauris vestibulum tristique vitae eu orci. Praesent bibendum arcu sit amet.', '6789bacc0096f-knowledge-sharing-platform-examples.jpg', '2025-01-17 02:05:00', '2025-01-17 02:05:00', 0, 0),
(26, 6, 'New Trial 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam et justo quis arcu mattis malesuada. Maecenas nec risus vel mauris vestibulum tristique vitae eu orci. Praesent bibendum arcu sit amet.', '6789bbc2618d4-168838.jpg', '2025-01-17 02:09:06', '2025-01-17 02:09:06', 0, 0),
(27, 6, 'New Trial 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam et justo quis arcu mattis malesuada. Maecenas nec risus vel mauris vestibulum tristique vitae eu orci. Praesent bibendum arcu sit amet.', '6789bbd8a822d-WhatsApp Image 2025-01-13 at 15.24.57_949b4f8e.jpg', '2025-01-17 02:09:28', '2025-01-17 02:09:28', 0, 0),
(28, 6, 'New Trial 4', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam et justo quis arcu mattis malesuada. Maecenas nec risus vel mauris vestibulum tristique vitae eu orci. Praesent bibendum arcu sit amet.', '6789bbed0741e-20241106_142221.jpg', '2025-01-17 02:09:49', '2025-01-17 02:09:49', 0, 0),
(29, 8, 'Another Trial', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam et justo quis arcu mattis malesuada. Maecenas nec risus vel mauris vestibulum tristique vitae eu orci. Praesent bibendum arcu sit amet.', '6789be51b415c-IMG-20240724-WA0057.jpg', '2025-01-17 02:20:01', '2025-01-17 02:20:01', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `postcategory`
--

CREATE TABLE `postcategory` (
  `PostID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postcategory`
--

INSERT INTO `postcategory` (`PostID`, `CategoryID`) VALUES
(24, 11),
(25, 8),
(26, 8),
(27, 12),
(28, 12),
(29, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `postcategory`
--
ALTER TABLE `postcategory`
  ADD PRIMARY KEY (`PostID`,`CategoryID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `post` (`ID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `account` (`ID`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `account` (`ID`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `account` (`ID`);

--
-- Constraints for table `postcategory`
--
ALTER TABLE `postcategory`
  ADD CONSTRAINT `postcategory_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `post` (`ID`),
  ADD CONSTRAINT `postcategory_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

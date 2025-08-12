-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 04:36 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ans`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `admin_id` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(16) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `phone_number` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`first_name`, `last_name`, `admin_id`, `email`, `password`, `date_of_birth`, `gender`, `phone_number`) VALUES
('Admin', 'A', 'Admin', 'admin@gmail.com', '1234', '2000-01-01', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `ban`
--

CREATE TABLE `ban` (
  `banned_by` varchar(10) NOT NULL,
  `banned_user_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `message_id` int(11) NOT NULL,
  `id` varchar(10) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`message_id`, `id`, `message`, `timestamp`) VALUES
(15, 'ssabab16', 'Message test from user', '2024-11-18 20:00:15'),
(16, 'Admin', 'Message test from Admin', '2024-11-18 20:00:38'),
(17, 'mod123', 'Message Test from Moderator', '2024-11-18 20:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `moderator`
--

CREATE TABLE `moderator` (
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `moderator_id` varchar(10) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(16) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `phone_number` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `moderator`
--

INSERT INTO `moderator` (`first_name`, `last_name`, `moderator_id`, `email`, `password`, `date_of_birth`, `gender`, `phone_number`) VALUES
('Moderator', '1', 'mod123', 'mod@gmail.com', '123123', '2000-01-01', 'M', '');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `uploaded_by` varchar(50) NOT NULL,
  `notice_text` text NOT NULL,
  `notice_link` varchar(255) DEFAULT NULL,
  `time_date_uploaded` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `uploaded_by`, `notice_text`, `notice_link`, `time_date_uploaded`) VALUES
(3, 'Admin', 'Welcome to our platform.', '', '2024-11-16 14:32:18'),
(6, 'Admin', 'Please read our rules and regulations.', '', '2024-11-18 16:29:05'),
(7, 'mod123', 'Test from Moderator. (Updated)', '', '2024-11-18 16:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `user_id` varchar(10) NOT NULL,
  `upload` int(11) NOT NULL,
  `download` int(11) NOT NULL,
  `ratio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`user_id`, `upload`, `download`, `ratio`) VALUES
('nafesh12', 0, 0, 0),
('ssabab16', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `title`, `author`, `category`, `genre`, `description`, `file_path`, `thumbnail_path`, `uploaded_by`, `timestamp`) VALUES
(4, ' INTRODUCTION TO  ALGORITHMS  THIRD EDITION', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein', 'CSE', 'Education', 'This book is used for CSE373 course.', 'uploads/Introduction To Algorithms (third edition).pdf', 'uploads/Introduction To Algorithms (third edition)_thumbnail.png', 'ssabab16', '2024-11-18 14:14:39'),
(5, 'CALCULUS  EARLYTRANSCENDENTALS', 'HOWARD ANTON, IRL BIVENS, STEPHEN DAVIS', 'MATHS', 'Education', 'This book is used for MAT120, MAT130 and MAT250 course.', 'uploads/Howard Anton, Irl C. Bivens, Stephen Davis - Calculus Early Transcendentals (10th Edition).pdf', 'uploads/Howard Anton, Irl C. Bivens, Stephen Davis - Calculus Early Transcendentals (10th Edition)_thumbnail.png', 'ssabab16', '2024-11-18 14:18:05'),
(6, ' Probability and Statistics  for Engineers and  Scientists', ' Anthony Hayter', 'MATHS', 'Education', 'This book is used for MAT361 course.', 'uploads/Probability and Statistics for Engineers and Scientists  FOURTH EDITION  Anthony Hayter.pdf', 'uploads/Probability and Statistics for Engineers and Scientists  FOURTH EDITION  Anthony Hayter_thumbnail.png', 'nafesh12', '2024-11-18 14:24:29'),
(7, 'Digital Fundamentals', 'Thomas L. Floyd', 'CSE', 'Education', 'This book is used for CSE231 course.', 'uploads/DIGITAL_ELECTRONICS-by-Flyod.pdf', 'uploads/DIGITAL_ELECTRONICS-by-Flyod_thumbnail.png', 'nafesh12', '2024-11-18 14:27:32'),
(9, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'Entertainment', 'Fantasy', 'The first book of the Harry Potter Series by JK Rowling.', 'uploads/harry-potter-sorcerers-stone.pdf', 'uploads/harry-potter-sorcerers-stone_thumbnail.png', 'test123', '2024-11-18 14:36:00'),
(10, 'Electronic  Devices and Circuit Theory', 'Robert L. Boylestad, Louis Nashelsky', 'EEE', 'Education', '', 'uploads/Electronics_Boylestad_11th.pdf', 'uploads/Electronics_Boylestad_11th_thumbnail.png', 'ssabab16', '2024-11-18 14:40:33'),
(11, 'Fundamentals of  Electric Circuits', 'Charles K. Alexander, Matthew N. O. Sadiku', 'EEE', 'Education', 'This book is used for EEE141 course.', 'uploads/Fundamentals of Electric Circuits 4th ed - C. Alexander, M. Sadiku (McGraw-Hill, 2009) WW.pdf', 'uploads/Fundamentals of Electric Circuits 4th ed - C. Alexander, M. Sadiku (McGraw-Hill, 2009) WW_thumbnail.png', 'ssabab16', '2024-11-18 14:43:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `user_id` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(16) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `phone_number` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`first_name`, `last_name`, `user_id`, `email`, `password`, `date_of_birth`, `gender`, `phone_number`) VALUES
('Md. Nafesh ', 'Anam ', 'nafesh12', 'nafesh.anam@northsouth.edu', '4321', '2024-11-01', 'M', ''),
('Sudipto ', 'Sabab', 'ssabab16', 'sudipto.sabab@northsouth.edu', '1234', '2003-07-05', 'M', '+8801305604392');

-- --------------------------------------------------------

--
-- Table structure for table `warn`
--

CREATE TABLE `warn` (
  `id` int(11) NOT NULL,
  `warned_by` varchar(10) NOT NULL,
  `warned_user_id` varchar(10) NOT NULL,
  `warned_reason` text DEFAULT NULL,
  `time_date_uploaded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warn`
--

INSERT INTO `warn` (`id`, `warned_by`, `warned_user_id`, `warned_reason`, `time_date_uploaded`) VALUES
(14, 'Admin', 'mod123', 'Warning to Moderator test.', '2024-11-18 15:18:36'),
(15, 'Admin', 'ssabab16', 'You have broken our rules and regulations. This is your first warning. ', '2024-11-18 15:32:16'),
(16, 'mod123', 'nafesh12', 'Warning from Moderator.', '2024-11-18 15:35:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `ban`
--
ALTER TABLE `ban`
  ADD PRIMARY KEY (`banned_by`,`banned_user_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `moderator`
--
ALTER TABLE `moderator`
  ADD PRIMARY KEY (`moderator_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `warn`
--
ALTER TABLE `warn`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `warn`
--
ALTER TABLE `warn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `statistics`
--
ALTER TABLE `statistics`
  ADD CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

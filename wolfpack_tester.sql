-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 12, 2018 at 12:37 PM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wolfpack_tester`
--

-- Drop tables
DROP TABLE IF EXISTS teaches;
DROP TABLE IF EXISTS grouped_by;
DROP TABLE IF EXISTS answers;
DROP TABLE IF EXISTS owns;
DROP TABLE IF EXISTS has;
DROP TABLE IF EXISTS uses;
DROP TABLE IF EXISTS is_in;

DROP TABLE IF EXISTS professor_account;
DROP TABLE IF EXISTS student_account;
DROP TABLE IF EXISTS class_course;
DROP TABLE IF EXISTS class_section;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS question_session;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `answer` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_course`
--

CREATE TABLE `class_course` (
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `class_course_number` int(11) DEFAULT NULL,
  `location` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offering` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_section`
--

CREATE TABLE `class_section` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `class_section_number` int(11) DEFAULT NULL,
  `location` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offering` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grouped_by`
--

CREATE TABLE `grouped_by` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `has`
--

CREATE TABLE `has` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `is_in`
--

CREATE TABLE `is_in` (
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owns`
--

CREATE TABLE `owns` (
  `professor_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `professor_account`
--

CREATE TABLE `professor_account` (
  `professor_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `professor_school_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `salted_password` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `professor_asked` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(1500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tags` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_session`
--

CREATE TABLE `question_session` (
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `time_created` datetime DEFAULT NULL,
  `tags` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_account`
--

CREATE TABLE `student_account` (
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `student_school_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `salted_password` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uniqueID` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isConfirmed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `student_account`
--

INSERT INTO `student_account` (`student_id`, `first_name`, `last_name`, `student_school_id`, `email`, `salted_password`, `uniqueID`, `isConfirmed`) VALUES
(11, 'firstname', 'lastname', 'aValue', 'sacapuntas9@gmail.com', '$2y$11$oG/VsjxLiKyMsHMCkR0QWONkK35.w.nqpneTTkKWNIejTmXJxD.yG', 'e07991f77bb71abdd18dea9d75b7caec953324d71727a29c0ea25546aa1326a35dac01ac5a517bb76d7abdfe38ecb14ed54beb654b126de4b31d81dc0472a4d2', 1),
(12, 'firstname', 'lastname', 'aValue', 'thepabloski@gmail.com', '$2y$11$c14ESWacmwKBo8.cdD18y.UevfOf1xRCe5vaMrhq2L5QJgd.ctmJC', 'f884746bea5d87d7e2736a363aa2b665dcd3c1419a3ebb89d8112b27d6af7155e5caa265274a6d649ad886f3a752913c2b12c6ee5f7bc6b60f364dc70b6433a3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teaches`
--

CREATE TABLE `teaches` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `professor_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uses`
--

CREATE TABLE `uses` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD KEY `question_id` (`question_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `class_course`
--
ALTER TABLE `class_course`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `class_id` (`class_id`);

--
-- Indexes for table `class_section`
--
ALTER TABLE `class_section`
  ADD PRIMARY KEY (`section_id`),
  ADD UNIQUE KEY `section_id` (`section_id`);

--
-- Indexes for table `grouped_by`
--
ALTER TABLE `grouped_by`
  ADD KEY `question_id` (`question_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `has`
--
ALTER TABLE `has`
  ADD KEY `section_id` (`section_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `is_in`
--
ALTER TABLE `is_in`
  ADD PRIMARY KEY (`class_id`,`student_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `owns`
--
ALTER TABLE `owns`
  ADD KEY `professor_id` (`professor_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `professor_account`
--
ALTER TABLE `professor_account`
  ADD PRIMARY KEY (`professor_id`),
  ADD UNIQUE KEY `professor_id` (`professor_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD UNIQUE KEY `question_id` (`question_id`);

--
-- Indexes for table `question_session`
--
ALTER TABLE `question_session`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `session_id` (`session_id`);

--
-- Indexes for table `student_account`
--
ALTER TABLE `student_account`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `teaches`
--
ALTER TABLE `teaches`
  ADD PRIMARY KEY (`professor_id`),
  ADD UNIQUE KEY `professor_id` (`professor_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `uses`
--
ALTER TABLE `uses`
  ADD KEY `section_id` (`section_id`),
  ADD KEY `session_id` (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_course`
--
ALTER TABLE `class_course`
  MODIFY `class_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class_section`
--
ALTER TABLE `class_section`
  MODIFY `section_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `professor_account`
--
ALTER TABLE `professor_account`
  MODIFY `professor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question_session`
--
ALTER TABLE `question_session`
  MODIFY `session_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_account`
--
ALTER TABLE `student_account`
  MODIFY `student_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `teaches`
--
ALTER TABLE `teaches`
  MODIFY `professor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student_account` (`student_id`),
  ADD CONSTRAINT `answers_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `question_session` (`session_id`);

--
-- Constraints for table `grouped_by`
--
ALTER TABLE `grouped_by`
  ADD CONSTRAINT `grouped_by_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `grouped_by_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `question_session` (`session_id`);

--
-- Constraints for table `has`
--
ALTER TABLE `has`
  ADD CONSTRAINT `has_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`),
  ADD CONSTRAINT `has_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class_course` (`class_id`);

--
-- Constraints for table `is_in`
--
ALTER TABLE `is_in`
  ADD CONSTRAINT `is_in_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_account` (`student_id`),
  ADD CONSTRAINT `is_in_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class_course` (`class_id`),
  ADD CONSTRAINT `is_in_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`);

--
-- Constraints for table `owns`
--
ALTER TABLE `owns`
  ADD CONSTRAINT `owns_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professor_account` (`professor_id`),
  ADD CONSTRAINT `owns_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

--
-- Constraints for table `teaches`
--
ALTER TABLE `teaches`
  ADD CONSTRAINT `teaches_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professor_account` (`professor_id`),
  ADD CONSTRAINT `teaches_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`);

--
-- Constraints for table `uses`
--
ALTER TABLE `uses`
  ADD CONSTRAINT `uses_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`),
  ADD CONSTRAINT `uses_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `question_session` (`session_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

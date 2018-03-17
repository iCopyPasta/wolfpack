-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 17, 2018 at 07:22 PM
-- Server version: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.25-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alpha_wolfpack`
--
CREATE DATABASE IF NOT EXISTS `alpha_wolfpack` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `alpha_wolfpack`;

-- --------------------------------------------------------

--
-- Table structure for table `active_question`
--

CREATE TABLE `active_question` (
  `question_set_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `active_question_set`
--

CREATE TABLE `active_question_set` (
  `class_id` bigint(20) NOT NULL,
  `question_set_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `student_id` bigint(20) NOT NULL,
  `session_id` bigint(20) NOT NULL,
  `question_history_id` bigint(20) NOT NULL,
  `answer_type` varchar(200) DEFAULT NULL,
  `answer` blob,
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `class_course_section`
--

CREATE TABLE `class_course_section` (
  `class_id` bigint(20) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `offering` varchar(100) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owns_question`
--

CREATE TABLE `owns_question` (
  `teacher_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owns_question_set`
--

CREATE TABLE `owns_question_set` (
  `teacher_id` bigint(20) DEFAULT NULL,
  `question_set_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` bigint(20) NOT NULL,
  `question_type` varchar(100) DEFAULT 'multiple_choice',
  `description` varchar(300) DEFAULT NULL,
  `potential_answers` blob,
  `correct_answers` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question_history`
--

CREATE TABLE `question_history` (
  `id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `session_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question_is_in`
--

CREATE TABLE `question_is_in` (
  `question_set_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question_session`
--

CREATE TABLE `question_session` (
  `id` bigint(20) NOT NULL,
  `class_id` bigint(20) NOT NULL,
  `question_set_id` bigint(20) NOT NULL,
  `start_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NULL DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question_set`
--

CREATE TABLE `question_set` (
  `question_set_id` bigint(20) NOT NULL,
  `question_set_name` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_account`
--

CREATE TABLE `student_account` (
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `is_confirmed` tinyint(1) DEFAULT '0',
  `salted_password` varchar(200) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `uniqueID` varchar(128) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_is_in`
--

CREATE TABLE `student_is_in` (
  `student_id` bigint(20) NOT NULL,
  `class_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_account`
--

CREATE TABLE `teacher_account` (
  `teacher_id` bigint(20) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `salted_password` varchar(200) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `uniqueID` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `teaches`
--

CREATE TABLE `teaches` (
  `class_id` bigint(20) NOT NULL,
  `teacher_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` varchar(2000) DEFAULT NULL,
  `name` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_question`
--
ALTER TABLE `active_question`
  ADD PRIMARY KEY (`question_set_id`,`question_id`),
  ADD KEY `FK_active_question_question_id` (`question_id`);

--
-- Indexes for table `active_question_set`
--
ALTER TABLE `active_question_set`
  ADD PRIMARY KEY (`class_id`,`question_set_id`),
  ADD KEY `FK_active_question_set_question_set_id` (`question_set_id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`student_id`,`session_id`,`question_history_id`),
  ADD KEY `FK_answers_session_id` (`session_id`),
  ADD KEY `FK_answers_question_history_id` (`question_history_id`);

--
-- Indexes for table `class_course_section`
--
ALTER TABLE `class_course_section`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `owns_question`
--
ALTER TABLE `owns_question`
  ADD PRIMARY KEY (`question_id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`),
  ADD KEY `FK_owns_question_question_id` (`question_id`);

--
-- Indexes for table `owns_question_set`
--
ALTER TABLE `owns_question_set`
  ADD PRIMARY KEY (`question_set_id`),
  ADD KEY `FK_owns_question_set_teacher_id` (`teacher_id`),
  ADD KEY `FK_owns_question_set_question_set_id` (`question_set_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_history`
--
ALTER TABLE `question_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_question_history_question_id` (`question_id`),
  ADD KEY `FK_question_history_session_id` (`session_id`);

--
-- Indexes for table `question_is_in`
--
ALTER TABLE `question_is_in`
  ADD PRIMARY KEY (`question_set_id`,`question_id`),
  ADD KEY `FK_question_is_in_question_id` (`question_id`);

--
-- Indexes for table `question_session`
--
ALTER TABLE `question_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_question_session_class_id` (`class_id`),
  ADD KEY `FK_question_session_question_set_id` (`question_set_id`);

--
-- Indexes for table `question_set`
--
ALTER TABLE `question_set`
  ADD PRIMARY KEY (`question_set_id`);

--
-- Indexes for table `student_account`
--
ALTER TABLE `student_account`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_is_in`
--
ALTER TABLE `student_is_in`
  ADD PRIMARY KEY (`student_id`,`class_id`),
  ADD KEY `FK_student_is_in_class_id` (`class_id`);

--
-- Indexes for table `teacher_account`
--
ALTER TABLE `teacher_account`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `teaches`
--
ALTER TABLE `teaches`
  ADD PRIMARY KEY (`class_id`,`teacher_id`),
  ADD KEY `FK_teacher_teacher_id` (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_course_section`
--
ALTER TABLE `class_course_section`
  MODIFY `class_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2001;
--
-- AUTO_INCREMENT for table `question_history`
--
ALTER TABLE `question_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question_session`
--
ALTER TABLE `question_session`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question_set`
--
ALTER TABLE `question_set`
  MODIFY `question_set_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;
--
-- AUTO_INCREMENT for table `student_account`
--
ALTER TABLE `student_account`
  MODIFY `student_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7501;
--
-- AUTO_INCREMENT for table `teacher_account`
--
ALTER TABLE `teacher_account`
  MODIFY `teacher_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

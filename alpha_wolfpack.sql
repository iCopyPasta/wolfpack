-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2018 at 08:52 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alpha_wolfpack`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_question`
--

DROP TABLE IF EXISTS `active_question`;
CREATE TABLE `active_question` (
  `question_set_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `active_question_set`
--

DROP TABLE IF EXISTS `active_question_set`;
CREATE TABLE `active_question_set` (
  `class_id` bigint(20) NOT NULL,
  `question_set_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `student_id` bigint(20) NOT NULL,
  `sesssion_id` bigint(20) NOT NULL,
  `question_history_id` bigint(20) NOT NULL,
  `answer_type` varchar(200) DEFAULT NULL,
  `answer` blob,
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`student_id`, `sesssion_id`, `question_history_id`, `answer_type`, `answer`, `submit_time`) VALUES
(1, 1, 1, 'multiple_choice', NULL, '2018-03-16 04:00:10'),
(2, 1, 1, 'multiple_choice', NULL, '2018-03-16 08:38:43'),
(3, 2, 4, 'multiple_choice', NULL, '2018-03-16 08:49:10'),
(1, 2, 4, 'multiple_choice', NULL, '2018-03-16 08:49:25'),
(2, 2, 4, 'multiple_choice', NULL, '2018-03-16 08:49:25'),
(1, 1, 2, 'multiple_choice', NULL, '2018-03-16 08:50:56'),
(1, 1, 3, 'multiple_choice', NULL, '2018-03-16 04:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `class_course_section`
--

DROP TABLE IF EXISTS `class_course_section`;
CREATE TABLE `class_course_section` (
  `class_id` bigint(20) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `offering` varchar(100) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_course_section`
--

INSERT INTO `class_course_section` (`class_id`, `title`, `description`, `offering`, `location`) VALUES
(1, 'CMPSC462', 'Data Structures', 'Fall', 'Olmsted');

-- --------------------------------------------------------

--
-- Table structure for table `owns_question`
--

DROP TABLE IF EXISTS `owns_question`;
CREATE TABLE `owns_question` (
  `teacher_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `owns_question`
--

INSERT INTO `owns_question` (`teacher_id`, `question_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `owns_question_set`
--

DROP TABLE IF EXISTS `owns_question_set`;
CREATE TABLE `owns_question_set` (
  `id` bigint(20) NOT NULL,
  `teacher_id` bigint(20) DEFAULT NULL,
  `question_set_id` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `owns_question_set`
--

INSERT INTO `owns_question_set` (`id`, `teacher_id`, `question_set_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `question_id` bigint(20) NOT NULL,
  `type` varchar(100) DEFAULT 'multiple_choice',
  `description` varchar(300) DEFAULT NULL,
  `potential_answers` blob,
  `correct_answers` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `type`, `description`, `potential_answers`, `correct_answers`) VALUES
(1, 'multiple_choice', 'What does that say?', NULL, NULL),
(2, 'multiple_choice', 'What is a stack?', NULL, NULL),
(3, 'multiple_choice', 'What does optimal mean?', NULL, NULL),
(4, 'multiple_choice', 'What does onPause() do?', NULL, NULL),
(5, 'multiple_choice', 'What is the transitive property?', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_history`
--

DROP TABLE IF EXISTS `question_history`;
CREATE TABLE `question_history` (
  `id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `session_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_history`
--

INSERT INTO `question_history` (`id`, `question_id`, `session_id`) VALUES
(4, 3, 2),
(3, 1, 2),
(2, 4, 1),
(1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question_is_in`
--

DROP TABLE IF EXISTS `question_is_in`;
CREATE TABLE `question_is_in` (
  `question_set_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_is_in`
--

INSERT INTO `question_is_in` (`question_set_id`, `question_id`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `question_session`
--

DROP TABLE IF EXISTS `question_session`;
CREATE TABLE `question_session` (
  `id` bigint(20) NOT NULL,
  `class_id` bigint(20) NOT NULL,
  `question_set_id` bigint(20) NOT NULL,
  `start` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NULL DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_session`
--

INSERT INTO `question_session` (`id`, `class_id`, `question_set_id`, `start`, `end`, `date`) VALUES
(1, 1, 1, '2018-03-16 04:00:00', '2018-03-17 04:00:00', '2018-03-16 04:00:00'),
(2, 1, 2, '2018-03-30 04:00:00', '2018-03-31 04:00:00', '2018-03-30 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `question_set`
--

DROP TABLE IF EXISTS `question_set`;
CREATE TABLE `question_set` (
  `question_set_id` bigint(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_set`
--

INSERT INTO `question_set` (`question_set_id`, `name`) VALUES
(1, '462 Question Set 1'),
(2, '462 Question Set 2');

-- --------------------------------------------------------

--
-- Table structure for table `student_account`
--

DROP TABLE IF EXISTS `student_account`;
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

--
-- Dumping data for table `student_account`
--

INSERT INTO `student_account` (`student_id`, `email`, `first_name`, `last_name`, `is_confirmed`, `salted_password`, `title`, `uniqueID`) VALUES
(1, 'hexi@gmail.com', 'Legendary', 'Hexi', 1, 'HEXISALTEDPASS', 'student', 'HEXIEMAILID'),
(2, 'basketball4lyf@gmail.com', 'Big', 'Chris', 1, 'BIGCHRISPASS', 'baller', 'BIGCHRISEMAILID'),
(3, 'kraken@gmail.com', 'The', 'Kraken', 0, 'KRAKENPASS', NULL, 'KRAKENEMAILID');

-- --------------------------------------------------------

--
-- Table structure for table `student_is_in`
--

DROP TABLE IF EXISTS `student_is_in`;
CREATE TABLE `student_is_in` (
  `student_id` bigint(20) NOT NULL,
  `class_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student_is_in`
--

INSERT INTO `student_is_in` (`student_id`, `class_id`) VALUES
(1, 1),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_account`
--

DROP TABLE IF EXISTS `teacher_account`;
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

--
-- Dumping data for table `teacher_account`
--

INSERT INTO `teacher_account` (`teacher_id`, `email`, `first_name`, `last_name`, `is_confirmed`, `salted_password`, `title`, `uniqueID`) VALUES
(1, 'blum@psu.edu', 'Jeremy', 'Blum', 1, 'JEREMYBLUMSALTEDPASSWORD', NULL, 'JEREMYBLUMEMAILID'),
(2, 'Omar.El.Ariss@tamuc.edu', 'Omar', 'El Ariss', 1, 'OMARSALTEDPASSWORD', 'Professor', 'OMAREMAILID');

-- --------------------------------------------------------

--
-- Table structure for table `teaches`
--

DROP TABLE IF EXISTS `teaches`;
CREATE TABLE `teaches` (
  `class_id` bigint(20) NOT NULL,
  `teacher_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teaches`
--

INSERT INTO `teaches` (`class_id`, `teacher_id`) VALUES
(1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_question`
--
ALTER TABLE `active_question`
  ADD PRIMARY KEY (`question_set_id`,`question_id`);

--
-- Indexes for table `active_question_set`
--
ALTER TABLE `active_question_set`
  ADD PRIMARY KEY (`class_id`,`question_set_id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`student_id`,`sesssion_id`,`question_history_id`);

--
-- Indexes for table `class_course_section`
--
ALTER TABLE `class_course_section`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `owns_question`
--
ALTER TABLE `owns_question`
  ADD PRIMARY KEY (`teacher_id`,`question_id`);

--
-- Indexes for table `owns_question_set`
--
ALTER TABLE `owns_question_set`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_history`
--
ALTER TABLE `question_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_is_in`
--
ALTER TABLE `question_is_in`
  ADD PRIMARY KEY (`question_set_id`,`question_id`);

--
-- Indexes for table `question_session`
--
ALTER TABLE `question_session`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`student_id`,`class_id`);

--
-- Indexes for table `teacher_account`
--
ALTER TABLE `teacher_account`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `teaches`
--
ALTER TABLE `teaches`
  ADD PRIMARY KEY (`class_id`,`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_course_section`
--
ALTER TABLE `class_course_section`
  MODIFY `class_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `owns_question_set`
--
ALTER TABLE `owns_question_set`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `question_history`
--
ALTER TABLE `question_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `question_session`
--
ALTER TABLE `question_session`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `question_set`
--
ALTER TABLE `question_set`
  MODIFY `question_set_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `student_account`
--
ALTER TABLE `student_account`
  MODIFY `student_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `teacher_account`
--
ALTER TABLE `teacher_account`
  MODIFY `teacher_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

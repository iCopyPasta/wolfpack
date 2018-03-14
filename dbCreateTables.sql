SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

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

CREATE TABLE IF NOT EXISTS `answers` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `answer` blob NOT NULL,
  KEY `question_id` (`question_id`),
  KEY `student_id` (`student_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `class_course` (
  `class_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_id` (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `class_section` (
  `section_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_section_number` int(11) DEFAULT NULL,
  `location` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offering` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`section_id`),
  UNIQUE KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `grouped_by` (
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  KEY `question_id` (`question_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `has` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`section_id`,`class_id`),
  KEY `section_id` (`section_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `is_in` (
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`class_id`,`student_id`),
  KEY `student_id` (`student_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `owns` (
  `professor_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  KEY `professor_id` (`professor_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `professor_account` (
  `professor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `professor_school_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `salted_password` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`professor_id`),
  UNIQUE KEY `professor_id` (`professor_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `question` (
  `question_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `asked` tinyint(1) DEFAULT '0',
  `description` varchar(1500) COLLATE utf8_unicode_ci DEFAULT 'None',
  `tags` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `potential_answers` json NOT NULL,
  `correct_answers` json NOT NULL,
  PRIMARY KEY (`question_id`),
  UNIQUE KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=802 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `question_session` (
  `session_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `time_created` datetime DEFAULT NULL,
  `tags` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_account` (
  `student_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `student_school_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `salted_password` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uniqueID` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isConfirmed` tinyint(1) DEFAULT '0',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `student_id` (`student_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `teaches` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `professor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`professor_id`),
  UNIQUE KEY `professor_id` (`professor_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `uses` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  KEY `section_id` (`section_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student_account` (`student_id`),
  ADD CONSTRAINT `answers_ibfk_3` FOREIGN KEY (`session_id`) REFERENCES `question_session` (`session_id`);

ALTER TABLE `grouped_by`
  ADD CONSTRAINT `grouped_by_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
  ADD CONSTRAINT `grouped_by_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `question_session` (`session_id`);

ALTER TABLE `has`
  ADD CONSTRAINT `has_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`),
  ADD CONSTRAINT `has_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class_course` (`class_id`);

ALTER TABLE `is_in`
  ADD CONSTRAINT `is_in_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_account` (`student_id`),
  ADD CONSTRAINT `is_in_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class_course` (`class_id`),
  ADD CONSTRAINT `is_in_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`);

ALTER TABLE `owns`
  ADD CONSTRAINT `owns_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professor_account` (`professor_id`),
  ADD CONSTRAINT `owns_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`);

ALTER TABLE `teaches`
  ADD CONSTRAINT `teaches_ibfk_1` FOREIGN KEY (`professor_id`) REFERENCES `professor_account` (`professor_id`),
  ADD CONSTRAINT `teaches_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`);

ALTER TABLE `uses`
  ADD CONSTRAINT `uses_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`section_id`),
  ADD CONSTRAINT `uses_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `question_session` (`session_id`);
COMMIT;

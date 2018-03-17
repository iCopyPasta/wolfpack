#
# TABLE STRUCTURE FOR: active_question
#

DROP TABLE IF EXISTS active_question;

CREATE TABLE `active_question` (
  `question_set_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  PRIMARY KEY (`question_set_id`,`question_id`),
  KEY `FK_active_question_question_id` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: active_question_set
#

DROP TABLE IF EXISTS active_question_set;

CREATE TABLE `active_question_set` (
  `class_id` bigint(20) NOT NULL,
  `question_set_id` bigint(20) NOT NULL,
  PRIMARY KEY (`class_id`,`question_set_id`),
  KEY `FK_active_question_set_question_set_id` (`question_set_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: answers
#

DROP TABLE IF EXISTS answers;

CREATE TABLE `answers` (
  `student_id` bigint(20) NOT NULL,
  `session_id` bigint(20) NOT NULL,
  `question_history_id` bigint(20) NOT NULL,
  `answer_type` varchar(200) DEFAULT NULL,
  `answer` blob,
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`,`session_id`,`question_history_id`),
  KEY `FK_answers_session_id` (`session_id`),
  KEY `FK_answers_question_history_id` (`question_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: class_course_section
#

DROP TABLE IF EXISTS class_course_section;

CREATE TABLE `class_course_section` (
  `class_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `offering` varchar(100) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=276 DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: owns_question
#

DROP TABLE IF EXISTS owns_question;

CREATE TABLE `owns_question` (
  `teacher_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  PRIMARY KEY (`question_id`),
  UNIQUE KEY `teacher_id` (`teacher_id`),
  KEY `FK_owns_question_question_id` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


#
# TABLE STRUCTURE FOR: owns_question_set
#

DROP TABLE IF EXISTS owns_question_set;

CREATE TABLE `owns_question_set` (
  `teacher_id` bigint(20) DEFAULT NULL,
  `question_set_id` bigint(20) NOT NULL,
  PRIMARY KEY (`question_set_id`),
  KEY `FK_owns_question_set_teacher_id` (`teacher_id`),
  KEY `FK_owns_question_set_question_set_id` (`question_set_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# TABLE STRUCTURE FOR: question
#

DROP TABLE IF EXISTS question;

CREATE TABLE `question` (
  `question_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question_type` varchar(100) DEFAULT 'multiple_choice',
  `description` varchar(300) DEFAULT NULL,
  `potential_answers` blob,
  `correct_answers` blob,
  PRIMARY KEY (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;



#
# TABLE STRUCTURE FOR: question_history
#

DROP TABLE IF EXISTS question_history;

CREATE TABLE `question_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) NOT NULL,
  `session_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_question_history_question_id` (`question_id`),
  KEY `FK_question_history_session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: question_is_in
#

DROP TABLE IF EXISTS question_is_in;

CREATE TABLE `question_is_in` (
  `question_set_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  PRIMARY KEY (`question_set_id`,`question_id`),
  KEY `FK_question_is_in_question_id` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# TABLE STRUCTURE FOR: question_session
#

DROP TABLE IF EXISTS question_session;

CREATE TABLE `question_session` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) NOT NULL,
  `question_set_id` bigint(20) NOT NULL,
  `start` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NULL DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_question_session_class_id` (`class_id`),
  KEY `FK_question_session_question_set_id` (`question_set_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: question_set
#

DROP TABLE IF EXISTS question_set;

CREATE TABLE `question_set` (
  `question_set_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`question_set_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1001 DEFAULT CHARSET=latin1;



#
# TABLE STRUCTURE FOR: student_account
#

DROP TABLE IF EXISTS student_account;

CREATE TABLE `student_account` (
  `student_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(200) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `is_confirmed` tinyint(1) DEFAULT '0',
  `salted_password` varchar(200) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `uniqueID` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7501 DEFAULT CHARSET=latin1;



#
# TABLE STRUCTURE FOR: student_is_in
#

DROP TABLE IF EXISTS student_is_in;

CREATE TABLE `student_is_in` (
  `student_id` bigint(20) NOT NULL,
  `class_id` bigint(20) NOT NULL,
  PRIMARY KEY (`student_id`,`class_id`),
  KEY `FK_student_is_in_class_id` (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



#
# TABLE STRUCTURE FOR: teacher_account
#

DROP TABLE IF EXISTS teacher_account;

CREATE TABLE `teacher_account` (
  `teacher_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `salted_password` varchar(200) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `uniqueID` varchar(128) NOT NULL,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=latin1;



#
# TABLE STRUCTURE FOR: teaches
#

DROP TABLE IF EXISTS teaches;

CREATE TABLE `teaches` (
  `class_id` bigint(20) NOT NULL,
  `teacher_id` bigint(20) NOT NULL,
  PRIMARY KEY (`class_id`,`teacher_id`),
  KEY `FK_teacher_teacher_id` (`teacher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




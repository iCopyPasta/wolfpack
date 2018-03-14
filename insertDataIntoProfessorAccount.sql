DELETE FROM student_account;
ALTER TABLE student_account AUTO_INCREMENT = 1;
CREATE TABLE IF NOT EXISTS `professor_account` (
  `professor_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `professor_school_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `salted_password` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uniqueID` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isConfirmed` tinyint(1) DEFAULT '0',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`professor_id`),
  UNIQUE KEY `professor_id` (`professor_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

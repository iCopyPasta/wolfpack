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



CREATE TABLE professor_account(
professor_id        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE, 
first_name          VARCHAR(100) NOT NULL,
last_name           VARCHAR(100) NOT NULL,
professor_school_id VARCHAR(150), 
email               VARCHAR(200) UNIQUE NOT NULL,
salted_password     VARCHAR(200),
PRIMARY KEY (professor_id)
);

CREATE TABLE student_account(
student_id          SERIAL,
first_name          VARCHAR(100) NOT NULL,
last_name           VARCHAR(100) NOT NULL,
student_school_id   VARCHAR(150),
email               VARCHAR(200) UNIQUE NOT NULL,
salted_password     VARCHAR(200),
PRIMARY KEY(student_id)
);

CREATE TABLE class_section(
section_id		        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
class_section_number    INT,
location	            VARCHAR(250),
offering		        VARCHAR(50),
PRIMARY KEY (section_id)	
);

CREATE TABLE class_course(
class_id BIGINT     UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
class_course_number INT,
location            VARCHAR(250),
offering            VARCHAR(50),
PRIMARY KEY(class_id)
);

CREATE TABLE question_session(
session_id      BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
time_created    DATETIME,
tags		    VARCHAR(3000),
PRIMARY KEY (session_id)
);

CREATE TABLE question(
question_id     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
professor_asked VARCHAR(100),
description     VARCHAR(1500),
tags            VARCHAR(3000),
PRIMARY KEY(question_id)
);

CREATE TABLE teaches(
section_id   BIGINT UNSIGNED NOT NULL,
professor_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
PRIMARY KEY (professor_id),
FOREIGN KEY (professor_id) REFERENCES professor_account(professor_id),
FOREIGN KEY (section_id)   REFERENCES class_section(section_id)
);



CREATE TABLE has(
section_id  BIGINT UNSIGNED NOT NULL,
class_id    BIGINT UNSIGNED NOT NULL,
FOREIGN KEY(section_id) REFERENCES class_section(section_id),
FOREIGN KEY (class_id)  REFERENCES class_course(class_id)
);



CREATE TABLE uses(
section_id      BIGINT UNSIGNED NOT NULL,
session_id      BIGINT UNSIGNED NOT NULL,
FOREIGN KEY (section_id) REFERENCES class_section(section_id),
FOREIGN KEY (session_id) REFERENCES question_session(session_id)
); 


CREATE TABLE grouped_by(
question_id     BIGINT UNSIGNED NOT NULL,
session_id      BIGINT UNSIGNED NOT NULL,
FOREIGN KEY(question_id) REFERENCES question(question_id),
FOREIGN KEY(session_id)  REFERENCES question_session(session_id)
);


CREATE TABLE owns(
professor_id    BIGINT UNSIGNED NOT NULL,
question_id		BIGINT UNSIGNED NOT NULL,
FOREIGN KEY (professor_id) REFERENCES professor_account(professor_id),
FOREIGN KEY (question_id)  REFERENCES question(question_id)
);

CREATE TABLE is_in(
student_id  BIGINT UNSIGNED NOT NULL,
class_id    BIGINT UNSIGNED NOT NULL,
section_id  BIGINT UNSIGNED NOT NULL,
PRIMARY KEY(class_id, student_id), 
FOREIGN KEY(student_id) REFERENCES student_account(student_id),
FOREIGN KEY(class_id)   REFERENCES class_course(class_id),
FOREIGN KEY(section_id) REFERENCES class_section(section_id)
);

CREATE TABLE answers(
question_id BIGINT UNSIGNED NOT NULL,
student_id  BIGINT UNSIGNED NOT NULL,
session_id  BIGINT UNSIGNED NOT NULL,
submit_time TIMESTAMP NOT NULL,
answer      BLOB NOT NULL,
FOREIGN KEY(question_id) REFERENCES question(question_id),
FOREIGN KEY(student_id)  REFERENCES student_account(student_id),
FOREIGN KEY(session_id)  REFERENCES question_session(session_id)
);

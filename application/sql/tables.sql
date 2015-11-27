CREATE TABLE `users` (
  `user_id` int NOT NULL UNIQUE AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,	
  `password` char(192) NOT NULL,
  `registration_date` DATETIME NOT NULL,
  `image_path` varchar(255) DEFAULT "https://w1416464.users.ecs.westminster.ac.uk/CI_1/application/images/default.jpg" NOT NULL,
  `user_type` ENUM('N','M') DEFAULT "N" NOT NULL,
  `number_of_questions` int NOT NULL DEFAULT 0,
  `number_of_answers` int NOT NULL DEFAULT 0,
  `rating` int NOT NULL DEFAULT 0,
  Primary Key (user_id)
);

CREATE TABLE `questions` (
  `question_id` int NOT NULL UNIQUE AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` Varchar(255) NOT NULL,	
  `content` Varchar(4096) NOT NULL,
  `category` Varchar(255) NOT NULL,
  `date_posted` DATETIME NOT NULL,
  Primary Key (question_id),
  Foreign Key (user_id) REFERENCES users(users_id)
);

CREATE TABLE `tags` (
  `tag_id` int NOT NULL UNIQUE AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `tag` Varchar(255) NOT NULL,
  Primary Key (tag_id),
  Foreign Key (question_id) REFERENCES questions(question_id)
);

CREATE TABLE `answers` (
  `answer_id` int NOT NULL UNIQUE AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `user_id` int NOT NULL,	
  `content` Varchar(4096) NOT NULL,
  `rating` SmallInt NOT NULL,
  `date_posted` DATETIME NOT NULL,
  Primary Key (answer_id),
  Foreign Key (question_id) REFERENCES questions(question_id),
  Foreign Key (user_id) REFERENCES users(users_id)
);

CREATE TABLE `ci_sessions` (
	`session_id` Varchar(40) DEFAULT "0" NOT NULL,
	`ip_address` Varchar(45) DEFAULT "0" NOT NULL,
	`user_agent` Varchar(120) NOT NULL,
	`last_activity` int(10) unsigned DEFAULT "0" NOT NULL,
	`user_data` Text NOT NULL,
	Primary Key (session_id)
);
CREATE TABLE `voted` (
  `vote_id` int NOT NULL UNIQUE AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `user_id` int NOT NULL,	
  `answer_id` Varchar(4096) NOT NULL,
  `vote` ENUM('Up','Down') NOT NULL,
  Primary Key (vote_id),
  Foreign Key (question_id) REFERENCES questions(question_id),
  Foreign Key (user_id) REFERENCES users(users_id),
  Foreign Key (answer_id) REFERENCES answers(answer_id)
);

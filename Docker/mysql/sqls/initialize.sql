CREATE DATABASE poke_note;
use poke_note;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nickname` varchar(32),
  `email` varchar(255) NOT NULL,
  `image` varchar(255),
  `description` varchar(255),
  `link` varchar(255),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `article_count` int NOT NULL DEFAULT 0, 
  `follow_count` int NOT NULL DEFAULT 0,
  `follower_count` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `articles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255),
  `body` TEXT,
  `published` BOOLEAN DEFAULT FALSE,
  `created` DATETIME,
  `modified` DATETIME,
  `comment_count` int NOT NULL DEFAULT 0,
  `favorite_count` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY user_key (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tags` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255),
  `created` DATETIME,
  `modified` DATETIME,
  `description` varchar(255),
  `article_count` int NOT NULL DEFAULT 0, 
  PRIMARY KEY (`id`),
  UNIQUE KEY (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `articles_tags` (
  `article_id` INT NOT NULL,
  `tag_id` INT NOT NULL,
  PRIMARY KEY (article_id, tag_id),
  FOREIGN KEY tag_key(tag_id) REFERENCES tags(id),
  FOREIGN KEY article_key(article_id) REFERENCES articles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `body` TEXT,
  `article_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `created` DATETIME,
  `modified` DATETIME,
  PRIMARY KEY (`id`),
  FOREIGN KEY user_key(user_id) REFERENCES users(id),
  FOREIGN KEY article_key(article_id) REFERENCES articles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `favorites` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `article_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `created` DATETIME,
  `modified` DATETIME,
  PRIMARY KEY (`id`),
  FOREIGN KEY user_key(user_id) REFERENCES users(id),
  FOREIGN KEY article_key(article_id) REFERENCES articles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `follows` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `follow_user_id` INT NOT NULL,
  `created` DATETIME,
  `modified` DATETIME,
  PRIMARY KEY (`id`),
  FOREIGN KEY user_key(user_id) REFERENCES users(id),
  FOREIGN KEY follow_user_key(follow_user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO users(username, password, nickname, email, created, modified)
VALUES
('user1', 'password', 'ユーザー1', 'test1@example.com ', now(), now()),
('user2', 'password', 'ユーザー2', 'test2@example.com ', now(), now()),
('user3', 'password', 'ユーザー3', 'test3@example.com ', now(), now())
;
INSERT INTO articles(user_id, title, body, published, created, modified)
VALUES (
  1, 'テスト投稿1', 'これはテストです。2', true, now(), now()
),
 (
  2, 'テスト投稿2', 'これはテストです。2', false, now(), now()
),
(
  3, 'テスト投稿3', 'これはテストです。3', true, now(), now()
)
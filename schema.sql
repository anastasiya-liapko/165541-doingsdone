CREATE DATABASE `doingsdone-165541`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

    USE `doingsdone-165541`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `registration_date` DATE,
    `email` CHAR(128),
    `name` CHAR(64),
    `password` CHAR(64),
    `contact` CHAR(128)
);

CREATE UNIQUE INDEX email ON `users`(`email`);

CREATE TABLE `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` CHAR(64),
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `creation_date` DATE,
    `completion_date` DATE,
    `name` CHAR(64),
    `file` CHAR(128),
    `term_date` DATE DEFAULT NULL,
    `project_id` INT DEFAULT NULL,
    `user_id` INT NOT NULL,
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

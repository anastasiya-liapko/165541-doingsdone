CREATE DATABASE `doingsdone-165541`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

    USE `doingsdone-165541`;

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `registration_date` DATE,
    `email` CHAR(128),
    `user_name` CHAR(64),
    `user_password` CHAR(64),
    `contact` CHAR(128)
);

CREATE UNIQUE INDEX email ON `users`(`email`);

CREATE TABLE `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `project_name` CHAR(64),
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `creation_date` DATE,
    `completion_date` DATE,
    `task_name` CHAR(64),
    `task_file` CHAR(128),
    `term_date` DATE,
    `project_id` INT,
    `user_id` INT,
    FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

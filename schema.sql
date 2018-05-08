CREATE DATABASE doingsdone
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

    USE doingsdone;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_name CHAR(64)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    creation_date DATE,
    completion_date DATE,
    task_name CHAR(64),
    task_file CHAR(128),
    term DATETIME
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_date DATE,
    email CHAR(128),
    user_name CHAR(64),
    user_password CHAR(64),
    contact CHAR(128)
);

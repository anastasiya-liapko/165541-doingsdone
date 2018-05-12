INSERT INTO `users`(`registration_date`, `email`, `user_name`, `user_password`, `contact`)
    VALUES(NULL, 'ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', NULL);
INSERT INTO `users`(`registration_date`, `email`, `user_name`, `user_password`, `contact`)
    VALUES(NULL, 'kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', NULL);
INSERT INTO `users`(`registration_date`, `email`, `user_name`, `user_password`, `contact`)
    VALUES(NULL, 'warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', NULL);
-- SELECT * FROM `users`;

INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Все', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Входящие', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Учеба', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Работа', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Домашние дела', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Авто', 1);
-- SELECT * FROM `projects`;

INSERT INTO `tasks`(`creation_date`, `done`, `task_name`, `task_file`, `term`, `project_id`, `user_id`)
    VALUES(NULL, 'Нет', 'Собеседование в IT компании', NULL, '2018-06-01', 4, 1);
INSERT INTO `tasks`(`creation_date`, `done`, `task_name`, `task_file`, `term`, `project_id`, `user_id`)
    VALUES(NULL, 'Нет', 'Выполнить тестовое задание', NULL, '2018-05-25', 4, 1);
INSERT INTO `tasks`(`creation_date`, `done`, `task_name`, `task_file`, `term`, `project_id`, `user_id`)
    VALUES(NULL, 'Да', 'Сделать задание первого раздела', NULL, '2018-04-21', 3, 1);
INSERT INTO `tasks`(`creation_date`, `done`, `task_name`, `task_file`, `term`, `project_id`, `user_id`)
    VALUES(NULL, 'Нет', 'Встреча с другом', NULL, '2018-04-22', 2, 1);
INSERT INTO `tasks`(`creation_date`, `done`, `task_name`, `task_file`, `term`, `project_id`, `user_id`)
    VALUES(NULL, 'Нет', 'Купить корм для кота', NULL, NULL, 5, 1);
INSERT INTO `tasks`(`creation_date`, `done`, `task_name`, `task_file`, `term`, `project_id`, `user_id`)
    VALUES(NULL, 'Нет', 'Заказать пиццу', NULL, NULL, 5, 1);
-- SELECT * FROM `tasks`;


-- получить список из всех проектов для одного пользователя
SELECT * FROM `projects` WHERE `user_id` = 1;

-- получить список из всех задач для одного проекта
SELECT * FROM `tasks` WHERE `project_id` = 4;

-- пометить задачу как выполненную
UPDATE `tasks` SET `done` = 'Да' WHERE `id` = 5;

-- получить все задачи для завтрашнего дня
SELECT * FROM `tasks` WHERE TO_DAYS(`term`) - TO_DAYS(NOW()) BETWEEN 0 AND 1;

-- обновить название задачи по её идентификатору
UPDATE `tasks` SET `task_name` = 'Купить корм для кота Whiskas' WHERE `id` = 5;

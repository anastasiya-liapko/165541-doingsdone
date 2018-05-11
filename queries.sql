INSERT INTO `users`(`registration_date`, `email`, `user_name`, `user_password`, `contact`)
    VALUES('2018.04.16', 'konstantin9678@mail.ru', 'Константин', '12345678', 'г. Москва');
INSERT INTO `users`(`registration_date`, `email`, `user_name`, `user_password`, `contact`)
    VALUES('2018.01.23', 'alisa@mail.ru', 'Алиса', '87654321', 'г. Санкт-Петербург');
-- SELECT * FROM `users`;

INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Все', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Входящие', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Учеба', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Работа', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Домашние дела', 1);
INSERT INTO `projects`(`project_name`, `user_id`) VALUES('Авто', 1);
-- SELECT * FROM `projects`;

INSERT INTO `tasks`(`creation_date`, `completion_date`, `task_name`, `task_file`, `term`, `done`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Собеседование в IT компании', NULL, '2018.06.01', 'Нет', 4, 1);
INSERT INTO `tasks`(`creation_date`, `completion_date`, `task_name`, `task_file`, `term`, `done`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Выполнить тестовое задание', NULL, '2018.05.25', 'Нет', 4, 1);
INSERT INTO `tasks`(`creation_date`, `completion_date`, `task_name`, `task_file`, `term`, `done`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Сделать задание первого раздела', NULL, '2018.04.21', 'Да', 3, 1);
INSERT INTO `tasks`(`creation_date`, `completion_date`, `task_name`, `task_file`, `term`, `done`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Встреча с другом', NULL, '2018.04.22', 'Нет', 2, 1);
INSERT INTO `tasks`(`creation_date`, `completion_date`, `task_name`, `task_file`, `term`, `done`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Купить корм для кота', NULL, NULL, 'Нет', 5, 1);
INSERT INTO `tasks`(`creation_date`, `completion_date`, `task_name`, `task_file`, `term`, `done`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Заказать пиццу', NULL, NULL, 'Нет', 5, 1);
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


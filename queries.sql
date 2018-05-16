INSERT INTO `users`(`registration_date`, `email`, `name`, `password`, `contact`)
    VALUES(NULL, 'ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', NULL),
    (NULL, 'kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', NULL),
    (NULL, 'warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', NULL);
-- SELECT * FROM `users`;

INSERT INTO `projects`(`name`, `user_id`)
  VALUES('Учеба', 1),
  ('Работа', 1),
  ('Домашние дела', 1),
  ('Авто', 1),
  ('Учеба', 2),
  ('Работа', 2),
  ('Домашние дела', 2);
-- SELECT * FROM `projects`;

INSERT INTO `tasks`(`creation_date`, `completion_date`, `name`, `file`, `term_date`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Собеседование в IT компании', NULL, '2018-06-01', 2, 1),
    (NULL, NULL, 'Выполнить тестовое задание', NULL, '2018-05-25', 2, 1),
    (NULL, '2018-04-21', 'Сделать задание первого раздела', NULL, '2018-04-21', 1, 1),
    (NULL, NULL, 'Встреча с другом', NULL, '2018-04-22', NULL, 1),
    (NULL, NULL, 'Купить корм для кота', NULL, NULL, 3, 1),
    (NULL, NULL, 'Заказать пиццу', NULL, NULL, 3, 1),
    (NULL, NULL, 'Собеседование в IT компании', NULL, '2018-06-01', 2, 2),
    (NULL, NULL, 'Встреча с другом', NULL, '2018-04-22', NULL, 2);
-- SELECT * FROM `tasks`;


-- получить список из всех проектов для одного пользователя
-- SELECT * FROM `projects` WHERE `user_id` = 1;

-- получить список из всех задач для одного проекта
-- SELECT * FROM `tasks` WHERE `project_id` = 4;

-- пометить задачу как выполненную
-- UPDATE `tasks` SET `completion_date` = '2018-05-02' WHERE `id` = 5;

-- получить все задачи для завтрашнего дня
-- SELECT * FROM `tasks` WHERE TO_DAYS(`term_date`) - TO_DAYS(NOW()) BETWEEN 0 AND 1;

-- обновить название задачи по её идентификатору
-- UPDATE `tasks` SET `task_name` = 'Купить корм для кота Whiskas' WHERE `id` = 5;

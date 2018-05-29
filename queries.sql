INSERT INTO `users`(`registration_date`, `email`, `name`, `password`, `contact`)
    VALUES(NULL, 'ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', NULL),
    (NULL, 'kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', NULL),
    (NULL, 'warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', NULL);

INSERT INTO `projects`(`name`, `user_id`)
  VALUES('Учеба', 1),
  ('Работа', 1),
  ('Домашние дела', 1),
  ('Авто', 1),
  ('Учеба', 2),
  ('Работа', 2),
  ('Домашние дела', 2);

INSERT INTO `tasks`(`creation_date`, `completion_date`, `name`, `file`, `term_date`, `project_id`, `user_id`)
    VALUES(NULL, NULL, 'Собеседование в IT компании', NULL, '2018-06-01', 2, 1),
    (NULL, NULL, 'Выполнить тестовое задание', NULL, '2018-05-25', 2, 1),
    (NULL, '2018-04-21', 'Сделать задание первого раздела', NULL, '2018-04-21', 1, 1),
    (NULL, NULL, 'Встреча с другом', NULL, '2018-04-22', NULL, 1),
    (NULL, NULL, 'Купить корм для кота', NULL, NULL, 3, 1),
    (NULL, NULL, 'Заказать пиццу', NULL, NULL, 3, 1),
    (NULL, NULL, 'Собеседование в IT компании', NULL, '2018-06-01', 6, 2),
    (NULL, NULL, 'Встреча с другом', NULL, '2018-04-22', NULL, 2);

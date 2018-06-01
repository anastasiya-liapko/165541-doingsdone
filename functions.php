<?php
define("DEFAULT_PROJECT", "Входящие");
define("SECS_IN_HOUR", 3600);

/**
 * Добавляет новый проект
 *
 * @param $databaseLink Ссылка на базу данных
 * @param array $formsData данные из формы
 * @param int $userId Id пользователя
 * @return boolean
 */
function addNewProject($formsData, int $userId, $databaseLink)
{
    $sql = "
        INSERT INTO
            `projects` (`name`, `user_id`)
        VALUES
            (?, ?)
    ";

    $stmt = mysqli_prepare($databaseLink, $sql);
    mysqli_stmt_bind_param($stmt, 'sd', $formsData["name"], $userId);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

;

/**
 * Добавляет новую задачу
 *
 * @param $databaseLink Ссылка на базу данных
 * @param array $formsData данные из формы
 * @param int $userId Id пользователя
 * @return boolean
 */
function addNewTask($databaseLink, $formsData, int $userId)
{
    $sql = "
        INSERT INTO
            `tasks` (`creation_date`, `completion_date`, `name`, `file`, `term_date`, `project_id`, `user_id`)
        VALUES
            (NOW(), NULL, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($databaseLink, $sql);
    mysqli_stmt_bind_param($stmt, 'sssdd', $formsData["name"], $formsData["file"], $formsData["date"],
        $formsData["project"], $userId);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

;

/**
 * Добавляет нового пользователя
 *
 * @param $databaseLink Ссылка на базу данных
 * @param array $formsData данные из формы
 * @return boolean
 */
function addNewUser($databaseLink, $formsData)
{
    $sql = "
        INSERT INTO
            `users` (`registration_date`, `email`, `name`, `password`, `contact`)
        VALUES
            (NOW(), ?, ?, ?, NULL)
    ";

    $password = password_hash($formsData["password"], PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($databaseLink, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $formsData["email"], $formsData["name"], $password);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

;

/**
 * Изменяет статус задачи на выполненный
 *
 * @param $databaseLink Ссылка на базу данных
 * @param int $taskId Id задачи
 * @return boolean
 */
function changeTaskStatus($databaseLink, int $taskId)
{
    $today = date("d.m.Y");
    $sql = "
        SELECT
            `completion_date`
        FROM
            `tasks`
        WHERE
            `tasks`.`id` = '$taskId'
    ";
    if ($res = mysqli_query($databaseLink, $sql)) {
        $array = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    $completionDate = $array[0]["completion_date"];
    if ($completionDate === null) {
        $sqlUpdate = "
            UPDATE
                `tasks`
            SET
                `completion_date` = '$today'
            WHERE
                `tasks`.`id` = '$taskId'
        ";
    }
    if ($completionDate !== null) {
        $sqlUpdate = "
            UPDATE
                `tasks`
            SET
                `completion_date` = NULL
            WHERE
                `tasks`.`id` = '$taskId'
        ";
    }

    $result = mysqli_query($databaseLink, $sqlUpdate);

    return $result;
}

;

/**
 * Производит валидацию формы авторизации
 *
 * @param array $formsData данные из формы
 * @param array $userData Данные пользователя
 * @return array массив с ошибками
 */
function checkAutoFormOnErrors(array $formsData, array $userData): array
{
    $errors = [];

    $required = ["email", "password"];
    foreach ($required as $key) {
        if (empty($formsData[$key])) {
            $errors[$key] = "Заполните это поле";
        }
    }

    if (!count($errors) && $userData) {
        foreach ($userData as $key) {
            if (password_verify($formsData["password"], $key["password"])) {
                $_SESSION["user"] = $key;

            } else {
                $errors["password"] = "Неверный пароль";
            }
        }
    } else {
        $errors["email"] = "Такой пользователь не найден";
    }

    return $errors;
}

;

/**
 * Производит валидацию формы добавления проекта
 *
 * @param array $formsData данные из формы
 * @param int $userId Id пользователя
 * @param $databaseLink Ссылка на базу данных
 * @return array массив с ошибками
 */
function checkProjectFormOnErrors(array $formsData, int $userId, $databaseLink): array
{
    $errors = [];

    $required = ["name"];
    foreach ($required as $key) {
        if (empty($formsData[$key])) {
            $errors[$key] = "Заполните это поле";
        }
    }

    $sql = "
        SELECT
            `projects`.`name`
        FROM
            `projects`
        WHERE
            `projects`.`user_id` = '$userId'
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $userProjects = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    foreach ($userProjects as $item) {
        if ($formsData["name"] === $item["name"]) {
            $errors["name"] = "Такой проект уже существует";
        }
    }

    return $errors;
}

;

/**
 * Производит валидацию формы регистрации
 *
 * @param array $formsData данные из формы
 * @param $databaseLink Ссылка на базу данных
 * @return array массив с ошибками
 */
function checkRegFormOnErrors(array $formsData, $databaseLink): array
{
    $sql = "
        SELECT
            `users`.`email`
        FROM
            `users`
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $emails = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    $errors = [];
    $required = ["name", "email", "password"];

    foreach ($required as $key) {
        if (empty($formsData[$key])) {
            $errors[$key] = "Заполните это поле";
        }
    }

    foreach ($formsData as $key => $value) {
        foreach ($emails as $item) {
            if ($key === "email" && $value === $item["email"]) {
                $errors[$key] = "Данный email уже зарегистрирован";
            }
        }
    }

    foreach ($formsData as $key => $value) {
        if ($key === "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$key] = "E-mail введён некорректно";
        }
    }

    return $errors;
}

;

/**
 * Производит валидацию формы задач
 *
 * @param array $formsData данные из формы
 * @return array массив с ошибками
 */
function checkTasksFormOnErrors(array $formsData): array
{
    $errors = [];
    $required = ["name"];

    foreach ($required as $key) {
        if (empty($formsData[$key])) {
            $errors[$key] = "Заполните это поле";
        }
    }

    foreach ($formsData as $key => $value) {
        if ($key === "date" && !empty($value) && !validateDate($value)) {
            $errors[$key] = "Дата должна быть корректной";
        }
    }

    return $errors;
}

;

/**
 * Возвращает путь к файлу
 *
 * @return путь к файлу
 */
function getFile()
{
    if (isset($_FILES["preview"]["name"])) {
        $fileName = $_FILES["preview"]["name"];
        $tmpName = $_FILES["preview"]["tmp_name"];
        $filePath = __DIR__ . "/";
        $fileUrl = $filePath . $fileName;
        move_uploaded_file($tmpName, $fileUrl);

        return $fileName;
    }
}

;

/**
 * Возвращает список отфильтрованных задач
 *
 * @param array $userTasks массив всех задач пользователя
 * @param int $userId Id пользователя
 * @param int $selectedProjectId Id выбранного проекта
 * @return array Массив задач
 */
function getFilteredTasks(array $userTasks, int $userId, int $selectedProjectId): array
{
    $filteredTasks = [];

    if (isset($_GET["all_tasks"])) {
        $filteredTasks = getTasksListByProjectId($userTasks, $userId, $selectedProjectId);
    } else {
        if (isset($_GET["today_tasks"])) {
            $filteredTasks = getTodayTasks($userTasks);
        } else {
            if (isset($_GET["tomorrow_tasks"])) {
                $filteredTasks = getTomorrowTasks($userTasks);
            } else {
                if (isset($_GET["overdue_tasks"])) {
                    $filteredTasks = getOverdueTasks($userTasks);
                }
            }
        }
    }

    return $filteredTasks;
}

;

/**
 * Возвращает количество часов, оставшееся до каждой из дат
 *
 * @param $date дата завершения задачи
 * @return количество часов
 */
function getHoursCountTillTheDate($date)
{
    date_default_timezone_set('Europe/Moscow');
    if ($date !== null) {
        $ts = time();
        $endTs = strtotime($date);
        $tsDiff = $endTs - $ts;
        $hoursUntilEnd = floor($tsDiff / SECS_IN_HOUR);
        return $hoursUntilEnd;
    }
}

;

/**
 * Возвращает задачи, которые не были выполнены и у которых истек срок
 *
 * @param array $userTasks Массив задач пользователя
 * @return array Массив задач
 */
function getOverdueTasks(array $userTasks): array
{
    $overdueTasks = [];

    foreach ($userTasks as $i => $task) {
        if (getHoursCountTillTheDate($task["term_date"]) < 0 && $task["completion_date"] === null) {
            $overdueTasks[$i] = $task;
        }
    }

    return $overdueTasks;
}

;

/**
 * Возвращает список проектов для пользователя
 *
 * @param $databaseLink Ссылка на базу данных
 * @param int $taskId Id задачи
 * @return int Id проекта
 */
function getProjectIdByTaskId($databaseLink, int $taskId, int $userId): int
{
    $sql = "
        SELECT
            `project_id`
        FROM
            `tasks`
        WHERE
            `tasks`.`id` = '$taskId'
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $array = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $projectId = $array[0]["project_id"];
    }

    if ($projectId === null) {
        $projectId = $userId;
    }

    return $projectId;
}

;

/**
 * Возвращает список проектов для пользователя
 *
 * @param $databaseLink Ссылка на базу данных
 * @param int $userId Id пользователя
 * @return Массив проектов (строк)
 */
function getProjectsListForUser($databaseLink, int $userId)
{
    $projectsList = [];

    $sql = "
        SELECT
            `name`,
            `id`
        FROM
            `projects`
        WHERE
            `user_id` = $userId
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $projectsList = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $projectsList;
}

;

/**
 * Возвращает список искомых задач
 *
 * @param $databaseLink Ссылка на базу данных
 * @param int $userId Id пользователя
 * @return array список задач
 */
function getSearchTasks($databaseLink, int $userId): array
{
    $search = $_GET["search"] ?? "";
    $searchTasks = [];

    if ($search) {
        $sql = "
            SELECT
                *
            FROM
                `tasks`
            WHERE
                `user_id` = $userId
            AND
            MATCH(`name`) AGAINST(?)
        ";

        $stmt = db_get_prepare_stmt($databaseLink, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $searchTasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $searchTasks;
}

;

/**
 * Возвращает количество задач по имени проекта
 *
 * @param array $tasks Массив задач (строк)
 * @param string $projectName Название проекта, по-умолчанию - "Все"
 * @return int Количество задач
 */
function getTasksCountByProjectName(string $projectName = DEFAULT_PROJECT, array $tasks = []): int
{
    $result = 0;

    foreach ($tasks as $task) {
        if ($projectName === $task["project_name"] || ($projectName === DEFAULT_PROJECT && empty($task["project_name"]))) {
            $result++;
        }
    }

    return $result;
}

;

/**
 * Возвращает список задач для пользователя
 *
 * @param array $userTasks Задачи пользователя
 * @param int $userId Id пользователя
 * @param int $projectId Id выбранного проекта
 * @return Массив задач (строк)
 */
function getTasksListByProjectId(array $userTasks, int $userId, int $projectId): array
{
    $tasksByProject = [];

    if ($projectId === 0) {
        $tasksByProject = array_filter(
            $userTasks,
            function ($task) use ($projectId) {
                return $task["project_id"] === null;
            }
        );
    } else {
        $tasksByProject = array_filter(
            $userTasks,
            function ($task) use ($projectId) {
                return (int)$task["project_id"] === $projectId;
            }
        );
    }

    return $tasksByProject;
}

;

/**
 * Возвращает список задач для пользователя
 *
 * @param $databaseLink Ссылка на базу данных
 * @param int $userId Id пользователя
 * @return Массив задач (строк)
 */
function getTasksListForUser($databaseLink, $userId)
{
    $sql = "
        SELECT
            `tasks`.*,
            `projects`.`name` `project_name`
        FROM
            `tasks`
        LEFT  JOIN
            `projects` ON `tasks`.`project_id` = `projects`.`id`
        WHERE
            `tasks`.`user_id` = '$userId'
        ORDER BY
            `creation_date` DESC
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    foreach ($tasks as $i => $task) {
        if ($task["term_date"] !== null) {
            $tasks[$i]["term_date"] = date("H:i d.m.Y", strtotime($task["term_date"]));
        }
    }

    return $tasks;
}

;

/**
 * Возвращает задачи на сегодня
 *
 * @param array $userTasks Массив задач пользователя
 * @return array Массив задач
 */
function getTodayTasks(array $userTasks): array
{
    $today = date("d.m.Y");
    $tasks = $userTasks;
    $todayTasks = [];

    foreach ($userTasks as $i => $task) {
        if ($task["term_date"] !== null) {
            $tasks[$i]["term_date"] = date("d.m.Y", strtotime($task["term_date"]));
        }
    }

    $todayTasks = array_filter(
        $tasks,
        function ($task) use ($today) {
            return $task["term_date"] === $today;
        }
    );

    foreach ($todayTasks as $i => $task) {
        foreach ($userTasks as $key => $value) {
            if ($task["id"] === $value["id"]) {
                $todayTasks[$i]["term_date"] = $value["term_date"];
            }
        }
    }

    return $todayTasks;
}

;

/**
 * Возвращает задачи на завтра
 *
 * @param array $userTasks Массив задач пользователя
 * @return array Массив задач
 */
function getTomorrowTasks(array $userTasks): array
{
    $tomorrow = date("d.m.Y", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
    $tasks = $userTasks;
    $tomorrowTasks = [];

    foreach ($userTasks as $i => $task) {
        if ($task["term_date"] !== null) {
            $tasks[$i]["term_date"] = date("d.m.Y", strtotime($task["term_date"]));
        }
    }

    $tomorrowTasks = array_filter(
        $tasks,
        function ($task) use ($tomorrow) {
            return $task["term_date"] === $tomorrow;
        }
    );

    foreach ($tomorrowTasks as $i => $task) {
        foreach ($userTasks as $key => $value) {
            if ($task["id"] === $value["id"]) {
                $tomorrowTasks[$i]["term_date"] = $value["term_date"];
            }
        }
    }

    return $tomorrowTasks;
}

;

/**
 * Возвращает список предстоящих задач
 *
 * @param $databaseLink Ссылка на базу данных
 * @return array Массив задач
 */
function getUpcomingTasks($databaseLink)
{
    mysqli_query($databaseLink, "SET time_zone = 'Europe/Moscow'");
    $upcomingTasks = [];

    $sql = "
        SELECT
            `tasks`.`name` `task_name`,
            `tasks`.`term_date`,
            `users`.`name` `user_name`,
            `users`.`email` `user_email`
        FROM
            `tasks`
        JOIN
            `users` ON `tasks`.`user_id` = `users`.`id`
        WHERE
            `tasks`.`completion_date` is null
        AND
            `tasks`.`term_date` <= now() + INTERVAL 1 HOUR
        AND
            `tasks`.`term_date` > now()
        ORDER BY
            `users`.`id` DESC
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $upcomingTasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $upcomingTasks;
}

;

/**
 * Возвращает данные пользователя
 *
 * @param $databaseLink Ссылка на базу данных
 * @param array $formsData данные из формы
 * @return array Данные пользователя
 */
function getUserData($databaseLink, array $formsData): array
{
    $email = mysqli_real_escape_string($databaseLink, $formsData["email"]);

    $sql = "
        SELECT
            *
        FROM
            `users`
        WHERE
            `email` = '$email'
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $userData = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $userData;
}

;

/**
 * Функция отрисовки шаблона с данными
 *
 * @param string $template относительный путь к шаблону, например templates/index.php
 * @param array $data упакованный массив с даными для шаблона для передачи в extract()
 * @return string html-код шаблона
 */
function includeTemplate(string $template, array $data = []): string
{
    if (file_exists($template)) {
        extract($data);
        ob_start();
        require $template;
        $html = ob_get_contents();
        ob_end_clean();
    }

    return $html;
}

;

/**
 * Производит валидацию даты
 *
 * @param string $date дата
 * @param string $format формат даты
 * @return boolean
 */
function validateDate(string $date, string $format = "Y-m-d H:i")
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

;

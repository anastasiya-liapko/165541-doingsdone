<?php
define("DEFAULT_PROJECT", "Входящие");
define("SECS_IN_HOUR", 3600);

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
        if ($projectName == $task["project_name"] || ($projectName == DEFAULT_PROJECT && empty($task["project_name"]))) {
            $result++;
        }
    }

    return $result;
};

/**
 * Возвращает количество часов, оставшееся до каждой из дат
 *
 * @param $date дата завершения задачи
 * @return количество часов
 */
function getHoursCountTillTheDate($date)
{
    if ($date !== NULL) {
        $ts = time();
        $endTs = strtotime($date);
        $tsDiff = $endTs - $ts;
        $hoursUntilEnd = floor($tsDiff / SECS_IN_HOUR);
        return $hoursUntilEnd;
    }
};

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
    } else {
        $html = "";
    }

    return $html;
};

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
};

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
            `tasks`.`user_id` = $userId
        ORDER BY
            `creation_date` DESC
    ";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $tasks;
};

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
    return $d && $d->format($format) == $date;
};

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
        if ($key == "date" && !empty($value) && !validateDate($value)) {
            $errors[$key] = "Дата должна быть корректной";
        }
    }

    return $errors;
};

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
        foreach($emails as $item) {
            if ($key == "email" && $value == $item["email"]) {
                $errors[$key] = "Данный email уже зарегистрирован";
            }
        }
    }

    foreach ($formsData as $key => $value) {
        if ($key == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$key] = "E-mail введён некорректно";
        }
    }

    return $errors;
};

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
    mysqli_stmt_bind_param($stmt, 'sssdd', $formsData["name"], $formsData["file"], $formsData["date"], $formsData["project"], $userId);
    $result = mysqli_stmt_execute($stmt);

    return $result;
};

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
};

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
};

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

            }
            else {
                $errors["password"] = "Неверный пароль";
            }
        }
    } else {
        $errors["email"] = "Такой пользователь не найден";
    }

    return $errors;
};

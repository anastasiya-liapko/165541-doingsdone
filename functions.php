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

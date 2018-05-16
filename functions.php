<?php
define("PROJECT_ALL", "Все");
define("SECS_IN_HOUR", 3600);

/**
 * Возвращает количество задач по имени проекта
 *
 * @param array $tasks Массив задач (строк)
 * @param string $projectName Название проекта, по-умолчанию - "Все"
 * @return int Количество задач
 */
function getTasksCountByProjectName(string $projectName = PROJECT_ALL, array $tasks = []): int
{
    if (PROJECT_ALL == $projectName) {
        return count($tasks);
    }

    $result = 0;

    foreach ($tasks as $task) {
        if ($projectName == $task["project_name"]) {
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
    $sql = "SELECT `project_name` FROM `projects` WHERE `user_id` = $userId";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $projectsList = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $projects = [];
        foreach ($projectsList as $i => $project) {
            $projects[$i] = $project["project_name"];
        }
    }

    return $projects;
};

/**
 * Возвращает список задач для пользователя
 *
 * @param $databaseLink Ссылка на базу данных
 * @param int $userId Id пользователя
 * @return Массив задач (строк)
 */
function getTasksListForUser($databaseLink, int $userId)
{
    $sql = "SELECT `task_name`, `completion_date`, `term_date`, `project_name` FROM `tasks`
        JOIN `projects` ON `tasks`.`project_id` = `projects`.`id` WHERE `tasks`.`user_id` = $userId
        ORDER BY `completion_date` ASC";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $tasks;
};

/**
 * Возвращает список задач для проекта
 *
 * @param $databaseLink Ссылка на базу данных
 * @param int $userId Id пользователя
 * @param int $projectId Id проекта
 * @return Массив задач (строк)
 */
function getTasksListForProject($databaseLink, int $userId, int $projectId)
{
    $sql = "SELECT `task_name`, `completion_date`, `term_date`
        FROM `tasks` WHERE `project_id` = $projectId AND `user_id` = $userId";

    if ($res = mysqli_query($databaseLink, $sql)) {
        $tasksForProject = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $tasksForProject;
};

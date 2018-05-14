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

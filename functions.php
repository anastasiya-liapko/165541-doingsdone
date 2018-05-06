<?php
/**
 * Функция подсчета задач
 * @param string $match название проекта
 * @param array $tasks список всех задач в виде массива
 * @return integer число задач для переданного проекта
 */
function count_tasks($match, $tasks) {
    $count = 0;
    if ($match == "Все") {
        $count = count($tasks);
    }
    if ($match !== "Все") {
        foreach ($tasks as $index => $item) {
            if ($item["category"] == $match) {
                $count++;
            }
        }
    }
    return $count;
};

/**
 * Функция подсчета часов
 * @param string $time дата завершения задачи
 * @return integer оставшееся количество часов до каждой из имеющихся дат
 */
function check_important_tasks($time) {
    $secs_in_hour = 3600;
    $ts = time();
    $end_ts = strtotime($time);
    $ts_diff = $end_ts - $ts;
    $hours_until_end = floor($ts_diff / $secs_in_hour);
    return $hours_until_end;
};

/**
 * Функция отрисовки шаблона с данными
 * @param string $template относительный путь к шаблону, например templates/index.php
 * @param array $data упакованный массив с даными для шаблона для передачи в extract()
 * @return string html-код шаблона
 */
function include_template($template, $data) {
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
}

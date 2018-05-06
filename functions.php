<?php
/* Функция подсчета задач. Функция принимает два аргумента:
список всех задач в виде массива и название проекта.
Возвращает число задач для переданного проекта.
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

/* Функция подсчета часов. Функция принимает один аргумент - дату завершения задачи.
Возвращает число - оставшееся количество часов до каждой из имеющихся дат.
*/
function check_important_tasks($time) {
    $secs_in_hour = 3600;
    $ts = time();
    $end_ts = strtotime($time);
    $ts_diff = $end_ts - $ts;
    $hours_until_end = floor($ts_diff / $secs_in_hour);
    return $hours_until_end;
};

/* Функция-шаблонизатор. Принимает два аргумента: путь к файлу шаблона и массив с данными для этого шаблона.
Возвращает строку — итоговый HTML-код с подставленными данными.
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

<?php
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

function include_template($template, $data) {
    if (file_exists($template)) {
        // $show_complete_tasks = $data["show_complete_tasks"];
        // $tasks = $data["tasks"];
        // $content = $data["content"];
        // $projects = $data["projects"];
        // $title = $data["title"];
        ob_start();
        require $template;
        $html = ob_get_contents();
        ob_end_clean();
    } else {
        $html = "";
    }
    return $html;
}
?>

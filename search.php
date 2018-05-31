<?php
if (isset($_GET["search"])) {
    $searchTasks = getSearchTasks($link, $userId);

    if (empty($searchTasks)) {
        $content = includeTemplate("templates/error.php", ["error" => "Ничего не найдено по вашему запросу"]);
    } else {
        $content = includeTemplate(
            "templates/index.php",
            [
                "tasksByProject" => $searchTasks,
                "selectedProjectId" => $selectedProjectId
            ]
        );
    }
}

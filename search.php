<?php
if (isset($_GET["search"])) {

    if (empty($_GET["search"])) {
        header("Location: index.php?project_id=0&all_tasks");
    } else {
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
}

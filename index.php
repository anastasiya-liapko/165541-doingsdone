<?php
require_once "init.php";
require_once "functions.php";
require_once "data.php";

if (!$link) {
    $error = mysqli_connect_error();
    $content = includeTemplate("templates/error.php", ["error" => $error]);
} else {
    $selectedProjectId = isset($_GET["project_id"]) ? intval($_GET["project_id"]): 0;
    $existsProjects = array_filter(
        $projects,
        function($project) use ($selectedProjectId)
        {
            return $project["id"] == $selectedProjectId;
        }
    );

    if (empty($existsProjects)) {
        $content = includeTemplate("templates/error.php", ["error" => "Проект не найден"]);
    } else {
        $tasksByProject = array_filter(
            $tasks,
            function($task) use ($selectedProjectId)
            {
                return $task["project_id"] == $selectedProjectId;
            }
        );
        $content = includeTemplate(
            "templates/index.php",
            [
                "tasksByProject" => $tasksByProject,
                "showCompleteTasks" => $showCompleteTasks
            ]
        );
    }
}

$layoutContent = includeTemplate(
    "templates/layout.php",
    [
        "content" => $content,
        "projects" => $projects,
        "tasks" => $tasks,
        "tasksByProject" => $tasksByProject,
        "title" => "Дела в порядке",
        "showCompleteTasks" => $showCompleteTasks,
        "selectedProjectId" => $selectedProjectId
    ]
);

print($layoutContent);

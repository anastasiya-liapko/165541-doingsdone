<?php
require_once "init.php";
require_once "functions.php";
require_once "session.php";
require_once "enter.php";

if (isset($_GET["signup"])) {
    $content = includeTemplate("templates/register.php");
    include "signup.php";

} else {
    if (isset($_SESSION["user"])) {
        include "data.php";
        $formPopup = includeTemplate("templates/form.php", ["projects" => $projects]);
        $projectPopup = includeTemplate("templates/project.php");
        include "add.php";
        include "show_complete_tasks.php";
        if (!$link) {
            $error = mysqli_connect_error();
            $content = includeTemplate("templates/error.php", ["error" => $error]);
        } else {
            $selectedProjectId = isset($_GET["project_id"]) ? intval($_GET["project_id"]) : $userId;
            $existsProjects = array_filter(
                $projects,
                function ($project) use ($selectedProjectId) {
                    return $project["id"] == $selectedProjectId;
                }
            );

            if (empty($existsProjects)) {
                $content = includeTemplate("templates/error.php", ["error" => "Проект не найден"]);
            } else {
                $filteredTasks = getFilteredTasks($tasks, $userId, $selectedProjectId);

                $content = includeTemplate(
                    "templates/index.php",
                    [
                        "tasksByProject" => $filteredTasks,
                        "selectedProjectId" => $selectedProjectId
                    ]
                );
            }
        }

    } else {
        $content = includeTemplate("templates/guest.php");
    }
}

$layoutContentParameters = [
    "content" => $content,
    "title" => "Дела в порядке",
    "autorizationPopup" => $autorizationPopup
];

isset($errors) ? $layoutContentParameters = array_merge(["errors" => $errors], $layoutContentParameters) : "";
isset($formPopup) ? $layoutContentParameters = array_merge(["formPopup" => $formPopup], $layoutContentParameters) : "";
isset($projects) ? $layoutContentParameters = array_merge(["projects" => $projects], $layoutContentParameters) : "";
isset($tasks) ? $layoutContentParameters = array_merge(["tasks" => $tasks], $layoutContentParameters) : "";
isset($selectedProjectId) ? $layoutContentParameters = array_merge(["selectedProjectId" => $selectedProjectId],
    $layoutContentParameters) : "";
isset($projectPopup) ? $layoutContentParameters = array_merge(["projectPopup" => $projectPopup],
    $layoutContentParameters) : "";

$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

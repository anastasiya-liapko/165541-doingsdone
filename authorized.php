<?php
require_once "init.php";
require_once "mysql_helper.php";

$userId = $_SESSION["user"]["id"];
$projects = getProjectsListForUser($link, $userId);
$projects = array_merge([["name" => "Входящие", "id" => 0]], $projects);
$tasks = getTasksListForUser($link, $userId);

$formPopup = includeTemplate("templates/form.php", ["projects" => $projects]);
$projectPopup = includeTemplate("templates/project.php");
include "add.php";
include "show_complete_tasks.php";

$selectedProjectId = isset($_GET["project_id"]) ? intval($_GET["project_id"]) : 0;
$existsProjects = array_filter(
    $projects,
    function ($project) use ($selectedProjectId) {
        return (int)$project["id"] === $selectedProjectId;
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

include "search.php";

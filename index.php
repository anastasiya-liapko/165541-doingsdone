<?php
require_once("functions.php");
require_once("init.php");
require_once("data.php");

$user = 1;
$project = 1;

if (!$link) {
    $error = mysqli_connect_error();
    $content = includeTemplate("templates/error.php", ["error" => $error]);
} else {
    $projects = getProjectsListForUser($link, $user);
    if (!$projects) {
        $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
    }

    $tasks = getTasksListForUser($link, $user);
    if (!$tasks) {
        $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
    }

    $tasksByProject = getTasksListForProject($link, $user, $project);
    if (!$tasksByProject) {
        $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
    }
}

$layoutContent = includeTemplate("templates/layout.php", [
    "content" => $content,
    "projects" => $projects,
    "tasks" => $tasks,
    "tasksByProject" => $tasksByProject,
    "title" => "Дела в порядке",
    "showCompleteTasks" => $showCompleteTasks
    ]);

print($layoutContent);


// $id = $_GET["project_id"];
// $sql = "SELECT `task_name`, `completion_date`, `term_date`, `project_id` FROM `tasks`
//     WHERE `project_id` = ?";
// $stmt = mysqli_prepare($link, $sql);
// mysqli_stmt_bind_param($stmt, 'd', $id);
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);
// if ($result) {
//     $tasksByProject = mysqli_fetch_all($result, MYSQLI_ASSOC);
//     print_r($tasksByProjects);
//     $pageContent = includeTemplate("templates/index.php", ["tasksByProject" => $tasksByProject]);
// } else {
//     $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
// }

// $id = intval($_GET["project_id"]);
// $sql = "SELECT `task_name`, `completion_date`, `term_date`, `project_id` FROM `tasks`
//     WHERE `project_id` = $id";
// if ($result = mysqli_query($link, $sql)) {
//     if (!mysqli_num_rows($result)) {
//         http_response_code(404);
//         $content = includeTemplate("templates/error.php", ["error" => "Проект с этим идентификатором не найден"]);
//     } else {
//         $tasksByProject = mysqli_fetch_all($result, MYSQLI_ASSOC);
//         print_r($tasksByProjects);
//         $pageContent = includeTemplate("templates/index.php", ["tasksByProject" => $tasksByProject]);
//     }
// } else {
//     $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
// }

// $search = $_GET["project_id"] ?? "";
// $search = mysqli_real_escape_string($link, $search);
// $sql = "SELECT `task_name`, `completion_date`, `term_date`, `project_id` FROM `tasks`
//     WHERE `project_id` = '" . $search . "'";
// if ($res = mysqli_query($link, $sql)) {
//     $tasksByProject = mysqli_fetch_all($res, MYSQLI_ASSOC);
//     print_r($tasksByProjects);
//     $pageContent = includeTemplate("templates/index.php", ["tasksByProject" => $tasksByProject]);
// } else {
//     $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
// }

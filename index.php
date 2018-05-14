<?php
require_once("functions.php");
require_once("data.php");

$link = mysqli_connect("localhost", "root", "11111111", "doingsdone-165541");
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error();
    $content = includeTemplate("templates/error.php", ["error" => $error]);
} else {
    $sql = "SELECT `project_name` FROM `projects` WHERE `user_id` = 1";

    if ($res = mysqli_query($link, $sql)) {
        $projectsList = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $projects = [];
        foreach ($projectsList as $i => $project) {
            $projects[$i] = $project["project_name"];
        }
    } else {
        $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
    }

    $sql = "SELECT `task_name`, `completion_date`, `term_date`, `project_name` FROM `tasks`
        JOIN `projects` ON `tasks`.`project_id` = `projects`.`id` WHERE `tasks`.`user_id` = 1 ORDER BY `completion_date` ASC";

    if ($res = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $pageContent = includeTemplate("templates/index.php", ["tasks" => $tasks]);
    } else {
        $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
    }

    $sql = "SELECT `task_name`, `completion_date`, `term_date`, `project_id`
        FROM `tasks` WHERE `project_id` = 1 AND `user_id` = 1";

    if ($res = mysqli_query($link, $sql)) {
        $tasksByProject = mysqli_fetch_all($res, MYSQLI_ASSOC);
        print_r($tasksByProjects);
        $pageContent = includeTemplate("templates/index.php", ["tasksByProject" => $tasksByProject]);
    } else {
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

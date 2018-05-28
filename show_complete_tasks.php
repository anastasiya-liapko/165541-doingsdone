<?php
if (isset($_GET["check"])) {
    $taskId = intval($_GET["task_id"]);
    if ($_GET["check"] == 1) {
        setCookie($taskId, 1, 01 - 01 - 2027, "/");
    }
    if ($_GET["check"] == 0) {
        setCookie($taskId, 0, 01 - 01 - 2027, "/");
    }
    changeTaskStatus($link, $taskId);
    $projectId = getProjectIdByTaskId($link, $taskId, $userId);
    header("Location: index.php?project_id=$projectId&all_tasks");
}

if (isset($_GET["project_id"])) {
    $projectId = intval($_GET["project_id"]);
    setCookie("projectId", $projectId, 01 - 01 - 2027, "/");
}

if (isset($_GET["show_completed"])) {
    $showCompleteTasks = intval($_GET["show_completed"]);
    setCookie("showCompleteTasks", $showCompleteTasks, 01 - 01 - 2027, "/");
    $projectId = $_COOKIE["projectId"];
    header("Location: index.php?project_id=$projectId&all_tasks");
}

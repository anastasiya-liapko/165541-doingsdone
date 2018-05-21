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
    $formPopup = includeTemplate("templates/form.php",["projects" => $projects]);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tasksForm = $_POST;
        $errors = checkTasksFormOnErrors($tasksForm);
        if ($tasksForm["project"] == 0) {
            $tasksForm["project"] = NULL;
        }
        if ($tasksForm["date"] == "") {
            $tasksForm["date"] = NULL;
        }
        if (isset($_FILES["preview"]["name"])) {
            $fileName = $_FILES["preview"]["name"];
            $tmpName = $_FILES["preview"]["tmp_name"];
            $filePath = __DIR__ . "/";
            $fileUrl = "/" . $fileName;
            move_uploaded_file($tmpName, $fileUrl);
            $tasksForm["file"] = $fileName;
        }
        if (count($errors)) {
            $formPopup = includeTemplate(
                "templates/form.php",
                [
                    "tasksForm" => $tasksForm,
                    "errors" => $errors,
                    "projects" => $projects
                ]
            );
        } else {
            $addNewTask = addNewTask($link, $tasksForm);
            if($addNewTask) {
                // $taskId = mysqli_insert_id($link);
                header("Location: index.php?success=true");
            } else {
                $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
            }
        }
    } else {
        $formPopup = includeTemplate("templates/form.php",["projects" => $projects]);
    }
}
$layoutContentParameters = [
    "content" => $content,
    "projects" => $projects,
    "tasks" => $tasks,
    "tasksByProject" => $tasksByProject,
    "title" => "Дела в порядке",
    "showCompleteTasks" => $showCompleteTasks,
    "selectedProjectId" => $selectedProjectId,
    "formPopup" => $formPopup
];
if (count($errors)) {
    $layoutContentParameters = array_merge(["errors" => $errors], $layoutContentParameters);
}
$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

<?php
require_once "init.php";
require_once "functions.php";

session_start();

$autorizationPopup = includeTemplate("auth_form.php");

if (isset($_GET["logout"])) {
    $_SESSION = [];
    header("Location: index.php");
}

if (isset($_GET["signup"])) {
    $content = includeTemplate("register.php");
} else if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"]["id"];
    $showCompleteTasks = rand(0, 1);
    $projects = getProjectsListForUser($link, $user);
    $projects = array_merge([["name" => "Входящие", "id" => 0]], $projects);
    $tasks = getTasksListForUser($link, $user);
    $formPopup = includeTemplate("templates/form.php",["projects" => $projects]);
    $projectPopup = includeTemplate("templates/project.php");
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
 } else {
    $content = includeTemplate("guest.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $data = $_POST;
    $popupErrors = checkRegFormOnErrors($data, $link);
    if (count($popupErrors)) {
        $content = includeTemplate("register.php", ["errors" => $popupErrors, "formsData" => $data]);
    } else {
        $addNewUser = addNewUser($link, $data);
        if ($addNewUser) {
            $content = includeTemplate("register.php");
        } else {
            $error = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
            $content = includeTemplate("register.php", ["content" => $error]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["autorization"])) {
    $data = $_POST;
    $user = getUserData($link, $data);
    $errors = checkAutoFormOnErrors($data, $user);

    if (count($errors)) {
        $autorizationPopup = includeTemplate("auth_form.php", ["formsData" => $data, "errors" => $errors]);
        $content = includeTemplate("guest.php", []);
    } else {
        header("Location: index.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task"])) {
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
        $addNewTask = addNewTask($link, $tasksForm, $user);
        if($addNewTask) {
            header("Location: index.php?success=true");
        } else {
            $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
        }
    }
}

$layoutContentParameters = [
    "content" => $content,
    "title" => "Дела в порядке",
    "autorizationPopup" => $autorizationPopup
];
if (isset($errors)) {
    $layoutContentParameters = array_merge(["errors" => $errors], $layoutContentParameters);
}
if (isset($formPopup)) {
    $layoutContentParameters = array_merge(["formPopup" => $formPopup], $layoutContentParameters);
}
if (isset($projects)) {
    $layoutContentParameters = array_merge(["projects" => $projects], $layoutContentParameters);
}
if (isset($tasks)) {
    $layoutContentParameters = array_merge(["tasks" => $tasks], $layoutContentParameters);
}
if (isset($selectedProjectId)) {
    $layoutContentParameters = array_merge(["selectedProjectId" => $selectedProjectId], $layoutContentParameters);
}
if (isset($projectPopup)) {
    $layoutContentParameters = array_merge(["projectPopup" => $projectPopup], $layoutContentParameters);
}
$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

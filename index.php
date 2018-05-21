<?php
require_once "init.php";
require_once "functions.php";
require_once "data.php";

session_start();

$formPopup = includeTemplate("templates/form.php",["projects" => $projects]);
$autorizationPopup = includeTemplate("auth_form.php");

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
            $addNewTask = addNewTask($link, $tasksForm);
            if($addNewTask) {
                // $taskId = mysqli_insert_id($link);
                header("Location: index.php?success=true");
            } else {
                $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
            }
        }
    }
    // else {
    //     $formPopup = includeTemplate("templates/form.php",["projects" => $projects]);
    // }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["autorization"])) {
    $data = $_POST;
    $errors = [];
    $required = ["email", "password"];
    foreach ($required as $key) {
        if (empty($data[$key])) {
            $errors[$key] = "Заполните это поле";
        }
    }
    $email = mysqli_real_escape_string($link, $data["email"]);
    $sql = "
        SELECT
            *
        FROM
            `users`
        WHERE
            `email` = '$email'
    ";
    if ($res = mysqli_query($link, $sql)) {
        $user = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    if (!count($errors) and $user) {
        foreach ($user as $key) {
            if (password_verify($data["password"], $key["password"])) {
                $_SESSION["user"] = $key;
            }
            else {
                $errors["password"] = "Неверный пароль";
            }
        }
    }
    else {
        $errors["email"] = "Такой пользователь не найден";
    }
    if (count($errors)) {
        $autorizationPopup = includeTemplate("auth_form.php", ["formsData" => $data, "errors" => $errors]);
    }
    else {
        // $content = includeTemplate("templates/index.php", ["user" => $_SESSION["user"]]);
        header("Location: index.php");
    }
} else {
    if (isset($_SESSION["user"])) {
        $content = includeTemplate("templates/index.php", ["user" => $_SESSION["user"]]);
    }
    else {
        $content = includeTemplate("guest.php", []);
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
    "formPopup" => $formPopup,
    "autorizationPopup" => $autorizationPopup
];
if (isset($errors)) {
    $layoutContentParameters = array_merge(["errors" => $errors], $layoutContentParameters);
}
if (isset($_SESSION["user"])) {
    $layoutContentParameters = array_merge(["user" => $_SESSION["user"]], $layoutContentParameters);
}
$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

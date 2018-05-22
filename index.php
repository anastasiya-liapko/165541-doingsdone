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
    $content = includeUserPageTemplate($link, $user);

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
            $projects = getProjectsListForUser($link, $user);
            $projects = array_merge([["name" => "Входящие", "id" => 0]], $projects);
            $formPopup = includeTemplate(
                "templates/form.php",
                [
                    "tasksForm" => $tasksForm,
                    "errors" => $errors,
                    "projects" => $projects
                ]
            );
        } else {
            $userId = $_SESSION["user"]["id"];
            $addNewTask = addNewTask($link, $tasksForm, $userId);
            if($addNewTask) {
                // $taskId = mysqli_insert_id($link);
                header("Location: index.php?success=true");
            } else {
                $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
            }
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
        $user = getUserData($link, $data);
        $user = $_SESSION["user"]["id"];
        $content = includeUserPageTemplate($link, $user);
        $projects = getProjectsListForUser($link, $user);
        $projects = array_merge([["name" => "Входящие", "id" => 0]], $projects);
        $formPopup = includeTemplate("templates/form.php",["projects" => $projects]);
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
$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

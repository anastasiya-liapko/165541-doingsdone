<?php
require_once "init.php";
require_once "functions.php";
require_once "data.php";

session_start();

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
                "showCompleteTasks" => $showCompleteTasks,
                "projects" => $projects,
                "selectedProjectId" => $selectedProjectId,
                "tasks" => $tasks,
            ]
        );
    }

    $formPopup = includeTemplate("templates/form.php",["projects" => $projects]);
    $content = includeTemplate("templates/register.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["register"])) {
            $data = $_POST;
            $errors = checkRegFormOnErrors($data, $link);
            if (count($errors)) {
                $content = includeTemplate("templates/register.php", ["errors" => $errors, "formsData" => $data]);
            } else {
                $addNewUser = addNewUser($link, $data);
                if ($addNewUser) {
                    $autorizationPopup = includeTemplate("templates/autorization.php");
                } else {
                    $error = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
                    $content = includeTemplate("templates/register.php", ["content" => $error]);
                }
            }
        } else if (isset($_POST["autorization"])) {
            $data = $_POST;
            $popupErrors = [];
            $required = ["email", "password"];
            foreach ($required as $key) {
                if (empty($data[$key])) {
                    $popupEerrors[$key] = "Заполните это поле";
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
            if (!count($popupErrors) and $user) {
                foreach ($user as $key) {
                    if (password_verify($data["password"], $key["password"])) {
                        $_SESSION["user"] = $key;
                    }
                    else {
                        $popupErrors["password"] = "Неверный пароль";
                    }
                }
            }
            else {
                $popupErrors["email"] = "Такой пользователь не найден";
            }
            if (count($popupErrors)) {
                $autorizationPopup = includeTemplate("templates/autorization.php", ["formsData" => $data, "errors" => $popupErrors]);
            }
            else {
                // print("ok");
            }
        } else if (isset($_POST["task"])) {
            $tasksForm = $_POST;
            $popupErrors = checkTasksFormOnErrors($tasksForm);
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
            if (count($popupErrors)) {
                $formPopup = includeTemplate(
                    "templates/form.php",
                    [
                        "tasksForm" => $tasksForm,
                        "errors" => $popupErrors,
                        "projects" => $projects
                    ]
                );
            } else {
                $addNewTask = addNewTask($link, $tasksForm);
                if($addNewTask) {
                    // $taskId = mysqli_insert_id($link);
                    header("Location: index.php");
                } else {
                    $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
                }
            }
        }
    }

    // if (isset($_SESSION["user"])) {
    //     // $content = includeTemplate('welcome.php', ['username' => $_SESSION['user']['name']]);
    // } else {
    //     $autorizationPopup = includeTemplate("autorization.php", []);
    //     $content = includeTemplate("templates/register.php", ["autorizationPopup" => $autorizationPopup]);
    // }
}

$layoutContentParameters = [
    "content" => $content,
    "title" => "Дела в порядке",
    "formPopup" => $formPopup,
    "autorizationPopup" => $autorizationPopup
];
if (count($popupErrors)) {
    $layoutContentParameters = array_merge(["errors" => $popupErrors], $layoutContentParameters);
}
$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

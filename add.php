<?php
require_once "functions.php";
require_once "mysql_helper.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tasksForm = $_POST;
    $required = ["name"];
    $dict =
    [
        "name" => "Название",
        "project" => "Проект",
        "date" => "Срок выполнения",
        "preview" => "Файл"
    ];
    $errors = [];

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = "Заполните это поле";
        }
    }

    foreach ($tasksForm as $key => $value) {
        if ($key == "date" && !empty($value) && !validateDate($value)) {
            $errors[$key] = "Дата должна быть корректной";
        }
    }

    if (isset($_FILES["preview"]["name"])) {
        $fileName = $_FILES["preview"]["name"];
        $tmpName = $_FILES["preview"]["tmp_name"];
        $fileUrl = "/165541-doingsdone/" . $fileName;
        move_uploaded_file($tmpName, $fileUrl);
        $tasksForm["file"] = $fileName;
        // print_r("<a href=$fileUrl>$fileName</a>");
    }

    if (count($errors)) {
        $formPopup = includeTemplate(
            "templates/form.php",
            [
                "tasksForm" => $tasksForm,
                "errors" => $errors,
                "dict" => $dict,
                "projects" => $projects
            ]
        );
    } else {
        $sql = "
            INSERT INTO
                `tasks` (`creation_date`, `completion_date`, `name`, `file`, `term_date`, `project_id`, `user_id`)
            VALUES (NOW(), NULL, ?, ?, ?, ?, 1)
        ";
        $stmt = db_get_prepare_stmt($link, $sql, [$tasksForm["name"], $tasksForm["file"], $tasksForm["date"], $tasksForm["project"]]);
        $result = mysqli_stmt_execute($stmt);

        if($result) {
            $taskId = mysqli_insert_id($link);
            header("Location: /165541-doingsdone/index.php?success=true");
        } else {
            $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
        }
    }
} else {
    $formPopup = includeTemplate("templates/form.php",["projects" => $projects]);
}

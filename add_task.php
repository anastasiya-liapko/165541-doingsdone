<?php
$data = $_POST;
$errors = checkTasksFormOnErrors($data);
$data["project"] === $userId ? $data["project"] = null : $data["project"];
$data["date"] === "" ? $data["date"] = null : $data["date"];
$data["file"] = getFile();
if (count($errors)) {
    $formPopup = includeTemplate(
        "templates/form.php",
        [
            "formData" => $data,
            "errors" => $errors,
            "projects" => $projects
        ]
    );
} else {
    isset($data["project"]) ? $projectId = (int)$data["project"] : $projectId = $userId;
    $addNewTask = addNewTask($link, $data, $userId);
    if ($addNewTask) {
        header("Location: index.php?project_id=$projectId&all_tasks&success=true");
    } else {
        $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
    }
}

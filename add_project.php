<?php
$data = $_POST;
$errors = checkProjectFormOnErrors($data, $userId, $link);
if (count($errors)) {
    $projectPopup = includeTemplate("templates/project.php", ["formsData" => $data, "errors" => $errors]);
} else {
    $addNewProject = addNewProject($data, $userId, $link);
    $projectId = mysqli_insert_id($link);
    if ($addNewProject) {
        header("Location: index.php?project_id=$projectId&all_tasks");
    } else {
        $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
    }
}

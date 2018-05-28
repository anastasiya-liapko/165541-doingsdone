<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["task"])) {
        $data = $_POST;
        $errors = checkTasksFormOnErrors($data);
        $data["project"] == $userId ? $data["project"] = null : $data["project"];
        $data["date"] == "" ? $data["date"] = null : $data["date"];
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

    } else {
        if (isset($_POST["project"])) {
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
        }
    }
}

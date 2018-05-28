<?php
$autorizationPopup = includeTemplate("templates/auth_form.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["autorization"])) {
        $data = $_POST;
        $user = getUserData($link, $data);
        $errors = checkAutoFormOnErrors($data, $user);
        if (count($errors)) {
            $autorizationPopup = includeTemplate("templates/auth_form.php",
                ["formsData" => $data, "errors" => $errors]);
            $content = includeTemplate("templates/guest.php", []);
        } else {
            $projectId = $user[0]["id"];
            header("Location: index.php?project_id=$projectId&all_tasks");
        }
    }
}

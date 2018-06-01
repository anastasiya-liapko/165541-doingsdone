<?php
$autorizationPopup = includeTemplate("templates/auth_form.php");

if (isset($_POST["autorization"])) {
    $data = $_POST;
    $user = getUserData($link, $data);
    $errors = checkAutoFormOnErrors($data, $user);
    if (count($errors)) {
        $autorizationPopup = includeTemplate("templates/auth_form.php",
            ["formsData" => $data, "errors" => $errors]);
        $content = includeTemplate("templates/guest.php", []);
    } else {
        include "notify.php";
        header("Location: index.php?project_id=0&all_tasks");
    }
}

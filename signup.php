<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        $data = $_POST;
        $popupErrors = checkRegFormOnErrors($data, $link);
        if (count($popupErrors)) {
            $content = includeTemplate("templates/register.php", ["errors" => $popupErrors, "formsData" => $data]);
        } else {
            $addNewUser = addNewUser($link, $data);
            if ($addNewUser) {
                $content = includeTemplate("templates/register.php");
            } else {
                $error = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
                $content = includeTemplate("templates/register.php", ["content" => $error]);
            }
        }
    }
}

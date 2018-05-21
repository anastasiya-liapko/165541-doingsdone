<?php
require_once "init.php";
require_once "functions.php";

// session_start();
$autorizationPopup = includeTemplate("templates/autorization.php");
if (!$link) {
    $error = mysqli_connect_error();
    $error = includeTemplate("templates/error.php", ["error" => $error]);
} else {
    $registerContent = includeTemplate("templates/register.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
        $data = $_POST;
        $errors = checkRegFormOnErrors($data, $link);
        if (count($errors)) {
            $registerContent = includeTemplate("templates/register.php", ["errors" => $errors, "formsData" => $data]);
        } else {
            $addNewUser = addNewUser($link, $data);
            if ($addNewUser) {
                $autorizationPopup = includeTemplate("templates/autorization.php");
                $registerContent = includeTemplate("templates/register.php", ["autorizationPopup" => $autorizationPopup]);
            } else {
                $error = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
                $registerContent = includeTemplate("templates/register.php", ["error" => $error]);
            }
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["autorization"])) {
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
            $registerContent = includeTemplate("templates/register.php", ["autorizationPopup" => $autorizationPopup]);
        }
        else {
            header("Location: index.php");
        }
    }
    // if (isset($_SESSION["user"])) {
    //     // $content = includeTemplate('welcome.php', ['username' => $_SESSION['user']['name']]);
    // } else {
    //     $autorizationPopup = includeTemplate("autorization.php", []);
    //     $content = includeTemplate("templates/register.php", ["autorizationPopup" => $autorizationPopup]);
    // }
}

print($registerContent);

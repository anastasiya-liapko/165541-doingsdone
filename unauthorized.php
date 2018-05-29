<?php
require_once "enter.php";

if (isset($_GET["signup"])) {
    $content = includeTemplate("templates/register.php");
    if (isset($_POST["register"])) {
        include "signup.php";
    }
} else {
    $content = includeTemplate("templates/guest.php");
}

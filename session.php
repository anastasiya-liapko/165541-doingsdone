<?php
session_start();

if (isset($_GET["logout"])) {
    $_SESSION = [];
    header("Location: index.php");
}

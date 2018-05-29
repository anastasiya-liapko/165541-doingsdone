<?php
$link = mysqli_connect("mysql", "root", "", "doingsdone-165541");

if (!$link) {
    $error = mysqli_connect_error();
    echo $error;
    exit;
} else {
    mysqli_set_charset($link, "utf8");
}

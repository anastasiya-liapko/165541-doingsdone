<?php
date_default_timezone_set('Europe/Moscow');
require_once "vendor/autoload.php";
require_once "init.php";
require_once "functions.php";
require_once "session.php";

if (!isset($_SESSION["user"])) {
    include "unauthorized.php";
} else {
    include "authorized.php";
}

// include "notify.php";

$layoutContentParameters = [
    "content" => $content,
    "title" => "Дела в порядке"
];

$layoutContentParameters = array_merge(
    $layoutContentParameters,
    isset($errors) ? ["errors" => $errors] : [],
    isset($formPopup) ? ["formPopup" => $formPopup] : [],
    isset($projects) ? ["projects" => $projects] : [],
    isset($tasks) ? ["tasks" => $tasks] : [],
    isset($selectedProjectId) ? ["selectedProjectId" => $selectedProjectId] : [],
    isset($projectPopup) ? ["projectPopup" => $projectPopup] : [],
    isset($autorizationPopup) ? ["autorizationPopup" => $autorizationPopup] : []
);

$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

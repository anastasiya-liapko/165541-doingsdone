<?php
require_once("functions.php");
require_once("data.php");

$pageContent = includeTemplate("templates/index.php", [
    "showCompleteTasks" => $showCompleteTasks,
    "tasks" => $tasks
]);
$layoutContent = includeTemplate("templates/layout.php", [
	"content" => $pageContent,
    "projects" => $projects,
    "tasks" => $tasks,
	"title" => "Дела в порядке"
]);

print($layoutContent);

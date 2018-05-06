<?php
require_once("functions.php");
require_once('data.php');

$page_content = include_template("templates/index.php", [
    "show_complete_tasks" => $show_complete_tasks,
    "tasks" => $tasks
]);
$layout_content = include_template("templates/layout.php", [
	"content" => $page_content,
    "projects" => $projects,
    "tasks" => $tasks,
	"title" => "Дела в порядке"
]);

print($layout_content);

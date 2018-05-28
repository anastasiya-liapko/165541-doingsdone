<?php
$userId = $_SESSION["user"]["id"];
$projects = getProjectsListForUser($link, $userId);
$projects = array_merge([["name" => "Входящие", "id" => $userId]], $projects);
$tasks = getTasksListForUser($link, $userId);

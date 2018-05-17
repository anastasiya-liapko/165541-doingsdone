<?php
$showCompleteTasks = rand(0, 1);
$user = 2;
$projects = getProjectsListForUser($link, $user);
$projects = array_merge([["name" => "Входящие", "id" => 0]], $projects);
$tasks = getTasksListForUser($link, $user);

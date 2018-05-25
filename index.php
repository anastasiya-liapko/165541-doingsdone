<?php
require_once "init.php";
require_once "functions.php";

session_start();

$autorizationPopup = includeTemplate("auth_form.php");

if (isset($_GET["logout"])) {
    $_SESSION = [];
    header("Location: index.php");
}

if (isset($_GET["signup"])) {
    $content = includeTemplate("register.php");

} else {
    if (isset($_SESSION["user"])) {
        $user = $_SESSION["user"]["id"];
        $showCompleteTasks = rand(0, 1);
        $projects = getProjectsListForUser($link, $user);
        $projects = array_merge([["name" => "Входящие", "id" => $user]], $projects);
        $tasks = getTasksListForUser($link, $user);
        $formPopup = includeTemplate("templates/form.php", ["projects" => $projects]);
        $projectPopup = includeTemplate("templates/project.php");
        if (!$link) {
            $error = mysqli_connect_error();
            $content = includeTemplate("templates/error.php", ["error" => $error]);
        } else {
            $selectedProjectId = isset($_GET["project_id"]) ? intval($_GET["project_id"]) : $user;
            $existsProjects = array_filter(
                $projects,
                function ($project) use ($selectedProjectId) {
                    return $project["id"] == $selectedProjectId;
                }
            );
            if (empty($existsProjects)) {
                $content = includeTemplate("templates/error.php", ["error" => "Проект не найден"]);
            } else {
                $filteredTasks = [];
                if (isset($_GET["all_tasks"])) {
                    $filteredTasks = getTasksListByProjectId($tasks, $user, $selectedProjectId);
                } else {
                    if (isset($_GET["today_tasks"])) {
                        $filteredTasks = getTodayTasks($tasks);
                    } else {
                        if (isset($_GET["tomorrow_tasks"])) {
                            $filteredTasks = getTomorrowTasks($tasks);
                        } else {
                            if (isset($_GET["overdue_tasks"])) {
                                $filteredTasks = getOverdueTasks($tasks);
                            }
                        }
                    }
                }
                $content = includeTemplate(
                    "templates/index.php",
                    [
                        "tasksByProject" => $filteredTasks,
                        "showCompleteTasks" => $showCompleteTasks,
                        "selectedProjectId" => $selectedProjectId
                    ]
                );
            }
        }

    } else {
        $content = includeTemplate("guest.php");
    }
}

if (isset($_GET["show_completed"])) {
    $showCompleteTasks = intval($_GET["show_completed"]);
    header("Location: index.php?project_id=$selectedProjectId&all_tasks");
}

if (isset($_GET["check"])) {
    $taskId = intval($_GET["task_id"]);
    changeTaskStatus($link, $taskId);
    $projectId = getProjectIdByTaskId($link, $taskId, $user);
    header("Location: index.php?project_id=$projectId&all_tasks");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $data = $_POST;
    $popupErrors = checkRegFormOnErrors($data, $link);
    if (count($popupErrors)) {
        $content = includeTemplate("register.php", ["errors" => $popupErrors, "formsData" => $data]);
    } else {
        $addNewUser = addNewUser($link, $data);
        if ($addNewUser) {
            $content = includeTemplate("register.php");
        } else {
            $error = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
            $content = includeTemplate("register.php", ["content" => $error]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["autorization"])) {
    $data = $_POST;
    $user = getUserData($link, $data);
    $errors = checkAutoFormOnErrors($data, $user);
    if (count($errors)) {
        $autorizationPopup = includeTemplate("auth_form.php", ["formsData" => $data, "errors" => $errors]);
        $content = includeTemplate("guest.php", []);
    } else {
        $projectId = $user[0]["id"];
        header("Location: index.php?project_id=$projectId&all_tasks");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["task"])) {
        $data = $_POST;
        $errors = checkTasksFormOnErrors($data);
        $data["project"] == $user ? $data["project"] = null : $data["project"];
        $data["date"] == "" ? $data["date"] = null : $data["date"];
        $data["file"] = getFile();
        if (count($errors)) {
            $formPopup = includeTemplate(
                "templates/form.php",
                [
                    "formData" => $data,
                    "errors" => $errors,
                    "projects" => $projects
                ]
            );
        } else {
            isset($data["project"]) ? $projectId = (int)$data["project"] : $projectId = $user;
            $addNewTask = addNewTask($link, $data, $user);
            if ($addNewTask) {
                header("Location: index.php?project_id=$projectId&all_tasks&success=true");
            } else {
                $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
            }
        }
    } else {
        if (isset($_POST["project"])) {
            $data = $_POST;
            $errors = checkProjectFormOnErrors($data, $user, $link);
            if (count($errors)) {
                $projectPopup = includeTemplate("templates/project.php", ["formsData" => $data, "errors" => $errors]);
            } else {
                $addNewProject = addNewProject($data, $user, $link);
                $projectId = mysqli_insert_id($link);
                if ($addNewProject) {
                    header("Location: index.php?project_id=$projectId&all_tasks");
                } else {
                    $content = includeTemplate("templates/error.php", ["error" => mysqli_error($link)]);
                }
            }
        }
    }

}

$layoutContentParameters = [
    "content" => $content,
    "title" => "Дела в порядке",
    "autorizationPopup" => $autorizationPopup
];

isset($errors) ? $layoutContentParameters = array_merge(["errors" => $errors], $layoutContentParameters) : "";
isset($formPopup) ? $layoutContentParameters = array_merge(["formPopup" => $formPopup], $layoutContentParameters) : "";
isset($projects) ? $layoutContentParameters = array_merge(["projects" => $projects], $layoutContentParameters) : "";
isset($tasks) ? $layoutContentParameters = array_merge(["tasks" => $tasks], $layoutContentParameters) : "";
isset($selectedProjectId) ? $layoutContentParameters = array_merge(["selectedProjectId" => $selectedProjectId],
    $layoutContentParameters) : "";
isset($projectPopup) ? $layoutContentParameters = array_merge(["projectPopup" => $projectPopup],
    $layoutContentParameters) : "";

$layoutContent = includeTemplate("templates/layout.php", $layoutContentParameters);
print($layoutContent);

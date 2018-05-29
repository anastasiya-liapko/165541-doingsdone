<?php
if (isset($_POST["task"])) {
    include "add_task.php";
} else {
    if (isset($_POST["project"])) {
        include "add_project.php";
    }
}

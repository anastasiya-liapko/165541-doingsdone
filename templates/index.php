<main class="content__main">
    <?php if (isset($_GET["success"]) && isset($_SESSION["user"])): ?>
        <div class="alert alert-success"><p>Задача добавлена! </p>
        </div>
    <?php endif; ?>
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.html" method="post">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="index.php?project_id=<?= $selectedProjectId ?>&all_tasks"
               class="tasks-switch__item <?= isset($_GET["all_tasks"]) ? "tasks-switch__item--active" : ""; ?>">Все
                задачи</a>
            <a href="index.php?project_id=<?= $selectedProjectId ?>&today_tasks"
               class="tasks-switch__item <?= isset($_GET["today_tasks"]) ? "tasks-switch__item--active" : ""; ?>">Повестка
                дня</a>
            <a href="index.php?project_id=<?= $selectedProjectId ?>&tomorrow_tasks"
               class="tasks-switch__item <?= isset($_GET["tomorrow_tasks"]) ? "tasks-switch__item--active" : ""; ?>">Завтра</a>
            <a href="index.php?project_id=<?= $selectedProjectId ?>&overdue_tasks"
               class="tasks-switch__item <?= isset($_GET["overdue_tasks"]) ? "tasks-switch__item--active" : ""; ?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox"
                   value="<?= isset($_COOKIE["showCompleteTasks"]) && $_COOKIE["showCompleteTasks"] == 1 ? "checked" : ""; ?>"
                    <?= !isset($_COOKIE["showCompleteTasks"]) ? "checked" : ""; ?>
                    <?= isset($_COOKIE["showCompleteTasks"]) && $_COOKIE["showCompleteTasks"] == 1 ? "checked" : ""; ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php if (isset($tasksByProject)): ?>
            <?php foreach ($tasksByProject as $key => $item): ?>
                <tr class="tasks__item task <?= $item["completion_date"] !== null ? "task--completed" : ""; ?>
                <?= getHoursCountTillTheDate($item["term_date"]) <= 24 && $item["term_date"] !== null ? "task--important" : ""; ?>">
                    <?php if ((isset($_COOKIE["showCompleteTasks"]) && $_COOKIE["showCompleteTasks"] == 1) || !isset($_COOKIE["showCompleteTasks"])): ?>
                        <td class="task__select">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden task__checkbox"
                                       name="<?= $item["project_id"]; ?>" type="checkbox" value="<?= $item["id"]; ?>"
                                        <?= $item["completion_date"] !== null ? "checked" : ""; ?>>
                                <span class="checkbox__text"><?= htmlspecialchars($item["name"]); ?></span>
                            </label>
                        </td>

                        <td class="task__file">
                            <a class="download-link <?= $item["file"] ? "" : "hidden"; ?>"
                               href="/<?= $item["file"]; ?>"><?= htmlspecialchars($item["file"]); ?></a>
                        </td>

                        <td class="task__date"><?= htmlspecialchars($item["term_date"]); ?></td>
                    <?php endif; ?>

                    <?php if (isset($_COOKIE["showCompleteTasks"]) && $_COOKIE["showCompleteTasks"] == 0 && $item["completion_date"] == null): ?>
                        <td class="task__select">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden task__checkbox"
                                       name="<?= $item["project_id"] ?>" type="checkbox" value="<?= $item["id"]; ?>"
                                        <?= $item["completion_date"] !== null ? "checked" : ""; ?>>
                                <span class="checkbox__text"><?= htmlspecialchars($item["name"]); ?></span>
                            </label>
                        </td>

                        <td class="task__file">
                            <a class="download-link <?= $item["file"] ? "" : "hidden"; ?>"
                               href="/<?= $item["file"]; ?>"><?= htmlspecialchars($item["file"]); ?></a>
                        </td>

                        <td class="task__date"><?= htmlspecialchars($item["term_date"]); ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</main>

<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $item): ?>
            <li class="main-navigation__list-item  <?= $item["id"] == $selectedProjectId ? "main-navigation__list-item--active" : "" ?>">
                <a class="main-navigation__list-item-link" href="index.php?project_id=<?=$item["id"]?>"><?=$item["name"]?></a>
                <span class="main-navigation__list-item-count"><?= getTasksCountByProjectName($item["name"], $tasks) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button open-modal"
        href="javascript:;" target="project_add">Добавить проект</a>
</section>


<main class="content__main">
<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.html" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= isset($showCompleteTasks) == 1 ? "checked" : "" ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
<?php if (isset($tasksByProject)): ?>
    <?php foreach ($tasksByProject as $key => $item): ?>
    <tr class="tasks__item task <?= $item["completion_date"] !== NULL ? "task--completed" : "" ?>
    <?= getHoursCountTillTheDate($item["term_date"]) <= 24 && $item["completion_date"] !== NULL ? "task--important" : "" ?>">
        <?php if ($showCompleteTasks == 1): ?>
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                <span class="checkbox__text"><?=$item["name"];?></span>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link <?=$item["file"] ? "" : "hidden";?>" href="/<?=$item["file"];?>"><?=$item["file"];?></a>
        </td>

        <td class="task__date"><?=$item["term_date"];?></td>
        <?php endif; ?>

        <?php if ($showCompleteTasks == 0 && $item["completion_date"] == NULL): ?>
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                <span class="checkbox__text"><?=htmlspecialchars($item["name"]);?></span>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link <?=$item["file"] ? "" : "hidden";?>" href="/<?=$item["file"];?>"><?=$item["file"];?></a>
        </td>

        <td class="task__date"><?=htmlspecialchars($item["term_date"]);?></td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>

</main>

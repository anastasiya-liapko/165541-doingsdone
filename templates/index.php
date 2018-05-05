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
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= $show_complete_tasks == 1 ? "checked" : "" ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $key => $item): ?>
    <tr class="tasks__item task <?= $item["done"] == "Да" ? "task--completed" : "" ?>
    <?= check_important_tasks($item["date"]) <= 24 && $item["done"] !== "Да" ? "task--important" : "" ?>">
        <?php if ($show_complete_tasks == 1): ?>
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                <span class="checkbox__text"><?=$item["task"];?></span>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link" href="#">Home.psd</a>
        </td>

        <td class="task__date"><?=$item["date"];?></td>
        <?php endif; ?>

        <?php if ($show_complete_tasks == 0 && $item["done"] == "Нет"): ?>
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1">
                <span class="checkbox__text"><?=htmlspecialchars($item["task"]);?></span>
            </label>
        </td>

        <td class="task__file">
            <a class="download-link" href="#">Home.psd</a>
        </td>

        <td class="task__date"><?=htmlspecialchars($item["date"]);?></td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>

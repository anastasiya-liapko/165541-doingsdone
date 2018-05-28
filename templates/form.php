<div class="modal" <?= isset($errors) ? "" : "hidden"; ?> id="task_add">
    <button class="modal__close" type="button" name="button" href="/">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" action="index.php" enctype="multipart/form-data" method="post">

        <div class="form__row">
            <?php $classname = isset($errors["name"]) ? "form__input--error" : "";
            $value = isset($formData["name"]) ? $formData["name"] : ""; ?>

            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?= $classname; ?>" type="text" name="name" id="name" value="<?= $value; ?>"
                   placeholder="Введите название">

            <p class="form__message"><?= isset($errors["name"]) ? "Заполните это поле" : ""; ?></p>
        </div>

        <div class="form__row">
            <?php $classname = isset($errors["project"]) ? "form__input--error" : "";
            $value = isset($formData["project"]) ? $formData["project"] : ""; ?>

            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?= $classname; ?>" name="project" id="project">
                <?php foreach ($projects as $i => $item): ?>
                    <option value="<?= $item["id"] ?>"><?= $item["name"] == "Входящие" ? "" : $item["name"]; ?></option>
                <?php endforeach; ?>
            </select>

            <p class="form__message"><?= isset($errors["project"]) ? "Заполните это поле" : ""; ?></p>
        </div>

        <div class="form__row">
            <?php $classname = isset($errors["date"]) ? "form__input--error" : "";
            $value = isset($formData["date"]) ? $formData["date"] : ""; ?>

            <label class="form__label" for="date">Срок выполнения</label>


            <input class="form__input form__input--date <?= $classname; ?>" type="text" name="date" id="date"
                   value="<?= $value; ?>"
                   placeholder="Введите дату и время">

            <p class="form__message"><?= isset($errors["date"]) ? "Введите дату и время в формате ГГГГ-ММ-ДД ЧЧ:ММ" : ""; ?></p>
        </div>

        <div class="form__row">
            <label class="form__label" for="preview">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="task" value="Добавить">
        </div>
    </form>
</div>

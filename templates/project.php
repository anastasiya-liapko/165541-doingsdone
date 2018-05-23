<div class="modal"  <?=isset($errors) ? "" : "hidden"?> id="project_add">
  <button class="modal__close" type="button" name="button">Закрыть</button>

  <h2 class="modal__heading">Добавление проекта</h2>

  <form class="form" action="index.php" enctype="multipart/form-data" method="post">
    <div class="form__row">
    <?php $classname = isset($errors["name"]) ? "form__input--error" : "";
    $value = isset($formsData["name"]) ? $formsData["name"] : ""; ?>
      <label class="form__label" for="project_name">Название <sup>*</sup></label>

      <input class="form__input <?= $classname ?>" type="text" name="name" id="project_name" value="<?= $value ?>" placeholder="Введите название проекта">

      <p class="form__message"><?=isset($errors["name"]) ? $errors["name"] : ""?></p>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="project" value="Добавить">
    </div>
  </form>
</div>

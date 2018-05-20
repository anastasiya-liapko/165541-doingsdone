<div class="modal" style="margin-top: -10%; margin-left: -3%;">
  <!-- <button class="modal__close" type="button" name="button">Закрыть</button> -->

  <h2 class="modal__heading">Вход на сайт</h2>

  <form class="form" name="autorization" action="index.php" enctype="multipart/form-data" method="post">
    <div class="form__row">
    <?php $classname = isset($errors["email"]) ? "form__input--error" : "";
    $value = isset($formsData["email"]) ? $formsData["email"] : ""; ?>
      <label class="form__label" for="email">E-mail <sup>*</sup></label>

      <input class="form__input <?=$classname;?>" type="text" name="email" id="email" value="<?=$value;?>" placeholder="Введите e-mail">

      <p class="form__message"><?=isset($errors["email"]) ? $errors["email"] : ""?></p>
    </div>

    <div class="form__row">
    <?php $classname = isset($errors["password"]) ? "form__input--error" : "";
    $value = isset($formsData["password"]) ? $formsData["password"] : ""; ?>
      <label class="form__label" for="password">Пароль <sup>*</sup></label>

      <input class="form__input <?=$classname;?>" type="password" name="password" id="password" value="<?=$value;?>" placeholder="Введите пароль">

      <p class="form__message"><?=isset($errors["password"]) ? $errors["password"] : ""?></p>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="autorization" value="Войти">
    </div>
  </form>
</div>

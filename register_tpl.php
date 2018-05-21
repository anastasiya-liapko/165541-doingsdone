<section class="content__side">
          <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

          <a class="button button--transparent content__side-button" href="#">Войти</a>
        </section>

        <main class="content__main">

          <h2 class="content__main-heading">Регистрация аккаунта</h2>

          <form class="form" action="register.php" enctype="multipart/form-data" method="post">
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

                <input class="form__input <?=$classname;?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">

                <p class="form__message"><?=isset($errors["password"]) ? "Введите пароль" : ""?></p>
            </div>

            <div class="form__row">
            <?php $classname = isset($errors["name"]) ? "form__input--error" : "";
            $value = isset($formsData["name"]) ? $formsData["name"] : ""; ?>
                <label class="form__label" for="name">Имя <sup>*</sup></label>

                <input class="form__input <?=$classname;?>" type="text" name="name" id="name" value="<?=$value;?>" placeholder="Введите имя">

                <p class="form__message"><?=isset($errors["name"]) ? "Введите имя" : ""?></p>
            </div>

            <div class="form__row form__row--controls">
                <p class="error-message"><?=count($errors) ? "Пожалуйста, исправьте ошибки в форме" : ""?></p>

                <input class="button" type="submit" name="register" value="Зарегистрироваться">
            </div>
        </form>
        </main>

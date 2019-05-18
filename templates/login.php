<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($equipment_type as $category): ?>
            <!--заполните этот список из массива категорий-->
            <li class="nav__item">
                <a href="pages/all-lots.html"> <?=$category['name']?> </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<form class="form container <?php if (!empty($errors)) {print('form--invalid');} ?>" action="login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?php if (!empty($errors['email'])) {print('form__item--invalid');}?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="login[email]" placeholder="Введите e-mail" value="<?=$login['email'];?>">
        <span class="form__error"><?=$errors['email']?></span>
    </div>
    <div class="form__item form__item--last <?php if (!empty($errors['password'])) {print('form__item--invalid');}?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="login[password]" placeholder="Введите пароль" value="<?=$login['password'];?>">
        <span class="form__error"><?=$errors['password']?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
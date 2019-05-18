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

<form class="form container" action="sign_up.php" method="post" autocomplete="off"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if (!empty($errors['email'])) {print('form__item--invalid');}?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="user[email]" placeholder="Введите e-mail" value="<?=$user['email'];?>">
        <span class="form__error"><?=$errors['email']?></span>
    </div>
    <div class="form__item <?php if (!empty($errors['password'])) {print('form__item--invalid');}?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="user[password]" placeholder="Введите пароль" value="<?=$user['password'];?>">
        <span class="form__error"><?=$errors['password']?></span>
    </div>
    <div class="form__item <?php if (!empty($errors['name'])) {print('form__item--invalid');}?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="user[name]" placeholder="Введите имя" value="<?=$user['name'];?>">
        <span class="form__error"><?=$errors['name']?></span>
    </div>
    <div class="form__item <?php if (!empty($errors['message'])) {print('form__item--invalid');}?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="user[message]" placeholder="Напишите как с вами связаться"><?=$user['message'];?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
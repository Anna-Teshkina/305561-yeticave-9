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

<form class="form form--add-lot container <?php if (!empty($errors)) {print('form--invalid');} ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <div class="form__item <?php if (!empty($errors['name'])) {print('form__item--invalid');}?>"> <!-- form__item--invalid -->
      <label for="lot-name">Наименование <sup>*</sup></label>
      <input id="lot-name" type="text" name="lot[name]" placeholder="Введите наименование лота" value="<?=$lot['name'];?>">
      <span class="form__error"><?=$errors['name']?></span>
    </div>
    <div class="form__item <?php if (!empty($errors['category'])) {print('form__item--invalid');}?>">
      <label for="category">Категория <sup>*</sup></label>
      <select id="category" name="lot[category]">
        <option value="">Выберите категорию</option>
        <?php foreach ($equipment_type as $category): ?>
            <option <? if ($category['name'] == $lot['category']) { print("selected"); } ?> value="<?=$category['id'];?>"> <?=$category['name'];?> </option>
        <?php endforeach; ?>
      </select>
      <span class="form__error"><?=$errors['category']?></span>
    </div>
  </div>
  <div class="form__item form__item--wide <?php if (!empty($errors['message'])) {print('form__item--invalid');}?>">
    <label for="message">Описание <sup>*</sup></label>
    <textarea id="message" name="lot[message]" placeholder="Напишите описание лота"><?=$lot['message'];?></textarea>
    <span class="form__error"><?=$errors['message']?></span>
  </div>
  <div class="form__item form__item--file <?php if (!empty($errors['file'])) {print('form__item--invalid');}?>">
    <label>Изображение <sup>*</sup></label>
    <div class="form__input-file">
      <input class="visually-hidden" type="file" id="lot-img" name="file" value="<?=$file;?>">
      <label for="lot-img">
        Добавить
      </label>
    </div>
    <span class="form__error"><?=$errors['file']?></span>
  </div>
  <div class="form__container-three">
    <div class="form__item form__item--small <?php if (!empty($errors['rate'])) {print('form__item--invalid');}?>">
      <label for="lot-rate">Начальная цена <sup>*</sup></label>
      <input id="lot-rate" type="text" name="lot[rate]" placeholder="0" value="<?=$lot['rate'];?>">
      <span class="form__error"><?=$errors['rate']?></span>
    </div>
    <div class="form__item form__item--small <?php if (!empty($errors['step'])) {print('form__item--invalid');}?>">
      <label for="lot-step">Шаг ставки <sup>*</sup></label>
      <input id="lot-step" type="text" name="lot[step]" placeholder="0" value="<?=$lot['step'];?>">
      <span class="form__error"><?=$errors['step']?></span>
    </div>
    <div class="form__item <?php if (!empty($errors['date'])) {print('form__item--invalid');}?>">
      <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
      <input class="form__input-date" id="lot-date" type="text" name="lot[date]" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=$lot['date'];?>">
      <span class="form__error"><?=$errors['date']?></span>
    </div>
  </div>

  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Добавить лот</button>
</form>
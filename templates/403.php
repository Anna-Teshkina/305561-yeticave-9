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
<section class="lot-item container">
        <h2> 403 </h2>
        <p> Страница доступна только для авторизированных пользователей </p>
</section>
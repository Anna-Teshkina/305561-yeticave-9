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
    <h2>404 Страница не найдена</h2>
    <p>Данной страницы не существует на сайте.</p>
</section>
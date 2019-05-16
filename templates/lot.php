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
    <h2><?=htmlspecialchars($ad['lot_name']);?></h2>
    <div class="lot-item__content">
    <div class="lot-item__left">
        <div class="lot-item__image">
        <img src="../<?=htmlspecialchars($ad['img']);?>" width="730" height="548" alt="<?=htmlspecialchars($ad['lot_name']);?>">
        </div>
        <p class="lot-item__category">Категория: <span><?=$ad['cat_name'];?></span></p>
        <p class="lot-item__description"> <?=htmlspecialchars($ad['description']);?> </p>
    </div>
    <div class="lot-item__right">
        <div class="lot-item__state">
        <div class="lot-item__timer timer  <?if (is_timer_finishing($ad['date_end'])) { print("timer--finishing"); }?>">
            <?=get_time_left($ad['date_end']);?>
        </div>
        <div class="lot-item__cost-state">
            <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost">
                    <?=htmlspecialchars(number_format(get_max_element($ad['price_start'],$bets, 'user_price'),0, ',', ' '));?> p
                </span>
            </div>
            <div class="lot-item__min-cost">
            Мин. ставка <span> <?=htmlspecialchars(number_format($ad['price_start'],0, ',', ' '))?> р</span>
            </div>
        </div>
        <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
            <p class="lot-item__form-item form__item form__item--invalid">
            <label for="cost">Ваша ставка</label>
            <input id="cost" type="text" name="cost" placeholder="12 000">
            <span class="form__error">Введите наименование лота</span>
            </p>
            <button type="submit" class="button">Сделать ставку</button>
        </form>
        </div>
        <div class="history">
        <h3>История ставок (<span><?=count($bets)?></span>)</h3>
        <table class="history__list">
            <?php foreach ($bets as $bet): ?>
                <tr class="history__item">
                    <td class="history__name"><?=htmlspecialchars($bet['user_name']);?></td>
                    <td class="history__price"><?=htmlspecialchars(number_format($bet['user_price'], 0, ',', ' '));?> р</td>
                    <td class="history__time"><?=get_edit_date($bet['date'])?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </div>
    </div>
</section>
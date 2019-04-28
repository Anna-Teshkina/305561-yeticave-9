<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        
        <?php foreach ($equipment_type as $category): ?>
            <!--заполните этот список из массива категорий-->
            <li class="promo__item promo__item--<?=$category['img_key']?>">
                <a class="promo__link" href="pages/all-lots.html"> <?=$category['category_name']?> </a>
            </li>
        <?php endforeach; ?>
        
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        
        <?php foreach ($ad as $lot): ?>
        <?php print_r($lot);?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=htmlspecialchars($lot['img']);?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"> <?=$lot['category_name'];?> </span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"> <?=htmlspecialchars($lot['lot_name']);?> </a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"> <?=htmlspecialchars(editNumber($lot['price_start']))?> </span>
                            <span class="lot__cost"> <?=htmlspecialchars(editNumber($lot['user_price']))?> <!--b class="rub">р</b--></span>
                        </div>
                        <div class="lot__timer timer <?if (is_timer_finishing($lot['date_end'])) { print("timer--finishing"); }?>">
                            <?=time_left($lot['date_end']);?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
        
    </ul>
</section>
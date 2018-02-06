<div class="basket_blok" id="sidebar">
    <p class="title_reviews">Корзина</p>

    <p class="basket_blok_text">Ваш заказ на сумму <b><?php echo $sum; ?>
            + <?php echo $partner->delivery_cost; ?> <?php echo City::getMoneyKod(); ?></b> за доставку</p>
    <?php if ($sum < $partner->min_sum) { ?>
        <p class="date basket_blok_text">Не
            хватает <?php echo $partner->min_sum - $sum; ?> <?php echo City::getMoneyKod(); ?> ;?>до минимальной суммы
            заказа</p>
    <?php } ?>
    <a href="/cart"><img src="/images/go_basket_button.png"></a>
</div>
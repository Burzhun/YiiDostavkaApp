<img src="/images/bascet-img.png" width="64" height="65">
<? if ($partner->delivery_cost > 0 && $partner->free_delivery_sum > 0 && $sum >= $partner->free_delivery_sum) {
    $partner->delivery_cost = 0;
} ?>
<p>ваш заказ на сумму
    <span><?php echo $sum; ?></span><span> + </span><span><?php echo $partner->delivery_cost; ?> <?php echo City::getMoneyKod(); ?> </span>
    за доставку<br>
    <?php if ($sum < $partner->min_sum) { ?>
        <span
            class="min-price-text">Не хватает <?php echo $partner->min_sum - $sum; ?> <?php echo City::getMoneyKod(); ?>
            до минимальной суммы заказа</span>
    <?php } ?>
</p>
<a href="javascript:void(0);" id="bascet-button"></a>
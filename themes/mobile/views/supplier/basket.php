<img src="/images/bascet-img.png" width="64" height="65">
<p>ваш заказ на сумму
    <span><?php echo $sum; ?></span><span> + </span><span><?php echo $partner->delivery_cost; ?> <?php echo City::getMoneyKod($this->domain); ?></span>
    за доставку<br>
    <?php if ($sum < $partner->min_sum) { ?>
        <span
            class="min-price-text">Не хватает <?php echo $partner->min_sum - $sum; ?> <?php echo City::getMoneyKod($this->domain); ?>
            до минимальной суммы заказа</span>
    <?php } ?>
</p>
<a href="javascript:void(0);" id="bascet-button"></a>
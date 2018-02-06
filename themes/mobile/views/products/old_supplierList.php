<ul class="food_list">
    <?php if (empty($model)) { ?>
        <b>К сажалению ничего не найдено</b>
    <?php } ?>
    <?php foreach ($model as $m) { ?>
        <li class="" style="display: list-item; ">
            <a href="/restorany/<?php echo $m->tname ?>" class="full_link">
                <div class="logo_preview">
                    <?php if ($m->img) { ?>
                        <img src="/upload/partner/<?php echo $m->img ?>"
                             alt="доставка еды, махачкала, дагестан, <?php echo $m->name ?>"
                             title="<?php echo $m->name ?>, доставка еды, махачкала, дагестан"/>
                    <?php } else { ?>
                        <img src="/images/default.jpg"
                             alt="доставка еды, махачкала, дагестан, <?php echo $m->name ?>"
                             title="<?php echo $m->name ?>, доставка еды, махачкала, дагестан"/>
                    <?php } ?>
                </div>
                <div class="info">
                    <h3><?php echo $m->name ?></h3>
                    <span class="categories"><p><?php echo $m->text ?></p></span>
                    <ul>
                        <li>Минимальная сумма заказа<br/>

                            <p class="price"><?php echo $m->min_sum ? $m->min_sum . ' <?php echo City::getMoneyKod() ;?>' : 'Нет' ?></p>
                        </li>
                        <li>Стоимость достаки<br/>

                            <p class="price"><?php echo $m->delivery_cost ? $m->delivery_cost . ' <?php echo City::getMoneyKod() ;?>' : 'Бесплатно' ?></p>
                        </li>
                        <li>Среднее время доставки<br/>

                            <p class="price"><?php echo $m->delivery_duration ?></p></li>
                    </ul>
                </div>
            </a>
        </li>
    <?php } ?>
</ul>
<a class="up" href="javascript:void(0)"><img src="/images/up.png"></a>
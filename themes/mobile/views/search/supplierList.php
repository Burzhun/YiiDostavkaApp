<?php
/**
 * @var Partner[] $model
 */
?>
<ul class="food_list">
    <?php if (empty($model)) { ?>
        <b>К сажалению ничего не найдено</b>
    <?php } ?>
    <?php foreach ($model as $m) { ?>
        <?php if (Yii::app()->request->cookies['open_rest']->value == 'opened') {
            $warning = $m->isClosed();
        }
        ?>
        <li class="" style="display: list-item; ">
            <a href="/restorany/<?php echo $m->tname ?>" class="full_link">
                <div id="name-cafe">
                    <div id="cafe-logo">
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
                    <div id="cafe-info">
                        <h3><?php echo $m->name ?></h3>

                        <p><?php echo $m->text ?></p>
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
                        <?php if ($m->isClosed()) { ?>
                            <div class="cafe-warning">
                                Ресторан откроется через <span><?= $m->howLongWill() ?></span>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </a>
        </li>
    <?php } ?>
</ul>
<div id="page-up" class="up" href="javascript:void(0)"><a href="#">
        <img src="/images/up_img.png" class="page-up"></a>
</div>
<script>
    $(document).ready(function () {
        $(".page-up").click(function () {
            $('html, body').animate({scrollTop: 0}, 500);
        });
    });
</script>
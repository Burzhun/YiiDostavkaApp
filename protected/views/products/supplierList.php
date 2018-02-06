<?php
/**
 * @var ProductsController $this
 * @var Partner[] $model
 */
?>
<ul class="food_list">
    <? if (empty($model)) { ?>
        <b>К сожалению ничего не найдено</b>
    <? } ?>
    <? foreach ($model as $m) {
        $isClosed = $m->isClosed();
        if (isset(Yii::app()->request->cookies['open_rest']) && Yii::app()->request->cookies['open_rest']->value == 'opened' && $isClosed) {
            continue;
        } ?>

        <li class="<? if ($m->soon_opening) {
            echo "soon_opening";
        } ?>" style="display: list-item; ">
            <? if ($m->action) { ?>
                <img src="/images/action.png"
                     style="position: absolute;left: -4px;top:-4px;z-index: 3000;pointer-events: none;">
            <? } ?>
            <a href="/restorany/<? echo strtolower($m->tname) ?>" class="full_link">
                <div id="name-cafe">
                    <div id="cafe-logo" style="min-width:110px;min-height:113px;text-align:center;">
                        <img style="max-width:100px;max-height:113px;" src="<? if ($m->img) {
                            echo "/upload/partner/" . $m->img;
                        } else {
                            echo "/images/default.jpg";
                        } ?>" alt="<? echo $m->name ?>"
                             title="<? echo $m->name ?>"/>
                    </div>
                    <div id="cafe-info">
                        <h3><? echo $m->name ?></h3>

                        <p><? echo $m->text ?></p>
                        <ul>
                            <li>Минимальная сумма заказа<br/>
                                <? if ($this->domain->id == 1 && false){ ?>
                                <? $partner_rayon = PartnerRayon::model()->find("rayon_id=" . Yii::app()->request->cookies['rayon']->value . " and partner_id=" . $m->id); ?>
                                <p class="price"><?= $partner_rayon->min_sum . ' ' . City::getMoneyKod(); ?></p></li>
                            <? } else { ?>
                                <p class="price"><? echo $m->min_sum ? $m->min_sum . ' ' . City::getMoneyKod() : 'Нет' ?></p>
                                </li>
                            <? } ?>
                            <li>Стоимость доставки<br/>

                                <p class="price"><? echo $m->delivery_cost ? $m->delivery_cost . ' ' . City::getMoneyKod() : 'Бесплатно' ?></p>
                            </li>
                            <li>Среднее время доставки<br/>
                                <p class="price"><? echo $m->delivery_duration ?></p>
                            </li>
                            <? if ($m->use_kassa) { ?>
                                <li style="margin-top:14px;">
                                    Оплата картой онлайн
                                    <p>
                                        <img class="visa_mastercard_logo" src="/images/visa-master.png">
                                    </p>
                                </li>
                            <? } ?>
                        </ul>
                        <?  if ($isClosed) { ?>
                            <div class="cafe-warning">
                                Ресторан откроется через <span><?= $m->howLongWill() ?></span>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </a>
        </li>
    <? } ?>
</ul>
<div id="page-up" class="up" href="javascript:void(0)">
    <a href="#">
        <img src="/images/up_img.png" class="page-up">
    </a>
</div>
<div id="page-up" class="up" href="javascript:void(0)" style="left:5%">
    <a href="#">
        <img src="/images/up_img.png" class="page-up" style="left:0">
    </a>
</div>
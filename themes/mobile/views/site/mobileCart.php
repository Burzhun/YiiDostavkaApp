<div class="topNav">
    <a href="" class="cartIcon">
        <span><?= CartItem::countCartItem(); ?></span>
    </a>
    <? /*<a href="" class="shortLIcon"></a>
	<a href="" class="searchIcon"></a>*/ ?>

    <a href='#' class="backLink">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/arrowBack.png" alt=""> Назад
    </a>
</div>

<main class="content">
    <div class="padding30 mainBox mainBoxOrder cart">
        <? $sum = 0; ?>
        <? if (!empty($cart)) { ?>
            <ul class="orderList">
                <? foreach ($cart as $c) { ?>
                    <li>
                        <div class="orderListLeft">
                            <a href="#"><?= $c->goods->name; ?></a>
                            <span class="orderlistPrice"><?= $c->price; ?> р.</span>
                        </div>
                        <div class="orderListRight">
                            <div class="count-block">
                                <a class="minus" href="#" data-id="<? echo $c->id; ?>">-</a>

                                <p id="num_<? echo $c->id; ?>"><? echo $c->quality; ?></p>
                                <a class="plus" href="#" data-id="<? echo $c->id; ?>">+</a>
                            </div>
                        </div>
                    </li>
                    <? $sum += $c->price * $c->quality; ?>
                <? } ?>
            </ul>
        <? } ?>

        <div class="orderItog">
            <div class="orderListTop">
                <span class="orderlistPrice">Сумма заказа:</span>
                <span class="orderlistCoutn"><?= $sum; ?> р.</span>
            </div>
            <div class="orderListBottom">
                <span class="orderlistPrice">Доставка:</span>
                <span
                    class="orderlistCoutn"><? echo $partner->delivery_cost > 0 ? $partner->delivery_cost . " ".City::getMoneyKod() : "бесплатно"; ?></span>
            </div>

            <div class="borderDotted"></div>

            <span class='itogHead'>ИТОГО: </span>
            <span class='itogHeadCount'><?= $sum + $partner->delivery_cost; ?> р.</span>
        </div>
        <div style='clear:both'></div>
        <br>
        <br>
        <br>
        <center>
            <a href="#" class="orderZakaz">Оформить заказ</a>
        </center>
        <br>
        <br>
        <br>

        <div class="footerBox"></div>
    </div>
</main><!-- .content -->
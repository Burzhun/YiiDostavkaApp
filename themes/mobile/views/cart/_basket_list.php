<? if (!empty($cart)) { ?>
    <ul class="orderList">
        <? foreach ($cart as $c) { ?>
            <li id="cartItem_<?= $c->goods->id; ?>">
                <? /*<a href="javascript:void(0);" class="shopImg">
							<img src="/upload/goods/goods_1453.png" style="max-width:70px;">
						</a>*/ ?>
                <div class="orderListLeft">
                    <a href="javascript:void(0);"><?= $c->goods->name; ?></a>
                            <span class="orderlistPrice"><span
                                    id="item_price_<?= $c->goods->id ?>"><? echo $c->quality * $c->price; ?></span> <?php echo City::getMoneyKod($this->domain); ?></span>
                </div>
                <div class="orderListRight">
                    <div class="count-block">
                        <a href="#" onclick="minusProduct(<? echo $c->goods->id; ?>);return false;"
                           data-goodid="<?= $c->goods->id ?>">-</a>

                        <p id="num_<?= $c->goods->id ?>"><? echo $c->quality; ?></p>
                        <a href="#" onclick="plusProduct(<? echo $c->goods->id; ?>);return false;"
                           data-goodid="<?= $c->goods->id ?>">+</a>
                    </div>
                </div>
            </li>
        <? } ?>
    </ul>
    <span id="empty_basket" style="display:none;">Корзина пуста</span>
<? } else { ?>
    Корзина пуста
<? } ?>
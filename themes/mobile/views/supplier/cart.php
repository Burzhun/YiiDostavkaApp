<div id="pop-up-bascet">
    <div class="pop-header">
        <div id="close-pop-up1"></div>
        <img src="/images/bascet-img.png" width="64" height="65">

        <h3>Ваша корзина:</h3>
        <a class="clean-cart">очистить корзину</a>
    </div>
    <div class="popup-bascet ">
        <? if (!empty($cart)) { ?>
            <form action="" method="get" class="scroll-pane form-order">
                <ul class="bascet-list">
                    <? foreach ($cart as $c) { ?>
                        <li id="cart_item_<? echo $c->goods->id; ?>">
                            <label style='width:265px;'>
                                <img src="/images/close-product.png" width="15" height="16" class="close-list"
                                     onclick="deleteProduct(<? echo $c->goods->id; ?>)">
                                <img src="<?= $c->goods->image ?>" style="height:70px; margin-right:5px">

                                <div class='cart_item_name'><? echo $c->goods->name; ?></div>
                            </label>

                            <div class="update_count_product_container popup-update">
								<span class="minusupdate" onclick="minusProduct(<? echo $c->goods->id; ?>)">
									<img src="/images/basket_minus.png" width="16" height="16" style="padding:0">
								</span>
                                <input onchange="editProduct(<? echo $c->goods->id; ?>)"
                                       id="form_number_<? echo $c->goods->id; ?>" class="new_count" type="text"
                                       value="<? echo $c->quality; ?>" style="width:30px" maxlength="2">
								<span class="plusupdate" onclick="plusProduct(<? echo $c->goods->id; ?>)">
									<img src="/images/basket_plus.png" width="16" height="16" style="padding:0">
								</span>
                            </div>
                            <div class="price-in-bascet">
                                <label><? echo $c->price; ?> <?php echo City::getMoneyKod(); ?></label><br><label
                                    id="item_price_<? echo $c->goods->id; ?>"><b><? echo $c->quality * $c->price; ?> <?php echo City::getMoneyKod(); ?></b></label>
                            </div>
                        </li>
                    <? } ?>
                </ul>
            </form>
        <? } ?>
    </div>
    <div class="popup-o-rightside">
        <p>Сумма заказа: <span id="sum_cart"><? echo $sum; ?></span> <?php echo City::getMoneyKod(); ?></p>

        <p>Стоимость доставки:
            <span><? echo $partner->delivery_cost > 0 ? $partner->delivery_cost . " ".City::getMoneyKod() : "бесплатно"; ?></span></p>
        <hr>
        <p class="in-total">Итого: <span
                id="sum_itogo"><? echo $sum + $partner->delivery_cost; ?></span> <?php echo City::getMoneyKod(); ?></p>

        <div id="popup_order_errors" style="color:red;padding:7px 0 7px 0;"></div>

        <? if (Yii::app()->user->role == User::USER || Yii::app()->user->role == User::PARTNER) { ?>
            <p class='oBal'>Заказав, вы получите: <span><img src='/images/iconBal.png'><span
                        id="bonus_sum"><? echo round(($sum + $partner->delivery_cost) * User::BONUS_PROCENT_FROM_ORDER, 1); ?></span> баллов</span>
            </p>
        <? } ?>

        <a href="javascript:void(0);" class="checkout" onclick="yaCounter31131226.reachGoal('BY');return true;"></a>
    </div>
</div>


<script type="text/javascript">
    function plusProduct(p_id) {
        $.ajax({
            url: '<? echo CController::createUrl('/cart/plusProduct');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: {"product": p_id},
            success: function (data) {
                $("#form_number_" + p_id).val(data['count']);
                $("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod() ;?></b>");
                $("#sum_cart").html(data['sumCart']);
                $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
            }
        });
    }

    function minusProduct(p_id) {
        $.ajax({
            url: '<? echo CController::createUrl('/cart/minusProduct');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: {"product": p_id},
            success: function (data) {
                if (data['empty']) {
                    $("#pop-up-bascet").remove();
                    $("#bascet").html("");
                    $("#parent_popup").css("display", "none");
                } else {
                    $("#form_number_" + p_id).val(data['count']);
                    $("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod() ;?></b>");
                    $("#sum_cart").html(data['sumCart']);
                    $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
                }
            }
        });
    }

    function editProduct(p_id) {
        $.ajax({
            url: '<? echo CController::createUrl('/cart/editProduct');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: {"product": p_id, "count": $("#form_number_" + p_id).attr('value')},
            success: function (data) {
                if (data['empty']) {
                    $("#pop-up-bascet").remove();
                    $("#bascet").html("");
                    $("#parent_popup").css("display", "none");
                } else {
                    $("#form_number_" + p_id).val(data['count']);
                    $("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod() ;?></b>");
                    $("#sum_cart").html(data['sumCart']);
                    $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
                }
            }
        });
    }

    function deleteProduct(p_id) {
        $.ajax({
            url: '<? echo CController::createUrl('/cart/deleteProduct');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: {"product": p_id},
            success: function (data) {
                if (data['empty']) {
                    $("#pop-up-bascet").remove();
                    $("#bascet").html("");
                    $("#parent_popup").css("display", "none");
                } else {
                    $("#sum_cart").html(data['sumCart']);
                    $("#cart_item_" + p_id).remove();
                    $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
                }
            }
        });
    }


    $(document).on("click", ".clean-cart", function (event) {
        $.ajax({
            url: '<? echo CController::createUrl('/cart/deleteAllProduct');?>',
            type: "post",
            //dataType:"json",
            //cache:false,
            success: function (data) {
                if (data != false) {
                    $("#pop-up-bascet").remove();
                    $("#bascet").html("");
                    $("#parent_popup").css("display", "none");
                }
            }
        });
    });

</script>

<script src="/js/swiper.jquery.min.js"></script>
<link rel="stylesheet" href="/css/swiper.min.css">
<div class="topNav">
    <? /*<a href="" class="cartIcon">
		<span>1</span>
	</a>
	<a href="" class="shortLIcon"></a>
	<a href="" class="searchIcon"></a>*/ ?>

    <a href='javascript:history.go(-1);' class="backLink">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/arrowBack.png" alt=""> Корзина
    </a>
</div>
<?
$warning = $partner=="" ? false : $partner->isClosed();

$DaysString = Partner::getWorkDays($partner->id);
$DaysString = str_replace('  ', ' ', $DaysString);
$DaysString = Partner::getWorkDaysString($DaysString);
?>
<main class="content">
    <div class="padding30 mainBox mainBoxOrder cart">
        <div id="popup_order_errors"></div>
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
                                <a href="#" class="minusProduct"
                                   onclick="minusProduct(<? echo $c->goods->id; ?>);return false;"
                                   param_id="<?= $c->goods->id; ?>"
                                   param_name="<?= $c->goods->name; ?>" param_category="<?= $c->goods->tag->name; ?>"
                                   param_price="<?= $c->price; ?>" param_num="<?= $c->quality; ?>"
                                   data-goodid="<?= $c->goods->id ?>">-</a>

                                <p id="num_<?= $c->goods->id ?>"><? echo $c->quality; ?></p>
                                <a href="#" class="plusProduct"
                                   onclick="plusProduct(<? echo $c->goods->id; ?>);return false;"
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
        <? if (CartItem::hasDrinks()) { ?>
            <div class="more-cola">
                <? $default_image = 'default_' . $this->domain->id . '.jpg'; ?>
                <? $drinks = Goods::getDrinks($partner->id); ?>
                <? if ($drinks) { ?>
                    <div class="drink-title">А еще у нас есть напитки ;)</div>
                    <div class="container swiper-container">
                        <div class="swiper-wrapper">
                            <? foreach ($drinks as $drink) { ?>
                                <li class="swiper-slide">
                                    <span class="prev"></span>
                                    <div class="more-cola-img">
                                        <?php if ($drink->img) { ?>
                                            <img class="updateImg updateImgSuccess<?= $drink->id ?>"
                                                 src="/upload/goods/<?php echo $drink->img; ?>">
                                        <?php } else { ?>
                                            <?php if (!empty($partner->img)) { ?>
                                                <img class="updateImg"
                                                     src="/upload/partner/<?php echo $partner->img; ?>">
                                            <?php } else { ?>
                                                <img src="/images/<?= $default_image; ?>">
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="more-cola-buttons">
                                        <div class="more-cola-name"><?= $drink->name ?></div>
                                        <div class="more-cola-price">
                                            <b><?php echo $drink->getOrigPrice($this->domain->id); ?></b> руб.
                                        </div>


                                        <a class="button_order" href="javascript:void(0)"
                                           onclick="orderDrink(<?= $drink->id ?>)" param_name="<?= $drinks->name; ?>"
                                           id_param="<?= $drinks->name; ?>" price="<?= $drinks->price; ?>">Заказать</a>

                                    </div>
                                    <span class="next"></span>
                                </li>
                            <? } ?>
                        </div>
                    </div>

                <? } ?>
            </div>

        <? } ?>
        <div class="orderItog">
            <div class="orderListTop">
                <span class="orderlistPrice">Сумма заказа:</span>
                <span class="orderlistCoutn"><span
                        id="sum_cart"><? echo $sum; ?></span> <?php echo City::getMoneyKod($this->domain); ?></span>
            </div>
            <div class="orderListBottom">
                <? $delivery_cost = $partner->delivery_cost; ?>
                <? if ($partner->free_delivery_sum && $sum >= $partner->free_delivery_sum) {
                    $delivery_cost = 0;
                } ?>
                <span class="orderlistPrice">Доставка:</span>
                <span
                    class="orderlistCoutn delivery_cost"><? echo $delivery_cost; ?><?php echo City::getMoneyKod($this->domain); ?></span>
            </div>
            <div class="borderDotted"></div>
            <span class='itogHead'>ИТОГО: </span>
            <span class='itogHeadCount'><span
                    id="sum_itogo"><? echo $sum + $delivery_cost; ?></span> <?php echo City::getMoneyKod($this->domain); ?></span>
        </div>
        <div style='clear:both'></div>
        <? if ($partner->use_kassa) { ?>
            <div>
                <div class="itogHead" style="margin: 6px 17px;">Оплата</div>
                <br>

                <select id="payment_selector" style="margin-left:17px;">
                    <option value="cash">Наличными</option>
                    <option value="card">Картой онлайн</option>

                </select>
                <script>
                    $(document).ready(function () {
                        $("#payment_selector").click(function (e) {

                        });
                    });
                </script>

            </div>
        <? } ?>
        <br>
        <? if ($warning) { ?>
            <div class="ex">
                К сожалению, <span>«<?= $partner->name ?>»</span> в настоящее время заказы не
                принимает.<br>Возможен только предварительный заказ.</p>
            </div>
        <? } ?>
        <br>
        <? $style = ""; ?>
        <? if (!($partner->free_delivery_sum - $sum >= 0 && $partner->free_delivery_sum - $sum < 151)) { ?>
            <? $style = "display: none"; ?>
        <? } ?>
        <span class="delivery_message" style="<?= $style; ?>">
                Дополните ваш заказ на <span class='value'><?= ($partner->free_delivery_sum - $sum); ?></span> рублей, чтобы не платить за доставку.
        </span>

        <div class="rasp" style="text-align: center;color:#494949;font-size: 19px;">
            <?= $DaysString ?>: с <?= substr($partner->work_begin_time, 0, strlen($partner->work_begin_time) - 3) ?> до
            <?= substr($partner->work_end_time, 0, strlen($partner->work_end_time) - 3) ?>
        </div>
        <br>
        <? if (Yii::app()->user->role == User::USER || Yii::app()->user->role == User::PARTNER || Yii::app()->user->id == 989) { ?>
            <div style="text-align: center;font-size: 19px;">На счету <?= User::getBonus(Yii::app()->user->id); ?>
                баллов
            </div>
            <p class='oBal' style="text-align: center;font-size: 19px;">Заказав, вы получите:
                <span>
                    <img src='/images/iconBal.png'>
                    <span
                        id="bonus_sum"><? echo round(($sum + $partner->delivery_cost) * User::BONUS_PROCENT_FROM_ORDER, 0); ?></span> баллов
                </span>
            </p>

        <? } ?>
        <br>
        <center class="links">
            <a href="/order" class="orderZakaz checkout">Оформить заказ</a>
            <? if ($warning) { ?>

                <a href="/" class="orderZakaz checkout">Найти другой ресторан</a>
            <? } ?>
        </center>

        <br>

        <br>
        <br>

        <div class="footerBox"></div>
    </div>
</main><!-- .content -->


<script type="text/javascript">
    var delivery_cost =<? echo $partner->delivery_cost;?>;
    var delivery_cost_total =<? echo $delivery_cost; ?>;
    var valuta = "<?php echo City::getMoneyKod($this->domain);?>";
    function plusProduct(p_id) {
        $.ajax({
            url: '<? echo CController::createUrl('/cart/plusProduct');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: {"product": p_id},
            success: function (data) {
                $("#num_" + p_id).text(data['count']);
                $("#item_price_" + p_id).html(data['sumProduct']);
                $("#sum_cart").html(data['sumCart']);
                $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
                if (data['sum_for_free_delivery'] >= 0) {
                    if (data['sum_for_free_delivery'] <= 150) {
                        $(".delivery_message .value").html(data['sum_for_free_delivery']);
                        $(".delivery_message").show();
                    } else {
                        $(".delivery_message").hide();
                    }
                    $(".delivery_cost").html(delivery_cost + " " + valuta);
                    delivery_cost_total = delivery_cost;
                } else {
                    $(".delivery_cost").html("0");
                    delivery_cost_total = 0;
                    $(".delivery_message").hide();
                }
                $("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
            }
        });
    }
    $(document).ready(function () {
        var mySwiper = new Swiper('.swiper-container', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            autoplay: 10000,
            paginationClickable: true
        });
        $(".more-cola").on('click', ".swiper-slide-active .prev", function () {
            mySwiper.slidePrev();
        });
        $(".more-cola").on('click', ".swiper-slide-active .next", function () {
            mySwiper.slideNext();
        });
    });
    function minusProduct(p_id) {
        $.ajax({
            url: '<? echo CController::createUrl('/cart/minusProduct');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: {"product": p_id},
            success: function (data) {
                if (data['empty']) {
                    $(".orderList").remove();
                    //$("#bascet").html("");
                    $("#empty_basket").css("display", "block");
                    $("#sum_cart").html(0);
                    $("#sum_itogo").html(0);
                    $("#bonus_sum").html(0);
                } else {
                    if (data['count'] == 0) {
                        $("#cartItem_" + p_id).css("display", "none");
                    }
                    $("#num_" + p_id).text(data['count']);
                    $("#item_price_" + p_id).html(data['sumProduct']);

                    $("#sum_cart").html(data['sumCart']);
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
                    if (data['sum_for_free_delivery'] >= 0) {
                        if (data['sum_for_free_delivery'] <= 150) {
                            $(".delivery_message .value").html(data['sum_for_free_delivery']);
                            $(".delivery_message").show();
                        } else {
                            $(".delivery_message").hide();
                        }
                        $(".delivery_cost").html(delivery_cost + " " + valuta);
                        delivery_cost_total = delivery_cost;
                    } else {
                        $(".delivery_cost").html("0");
                        delivery_cost_total = 0;
                        $(".delivery_message").hide();
                    }
                    $("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
                }
            }
        });
    }
    function orderDrink(p_id) {
        //получаем id заказываемого товара
        var value = 1;
        $.ajax({
            url: '/cart/add',
            type: "post",
            cache: false,
            dataType: 'json',
            data: {"product": p_id, "count": value, 'type': 'drink'},
            beforeSend: function () {
                $('#pop-up-bascet').append('<img src="/images/loader2.gif" class="drink-loader">');
            },
            success: function (data) {
                $(".orderList").html(data.list);
                setTimeout(function () {
                    $('.bascet-list').html(data.list);
                    $('#sum_cart').html(data.sumCart);
                    $('#sum_itogo').html(data.sumCart);
                    //$('.drink-loader').remove();
                }, 500);
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
                    $("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod($this->domain);?></b>");
                    $("#sum_cart").html(data['sumCart']);
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
                    if (data['sum_for_free_delivery'] >= 0) {
                        if (data['sum_for_free_delivery'] <= 150) {
                            $(".delivery_message .value").html(data['sum_for_free_delivery']);
                            $(".delivery_message").show();
                        } else {
                            $(".delivery_message").hide();
                        }
                        $(".delivery_cost").html(delivery_cost + " " + valuta);
                        delivery_cost_total = delivery_cost;
                    } else {
                        $(".delivery_cost").html("0");
                        delivery_cost_total = 0;
                        $(".delivery_message").hide();
                    }
                    $("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
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
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) *<?=User::BONUS_PROCENT_FROM_ORDER;?>).toFixed(1));
                    if (data['sum_for_free_delivery'] >= 0) {
                        if (data['sum_for_free_delivery'] <= 150) {
                            $(".delivery_message .value").html(data['sum_for_free_delivery']);
                            $(".delivery_message").show();
                        } else {
                            $(".delivery_message").hide();
                        }
                        $(".delivery_cost").html(delivery_cost + " " + valuta);
                        delivery_cost_total = delivery_cost;
                    } else {
                        $(".delivery_cost").html("0");
                        delivery_cost_total = 0;
                        $(".delivery_message").hide();
                    }
                    $("#sum_itogo").html(data['sumCart'] + delivery_cost_total);
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

<script>
    $(document).ready(function () {
        $(document).on("click", ".checkout1", function (event) {
            <?if(!$warning){?>
            getOrder();
            <?}else{?>
            getWarning();
            <?}?>
            return false;
        });
        $(".orderZakaz").click(function () {
            getOrder();
            return false;
        });
    });

    function getWarning() {
        $.ajax({
            url: '/restorany/action/warning',
            type: "post",
            cache: false,
            data: {"partner":<? echo $partner->id?>},
            success: function (basketData) {
                $("#popup").append(basketData);
            }
        });
    }

    function getOrder() {
        $.ajax({
            url: '/restorany/action/order',
            type: "post",
            dataType: 'JSON',
            cache: false,
            data: {"partner":<? echo $partner->id?>, "cart_mobile": 1},
            success: function (data) {
                //нада поставить false true
                if (data['error']) {
                    $("#popup_order_errors").html("");
                    $("#popup_order_errors").append(data['error']);
                }
                if (data['page']) {
                    var cart = JSON.parse(data['cart_items']);
                    addCart(cart);
                    if ($("#payment_selector").val()) {
                        document.location.href = "<?=Yii::app()->request->hostInfo?>/order?payment=" + $("#payment_selector").val();
                    } else {
                        document.location.href = "<?=Yii::app()->request->hostInfo?>/order";
                    }
                }
            }
        });
    }

</script>

<style>
    #popup_order_errors {
        color: red;
        font-size: 18px;
    }
</style>
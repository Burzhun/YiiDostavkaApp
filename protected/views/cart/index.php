<?php /** @var Partner $partner */ ?>
<div class="page">
    <div class="blok">
        <p class="crumbs"><a href="">Главная</a> / Корзина</p>

        <div class="page_basket">
            <div class="lo_basket"></div>
            <p class="title_basket">Моя корзина</p>
            <a class="basket_order" href="/order"><img
                    src="/images/basket_button.png"></a>
            <div class="line_basket"></div>
            <div class="basket_home">
                <p class="title">в корзине: <?php if (empty($cart)) {
                        echo "пока что пусто";
                    } ?></p>
                <?php if (!empty($cart)) { ?>
                    <?php foreach ($cart as $c) { ?>
                        <div class="number" id="cart_item_<?php echo $c->goods->id; ?>">
                            <p><?php echo $c->goods->name; ?></p>
                            <a onclick="minusProduct(<?php echo $c->goods->id; ?>)"><img
                                    src="/images/basket_minus.png"></a>

                            <div class="form_number"
                                 id="form_number_<?php echo $c->goods->id; ?>"><?php echo $c->quality; ?></div>
                            <a onclick="plusProduct(<?php echo $c->goods->id; ?>)"><img
                                    src="/images/basket_plus.png"/></a>

                            <p class="price_p"><b><span
                                        id="sum_product_<?php echo $c->goods->id; ?>"><?php echo $c->quality * $c->price; ?></span></b>
                                руб</p>
                            <a onclick="deleteProduct(<?php echo $c->goods->id; ?>)"><img
                                    src="/images/basket_plus.png"
                                    style="-webkit-transform: rotate(45deg);"></a>
                        </div>
                    <?php } ?>
                    <p class="title">сумма заказа:</p>
                    <p class="price_p"><b><span id="sum_cart"><?php echo $sum; ?></span></b> руб</p>
                    <div class="line_prise"></div>
                    <p class="title">стоимость доставки:</p>
                    <p class="price_p"><b><?php echo $partner->delivery_cost; ?></b> руб</p>
                    <div class="line_prise"></div>
                    <p class="title">итого:</p>
                    <p class="price_p"><b><span id="sum_itogo"><?php echo $sum + $partner->delivery_cost; ?></span></b>
                        руб</p>
                <?php } ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function plusProduct(p_id) {
            $.ajax({
                url: '<?php echo CController::createUrl('/cart/plusProduct');?>',
                type: "post",
                dataType: "json",
                cache: false,
                data: {"product": p_id},
                success: function (data) {
                    $("#form_number_" + p_id).html(data['count']);
                    $("#sum_cart").html(data['sumCart']);
                    $("#sum_itogo").html(data['sumCart'] +<?php echo $partner->delivery_cost;?>);
                }
            });
        }

        function minusProduct(p_id) {
            $.ajax({
                url: '<?php echo CController::createUrl('/cart/minusProduct');?>',
                type: "post",
                dataType: "json",
                cache: false,
                data: {"product": p_id},
                success: function (data) {
                    $("#form_number_" + p_id).html(data['count']);
                    $("#sum_cart").html(data['sumCart']);
                    $("#sum_itogo").html(data['sumCart'] +<?php echo $partner->delivery_cost;?>);
                }
            });
        }

        function deleteProduct(p_id) {
            $.ajax({
                url: '<?php echo CController::createUrl('/cart/deleteProduct');?>',
                type: "post",
                dataType: "json",
                cache: false,
                data: {"product": p_id},
                success: function (data) {
                    $("#sum_cart").html(data['sumCart']);
                    $("#cart_item_" + p_id).remove();
                    $("#sum_itogo").html(data['sumCart'] +<?php echo $partner->delivery_cost;?>);
                }
            });
        }
    </script>
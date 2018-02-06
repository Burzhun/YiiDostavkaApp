<div id="pop-up-bascet">
    <div class="pop-header">
        <h3>
            <img src="<?= $partner->getImage(); ?>" style="width: 60px;margin-top: -23px;margin-right: 20px;">
            "<?= $partner->name ?>" не работаает в вашем районе
        </h3>
    </div>
    <div style="float: left;margin: 30px 40px;height: 250px;">
        <img src="/images/zamok.png">
    </div>
    <div>
        <p class="blocked-partner-msg-title">
            Рекомендуем обратить внимание на другие рестораны работающие в вашем районе.
        </p>

        <div style="margin-top: 20px;">
            <?
            /** @var Partner[] $partners */
            $partners = $partner->getPartnersForRayon(Yii::app()->request->cookies['rayon']->value);
            foreach ($partners as $partner) { ?>
                <a class="blocked-partner-other" href="<?= $partner->createUrl() ?>">
                    <img src="<?= $partner->getImage(); ?>">
                    <p>
                        <?= $partner->name; ?>
                    </p>
                </a>
            <? } ?>
        </div>
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
                $("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod();?></b>");
                $("#sum_cart").html(data['sumCart']);
                $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) * 0.01).toFixed(1));
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
                    $("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod();?></b>");
                    $("#sum_cart").html(data['sumCart']);
                    $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) * 0.01).toFixed(1));
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
                    $("#item_price_" + p_id).html("<b>" + data['sumProduct'] + " <?php echo City::getMoneyKod();?></b>");
                    $("#sum_cart").html(data['sumCart']);
                    $("#sum_itogo").html(data['sumCart'] +<? echo $partner->delivery_cost;?>);
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) * 0.01).toFixed(1));
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
                    $("#bonus_sum").html(((data['sumCart'] +<? echo $partner->delivery_cost;?>) * 0.01).toFixed(1));
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

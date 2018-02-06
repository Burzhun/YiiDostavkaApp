<style>
    .row input {
        width: 100%;
    }

    .required {
        color: red;
    }
</style>

<div class="topNav">
    <a href='/cart' class="backLink">
        <img src="/themes/mobile/img/arrowBack.png" alt=""> Оформление заказа
    </a>
</div>

<main class="content">
    <div class="topEditOrder"></div>
    <div class="editOrder">
        <? if (Yii::app()->user->isGuest || Yii::app()->user->getRole() == 'admin') {// если пользователь гость?>
            <div id="popup_order_errors"></div>
            <form id="orderForm">
                <div class="row">
                    <label for="Order_name">Ваше имя <span class="required">* </span> :</label>
                    <input type="text" id="Order_name" name="Order[name]" value="">
                </div>

                <div class="row">
                    <label for="Order_phone">Контактный телефон <span class="required">* </span> :</label>
                    <input type="text" id="Order_phone" name="Order[phone]" value="">
                </div>

                <? /*<div class="row">
					<label for="Order_email">Email :</label>
					<input type="text" id="Order_email" name="Order[email]" value="">
				</div>*/ ?>

                <b>Адрес доставки</b>
                <br><br>

                <div class="row">
                    <label for="Address_city">Город:</label>
                    <input type="text" id="Address_city" name="Address[city]" readonly
                           value="<?= $partner->city->name; ?>">
                </div>

                <div class="row">
                    <label for="Address_street">Улица <span class="required">* </span> :</label>
                    <input type="text" id="Address_street" name="Address[street]">
                </div>

                <div class="row">
                    <label for="Address_house">Дом <span class="required">* </span> :</label>
                    <input type="text" id="Address_house" name="Address[house]">
                </div>

                <div class="row">
                    <label for="Address_storey">Этаж:</label>
                    <input type="text" id="Address_storey" name="Address[storey]">
                </div>

                <div class="row">
                    <label for="Address_number">Номер квартиры/офиса:</label>
                    <input type="text" id="Address_number" name="Address[number]">
                </div>

                <div class="row">
                    <label for="Order_text">Комментарий к заказу :</label>
                    <textarea cols="10" rows="5" id="Order_text" name="Order[text]"></textarea>
                </div>

                <div class="row">
                    <center>
                        <button form="orderForm" class="orderZakaz" type="submit" name="yt1000" id="yt1000">Отправить
                            заказ
                        </button>
                    </center>
                </div>
                <div>
                    <? if (Yii::app()->user->isGuest) { ?>

                        <button class="checkoutFree checkoutFree_not_enough" type="button" name="yt1001">Заказать
                            бесплатно
                        </button>
                        <div class="orderFreeComment" style="display:none;color:#fe8300;">
                            <ul style="">Заполните эти поля и зарегистрируйтесь <br>для получения баллов за покупки</ul>
                            <div class="row">
                                <label>Имя <span class="required">* </span></label><input type="text" id="register_name"
                                                                                          name="register_name">
                            </div>
                            <div class="row">
                                <label>E-mail <span class="required">* </span></label><input type="text"
                                                                                             id="register_email"
                                                                                             name="register_email">
                            </div>
                            <div class="row">
                                <label>Пароль <span class="required">* </span></label><input type="password"
                                                                                             id="register_password"
                                                                                             name="register_password">
                            </div>


                            <div class="row">
                                <center>
                                    <button class="orderZakaz2" id="yt1002">Зарегистрироваться и отправить заказ
                                    </button>
                                </center>
                            </div>
                        </div>

                    <? } ?>
                </div>
                <style>
                    .reg_errors li {
                        list-style: circle;
                        margin-left: 15px;
                        color: red;
                    }
                </style>
            </form>
        <? } else { // если пользователь авторизован?>
            <?php $address = UserAddress::model()->findAll(array('condition' => 'user_id=' . Yii::app()->user->id, 'order' => 'id DESC')); ?>
            <?php if (!empty($address)) {// если у пользователя есть хоть один адрес?>
                <div id="newaddress" style="display:none;">
                    <?php echo CHtml::form(); ?>
                    <div id="popup_order_errors"></div>
                    <div class="row">
                        <b><?php echo CHtml::label('Улица', 'Address[street]'); ?></b>
                        <?php echo CHtml::textArea('Address[street]', ''); ?>
                    </div>
                    <div class="row">
                        <b><?php echo CHtml::label('Дом', 'Address[house]'); ?></b>
                        <?php echo CHtml::textArea('Address[house]', ''); ?>
                    </div>

                    <div class="row">
                        <b><?php echo CHtml::label('Подъезд', 'Address[podezd]'); ?></b>
                        <?php echo CHtml::textArea('Address[podezd]', ''); ?>
                    </div>

                    <div class="row">
                        <b><?php echo CHtml::label('Этаж', 'Address[storey]'); ?></b>
                        <?php echo CHtml::textArea('Address[storey]', ''); ?>
                    </div>

                    <div class="row">

                        <b><?php echo CHtml::label('Номер квартиры/офиса', 'Address[number]'); ?></b>
                        <?php echo CHtml::textArea('Address[number]', ''); ?>

                    </div>
                    <div class="row">
                        <?php echo CHtml::ajaxSubmitButton('Обработать', 'order/addAdress', array(
                            'type' => 'POST',
                            //'update' => '',
                            'success' => 'function(data) {
									$("#newaddress").hide();
									$("#Order_address").html(data);
								}',
                        ),
                            array(
                                'type' => 'submit'
                            )); ?>
                    </div>
                    <?php echo CHtml::endForm(); ?>
                </div>

                <div id="privet"></div>

                <div class="order_left">
                    <form id="orderForm" method="POST">
                        <div class="row">
                            <label for="Order_name">Ваше имя <span class="required">* </span> :</label>
                            <input type="text" id="Order_name" name="Order[name]"
                                   value="<?= Yii::app()->user->name; ?>">
                        </div>
                        <div class="row">
                            <label for="">Адрес:</label>
                            <?php echo $this->renderPartial('_address_select', array('address' => $address)); ?>
                        </div>
                        <div class="row">
                            <center>
                                <a href="javascript:void(0);" class="submit" onclick="$('#newaddress').toggle();">Добавить
                                    адрес</a>
                            </center>
                        </div>
                        <div class="row telRow">
                            <label for="">Телефон:</label>
                            <input type="text" name="Order[phone]"
                                   value="<?php echo !Yii::app()->user->isGuest ? $user->phone : ""; ?>">
                        </div>
                        <div class="row telRow">
                            <label for="">Комментарий к заказу </label>
                            <textarea rows="" name="Order[text]" cols="" style="height:110px;"></textarea>
                        </div>

                        <div style="font-family: 'PT Sans';font-size: 17px;">На
                            счету <?= User::getBonus(Yii::app()->user->id); ?> баллов
                        </div>
                        <p class='oBal' style="font-family: 'PT Sans';font-size: 17px;">Заказав, вы получите:
                                <span>
                                    <img src='/images/iconBal.png'>
                                    <span
                                        id="bonus_sum"><? echo round(($sum + $partner->delivery_cost) * User::BONUS_PROCENT_FROM_ORDER, 0); ?></span> баллов
                                </span>
                        </p>
                        </br>


                        <div class="row">
                            <center>
                                <input class="orderZakaz" type="submit" id="yt1000" value="Отправить заказ"/>
                            </center>
                        </div>
                        <? if (!Yii::app()->user->isGuest && $partner->allow_bonus) {
                            if($_GET['payment']!='card'){
                                $bonusCount = User::getBonus(Yii::app()->user->id);
                                if (User::isEnoughBonus(Yii::app()->user->id, $sum + $partner->delivery_cost)) { ?>
                                    <input form="orderForm" class="checkoutFree" type="button" name="yt1001" id="yt1001"
                                           value="Заказать бесплатно"/>
                                    <div class="orderFreeComment"
                                         style="color:#fe8300;text-align: center;font-size: 18px;margin-top: 8px;">У вас
                                        будет списано <span><?= ($sum + $partner->delivery_cost) * 4; ?> баллов</div>
                                <? } else { ?>
                                    <input form="orderForm_not_enough" class="checkoutFree checkoutFree_not_enough"
                                           type="button" name="yt1001" value="Заказать бесплатно"/>
                                    <div class="orderFreeComment"
                                         style="display:none;color:#fe8300;text-align: center;font-size: 18px;margin-top: 8px;">
                                        Для бесплатного заказа у вас не хватает
                                        <span><?= (($sum + $partner->delivery_cost) * 4) - $bonusCount; ?></span> баллов
                                    </div>
                                <? } ?>
                            <? } ?>
                        <? } else { ?>
                            <input form="orderForm_not_enough" class="checkoutFree checkoutFree_not_enough"
                                   type="button" name="yt1001" value=""/>
                            <div class="orderFreeComment" style="display:none;color:#fe8300;">Зарегистрируйтесь и
                                заказывайте еду бесплатно
                            </div>
                        <? } ?>

                        <? if (!Yii::app()->user->isGuest) {
                            $user_id = Yii::app()->user->id;
                            $promos=$partner->promos;
                            $s="(";
                            foreach($partner->promos as $promo){
                                $s.=$promo->id.",";
                            }
                            $promos=Promo::model()->with('partners')->findAll(array("having"=>"t1_c0 is null"));
                            foreach($promos as $promo){
                                $s.=$promo->id.",";
                            }
                            $s.="-1)";
                            //$user_promo=false;
                            $user_promo = UserPromo::model()->with('promo')->find("user_id=" . $user_id . " and used=0 and activated=1 and promo.id in ".$s);
                            if (!$user_promo && $s!="(-1)") { ?>
                                ?>
                                <div class="writePromoKodInOrder">
                                    <form id="promokod_form">
                                        Введите промо код и получите дополнительные баллы!
                                        <div id="promokod_error" style="color:red;"></div>
                                        <input type="text" name="promoKod" class="promoKod" id="promoKod">
                                        <input type="submit" class="promoActiveBtn" value="Использовать"/>

                                        <script>
                                            $(window).ready(function () {
                                                $("#promokod_form").submit(function () {
                                                    var kod = $('#promoKod').attr('value');
                                                    $("#promokod_error").text("");
                                                    if (kod == '') {
                                                        return false;
                                                    }
                                                    $.ajax({
                                                        url: '<? echo CController::createUrl('/restorany/usePromoKod');?>',
                                                        type: "post",
                                                        dataType: "json",
                                                        cache: false,
                                                        data: {"kod": kod,"partner_id":<?=$partner->id;?>},
                                                        success: function (data) {
                                                            if (data['error']) {
                                                                $("#promokod_error").text(data['error']);
                                                                //$("#pop-up-bascet").remove();
                                                                //$("#bascet").html("");
                                                                // $("#parent_popup").css("display", "none");
                                                            } else {
                                                                if (data['success']) {
                                                                    $("#promokod_form").html("<span style='color:green;font-size: 16px;'>Промокод активриован, после оформления заказа вам будут начислены баллы</span>");
                                                                }
                                                            }
                                                        },
                                                        error: function (data) {
                                                            //alert("jserror");
                                                        }
                                                    });
                                                    return false;
                                                });
                                            });
                                        </script>
                                    </form>
                                </div>
                            <? } ?>
                        <? } ?>
                        <? /*<div class="order_right"> <p class="title_basket"><?php echo $partner->name;?></p>
							<p class="price_p">Общая сумма заказа:<b><?php echo $sum;?> руб</b></p>
							<p class="price_p">Стоимость доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost." руб" : "бесплатно";?></b></p>
							<div class="line_order"></div>
							<p class="price_order">Итого: <b> <?php echo $sum+$partner->delivery_cost;?> руб</b></p>
							<input type="submit" style="background:url(images/basket_button.png);width:158px;height:37px;" value="">
						</div>*/ ?>
                    </form>
                </div>
            <?php } else { // если у пользователя нет адресов куда доставлять, заставим заполнить адрес здесь?>
                <div class="order_left">
                    <form id="orderForm" method="POST">
                        <div id="popup_order_errors"></div>
                        <div class="row">
                            <b>Контактный телефон <span class="required">* </span> </b>
                            <input type="text" name="Order[phone]"
                                   value="<?php echo !Yii::app()->user->isGuest ? $user->phone : ""; ?>">
                        </div>
                        <div class="row">
                            <b>Адрес доставки</b>
                        </div>
                        <div class="row">
                            <b>Улица <span class="required">* </span></b>
                            <input type="text" name="Address[street]" value="">
                        </div>
                        <div class="row">
                            <b>Дом <span class="required">* </span></b>
                            <input type="text" name="Address[house]" value="">
                        </div>
                        <div class="row">
                            <b>Этаж</b>
                            <input type="text" name="Address[storey]" value="">
                        </div>
                        <div class="row">
                            <b>Номер квартиры/офис</b>
                            <input type="text" name="Address[number]" value="">
                        </div>
                        <div class="row">
                            <b>Комментарий к заказу</b>
                            <textarea rows="5" name="Order[text]"></textarea>
                        </div>
                        <div class="order_right"><p class="title_basket"><?php echo $partner->name; ?></p>

                            <p class="price_p">Общая сумма заказа:<b><?php echo $sum; ?> руб</b></p>

                            <p class="price_p">Стоимость
                                доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost . " ".City::getMoneyKod() : "бесплатно"; ?></b>
                            </p>

                            <div class="line_order"></div>
                            <p class="price_order">Итого: <b> <?php echo $sum + $partner->delivery_cost; ?> руб</b></p>
                            <br><br>

                            <div style="font-family: 'PT Sans';font-size: 13px;">На
                                счету <?= User::getBonus(Yii::app()->user->id); ?> баллов
                            </div>
                            <p class='oBal' style="font-family: 'PT Sans';font-size: 13px;">Заказав, вы получите:
                            <span>
                                <img src='/images/iconBal.png'>
                                <span
                                    id="bonus_sum"><? echo round(($sum + $partner->delivery_cost) * User::BONUS_PROCENT_FROM_ORDER, 0); ?></span> баллов
                            </span>
                            </p>

                            </br>
                            <div class="row">
                                <center>
                                    <? /*<input class="orderZakaz" type="submit" value="Оформить заказ" id="yt1000"/>*/ ?>
                                    <button form="orderForm" class="orderZakaz" type="submit" name="yt1000" id="yt1000">
                                        Отправить заказ
                                    </button>
                                </center>
                            </div>

                            <?
                            $bonusCount = User::getBonus(Yii::app()->user->id);
                            if($partner->allow_bonus&&($_GET['payment']!='card')){
                                if (User::isEnoughBonus(Yii::app()->user->id, $sum + $partner->delivery_cost)) { ?>
                                    <input form="orderForm" class="checkoutFree" type="button" name="yt1001" id="yt1001"
                                           value="Заказать бесплатно"/>
                                    <div class="orderFreeComment"
                                         style="display:none;color:#fe8300;text-align: center;font-size: 18px;margin-top: 8px;">
                                        У вас будет списано <span><?= ($sum + $partner->delivery_cost) * 4; ?> баллов</div>
                                <? } else { ?>
                                    <input form="orderForm_not_enough" class="checkoutFree checkoutFree_not_enough"
                                           type="button" name="yt1001" value="Заказать бесплатно"/>
                                    <div class="orderFreeComment"
                                         style="display:none;color:#fe8300;text-align: center;font-size: 18px;margin-top: 8px;">
                                        Для бесплатного заказа у вас не хватает
                                        <span><?= (($sum + $partner->delivery_cost) * 4) - $bonusCount; ?></span> баллов
                                    </div>
                                <? } ?>
                            <? } ?>
                            <? if (!Yii::app()->user->isGuest) {
                                $user_id = Yii::app()->user->id;
                                $user_promo = UserPromo::model()->find("user_id=" . $user_id . " and used=1 and activated=0");
                                if (!$promo && Promo::model()->find()) {
                                    ?>
                                    <div class="writePromoKodInOrder">
                                        <form id="promokod_form">
                                            Введите промо код и получите дополнительные баллы!
                                            <div id="promokod_error" style="color:red;"></div>
                                            <input type="text" name="promoKod" class="promoKod" id="promoKod">
                                            <input type="submit" class="promoActiveBtn" value="Использовать"/>

                                            <script>
                                                $(window).ready(function () {
                                                    $("#promokod_form").submit(function () {
                                                        var kod = $('#promoKod').attr('value');
                                                        $("#promokod_error").text("");
                                                        if (kod == '') {
                                                            return false;
                                                        }
                                                        $.ajax({
                                                            url: '<? echo CController::createUrl('/restorany/usePromoKod');?>',
                                                            type: "post",
                                                            dataType: "json",
                                                            cache: false,
                                                            data: {"kod": kod},
                                                            success: function (data) {
                                                                if (data['error']) {
                                                                    $("#promokod_error").text(data['error']);
                                                                    //$("#pop-up-bascet").remove();
                                                                    //$("#bascet").html("");
                                                                    // $("#parent_popup").css("display", "none");
                                                                } else {
                                                                    if (data['success']) {
                                                                        $("#promokod_form").html("<span style='color:green;font-size: 16px;'>Промокод активриован, после оформления заказа вам будут начислены баллы</span>");
                                                                    }
                                                                }
                                                            },
                                                            error: function (data) {
                                                                //alert("jserror");
                                                            }
                                                        });
                                                        return false;
                                                    });
                                                });
                                            </script>
                                        </form>
                                    </div>
                                <? } ?>
                            <? } ?>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <style type="text/css">
                .title_basket {
                    margin-bottom: 10px;
                    font-size: 15px;
                }

                .price_order {
                    font-size: 16px;
                    font-weight: bold;
                }

                .price_p {
                    display: block;
                    margin-bottom: 10px;
                }
            </style>
        <?php } ?>

        <? /*<div class="row">

			<label for="">Адрес:</label>
			<select name="" id="">
				<option value="">Jjiisis@mail.ru</option>
				<option value="">wewJjiisis@mail.ruewe</option>
				<option value="">wewewJjiisis@mail.ru</option>
			</select>
		</div>

		<div class="row">
			<center>
				<input type="submit" value='Добавить адрес'>
			</center>
		</div>

		<div class="row telRow">
			<label for="">Телефон:</label>
			<span>+7  (</span> <input type="text" style='width:50px;'>	<span>)</span> <input type="text" style='width:140px;'>
		</div>

		<div class="row">
			<label for="">Комментарий:</label>
			<textarea name="" id="" cols="10" rows="5"></textarea>
		</div>

        <? } */ ?>

        <div class="footerBox"></div>
    </div>
</main><!-- .content -->

<script type="text/javascript">
    var payment="<?=isset($_GET['payment'])&&($_GET['payment']=='cash'||$_GET['payment']=='card') ? $_GET['payment'] : 'cash'?>";
    $('body').on('click', '#yt1000', function () {
        if(payment=='card'){
            jQuery.ajax({
                'type': 'post',
                'dataType': 'JSON',
                'success': function (data) {
                    if (data['error']) {
                        $("#popup_order_errors").html("");
                        $("#popup_order_errors").append(data['error']);
                        $("body,html").animate({"scrollTop": 0}, 1000);
                    }
                    if (data['page']) {
                        var url="/yandex_kassa.php?order_id="+data['order_id']+"&sum="+data['sum'];
                        window.location.href=url;
                    }
                },
                'url': '/restorany/action/orderkassa',
                'cache': false,
                'data': jQuery("#orderForm").serialize()
            });
        }else{
            jQuery.ajax({
                'type': 'post',
                'dataType': 'JSON',
                'success': function (data) {
                    if (data['error']) {
                        $("#popup_order_errors").html("");
                        $("#popup_order_errors").append(data['error']);
                        $("body,html").animate({"scrollTop": 0}, 1000);
                    }
                    if (data['page']) {
                        $(".editOrder").html(data['page']);
                        //$("#popup").append(data['page']);
                        //$("#pop-up-order").remove();
                        $(".bascet").css("display", "none");
                        //return false;
                        //$("#newaddress").hide();
                        //$("#Order_address").html(data);
                    }
                },
                'url': '/restorany/action/order',
                'cache': false,
                'data': jQuery("#orderForm").serialize()
            });
        }

        return false;
    });
    $('body').on('click', '#yt1001', function () {
        jQuery.ajax({
            'type': 'post',
            'dataType': 'JSON',
            'success': function (data) {
                if (data['error']) {
                    $("#popup_order_errors").html("");
                    $("#popup_order_errors").append(data['error']);
                    $("body,html").animate({"scrollTop": 0}, 1000);
                }
                if (data['page']) {
                    $(".editOrder").html(data['page']);
                    //$("#popup").append(data['page']);
                    //$("#pop-up-order").remove();
                    $(".bascet").css("display", "none");
                    //return false;
                    //$("#newaddress").hide();
                    //$("#Order_address").html(data);
                }
            },
            'url': '/restorany/action/order',
            'cache': false,
            'data': jQuery("#orderForm").serialize() + "&forbonus=1"
        });
        return false;
    });
    $('body').on('click', '#yt1002', function () {
        jQuery.ajax({
            'type': 'post',
            'dataType': 'JSON',
            'success': function (data) {
                if (data['error']) {
                    $("#popup_order_errors").html("");
                    $("#popup_order_errors").append(data['error']);
                    $("body,html").animate({"scrollTop": 0}, 1000);
                }
                if (data['page']) {
                    $(".editOrder").html(data['page']);
                    //$("#popup").append(data['page']);
                    //$("#pop-up-order").remove();
                    $(".bascet").css("display", "none");
                    //return false;
                    //$("#newaddress").hide();
                    //$("#Order_address").html(data);
                }
            },
            'url': '/restorany/action/order',
            'cache': false,
            'data': jQuery("#orderForm").serialize() + "&register=1"
        });
        return false;
    });
    $(window).ready(function () {
        var f_click = true;
        $(".checkoutFree_not_enough").click(function () {
            if (f_click) {
                $(".orderFreeComment").show();
                f_click = false;
            }
        });
    });
    var kassa_check_function=null;
    function check_kassa(){
        $.post('/YandexKassa/check_kassa',{},function(data){
            if(data=='payed'){
                clearInterval(kassa_check_function);
                window.location.href='/order/thanks';
            }
            if(data=='cancelled'){
                clearInterval(kassa_check_function);
            }
        });
    }
    $("#kassa").click(function(){
        var newWin = window.open('', "hello", "width=600,height=600");
        jQuery.ajax({
            'type': 'post',
            'dataType': 'JSON',
            beforeSend: function (data) {
                $(".loader_order").show();
            },
            'success': function (data) {
                $(" .loader_order").hide();

                if (data['page']) {
                    var url="/yandex_kassa.php?order_id="+data['order_id']+"&sum="+data['sum'];
                    newWin.location=url;
                    kassa_check_function=setInterval(check_kassa,2000);
                    return false;
                    $("#newaddress").hide();
                    $("#Order_address").html(data);
                }else{
                    if (data['error']) {
                        $("#popup_order_errors").html("");
                        $("#popup_order_errors").append(data['error']);
                        newWin.close();
                    }
                }
            },
            'url': '/restorany/action/orderkassa',
            'cache': false,
            'data': jQuery("#orderForm").serialize()
        });
        return false;
    });
</script>


<? /*<div class="page">
	<div class="blok">
		<p class="crumbs"><a href="/">Главная</a> / <a href="/cart">Корзина</a> / Оформление заказа</p>
		<div class="page_basket">
			<div class="lo_basket"></div>
			<p class="title_basket">Оформить заказ</p>
			<div class="line_basket"></div>
			<div class="basket_home">
				<?php if(Yii::app()->user->role != User::USER){// если пользователь гость?>
					<form method="POST">
						<div class="order_left">
							<table>
                                <tr>
                                    <td><b>Ваше Имя *</b></td>
                                    <td><input type="text" name="Order[name]" value="<?php echo Yii::app()->user->role == User::USER ? $user->name: "";?>"></td>
                                </tr>
                                <tr>
                                    <td><b>Контактный телефон *</b></td>
                                    <td><input type="text" name="Order[phone]" value="<?php echo Yii::app()->user->role == User::USER ? $user->phone: "";?>"></td>
                                </tr>
                                <tr>
                                    <td><b>E-mail *</b></td>
                                    <td><input type="text" name="Order[email]" value="<?php echo Yii::app()->user->role == User::USER ? $user->email: "";?>"></td>
                                </tr>
                                <tr>
                                    <td><b>Сообщение *</b></td>
                                    <td><textarea rows="" name="Order[text]" cols=""></textarea></td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;"><b>Адрес доставки</b></td>
                                </tr>
                                <tr>
                                    <td><b>Улица</b></td>
                                    <td><input type="text" name="Address[street]"></td>
                                </tr>
                                <tr>
                                    <td><b>Дом</b></td>
                                    <td><input type="text" name="Address[house]"></td>
                                </tr>
                                <tr>
                                    <td><b>Этаж</b></td>
                                    <td><input type="text" name="Address[storey]"></td>
                                </tr>
                                <tr>
                                    <td><b>Номер квартиры/офиса</b></td>
                                    <td><input type="text" name="Address[number]"></td>
                                </tr>
                            </table>
						</div>
						<div class="order_right"> <p class="title_basket"><?php echo $partner->name;?></p>
							<p class="price_p">Общая сумма заказа:<b><?php echo $sum;?> руб</b></p>
							<p class="price_p">Стоимость доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost." руб" : "бесплатно";?></b></p>
							<div class="line_order"></div>
							<p class="price_order">Итого: <b> <?php echo $sum+$partner->delivery_cost;?> руб</b></p>
							<input type="submit" style="background:url(images/basket_button.png);width:158px;height:37px;" value="">
						</div>
					</form>
				<?php }else{ // если пользователь авторизован?>
					<?php $address = UserAddress::model()->findAll(array('condition'=>'user_id='.Yii::app()->user->id, 'order'=>'id DESC'));?>
					<?php if(!empty($address)){// если у пользователя есть хоть один адрес?>
						<?php echo CHtml::form();?>
							<table style="display:none;" id="newaddress">
								<tr>
									<td><b><?php echo CHtml::label('Улица', 'Address[street]');?></b></td>
									<td><!-- input type="text" name="Order[name]" value="<?php echo !Yii::app()->user->isGuest ? $user->name: "";?>" -->
										<?php echo CHtml::textArea('Address[street]', '');?>
									</td>
								</tr>
								<tr>
                                    <td><b><?php echo CHtml::label('Дом', 'Address[house]');?></b></td>
                                    <td><?php echo CHtml::textArea('Address[house]', '');?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo CHtml::label('Этаж', 'Address[storey]');?></b></td>
                                    <td><?php echo CHtml::textArea('Address[storey]', '');?></td>
                                </tr>
								<tr>
                                    <td><b><?php echo CHtml::label('Номер квартиры/офиса', 'Address[number]');?></b></td>
                                    <td><?php echo CHtml::textArea('Address[number]', '');?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?php echo CHtml::ajaxSubmitButton('Обработать', 'order/addAdress', array(
										'type' => 'POST',
										//'update' => '',
										'success' => 'function(data) {
												$("#newaddress").hide();
												$("#Order_address").html(data);
											}',
										),
											array(
												'type' => 'submit'
										));?>
									</td>
								</tr>
							</table>
						<?php echo CHtml::endForm();?>

						<div id="privet"></div>

						<div class="order_left">
							<form method="POST">
	                            <table>
	                                <tr>
	                                    <td><b>Адрес доставки</b></td>
	                                    <td>
		                                    <?php echo $this->renderPartial('_address_select', array('address'=>$address));?>
	                                    </td>
	                                </tr>
	                                <tr>
	                                	<td><a href="javasript:void(0);" onclick="$('#newaddress').toggle();">Добавить адрес</a><br><br></td>
	                                </tr>
	                                <tr>
	                                    <td><b>Контактный телефон *</b></td>
	                                    <td><input type="text" name="Order[phone]" value="<?php echo !Yii::app()->user->isGuest ? $user->phone: "";?>"></td>
	                                </tr>
	                                <tr>
	                                    <td><b>Комментарий к заказу *</b></td>
	                                    <td><textarea rows="" name="Order[text]" cols=""></textarea></td>
	                                </tr>
	                            </table>
								<div class="order_right"> <p class="title_basket"><?php echo $partner->name;?></p>
									<p class="price_p">Общая сумма заказа:<b><?php echo $sum;?> руб</b></p>
									<p class="price_p">Стоимость доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost." руб" : "бесплатно";?></b></p>
									<div class="line_order"></div>
									<p class="price_order">Итого: <b> <?php echo $sum+$partner->delivery_cost;?> руб</b></p>
									<input type="submit" style="background:url(images/basket_button.png);width:158px;height:37px;" value="">
								</div>
							</form>
						</div>
					<?php }else { // если у пользователя нет адресов куда доставлять, заставим заполнить адрес здесь?>
						<div class="order_left">
							<form method="POST">
									<table>
		                                <tr>
		                                    <td><b>Контактный телефон *</b></td>
		                                    <td><input type="text" name="Order[phone]" value="<?php echo !Yii::app()->user->isGuest ? $user->phone: "";?>"></td>
		                                </tr>
		                                <tr>
		                                    <td><b>Сообщение *</b></td>
		                                    <td><textarea rows="" name="Order[text]" cols=""></textarea></td>
		                                </tr>
		                                <tr>
		                                    <td><b>Адрес доставки</b></td>
		                                    <td></td>
		                                </tr>
		                                <tr>
		                                    <td><b>Улица</b></td>
		                                    <td><input type="text" name="Address[street]" value=""></td>
		                                </tr>
		                                <tr>
		                                    <td><b>Дом</b></td>
		                                    <td><input type="text" name="Address[house]" value=""></td>
		                                </tr>
		                                <tr>
		                                    <td><b>Этаж</b></td>
		                                    <td><input type="text" name="Address[storey]" value=""></td>
		                                </tr>
		                                <tr>
		                                    <td><b>Номер квартиры/офис</b></td>
		                                    <td><input type="text" name="Address[number]" value=""></td>
		                                </tr>
									</table>
									<div class="order_right"> <p class="title_basket"><?php echo $partner->name;?></p>
										<p class="price_p">Общая сумма заказа:<b><?php echo $sum;?> руб</b></p>
										<p class="price_p">Стоимость доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost." руб" : "бесплатно";?></b></p>
										<div class="line_order"></div>
										<p class="price_order">Итого: <b> <?php echo $sum+$partner->delivery_cost;?> руб</b></p>
										<input type="submit" style="background:url(images/basket_button.png);width:158px;height:37px;" value="">
									</div>
								</form>
							</div>
						<?php }?>
					<?php }?>
				</div>
			</div>
	</div>
</div>*/ ?>
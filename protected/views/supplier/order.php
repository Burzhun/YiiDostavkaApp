<script type="text/javascript">
    $(function () {
        $('body').on('click', '.timedelivery', function () {
            $('#timedelivery').slideDown();
            $('.timedelivery').slideUp();
        });
        $('body').on('click', '.dateNo', function () {
            $('#timedel').slideUp();
        });
        $('body').on('click', '.dateYes', function () {
            $('#timedel').slideDown();
        });
    });
</script>
<? $phone = "";
if (isset(Yii::app()->request->cookies['incoming_call'])) {
    $order = Order::model()->find(array("condition" => "phone=" . Yii::app()->request->cookies['incoming_call']->value, "order" => 'id desc'));
    if ($order) {
        $street = $order->street;
        $house = $order->house;
        $podezd = $order->podezd;
        $storey = $order->storey;
        $number = $order->number;
        $customer_name = $order->customer_name;
    } else {
        $storey = "";
        $house = "";
        $podezd = "";
        $street = "";
        $number = "";
        $customer_name = "";
    }
    $phone = Yii::app()->request->cookies['incoming_call']->value;
}
?>
<div id="pop-up-order">
    <div class="pop-header">
        <div id="close-pop-up2"></div>
        <img src="/images/bascet-img.png" width="64" height="65">

        <h3>Ваша корзина:</h3>
        <a href="#" class="edit-order">Редактировать заказ</a>
    </div>

    <div class="popup-order ">
        <? if (Yii::app()->user->isGuest || Yii::app()->user->getRole() != 'user' || isset(Yii::app()->request->cookies['incoming_call'])) {// если пользователь гость?>
            <form class="scroll-pane" id="orderForm" method="POST">
                <div id="popup_order_errors"></div>
                <ul class="name-order ">
                    <li><label>Ваше имя</label><input type="text" value="<?= $customer_name; ?>" name="Order[name]"
                                                      value=""></li>
                    <li><label>Контактный телефон<span>*</span></label><input type="text" value="<?= $phone; ?>"
                                                                              name="Order[phone]" value="">
                    </li>
                    <? /*<li><label>E-mail</label><input type="text" name="Order[email]" value=""></li>*/ ?>
                </ul>
                <ul class="add-order">
                    <li><label><b>АДРЕС ДОСТАВКИ</b></label></li>
                    <li><label>Город</label><input readonly type="text" name="Address[city]"
                                                   value="<?= $partner->city->name; ?>"></li>
                    <li><label>Улица<span>*</span></label><input type="text" value="<?= $street; ?>"
                                                                 name="Address[street]"></li>
                    <li><label>Дом<span>*</span></label><input type="text" value="<?= $house; ?>" name="Address[house]">
                    </li>
                    <li><label>Подъезд<span></span></label><input type="text" value="<?= $podezd; ?>"
                                                                  name="Address[podezd]"></li>
                    <li><label>Этаж</label><input type="text" value="<?= $storey; ?>" name="Address[storey]"></li>
                    <li><label>Номер квартиры/офиса</label><input type="text" value="<?= $number; ?>"
                                                                  name="Address[number]"></li>
                    <li><label style="vertical-align:top;padding-right:21px;">Комментарий к заказу</label><textarea
                            rows="7" name="Order[text]" cols="47"></textarea></li>
                </ul>
                <? //if(Yii::app()->user->role == 'admin'){?>

                <span class='timedelivery fastname'>Уточнить время и дату доставки</span>
                <br>

                <div id='timedelivery' style="display:none;">
                    <ul class="add-order fastorder">
                        <li>
                            <label style="vertical-align: top;">Время и дата доставки</label>

                            <div style="display:inline-block;width: 344px;text-align: left;">
                                <label>
                                    <?= CHtml::radioButton('Address[fast]', true, array('value' => 1, 'class' => 'dateNo')) ?>
                                    <span>Как можно скорее</span>
                                </label><br>
                                <label>
                                    <?= CHtml::radioButton('Address[fast]', false, array('value' => 0, 'class' => 'dateYes')) ?>
                                    <span>Доставка ко времени</span>
                                </label><br>

                                <div id="timedel" style="display:none;text-align: left;">

                                    <?= CHtml::dropDownList('Address[date]', '', Order::getOrderDate(20), array('width' => '100')) ?>

                                    <? $beginWork = idate('H', strtotime($partner->work_begin_time));
                                    $endWork = idate('H', strtotime($partner->work_end_time));

                                    echo CHtml::dropDownList('Address[time]', '', Order::getTimeOfPeriod($beginWork, $endWork));

                                    echo CHtml::dropDownList('Address[timeMin]', '', Order::getTimeMinutesPeriod()); ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <br><br>
                <? if (Yii::app()->user->getRole() != 'admin') { ?>
                    <ul class="register-order" style="display: none;">
                        <ul style="">Заполните эти поля и зарегистрируйтесь <br>для получения баллов за покупки</ul>
                        <li><label>Имя <span>*</span></label><input type="text" value="" id="register_name"
                                                                    name="register_name"></li>
                        <li><label>E-mail <span>*</span></label><input type="text" value="" id="register_email"
                                                                       name="register_email"></li>
                        <li><label>Пароль <span>*</span></label><input type="password" value="" id="register_password"
                                                                       name="register_password"></li>
                        <button class="checkout2" type="submit" name="yt1002" value="" id="yt1002"/>
                    </ul>
                <? } ?>
                <style>
                    .reg_errors li {
                        list-style: circle;
                        margin-left: 15px;
                        color: red;
                        margin-left: 55px;
                    }
                </style>


                <? /*}else{?>

				<?}*/ ?>
            </form>
        <? } else {// если пользователь авторизован?>
            <? $address = UserAddress::model()->findAll(array('condition' => 'user_id=' . Yii::app()->user->id, 'order' => 'id DESC')); ?>
            <? if (!empty($address)) {// если у пользователя есть хоть один адрес?>
                <form class="scroll-pane" id="addAddressForm" method="POST">
                    <div id="popup_order_errors"></div>
                    <ul class="name-order" style="display:none;" id="newaddress">
                        <li>
                            <? echo CHtml::label('Город', 'Address[city_id]'); ?>
                            <select style="width:235px;margin-left:20px;" name="Address[city_id]">
                                <? foreach (City::model()->findAll() as $c){ ?>
                                <option value="<? echo $c->id ?>"><?= $c->name; ?>
                                    <? } ?>
                            </select>
                        </li>
                        <li><? echo CHtml::label('Улица', 'Address[street]'); ?><? echo CHtml::textField('Address[street]', ''); ?></li>
                        <li><? echo CHtml::label('Дом', 'Address[house]'); ?><? echo CHtml::textField('Address[house]', ''); ?></li>
                        <li><? echo CHtml::label('Подъезд', 'Address[podezd]'); ?><? echo CHtml::textField('Address[podezd]', ''); ?></li>
                        <li><? echo CHtml::label('Этаж', 'Address[storey]'); ?><? echo CHtml::textField('Address[storey]', ''); ?></li>
                        <li><? echo CHtml::label('Номер квартиры/офиса', 'Address[number]'); ?><? echo CHtml::textField('Address[number]', ''); ?></li>
                        <li><input type="submit" name="yt555" value="Добавить" id="yt555"
                                   style="background:#ADADAD;border-radius:6px;height:26px;width:274px;cursor:inherit;"/>
                        </li>
                    </ul>
                </form>
                <form method="POST" id="orderForm" class="scroll-pane">
                    <ul class="name-order" id="newaddress" id="orderForm">
                        <li><label>Ваше имя</label><input type="text" name="Order[name]"
                                                          value="<? echo !Yii::app()->user->isGuest ? $user->name : ""; ?>">
                        </li>
                        <li><label>Адрес
                                доставки</label><? echo $this->renderPartial('_address_select', array('address' => $address)); ?>
                        </li>
                        <li><label><a href="javascript:void(0);" onclick="$('#newaddress').toggle();">Добавить адрес</a></label>
                        </li>
                        <li><label>Контактный телефон<span>*</span></label><input type="text" name="Order[phone]"
                                                                                  value="<? echo !Yii::app()->user->isGuest ? $user->phone : ""; ?>">
                        </li>
                        <li><label style="vertical-align:top;padding-right:21px;">Комментарий к заказу</label><textarea
                                rows="7" cols="47" name="Order[text]"></textarea></li>
                    </ul>

                    <span class='timedelivery fastname'>Уточнить время и дату доставки</span>
                    <br>

                    <div id='timedelivery' style="display:none;">
                        <ul class="add-order fastorder">
                            <li>
                                <label style="vertical-align: top;">Время и дата доставки</label>

                                <div style="display:inline-block;width: 344px;text-align: left;">
                                    <label>
                                        <?= CHtml::radioButton('Address[fast]', true, array('value' => 1, 'class' => 'dateNo')) ?>
                                        <span>Как можно скорее</span>
                                    </label><br>
                                    <label>
                                        <?= CHtml::radioButton('Address[fast]', false, array('value' => 0, 'class' => 'dateYes')) ?>
                                        <span>Доставка ко времени</span>
                                    </label><br>

                                    <div id="timedel" style="display:none;text-align: left;">

                                        <?= CHtml::dropDownList('Address[date]', '', Order::getOrderDate(20), array('width' => '100')) ?>

                                        <? $beginWork = idate('H', strtotime($partner->work_begin_time));
                                        $endWork = idate('H', strtotime($partner->work_end_time));

                                        echo CHtml::dropDownList('Address[time]', '', Order::getTimeOfPeriod($beginWork, $endWork));

                                        echo CHtml::dropDownList('Address[timeMin]', '', Order::getTimeMinutesPeriod()); ?>
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                    <br><br>

                </form>
            <? } else { ?>
                <div id="popup_order_errors"></div>
                <form class="scroll-pane" id="orderForm" method="POST">
                    <ul class="name-order" id="newaddress">
                        <li><label>Ваше имя</label><input type="text" name="Order[name]"
                                                          value="<? echo !Yii::app()->user->isGuest ? $user->name : ""; ?>">
                        </li>
                        <li><label>Контактный телефон <span>*</span></label><input type="text" name="Order[phone]"
                                                                                   value="<? echo !Yii::app()->user->isGuest ? $user->phone : ""; ?>">
                        </li>
                        <li><label style="vertical-align:top;padding-right:21px;">Комментарий к заказу</label><textarea
                                rows="7" cols="47" name="Order[text]"></textarea></li>
                    </ul>
                    <ul class="add-order">
                        <li><label><b>АДРЕС ДОСТАВКИ</b></label></li>
                        <li><label>Улица <span>*</span></label><input type="text" name="Address[street]" value=""></li>
                        <li><label>Дом <span>*</span></label><input type="text" name="Address[house]" value=""></li>
                        <li><label>Этаж </label><input type="text" name="Address[storey]" value=""></li>
                        <li><label>Номер квартиры/офис </label><input type="text" name="Address[number]" value=""></li>
                    </ul>

                    <span class='timedelivery fastname'>Уточнить время и дату доставки</span>
                    <br>

                    <div id='timedelivery' style="display:none;">
                        <ul class="add-order fastorder">
                            <li>
                                <label style="vertical-align: top;">Время и дата доставки</label>

                                <div style="display:inline-block;width: 344px;text-align: left;">
                                    <label>
                                        <?= CHtml::radioButton('Address[fast]', true, array('value' => 1, 'class' => 'dateNo')) ?>
                                        <span>Как можно скорее</span>
                                    </label><br>
                                    <label>
                                        <?= CHtml::radioButton('Address[fast]', false, array('value' => 0, 'class' => 'dateYes')) ?>
                                        <span>Доставка ко времени</span>
                                    </label><br>

                                    <div id="timedel" style="display:none;text-align: left;">

                                        <?= CHtml::dropDownList('Address[date]', '', Order::getOrderDate(20), array('width' => '100')) ?>

                                        <? $beginWork = idate('H', strtotime($partner->work_begin_time));
                                        $endWork = idate('H', strtotime($partner->work_end_time));

                                        echo CHtml::dropDownList('Address[time]', '', Order::getTimeOfPeriod($beginWork, $endWork));

                                        echo CHtml::dropDownList('Address[timeMin]', '', Order::getTimeMinutesPeriod()); ?>
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                    <br><br>

                </form>
            <? } ?>
        <? } ?>
    </div>


    <div class="popup-o-rightside">
        <p>Сумма заказа: <? echo $sum; ?> <?php echo City::getMoneyKod($this->domain); ?></p>
        <? $delivery_cost = $partner->delivery_cost; ?>
        <? if ($partner->free_delivery_sum && $sum >= $partner->free_delivery_sum) {
            $delivery_cost = 0;
        } ?>
        <p>Стоимость
            доставки: <? echo $delivery_cost > 0 ? $delivery_cost . " " . City::getMoneyKod($this->domain) : "бесплатно"; ?></p>
        <hr>
        <p class="in-total">Итого: <span
                id="sum_itogo"><? echo $sum + $delivery_cost; ?></span> <?php echo City::getMoneyKod($this->domain); ?>
        </p>

        <? if (Yii::app()->user->role == User::USER || Yii::app()->user->role == User::PARTNER || Yii::app()->user->id == 989) { ?>
            <? if ($partner->allow_bonus) { ?>
                <div>На счету <?= User::getBonus(Yii::app()->user->id); ?> баллов</div>
                <p class='oBal'>Заказав, вы получите:
                    <span>
                        <img src='/images/iconBal.png'>
                        <span
                            id="bonus_sum"><? echo round(($sum + $partner->delivery_cost) * User::BONUS_PROCENT_FROM_ORDER, 0); ?></span> баллов
                    </span>
                </p>
            <? } ?>
        <? } ?>



        <? if ($partner->use_kassa) { ?>
            <span id="payment_title">Оплата</span>
            <div id="payment_method">
                <button id="use_cash" class="selected">Наличными</button>
                <button id="use_card">Картой-онлайн</button>
            </div>
        <? } ?>

        <input form="orderForm" class="checkout1" type="submit" name="yt1000" value="" id="yt1000"/>
		<span class="loader_order">
			<img src="/images/loader2.gif" width="50px">
		</span>


        <br>


        <? if ($partner->allow_bonus > 0 && (Yii::app()->user->role == User::USER || Yii::app()->user->role == User::PARTNER || Yii::app()->user->id == 989)) {
            $bonusCount = User::getBonus(Yii::app()->user->id);
            if (User::isEnoughBonus(Yii::app()->user->id, $sum + $partner->delivery_cost)) { ?>
                <input form="orderForm" class="checkoutFree" type="button" name="yt1001" value="" id="yt1001"/>
                <div class="orderFreeComment">У вас будет списано <span><?= ($sum + $partner->delivery_cost) * 4; ?>
                        баллов</div>
            <? } else { ?>
                <input form="orderForm_not_enough" class="checkoutFree checkoutFree_not_enough" type="button"
                       name="yt1001" value=""/>
                <div class="orderFreeComment" style="display:none;color:#fe8300;">Для бесплатного заказа у вас не
                    хватает <span><?= (($sum + $partner->delivery_cost) * 4) - $bonusCount; ?></span> баллов
                </div>
            <? } ?>
        <? } ?>
        <? if (Yii::app()->user->isGuest) { ?>
            <input form="orderForm_not_enough" class="checkoutFree checkoutFree_not_enough" type="button" name="yt1001"
                   value=""/>
            <div class="orderFreeComment" style="display:none;color:#fe8300;">Зарегистрируйтесь и заказывайте еду
                бесплатно
            </div>
            <br>

        <? } ?>
        <br>

        <? if (!Yii::app()->user->isGuest) {
            $user_id = Yii::app()->user->id;
            $promos = $partner->promos;
            $s = "(";
            foreach ($partner->promos as $promo) {
                $s .= $promo->id . ",";
            }
            $promos = Promo::model()->with('partners')->findAll(array("having" => "t1_c0 is null"));
            foreach ($promos as $promo) {
                $s .= $promo->id . ",";
            }
            $s .= "-1)";
            //$user_promo=false;
            $user_promo = UserPromo::model()->with('promo')->find("user_id=" . $user_id . " and used=0 and activated=1 and promo.id in " . $s);
            if (!$user_promo && $s != "(-1)") { ?>
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
                                    $.ajax({
                                        url: '<? echo CController::createUrl('/restorany/UsePromoKod');?>',
                                        type: "post",
                                        dataType: "json",
                                        cache: false,
                                        data: {"kod": kod, "partner_id":<?=$partner->id;?>},
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
    <script type="text/javascript">


    </script>
</div>

<script type="text/javascript">
    $(function () {
        $('#timedel select').styler();
        var f_click = true;
        $(".checkoutFree_not_enough").click(function () {
            if (f_click) {
                $("#orderForm").find(".register-order").show();
                f_click = false;
            }

        });
        <?if(City::getUserChooseCity() != 3){?>
        $("input[name='Order[phone]']").blur(function () {
            $(".order_message_hint").remove();
            var phone = $(this).val();
            var t = $(this);
            $.post('/order/checkphone', {phone: phone}, function (data) {
                $(".order_message_hint").remove();
                if (data == 'error') {
                    t.after("<div class='order_message_hint'>Кажется ваш номер неверный</div>");
                }
            });
        });
        <?}?>

    })
</script>
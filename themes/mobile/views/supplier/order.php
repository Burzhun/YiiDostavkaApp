<div id="pop-up-order">
    <div class="pop-header">
        <div id="close-pop-up2"></div>
        <img src="/images/bascet-img.png" width="64" height="65">

        <h3>Ваша корзина:</h3>
        <a href="#" class="edit-order">Редактировать заказ</a>
    </div>

    <div class="popup-order ">
        <? if (Yii::app()->user->role != User::USER) {// если пользователь гость?>
            <form class="scroll-pane" id="orderForm" method="POST">
                <div id="popup_order_errors"></div>
                <ul class="name-order ">
                    <li><label>Ваше имя</label><input type="text" name="Order[name]" value=""></li>
                    <li><label>Контактный телефон<span>*</span></label><input type="text" name="Order[phone]" value="">
                    </li>
                    <li><label>E-mail</label><input type="text" name="Order[email]" value=""></li>
                    <li><label style="vertical-align:top;padding-right:21px;">Комментарий к заказу</label><textarea
                            rows="7" name="Order[text]" cols="47"></textarea></li>
                </ul>
                <ul class="add-order">
                    <li><label><b>АДРЕС ДОСТАВКИ</b></label></li>
                    <li><label>Город</label><input readonly type="text"
                                                   value="<?= City::model()->findByPk(City::getUserChooseCity())->name; ?>">
                    </li>
                    <li><label>Улица<span>*</span></label><input type="text" name="Address[street]"></li>
                    <li><label>Дом<span>*</span></label><input type="text" name="Address[house]"></li>
                    <li><label>Этаж</label><input type="text" name="Address[storey]"></li>
                    <li><label>Номер квартиры/офиса</label><input type="text" name="Address[number]"></li>
                </ul>
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
                        <li><? echo CHtml::label('Этаж', 'Address[storey]'); ?><? echo CHtml::textField('Address[storey]', ''); ?></li>
                        <li><? echo CHtml::label('Номер квартиры/офиса', 'Address[number]'); ?><? echo CHtml::textField('Address[number]', ''); ?></li>
                        <li><input type="submit" name="yt555" value="Добавить" id="yt555"
                                   style="background:#ADADAD;border-radius:6px;height:26px;width:274px;cursor:inherit;"/>
                        </li>
                    </ul>
                </form>
                <form method="POST" id="orderForm" class="scroll-pane">
                    <ul class="name-order" id="newaddress" id="orderForm">
                        <li><label>Адрес
                                доставки</label><? echo $this->renderPartial('_address_select', array('address' => $address)); ?>
                        </li>
                        <li><label><a href="javasript:void(0);" onclick="$('#newaddress').toggle();">Добавить адрес</a></label>
                        </li>
                        <li><label>Контактный телефон<span>*</span></label><input type="text" name="Order[phone]"
                                                                                  value="<? echo !Yii::app()->user->isGuest ? $user->phone : ""; ?>">
                        </li>
                        <li><label style="vertical-align:top;padding-right:21px;">Комментарий к заказу</label><textarea
                                rows="7" cols="47" name="Order[text]"></textarea></li>
                    </ul>
                </form>
            <? } else { ?>
                <div id="popup_order_errors"></div>
                <form class="scroll-pane" id="orderForm" method="POST">
                    <ul class="name-order" id="newaddress">
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
                </form>
            <? } ?>
        <? } ?>
    </div>

    <div class="popup-o-rightside">
        <p>Сумма заказа: <? echo $sum; ?> <?php echo City::getMoneyKod(); ?></p>

        <p>Стоимость
            доставки: <? echo $partner->delivery_cost > 0 ? $partner->delivery_cost . " ".City::getMoneyKod() : "бесплатно"; ?></p>
        <hr>
        <p class="in-total">Итого: <span
                id="sum_itogo"><? echo $sum + $partner->delivery_cost; ?></span> <?php echo City::getMoneyKod(); ?></p>

        <div style="padding:0 0 14px 0"></div>

        <? if (Yii::app()->user->role == User::USER || Yii::app()->user->role == User::PARTNER) { ?>
            <p class='oBal'>Заказав, вы получите: <span><img src='/images/iconBal.png'><span
                        id="bonus_sum"><? echo round(($sum + $partner->delivery_cost) * User::BONUS_PROCENT_FROM_ORDER, 1); ?></span> баллов</span>
            </p>

        <? } ?>

        <input form="orderForm" class="checkout1" type="submit" name="yt1000" value="" id="yt1000"/>
    </div>
    <script type="text/javascript">


    </script>
</div>
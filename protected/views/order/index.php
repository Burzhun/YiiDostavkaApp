<div class="page">
    <div class="blok">
        <p class="crumbs"><a href="/">Главная</a> / <a href="/cart">Корзина</a> / Оформление заказа</p>

        <div class="page_basket">
            <div class="lo_basket"></div>
            <p class="title_basket">Оформить заказ</p>

            <div class="line_basket"></div>
            <div class="basket_home">
                <?php if (Yii::app()->user->role != User::USER) {// если пользователь гость?>
                    <form method="POST">
                        <div class="order_left">
                            <table>
                                <tr>
                                    <td><b>Ваше Имя *</b></td>
                                    <td><input type="text" name="Order[name]"
                                               value="<?php echo Yii::app()->user->role == User::USER ? $user->name : ""; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Контактный телефон *</b></td>
                                    <td><input type="text" name="Order[phone]"
                                               value="<?php echo Yii::app()->user->role == User::USER ? $user->phone : ""; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>E-mail *</b></td>
                                    <td><input type="text" name="Order[email]"
                                               value="<?php echo Yii::app()->user->role == User::USER ? $user->email : ""; ?>">
                                    </td>
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
                        <div class="order_right"><p class="title_basket"><?php echo $partner->name; ?></p>

                            <p class="price_p">Общая сумма заказа:<b><?php echo $sum; ?> руб</b></p>

                            <p class="price_p">Стоимость
                                доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost ." ".City::getMoneyKod() : "бесплатно"; ?></b>
                            </p>

                            <div class="line_order"></div>
                            <p class="price_order">Итого: <b> <?php echo $sum + $partner->delivery_cost; ?> руб</b></p>
                            <input type="submit"
                                   style="background:url(images/basket_button.png);width:158px;height:37px;" value="">
                        </div>
                    </form>
                <?php } else { // если пользователь авторизован?>
                    <?php $address = UserAddress::model()->findAll(array('condition' => 'user_id=' . Yii::app()->user->id, 'order' => 'id DESC')); ?>
                    <?php if (!empty($address)) {// если у пользователя есть хоть один адрес?>
                        <?php echo CHtml::form(); ?>
                        <table style="display:none;" id="newaddress">
                            <tr>
                                <td><b><?php echo CHtml::label('Улица', 'Address[street]'); ?></b></td>
                                <td>
                                    <!-- input type="text" name="Order[name]" value="<?php echo !Yii::app()->user->isGuest ? $user->name : ""; ?>" -->
                                    <?php echo CHtml::textArea('Address[street]', ''); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><b><?php echo CHtml::label('Дом', 'Address[house]'); ?></b></td>
                                <td><?php echo CHtml::textArea('Address[house]', ''); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo CHtml::label('Этаж', 'Address[storey]'); ?></b></td>
                                <td><?php echo CHtml::textArea('Address[storey]', ''); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo CHtml::label('Номер квартиры/офиса', 'Address[number]'); ?></b></td>
                                <td><?php echo CHtml::textArea('Address[number]', ''); ?></td>
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
                                        )); ?>
                                </td>
                            </tr>
                        </table>
                        <?php echo CHtml::endForm(); ?>

                        <div id="privet"></div>

                        <div class="order_left">
                            <form method="POST">
                                <table>
                                    <tr>
                                        <td><b>Адрес доставки</b></td>
                                        <td>
                                            <?php echo $this->renderPartial('_address_select', array('address' => $address)); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="javasript:void(0);" onclick="$('#newaddress').toggle();">Добавить
                                                адрес</a><br><br></td>
                                    </tr>
                                    <tr>
                                        <td><b>Контактный телефон *</b></td>
                                        <td><input type="text" name="Order[phone]"
                                                   value="<?php echo !Yii::app()->user->isGuest ? $user->phone : ""; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Комментарий к заказу *</b></td>
                                        <td><textarea rows="" name="Order[text]" cols=""></textarea></td>
                                    </tr>
                                </table>
                                <div class="order_right"><p class="title_basket"><?php echo $partner->name; ?></p>

                                    <p class="price_p">Общая сумма заказа:<b><?php echo $sum; ?> руб</b></p>

                                    <p class="price_p">Стоимость
                                        доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost . " ".City::getMoneyKod() : "бесплатно"; ?></b>
                                    </p>

                                    <div class="line_order"></div>
                                    <p class="price_order">Итого: <b> <?php echo $sum + $partner->delivery_cost; ?>
                                            руб</b></p>
                                    <input type="submit"
                                           style="background:url(images/basket_button.png);width:158px;height:37px;"
                                           value="">
                                </div>
                            </form>
                        </div>
                    <?php } else { // если у пользователя нет адресов куда доставлять, заставим заполнить адрес здесь?>
                        <div class="order_left">
                            <form method="POST">
                                <table>
                                    <tr>
                                        <td><b>Контактный телефон *</b></td>
                                        <td><input type="text" name="Order[phone]"
                                                   value="<?php echo !Yii::app()->user->isGuest ? $user->phone : ""; ?>">
                                        </td>
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
                                <div class="order_right"><p class="title_basket"><?php echo $partner->name; ?></p>

                                    <p class="price_p">Общая сумма заказа:<b><?php echo $sum; ?> руб</b></p>

                                    <p class="price_p">Стоимость
                                        доставки:<b><?php echo $partner->delivery_cost > 0 ? $partner->delivery_cost . " ".City::getMoneyKod() : "бесплатно"; ?></b>
                                    </p>

                                    <div class="line_order"></div>
                                    <p class="price_order">Итого: <b> <?php echo $sum + $partner->delivery_cost; ?>
                                            руб</b></p>
                                    <input type="submit"
                                           style="background:url(images/basket_button.png);width:158px;height:37px;"
                                           value="">
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
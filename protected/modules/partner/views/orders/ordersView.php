<? Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<div class="well">
    <br><br>
    <div>
        <div style="float:left;padding-right:35px;">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $order_item_model->search(array('order_id' => $order->id)),
                'id' => 'orderItemsGrid',
                'emptyText' => 'Ничего не найдено',
                'emptyText' => 'Ничего не найдено',
                'htmlOptions' => array('class' => 'table table-bordered'),
                'summaryText' => '',
                'columns' => array(
                    'id',
                    array(
                        'class' => 'CLinkColumn',
                        'header' => 'Название',
                        'labelExpression' => '$data->goods ? $data->goods->name : "Товар удален"',
                        'urlExpression' => '$data->goods ? "/partner/menu/product/".$data->goods->id : ""',
                    ),
                    array(
                        'type' => 'html',
                        'header' => 'Фото',
                        'value' => '$data->goods ? ($data->goods->img ? CHtml::image("/upload/goods/small".$data->goods->img, $data->goods->name, array("style"=>"width:150px")) : CHtml::image("/images/default.jpg", $data->goods->name, array("style"=>"width:150px"))) : ""',
                    ),
                    'price_for_one',
                    array(
                        'class' => 'editable.EditableColumn',
                        'header' => 'Изменить количество',
                        'name' => 'quantity',
                        'value' => '$data->quantity',
                        'editable' => array(
                            'title' => 'Изменить',
                            'type' => 'text',
                            'model' => $data,
                            'attribute' => 'quantity',
                            'url' => $this->createUrl('orders/updateOrderItemCount'),
                            'success' => 'js: function(data) {
				        $.fn.yiiGridView.update("orderItemsGrid");
				        $("#orderTotalPrice").html(data);
				    }'
                        ),
                        'filter' => false,
                    ),
                    array(
                        'header' => 'Сумма',
                        'value' => '$data->price_for_one*$data->quantity',
                    ),
                ),
            ));
            ?>
        </div>
        <div>
            <?php if ($order->status == Order::$APPROVED_SITE) {
                $disabled = array('disabled' => 'disabled', 'selected' => 'selected');
            } else {
                $disabled = array('disabled' => 'disabled');
            } ?>
            Установить статус заказа :
            <br><?php echo CHtml::dropDownList('status', $order->status, array(Order::$APPROVED_SITE => Order::$APPROVED_SITE, Order::$APPROVED_PARTNER => Order::$APPROVED_PARTNER, Order::$DELIVERED => Order::$DELIVERED, Order::$CANCELLED => Order::$CANCELLED), array('class' => 'statuseditor', 'options' => array(Order::$APPROVED_SITE => $disabled))); ?>
        </div>
        <? if ($this->domain->id > 1) { ?>
            <div style="max-width: 400px; float: left;">
                <button id="send_approve_sms" class="btn btn-success">Отправить смс клиенту</button>
                <br>

                <div style="float: left;max-width: 400px;">
                    Нажимая эту кнопку,клиенту будет отправлено информационное смс о том, что его заказ подтвержден.
                    Нажимая эту кнопку Вы соглашаетесь, что с вашего баланса будет списано 2 рубля за сервисное
                    оповещение клиента.
                </div>
            </div>
            <style>
                #send_approve_sms {
                    height: 50px;
                    font-size: 18px;
                    background-color: rgb(11, 167, 11);
                    border: medium none;
                    color: rgb(228, 228, 228);
                    border-radius: 10px;
                    cursor: pointer;
                    margin-top: 20px;
                }
            </style>
        <? if ($order->status == Order::$DELIVERED || $order->status == Order::$CANCELLED || $this->domain->id == 1){ ?>
            <style>
                #send_approve_sms {
                    background-color: grey;
                }
            </style>
        <? }else{ ?>
            <script>
                $(window).ready(function () {
                    $("#send_approve_sms").click(function () {
                        var order_id =<?=$order->id;?>;
                        $.post('/site/Sendusersms', {'order_id': order_id}, function (data) {
                            if (data == 'Ok') {
                                $("#send_approve_sms").text('Сообщение отправлено');
                            }
                        });
                    });
                });
            </script>
        <? } ?>
        <? } ?>
    </div>
    <div style="clear:both;float:none;"></div>
    <div style="position: relative;max-width: 500px;">
        <br>
        <style type="text/css">
            #orderInfo b {
                display: inline-block;
                width: 150px;
            }

            #orderInfo {
                border: 1px solid #ccc;
                display: inline-block;
                padding: 16px;
                border-radius: 4px;
            }
        </style>
        <b>Общая стоимость заказа : </b><span
            id="orderTotalPrice"><?php echo $order->sum; ?></span><br>
        <? if ($order->partner->delivery_cost) { ?>
            <? if ($order->partner->delivery_cost) { ?><b>
                <? $delivery_cost = $order->partner->delivery_cost; ?>
                <? if ($order->partner->free_delivery_sum && $order->sum >= $order->partner->free_delivery_sum) {
                    $delivery_cost = 0;
                } ?>
                Стоимость доставки : </b><?= $delivery_cost; ?><br><? } ?>
        <? } ?>
        <b>Статус заказа : </b><?php echo $order->status; ?><br>
        <? if ($order->cancel_reason) { ?>
            <b>Причина отмены
                : </b><?= $order->cancel_reason_text == '' ? ($order->cancel_reason == 0 ? '' : Order::getReasons()[$order->cancel_reason - 1]['name']) : $order->cancel_reason_text; ?>
            <br>
        <? } ?>
        <b>Время заказа : </b><?php echo $order->FormatDate(); ?><br>
        <b>Комментарии к заказу : </b><?php echo $order->info; ?><br>
        <br>

        <form id='orderInfo'>
            <?= CHtml::activeHiddenField($order, 'id') ?>
            <b>Заказчик : </b><?= CHtml::activeTextField($order, 'customer_name') ?><br>
            <b>Телефон : </b><?= CHtml::activeTextField($order, 'phone') ?><br>
            <br>
            <b>Город : </b><?= CHtml::activeTextField($order, 'city') ?><br>
            <b>Улица : </b><?= CHtml::activeTextField($order, 'street') ?><br>
            <b>Дом : </b><?= CHtml::activeTextField($order, 'house') ?><br>
            <b>Этаж : </b><?= CHtml::activeTextField($order, 'storey') ?><br>
            <b>Номер квартиры/офиса : </b><?= CHtml::activeTextField($order, 'number') ?><br>
            <? if ($order->comment) { ?>
                <b>Комментарий : </b> <?= $order->comment; ?><br><br>
            <? } ?>
            <b></b>
            <input id="submitOrderInfo" type='submit' value="Сохранить">

            <script>
                $(function () {
                    $('#submitOrderInfo').on('click', function () {
                        var form = $('#orderInfo').serialize();

                        $.ajax({
                            url: "/partner/orders/updateOrderInfo",
                            type: "post",
                            data: form,
                            success: function (data) {
                                var message = $.parseJSON(data);
                                if (message.success) {
                                    alert(message.success);
                                } else {
                                    alert(message.errors);
                                }
                            }
                        });
                        return false;
                    })
                });
            </script>

        </form>
    </div>
</div>
<style>
    .cancel_reason span {
        cursor: pointer;
    }
</style>
<div id="dialog" style="background-color: white;width: 800px;display: inline-block;border-radius: 10px;
padding: 5px;display: none;">
    <div class="modal-header" style="font-size: 18px;margin: 10px;">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <span class="modal-title">Выберите причину отказа</span>
    </div>
    <? foreach (Order::getReasons() as $index => $reason) { ?>
        <div class="cancel_reason">
            <input type='radio' name="reason" class='r_button' id='<?= $reason['id'] ?>'><span
                id="r_text_<?= $reason['id'] ?>"><?= $reason['name'] ?></span><br>
        </div>
    <? } ?>
    <div class="cancel_reason">
        <input type='radio' name="reason" class='r_button' id='<?= count(Order::getReasons()) + 1 ?>'><span
            id="r_text_<?= count(Order::getReasons()) + 1 ?>">Другое</span><br>
    </div>
    <input type="text" id="r_text" style="width: 700px;margin-left:15px;margin-top:10px;display: none"><br>
    <button class="btn btn-success" style="margin: 15px;width: 50px;" id="set_reason">Ok</button>
</div>
<script>
    var prev_val = "";
    $(document).on('focus', '.statuseditor', function (event) {
        var prev_val = $(this).val();
    });
    $("#dialog .close").click(function () {
        $(this).val(prev_val);
        $("#dialog").dialog("close");
    });
    $(document).on('change', '.statuseditor', function (event) {
        var reason = "";
        $("#dialog .r_button").last().click(function () {
            $("#r_text").show();
        });
        var p_id = <?=$order->id?>;
        var status = $(this).val();
        switch (status) {
            case "<?=Order::$APPROVED_SITE?>?>":
                status = 1;
                break;
            case "<?=Order::$APPROVED_PARTNER?>":
                status = 2;
                break;
            case "<?=Order::$SENT?>":
                status = 3;
                break;
            case "<?=Order::$DELIVERED?>":
                status = 4;
                break;
            case "<?=Order::$CANCELLED?>":
                status = 5;
                break;
        }

        if (status == 5) {
            $("#dialog").dialog({width: "800px"}
            );
            $("#dialog .btn").click(function (event) {
                event.stopImmediatePropagation();
                var r_id = $("input[name=reason]:checked").attr('id');
                var r_text = "";
                if (!r_id) {
                    return false;
                }
                if (r_id == $("input[name=reason]").last().attr('id')) {
                    r_text = $("#dialog #r_text").val();
                    if (r_text == '') {
                        return false;
                    }
                } else {
                    r_text = $("#r_text_" + r_id).html();
                }
                $("#dialog").dialog("close");
                $.ajax({
                    url: "/partner/orders/id/" + p_id + "/changeStatus?status=" + status,
                    type: "post",
                    //cache:false,
                    data: {"product": p_id, "status": status, "reason": r_id, "reason_text": r_text},
                    success: function (data) {
                        $.fn.yiiGridView.update("order-grid");
                    }
                });
            });
        }
        else {
            $.ajax({
                url: "/partner/orders/id/" + p_id + "/changeStatus?status=" + status,
                type: "post",
                data: {"product": p_id, "status": status},
                success: function (data) {
                    $.fn.yiiGridView.update("order-grid");
                }
            });
        }
    });
</script>
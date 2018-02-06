<? Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>

<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<br><br>
<style>
    .cancel_reason span {
        cursor: pointer;
    }
</style>
<div class="well">
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
                        url: "/admin/order/id/" + p_id + "/changeStatus?status=" + status,
                        type: "post",
                        data: {"product": p_id, "status": status, "reason": r_id, "reason_text": r_text},
                        success: function (data) {
                            $.fn.yiiGridView.update("order-grid");
                        }
                    });
                });
            }
            else {
                $.ajax({
                    url: '/admin/order/id/' + p_id + '/changeStatus?status=' + status,
                    type: "post",
                    data: {"product": p_id, "status": status},
                    success: function (data) {
                        $.fn.yiiGridView.update("order-grid");
                    }
                });
            }
            $("body").on('click', '.cancel_reason span', function () {
                $(this).parent().find("input").click();
            });
        });
    </script>
    <div style="float:left;padding-right:35px;">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $order_item_model->search(array('order_id' => $order->id)),
            'htmlOptions' => array('class' => 'table table-bordered'),
            'emptyText' => 'Ничего не найдено',
            'summaryText' => '',
            'columns' => array(
                'id',
                array(
                    'class' => 'CLinkColumn',
                    'header' => 'Название',
                    'labelExpression' => '$data->goods ? $data->goods->name : "Товар был удален"',
                    'urlExpression' => '$data->goods ? "/admin/partner/id/".$data->goods->partner_id."/product/".$data->goods->id : ""',
                ),
                array(
                    'type' => 'html',
                    'header' => 'Фото',
                    'value' => '($data->goods && $data->goods->img) ? CHtml::image("/upload/goods/".$data->goods->img, $data->goods->name, array("style"=>"width:150px")) : ""',
                ),
                'price_for_one',
                'quantity',
                array(
                    'header' => 'Сумма',
                    'value' => '$data->price_for_one*$data->quantity',
                ),
            ),
        ));
        ?>
    </div>
    <div style=" vertical-align: top;">
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
                Нажимая эту кнопку Вы соглашаетесь, что с вашего баланса будет списано 2 рубля за сервисное оповещение
                клиента.
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
        <? if ($order->status == Order::$DELIVERED || $order->status == Order::$CANCELLED){ ?>
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
    <br>
    <div style="float: left;width:50%;">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#info">Информация о заказе</a></li>
            <li><a data-toggle="tab" href="#log">Логи</a></li>
        </ul>

        <div class="tab-content">
            <div id="info" class="tab-pane fade in active">
                <b>Общая стоимость заказа : </b><?php echo $order->sum; ?><br>
                <? if ($order->partner->delivery_cost) { ?>
                    <? if ($order->partner->delivery_cost) { ?><b>
                        <? $delivery_cost = $order->partner->delivery_cost; ?>
                        <? if ($order->partner->free_delivery_sum && $order->sum >= $order->partner->free_delivery_sum) {
                            $delivery_cost = 0;
                        } ?>
                        Стоимость доставки : </b><?= $delivery_cost; ?><br><? } ?>
                <? } ?>
                <b>Статус заказа : </b><?php echo $order->status; ?><br>
                <b>Время заказа : </b><?php echo $order->date; ?><br>
                <br>
                <b>Заказчик : </b><?php echo $order->customer_name ?><br>
                <b>Телефон : </b><?php echo $order->phone; ?><br>
                <b>Комментарии к заказу : </b><?php echo $order->info; ?><br>
                <br>
                <b>Город : </b><?php echo $order->city; ?><br>
                <b>Улица : </b><?php echo $order->street; ?><br>
                <b>Дом : </b><?php echo $order->house; ?><br>
                <b>Этаж : </b><?php echo $order->storey; ?><br>
                <b>Номер квартиры/офиса : </b><?php echo $order->number; ?><br>
            </div>
            <div id="log" class="tab-pane fade in">
                <table>
                    <?
                    /** @var Action[] $logs */
                    $logs = Action::model()->findAll(array('condition' => 'order_id = '.$order->id, 'order' => 'date DESC'));
                    if($logs)
                    {
                        foreach ($logs as $log) {
                            echo '<tr><td>'.$log->date.' - '.$log->info;echo '</td></tr>';
                        }
                    } else {
                        echo 'Действий по товару не обноруженно';
                    } ?>
                </table>
            </div>
        </div>
    </div>
</div>
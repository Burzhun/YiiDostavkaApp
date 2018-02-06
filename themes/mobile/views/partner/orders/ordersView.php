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
                        'labelExpression' => '($data->goods ? $data->goods->name : "Товар удален") ."<br>". ($data->goods ? CHtml::image("/upload/goods/small".$data->goods->img, $data->goods->name, array("style"=>"width:150px")) : "")',
                        'urlExpression' => '$data->goods ? "/partner/menu/product/".$data->goods->id : ""',
                    ),
                    array(
                        'class' => 'editable.EditableColumn',
                        'header' => 'Кол-во',
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
            <br><?php echo CHtml::dropDownList('status', $model->status, array(Order::$APPROVED_SITE => Order::$APPROVED_SITE, Order::$APPROVED_PARTNER => Order::$APPROVED_PARTNER, Order::$DELIVERED => Order::$DELIVERED, Order::$CANCELLED => Order::$CANCELLED), array('class' => 'statuseditor', 'options' => array(Order::$APPROVED_SITE => $disabled))); ?>
        </div>
    </div>
    <div style="clear:both;float:none;"></div>
    <div>
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
            id="orderTotalPrice"><?php echo $order->sum ?></span><br>
        <? if ($order->partner->delivery_cost) { ?><b>Стоимость доставки : </b><?= $order->partner->delivery_cost; ?>
            <br><? } ?>
        <b>Статус заказа : </b><?php echo $order->status; ?><br>
        <b>Время заказа : </b><?php echo $order->date; ?><br>
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

<script>
    $(document).on('change', '.statuseditor', function (event) {
        var p_id = <?php echo $order->id?>;
        var status = $(this).val();
        switch (status) {
            case "<?=Order::$APPROVED_SITE?>":
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
        $.ajax({
            url: "/partner/orders/id/" + p_id + "/changeStatus?status=" + status,
            type: "post",
            data: {"product": p_id, "status": status},
            success: function (data) {
                $.fn.yiiGridView.update("order-grid");
            }
        });
    });
</script>
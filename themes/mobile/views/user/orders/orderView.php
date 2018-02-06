<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
        <br><br>
        <?php if ($order->status == Order::$APPROVED_SITE) { ?>
            <a href="/user/orders/cancelled/id/<?php echo $order->id ?>"
               class="btn btn-danger">Отменить заказ</a><br><br>
        <?php } ?>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $order_item_model->search(array('order_id' => $order->id)),
            'emptyText' => 'Ничего не найдено',
            'htmlOptions' => array('class' => 'table table-bordered'),
            'summaryText' => '',
            'columns' => array(
                'id',
                array(
                    'class' => 'CLinkColumn',
                    'header' => 'Название',
                    'labelExpression' => '$data->goods ? $data->goods->name : "Товар был удален"',
                ),
                array(
                    'type' => 'raw',
                    'header' => 'Фото',
                    'value' => '$data->goods ? CHtml::image("/upload/goods/small".$data->goods->img, $data->goods->name, array("style"=>"max-width:150px;max-height:150px;")) : ""',
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
        <br>

        <b>Общая стоимость заказа :</b> <?php echo Order::totalPriceAdmin($order->sum,$order->forbonus) ?><br>
        <b>Статус заказа :</b> <?php echo $order->status; ?><br>
        <br>
        <b>Поставщик :</b> <?php echo $order->partner->name ?><br>
        <b>Телефон :</b> <?php echo $order->partner->user->phone; ?><br>
        <br>
        <b>Город :</b> <?php echo $order->city; ?><br>
        <b>Улица :</b> <?php echo $order->street; ?><br>
        <b>Дом :</b> <?php echo $order->house; ?><br>
        <b>Этаж :</b> <?php echo $order->storey; ?><br>
        <b>Номер квартиры/офиса :</b> <?php echo $order->number; ?><br>
    </div>
</div>
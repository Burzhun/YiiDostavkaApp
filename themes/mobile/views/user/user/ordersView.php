3563634
<?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $order_item_model->search(array('order_id' => $order->id)),
    'filter' => $order_item_model,
    'emptyText' => 'Ничего не найдено',
    'columns' => array(
        'id',
        array(
            'class' => 'CLinkColumn',
            'header' => 'Название',
            'labelExpression' => '$data->goods->name',
            'urlExpression' => '"/admin/goods/id/".$data->goods->id."/view"',
        ),
        array(
            'header' => 'Фото',
            'value' => '$data->goods->img',
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

Общая стоимость заказа : <?php echo Order::totalPriceAdmin($order->sum,$order->forbonus) ?><br>
Статус заказа : <?php echo $order->status; ?><br>
<br>
Поставщик : <?php echo $order->partner->name ?><br>
Телефон : <?php echo $order->partner->user->phone; ?><br>
<br>
Заказчик : <?php echo $model->name ?><br>
Телефон : <?php echo $model->phone; ?><br>
<br>
Город : <?php echo $order->city; ?><br>
Улица : <?php echo $order->street; ?><br>
Дом : <?php echo $order->house; ?><br>
Этаж : <?php echo $order->storey; ?><br>
Номер квартиры/офиса : <?php echo $order->number; ?><br>
<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $order_item_model->search(array('order_id' => $order->id)),
        //'filter'=>$order_item_model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            'id',
            array(
                'class' => 'CLinkColumn',
                'header' => 'Название',
                'labelExpression' => '$data->goods ? $data->goods->name : "Товар удален"',
                'urlExpression' => '$data->goods ? "/admin/partner/id/".$data->goods->partner_id."/product/".$data->goods->id : ""',
            ),
            array(
                'type' => 'html',
                'header' => 'Фото',
                'value' => '($data->goods && $data->goods->img) ? CHtml::image("/upload/goods/small".$data->goods->img, $data->goods->name, array("style"=>"width:150px")) : ""',
                /*'header'=>'Фото',
                'value'=>'$data->goods ? $data->goods->img : ""',*/
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

    <b>Общая стоимость заказа : </b><?php echo $order->sum; ?><br>
    <b>Статус заказа : </b><?php echo $order->status; ?><br>
    <b>Время заказа : </b><?php echo $order->date; ?><br>
    <br>
    <b>Поставщик : </b><?php echo $order->partner->name ?><br>
    <b>Телефон : </b><?php echo $order->partner->user->phone; ?><br>
    <? /*
<br>
<b>Заказчик : </b><?php echo $model->name?><br>
<b>Телефон : </b><?php echo $model->phone;?><br>
*/ ?>
    <br>
    <b>Город : </b><?php echo $order->city; ?><br>
    <b>Улица : </b><?php echo $order->street; ?><br>
    <b>Дом : </b><?php echo $order->house; ?><br>
    <b>Этаж : </b><?php echo $order->storey; ?><br>
    <b>Номер квартиры/офиса : </b><?php echo $order->number; ?><br>
</div>
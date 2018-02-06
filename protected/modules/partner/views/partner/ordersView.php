<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<br><br>
<div class="well">
    Установить статус заказа :
    <form
        method="post"><?php echo ZHtml::enumDropDownList($order, "status", array("id" => $order->id, "class" => "statuseditor", "style" => "width:150px", "options" => array(Order::$APPROVED_SITE => $order->status == Order::$APPROVED_SITE ? array("disabled" => "disabled", "selected" => "selected") : array("disabled" => "disabled")))); ?>
        <br><input type="submit" name="editstatus" id="editstatus" class="btn btn-primary"></form>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $order_item_model->search(array('order_id' => $order->id)),
        //'filter'=>$order_item_model,
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
                'value' => '($data->goods && $data->goods->img) ? CHtml::image("/upload/goods/small".$data->goods->img, $data->goods->name, array("style"=>"width:150px")) : ""',
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
    <b>Заказчик : </b><?php echo $order->customer_name ?><br>
    <b>Телефон : </b><?php echo $order->phone; ?><br>
    <br>
    <b>Город : </b><?php echo $order->city; ?><br>
    <b>Улица : </b><?php echo $order->street; ?><br>
    <b>Дом : </b><?php echo $order->house; ?><br>
    <b>Этаж : </b><?php echo $order->storey; ?><br>
    <b>Номер квартиры/офиса : </b><?php echo $order->number; ?><br>
</div>
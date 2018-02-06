<?
Diva::linkMainCsss();
Diva::linkMainJss();
?>

<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'order-grid',
        'dataProvider' => $order_model->search(array('user_id' => $model->id)),
        'filter' => $order_model,
        //'cssFile'=>'css/gridView/partnerOrderGrid.css',
        'rowCssClassExpression' => 'Order::getRowColor($data->status,$data->forbonus)',
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            //'id',
            array(
                'name' => 'id',
                'type' => 'raw',
                'header' => '№',
                'value' => '$data->id',
                'filter' => CHtml::textField('Order[id]', $order_model->id, array('class' => 'input-mini', 'style' => 'width:25px')),
            ),
            /*array(
                'type'=>'raw',
                'header'=>'Заказчик',
                'name'=>'user_id',
                'value'=>'$data->user ? CHtml::link($data->customer_name, array("/admin/user/id/".$data->id."/orders/")) : $data->customer_name',
                'filter'=>CHtml::textField("User[user_id]", $order_model->user_id, array("class"=>"input-mini")),
            ),*/
            array(
                'name' => 'partners_id',
                'type' => 'raw',
                //'value'=>'CHtml::link($data->partner ? $data->partner->name : "", array("/admin/partner/id/".$data->partners_id."/orders/"))',
                'value' => 'CHtml::link($data->partner ? $data->partner->name : "", "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getPartnerRecvizit/".$data->partners_id, "_blank", "400", "350")))',
                'filter' => CHtml::textField("Partner[partners_id]", $order_model->partners_id, array("class" => "input-mini")),
                'cssClassExpression' => '"table_link_cell"',
            ),
            array(
                'class' => 'CLinkColumn',
                'header' => 'Заказы',
                'labelExpression' => '"Заказ"',
                'urlExpression' => '"/admin/user/id/".$data->user_id."/orders/".$data->id',
                'cssClassExpression' => '"table_link_cell"',
            ),
            array(
                'header' => 'Cумма заказа',
                'value' => 'Order::totalPriceAdmin($data->sum,$data->forbonus, true)',
                //'filter'=>CHtml::textField("Order[date]", $order_model->date, array("class"=>"input-mini", 'style'=>'width:110px;')),
            ),
            array(
                'name' => 'date',
                'filter' => CHtml::textField("Order[date]", $order_model->date, array("class" => "input-mini", 'style' => 'width:110px;')),
            ),
            array(
                'type' => 'raw',
                'header' => 'Адрес',
                //'name'=>'user_id',
                'value' => '$data->city.", ".$data->street',
                'headerHtmlOptions' => array('style' => 'width:10px; textdecoration:none'),
            ),
            array(
                //'type'=>'raw',
                'name' => 'approved_site',
                'type' => 'html',
                'value' => '"<img src=\'/images/clock-img.png\'>".$data->approved_site',
                'cssClassExpression' => '$data->status==Order::$APPROVED_SITE && (strtotime("-5 minutes") > strtotime(date("Y-m-d", strtotime($data->date))." ".$data->approved_site)) ? "blink_order" : ""',
                'htmlOptions' => array('class' => "approved_site_cell", 'style' => 'background:url("/images/blue-fon.png") repeat-x;background-size:contain;color:#ffffff;'),
                'filter' => CHtml::textField("Order[approved_site]", $order_model->approved_site, array("class" => "input-mini")),
            ),
            array(
                //'type'=>'raw',
                'name' => 'approved_partner',
                'type' => 'html',
                'value' => '"<img src=\'/images/clock-img.png\'>".$data->approved_partner',
                'cssClassExpression' => '$data->status==Order::$APPROVED_PARTNER && (strtotime("-30 minutes") > strtotime(date("Y-m-d", strtotime($data->date))." ".$data->approved_partner)) ? "blink_order" : ""',
                'htmlOptions' => array('class' => 'approved_partner', 'style' => 'background:url("/images/yellow-fon.png") repeat-x;background-size:contain;color:#000001;',),
                'filter' => CHtml::textField("Order[approved_partner]", $order_model->approved_partner, array("class" => "input-mini")),
            ),
            array(
                'name' => 'delivered',
                'htmlOptions' => array('class' => 'delivered', 'style' => 'background:url("/images/green-fon.png") repeat-x;background-size:contain;color:#ffffff;'),
                'filter' => CHtml::textField("Order[delivered]", $order_model->delivered, array("class" => "input-mini")),
            ),
            array(
                'name' => 'cancelled',
                'htmlOptions' => array('class' => 'cancelled', 'style' => 'background: rgba(255, 255, 255, 1);color:#000000;'),
                'filter' => CHtml::textField("Order[cancelled]", $order_model->cancelled, array("class" => "input-mini")),
            ),
            array(
                'header' => 'Статус заказа',
                'type' => 'raw',
                'value' => '$data->status',//'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor", "style"=>"width:150px"))',
                'name' => 'status',
                'htmlOptions' => array('class' => 'cancelled', 'style' => 'background: rgba(245, 245, 245, 1);color:#000000;'),
                'filter' => CHtml::activeDropDownList($order_model, 'status', ZHtml::enumItem($order_model, 'status'), array('empty' => '', 'style' => 'width:150px')),
            ),
        ),
    ));
    ?>
</div>
<script>
    setInterval(function () {
        $.fn.yiiGridView.update("order-grid");
    }, 60000);
</script>
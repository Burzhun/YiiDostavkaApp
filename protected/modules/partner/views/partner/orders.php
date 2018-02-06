<audio id="myaudio">
    <source src="http://dostavka05.ru/images/new_order.mp3" type="audio/mp3">
    <source src="http://dostavka05.ru/images/new_order.ogg" type="audio/ogg">
    <source src="http://dostavka05.ru/images/new_order.wav" type="audio/wav">
    Ваш браузер не поддерживает звуки.
</audio>
<audio id="mybell">
    <source src="http://dostavka05.ru/images/bell.mp3" type="audio/mp3">
    <source src="http://dostavka05.ru/images/bell.ogg" type="audio/ogg">
    <source src="http://dostavka05.ru/images/bell.wav" type="audio/wav">
    Ваш браузер не поддерживает звуки.
</audio>
<script type="text/javascript">
    function playAudio() {
        oAudio = document.getElementById('myaudio');
        oAudio.play();
    }
    function playBell() {
        oAudio1 = document.getElementById('mybell');
        oAudio1.play();
    }
</script>

<?
Diva::linkMainCsss();
Diva::linkMainJss();
?>

<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'order-grid',
        'dataProvider' => $order_model->search(array('one_partners_id' => $model->id)),
        'filter' => $order_model,
        'rowCssClassExpression' => 'Order::getRowColor($data->status,$data->forbonus)',
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'type' => 'raw',
                'header' => 'Заказчик',
                'name' => 'user_id',
                'cssClassExpression' => '"table_link_cell"',
                'value' => 'CHtml::link($data->customer_name, "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getUserRecvizit/".$data->id, "_blank", "400", "350")))',
                'filter' => CHtml::textField("User[user_id]", $order_model->user_id, array("class" => "input-mini")),
            ),
            array(
                'class' => 'CLinkColumn',
                'header' => 'Заказы',
                'labelExpression' => '"Заказ"',
                'urlExpression' => '"/admin/partner/id/".$data->partners_id."/orders/".$data->id',
                'cssClassExpression' => '"table_link_cell"',
                'headerHtmlOptions' => array('style' => 'max-width:100px;'),
            ),
            array(
                'header' => 'Cумма заказа',
                'value' => 'Order::totalPriceAdmin($data->sum,$data->forbonus, true)',
            ),
            array(
                'name' => 'date',
                'filter' => CHtml::textField("Order[date]", $order_model->date, array("class" => "input-mini", 'style' => 'max-width:110px;')),
            ),
            array(
                'type' => 'raw',
                'header' => 'Адрес',
                'value' => '$data->city.", ".$data->street',
                'headerHtmlOptions' => array('style' => 'width:200px; textdecoration:none'),
            ),
            array(
                'name' => 'approved_site',
                'type' => 'html',
                'value' => '"<img src=\'/images/clock-img.png\'>".$data->approved_site',
                'cssClassExpression' => '$data->status==Order::$APPROVED_SITE && (strtotime("-5 minutes") > strtotime(date("Y-m-d", strtotime($data->date))." ".$data->approved_site)) ? "blink_order" : ""',
                'htmlOptions' => array('class' => "approved_site_cell", 'style' => 'background:url("/images/blue-fon.png") repeat-x;background-size:contain;color:#ffffff;'),
                'filter' => CHtml::textField("Order[approved_site]", $order_model->approved_site, array("class" => "input-mini")),
            ),
            array(
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
                'htmlOptions' => array('class' => 'cancelled', 'style' => 'background: rgba(255, 255, 255, 1);color:#000000;'/*, 'date'=>date('Y-m-d', strtotime($data->date))*/),
                'filter' => CHtml::textField("Order[cancelled]", $order_model->cancelled, array("class" => "input-mini")),
            ),
            array(
                'header' => 'Статус заказа',
                'type' => 'raw',
                'value' => 'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor", "style"=>"width:150px", "options"=>array(Order::$APPROVED_SITE=> $data->status == Order::$APPROVED_SITE ? array("disabled"=>"disabled", "selected"=>"selected") : array("disabled"=>"disabled"))))',
                'name' => 'status',
                'htmlOptions' => array('style' => 'background: rgba(245, 245, 245, 1);'),
                'filter' => CHtml::activeDropDownList($order_model, 'status', ZHtml::enumItem($order_model, 'status'), array('empty' => '', 'style' => 'width:150px')),
            ),
        ),
    ));
    ?>

</div>
<script>
    //при изменении статуса
    $(document).on('change', '.statuseditor', function (event) {
        var p_id = $(this).attr('id');
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
            url: "/admin/partner/id/" + p_id + "/changeStatus?status=" + status,
            type: "post",
            data: {"product": p_id, "status": status},
            success: function (data) {
                $.fn.yiiGridView.update("order-grid");
            }
        });
    });

    /*setInterval(function () {
        //обновляем таблицу заказов
        $.fn.yiiGridView.update("order-grid");
        //проверяем наличие новых заказов. Если такие найдены, то оповещаем звуком
        $.ajax({
            url: "/admin/partner/id/<?=$_GET['id']?>/checkNewOrderForMusik?status=" + status,
            type: "post",
            //cache:false,
            success: function (data) {
                if (data == 1) {
                    playAudio();
                }
            }
        });
        $.ajax({
            url: "/admin/partner/id/<?=$_GET['id']?>/overdueOrders?status=" + status,
            type: "post",
            success: function (data) {
                if (data == 1) {
                    playBell();
                }
            }
        });
    }, 30000);*/

    var lastActionId = <?=Action::getLastId()?>;
    setInterval(function () {
        $.ajax({
            url: "/partner/orders/chackOrders",
            type: "post",
            dataType: "json",
            data: {'lastActionId': lastActionId, 'partner_id': <?=$model->id?>},
            success: function (data) {
                if (data['haveNewOrders'] && data['haveNewOrders'] == 1)
                    playAudio();
                if (data['haveOverdueOrders'] && data['haveOverdueOrders'] == 1)
                    playBell();
                if (data['newActions'] && data['newActions'] == 1) {
                    $.fn.yiiGridView.update("order-grid");
                    lastActionId = data['lastActionId'];
                    console.log(lastActionId);
                }
            }
        });
    }, 30000);
</script>
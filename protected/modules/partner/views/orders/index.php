<? Yii::app()->clientScript->registerCoreScript('jquery.ui');?>
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
<? if ($model->id == 59) { ?>
    <script>
        setInterval(function () {
            playAudio();
        }, 5000);
    </script>
<? } ?>
<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<div style="padding-left:15px;">
    <a href="/partner/orders"  class="btn btn-primary">Показать все заказы</a>
    <a href="/partner/orders?orders_status=active"  class="btn btn-success">Показать активные заказы</a>
</div>
<div class="well">
    <?php if (!$model->status) {
        $data=$order_model->search(array('one_partners_id' => $model->id,'blocked'=>1));
     } else {
        $data = $order_model->search(array('one_partners_id' => $model->id));
    }
         $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'order-grid',
            'dataProvider' => $data,
            'filter' => $order_model,
            //'cssFile'=>'css/gridView/partnerOrderGrid.css',
            'rowCssClassExpression' => 'Order::getRowColor($data->status,$data->forbonus)',
            'emptyText' => 'Ничего не найдено',
            'htmlOptions' => array('class' => 'table table-bordered'),
            'summaryText' => '',
            'columns' => array(
                //'id',
                //array(
                //	'name'=>'id',
                //	'type'=>'raw',
                //	'header'=>'№',
                //	'value'=>'$data->id',
                //	'filter'=>CHtml::textField('Order[id]', $order_model->id, array('class'=>'input-mini', 'style'=>'width:30px')),
                //),
                array(
                    'type' => 'raw',
                    'header' => 'Заказчик',
                    'name' => 'user_id',
                    //'value'=>'$data->customer_name',
                    'value' => 'CHtml::link($data->customer_name, "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getUserRecvizit/".$data->id, "_blank", "400", "350")))',
                    'filter' => CHtml::textField("User[user_id]", $order_model->user_id, array("class" => "input-mini")),
                    'cssClassExpression' => '"table_link_cell"',
                ),
                //array(
                //	'name'=>'partners_id',
                //	'type'=>'raw',
                //	'value'=>'CHtml::link($data->partner ? $data->partner->name : "", array("/admin/partner/id/".$data->partners_id."/orders/"))',
                //	'filter'=>CHtml::textField("Partner[partners_id]", $order_model->partners_id, array("class"=>"input-mini")),
                //),
                array(
                    'class' => 'CLinkColumn',
                    'header' => 'Заказы',
                    'labelExpression' => '"Заказ"',
                    'urlExpression' => '"/partner/orders/".$data->id',
                    'cssClassExpression' => '"table_link_cell"',
                    'headerHtmlOptions' => array('style' => 'min-width:100px;max-width:100px;'),
                ),
                array(
                    'header' => 'Cумма заказа',
                    'value' => 'Order::totalPriceAdmin($data->sum,$data->forbonus, true)',
                    //'filter'=>CHtml::textField("Order[date]", $order_model->date, array("class"=>"input-mini", 'style'=>'width:110px;')),
                ),
                array(
                    'name' => 'date',
                    'value'=>'$data->FormatDate()',
                    'filter' => CHtml::textField("Order[date]", $order_model->date, array("class" => "input-mini", 'style' => 'width:110px;')),
                ),
                array(
                    'type' => 'raw',
                    'header' => 'Адрес',
                    //'name'=>'user_id',
                    'value' => '$data->city.", ".$data->street',
                    'headerHtmlOptions' => array('style' => 'textdecoration:none'),
                ),
                array(
                    'header' => 'Время заказа',
                    'name' => 'approved_site',
                    'type' => 'html',
                    'value' => '$data->approvedHtml',
                    'htmlOptions' => array('class' => 'appr-td'),
                    'filter' => false,
                ),
               /* array(
                    'type' => 'html',
                    'value' => '"<img src=\'/images/clock-img.png\'>".$data->approved_site',
                    'name' => 'approved_site',
                    'cssClassExpression' => '$data->status==Order::$APPROVED_SITE && (strtotime("-5 minutes") > strtotime(date("Y-m-d", strtotime($data->date))." ".$data->approved_site)) ? "blink_order" : ""',
                    'htmlOptions' => array('class' => 'approved_site_cell', 'style' => 'background:url("../images/blue-fon.png") repeat-x;background-size:contain;color:#ffffff;'),
                    'filter' => CHtml::textField("Order[approved_site]", $order_model->approved_site, array("class" => "input-mini")),
                ),
                array(
                    'type' => 'html',
                    'value' => '"<img src=\'/images/clock-img.png\'>".$data->approved_partner',
                    'name' => 'approved_partner',
                    'cssClassExpression' => '$data->status==Order::$APPROVED_PARTNER && (strtotime("-30 minutes") > strtotime(date("Y-m-d", strtotime($data->date))." ".$data->approved_partner)) ? "blink_order" : ""',
                    'htmlOptions' => array('class' => 'approved_partner', 'style' => 'background:url("../images/yellow-fon.png") repeat-x;background-size:contain;color:#000000;'),
                    'filter' => CHtml::textField("Order[approved_partner]", $order_model->approved_partner, array("class" => "input-mini")),
                ),
                array(
                    'name' => 'delivered',
                    'htmlOptions' => array('class' => 'delivered', 'style' => 'background:url("../images/green-fon.png") repeat-x;background-size:contain;color:#ffffff;'),
                    'filter' => CHtml::textField("Order[delivered]", $order_model->delivered, array("class" => "input-mini")),
                ),
                array(
                    'name' => 'cancelled',
                    'htmlOptions' => array('class' => 'cancelled', 'style' => 'background: rgba(255, 255, 255, 1);color:#000000;'),
                    'filter' => CHtml::textField("Order[cancelled]", $order_model->cancelled, array("class" => "input-mini")),
                ),*/
                array(
                    'header' => 'Статус заказа',
                    'type' => 'raw',
                    'value' => 'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor", "style"=>"width:150px", "options"=>array(Order::$APPROVED_SITE=> $data->status == Order::$APPROVED_SITE ? array("disabled"=>true, "selected"=>true) : array("disabled"=>true))))',
                    'htmlOptions' => array('style' => 'background: rgba(245, 245, 245, 1);color:#000000;'),
                    'name' => 'status',
                    'filter' => CHtml::activeDropDownList($order_model, 'status', ZHtml::enumItem($order_model, 'status'), array('empty' => '', 'style' => 'width:150px')),
                ),
            ),
        ));

        ?>
</div>
<style>
    .cancel_reason span{
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
        <input type='radio' name="reason" class='r_button' id='<?= count(Order::getReasons())+1 ?>'><span
            id="r_text_<?= count(Order::getReasons())+1 ?>">Другое</span><br>
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
        var p_id = $(this).attr('id');
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
                    data: {"product": p_id, "status": status, "reason": r_id, "reason_text":r_text},
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
                //cache:false,
                data: {"product": p_id, "status": status},
                success: function (data) {
                    $.fn.yiiGridView.update("order-grid");
                }
            });
        }

    });

    /*setInterval(function () {
        $.fn.yiiGridView.update("order-grid");
        $.ajax({
            url: "/partner/orders/checkNewOrderForMusik?status=1",
            type: "post",
            //cache:false,
            success: function (data) {
                if (data == 1) {
                    playAudio();
                }
            }
        });
        $.ajax({
            url: "/partner/orders/overdueOrders?status=1",
            type: "post",
            //cache:false,
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

    $("body").on('click','.cancel_reason span',function(){
        $(this).parent().find("input").click();
    });
</script>
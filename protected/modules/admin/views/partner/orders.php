<? Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
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
                'value'=>'$data->FormatDate()',
                'filter' => CHtml::textField("Order[date]", $order_model->date, array("class" => "input-mini", 'style' => 'max-width:110px;')),
            ),
            array(
                'type' => 'raw',
                'header' => 'Адрес',
                'value' => '$data->city.", ".$data->street',
                'headerHtmlOptions' => array('style' => 'width:200px; textdecoration:none'),
            ),
            array(
                'header' => 'Время заказа',
                'name' => 'approved_site',
                'type' => 'html',
                'value' => '$data->approvedHtml',
                'htmlOptions' => array('class' => 'appr-td'),
                'filter' => false,
            ),
            array(
                'header' => 'Принят оператором',
                'name' => 'approved_operator',
                'type' => 'html',
                'value' => '$data->getOperator() ? "<img src=\'/images/clock-img.png\'>".$data->getOperator()->name  : "<img src=\'/images/clock-img.png\'>"',
                'cssClassExpression' => '$data->status==Order::$APPROVED_PARTNER && (strtotime($data->partner ? $data->partner->getDurationTime() : "-30 minutes") > strtotime(date("Y-m-d", strtotime($data->date))." ".$data->approved_partner)) ? "blink_order" : ""',
                'htmlOptions' => array('class' => 'approved_partner yellow', 'style' => 'background:url("/images/yellow-fon.png") repeat-x;background-size:contain;color:#000000;'),
                'filter' => array('' => 'Не выбран', 'all' => 'Все операторы') + CHtml::ListData(User::getAdmins(), 'id', 'name'),
            ),
            array(
                'header' => 'Статус заказа',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'background: rgba(245, 245, 245, 1);color:#000000;'),
                'value' => 'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor", "style"=>"width:150px", "options"=>array(Order::$APPROVED_SITE=> $data->status == Order::$APPROVED_SITE ? array("disabled"=>"disabled", "selected"=>"selected") : array("disabled"=>"disabled"))))',
                'name' => 'status',
                'filter' => CHtml::activeDropDownList($order_model, 'status', ZHtml::enumItem($order_model, 'status'), array('empty' => '', 'style' => 'width:150px')),
            ),
        ),
    ));
    ?>
</div>
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
<style>
    .cancel_reason span{
        cursor: pointer;
    }
</style>
<script>
    var prev_val = "";
    $(document).on('focus', '.statuseditor', function (event) {
        var prev_val = $(this).val();
    });
    $("#dialog .close").click(function () {
        $(this).val(prev_val);
        $("#dialog").dialog("close");
    });
    //при изменении статуса
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
                    url: "/admin/order/id/" + p_id + "/changeStatus?status=" + status,
                    type: "post",
                    data: {"product": p_id, "status": status, "reason": r_id, "reason_text":r_text},
                    success: function (data) {
                        $.fn.yiiGridView.update("order-grid");
                    }
                });
            });
        }
        else {
            $.ajax({
                url: "/admin/order/id/" + p_id + "/changeStatus?status=" + status,
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
            url: "/admin/partner/chackOrders",
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
                }
            }
        });
    }, 30000);
    $("body").on('click','.cancel_reason span',function(){
        $(this).parent().find("input").click();
    });
</script>
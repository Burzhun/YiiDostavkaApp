<? Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<input type="hidden" id="audiofile" size="80" value="http://dostavka05.ru/images/new_order.mp3"/>
<audio id="myaudio">
    HTML5 audio not supported
</audio>
<script type="text/javascript">
    var currentFile = "";
    var oAudio;
    var btn;
    var audioURL;

    function playAudio() {
        if (window.HTMLAudioElement) {
            try {
                oAudio = document.getElementById('myaudio');
                btn = document.getElementById('play');
                audioURL = document.getElementById('audiofile');

                if (audioURL.value !== currentFile) {
                    oAudio.src = audioURL.value;
                    currentFile = audioURL.value;
                }
                if (oAudio.paused) {
                    oAudio.play();
                }
                else {
                    oAudio.pause();
                }
            }
            catch (e) {
                if (window.console && console.error("Error:" + e));
            }
        }
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
        'dataProvider' => $order_model->search(array('partners_id' => $model->id)),
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
                //'name'=>'user_id',
                'value' => '$data->city.", ".$data->street',
                'headerHtmlOptions' => array('style' => 'width:200px; textdecoration:none'),
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
                'htmlOptions' => array('class' => 'cancelled', 'style' => 'background: rgba(255, 255, 255, 1);color:#000000;'/*, 'date'=>date('Y-m-d', strtotime($data->date))*/),
                'filter' => CHtml::textField("Order[cancelled]", $order_model->cancelled, array("class" => "input-mini")),
            ),
            array(
                'header' => 'Статус заказа',
                'type' => 'raw',
                //'value'=>'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor", "style"=>"width:150px"))',
                'value' => 'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor", "style"=>"width:150px", "options"=>array(Order::$APPROVED_SITE=> $data->status == Order::$APPROVED_SITE ? array("disabled"=>"disabled", "selected"=>"selected") : array("disabled"=>"disabled"))))',
                'name' => 'status',
                'htmlOptions' => array('style' => 'background: rgba(245, 245, 245, 1);'),
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
        <input type='radio' name="reason" class='r_button' id='<?= $index ?>'><span
            id="r_text_<?= $index ?>"><?= $reason['name'] ?></span><br>
    <? } ?>
    <input type='radio' name="reason" class='r_button' id='<?= count(Order::getReasons())+1 ?>'><span
        id="r_text_<?= count(Order::getReasons())+1 ?>">Другое</span><br>
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
        if (status == 5) {
            $("#dialog").dialog({width: "800px"}
            );
            $("#dialog .r_button").last().click(function () {
                $("#r_text").show();
            });
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
                url: '/admin/order/id/' + p_id + '/changeStatus?status=' + status,
                type: "post",
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
            url: "/admin/partner/id/<?=$_GET['id']?>/checkNewRelationOrderForMusik?status=" + status,
            type: "post",
            success: function (data) {
                if (data == 1) {
                    playAudio();
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
            data: {'lastActionId': lastActionId, 'partner_id': <?=$model->id?>, 'relation': 1},
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
</script>

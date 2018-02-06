<? Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
<script type="text/javascript">
    // Global variable to track current file name.
    var currentFile = "";
    function playAudio() {
        // Check for audio element support.
        if (window.HTMLAudioElement) {
            try {
                var oAudio = document.getElementById('myaudio');
                var btn = document.getElementById('play');
                var audioURL = document.getElementById('audiofile');

                //Skip loading if current file hasn't changed.
                if (audioURL.value !== currentFile) {
                    oAudio.src = audioURL.value;
                    currentFile = audioURL.value;
                }

                // Tests the paused attribute and set state. 
                if (oAudio.paused) {
                    oAudio.play();
                    //btn.textContent = "Pause";
                }
                else {
                    oAudio.pause();
                    //btn.textContent = "Play";
                }
            }
            catch (e) {
                // Fail silently but show in F12 developer tools console
                if (window.console && console.error("Error:" + e));
            }
        }
    }
</script>
<input type="hidden" id="audiofile" size="80" value="/images/new_order.mp3"/>


<?
Diva::linkMainCsss();
Diva::linkMainJss();
?>

<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php if (!$model->status) { ?>
        Данная страница будет доступна поле активации.
    <?php } else { ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'order-grid',
            'dataProvider' => $order_model->search(array('partners_id' => $model->id)),
            'filter' => $order_model,
            //'cssFile'=>'css/gridView/partnerOrderGrid.css',
            'rowCssClassExpression' => 'Order::getRowColor($data->status,$data->forbonus)',
            'emptyText' => 'Ничего не найдено',
            'htmlOptions' => array('class' => 'table table-bordered'),
            'summaryText' => '',
            'columns' => array(
                array(
                    'type' => 'raw',
                    'header' => 'Заказчик',
                    'name' => 'user_id',
                    'value' => 'CHtml::link($data->customer_name, "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getUserRecvizit/".$data->id, "_blank", "400", "350")))',
                    'filter' => CHtml::textField("User[user_id]", $order_model->user_id, array("class" => "input-mini")),
                    'cssClassExpression' => '"table_link_cell"',
                ),
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
    <?php } ?>
</div>
<div id="dialog" style="background-color: white;width: 800px;display: inline-block;border-radius: 10px;
padding: 5px;display: none;">
    <div class="modal-header" style="font-size: 18px;margin: 10px;">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <span class="modal-title">Выберите причину отказа</span>
    </div>
    <? foreach (Order::getReasons() as $index => $reason) { ?>
        <input type='radio' name="reason" class='r_button' id='<?= $reason['id'] ?>'><span
            id="r_text_<?= $reason['id'] ?>"><?= $reason['name'] ?></span><br>
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

    var lastActionId = <?=Action::getLastId()?>;
    setInterval(function () {
        $.ajax({
            url: "/partner/orders/chackOrders",
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
                    console.log(lastActionId);
                }
            }
        });
    }, 30000);
</script>
<audio id="myaudio">
    HTML5 audio not supported
</audio>
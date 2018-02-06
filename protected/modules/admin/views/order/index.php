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

<div class="h1-box">
    <div class="well">
        <h1>Заказы</h1>
    </div>
</div>
<div class="well well-bottom">
    <!-- Заголовок страницы -->
    <? if (stripos($_SERVER['HTTP_USER_AGENT'], "iPod") || stripos($_SERVER['HTTP_USER_AGENT'], "iPad") || stripos($_SERVER['HTTP_USER_AGENT'], "iPhone") || stripos($_SERVER['HTTP_USER_AGENT'], "iOS")) { ?>
        .
    <? } ?>
    <div>
        <a href="/admin/order" class="btn btn-primary">Показать все заказы</a>
        <a href="/admin/order?orders_status=active" class="btn btn-success">Показать активные заказы</a>
    </div>
    <br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'order-grid',
        'dataProvider' => $order_model->search(),
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
                'value' => 'CHtml::link($data->customer_name ? $data->customer_name : "Нет имени", "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getUserRecvizit/".$data->id, "_blank", "500", "750")))',
                'filter' => CHtml::textField("User[user_id]", $order_model->user_id, array("class" => "input-mini")),
                'cssClassExpression' => '"table_link_cell"',
                'htmlOptions' => array('style' => 'max-width:50px;'),
            ),
            array(
                'name' => 'partners_id',
                'type' => 'raw',
                'value' => 'CHtml::link($data->partner ? $data->partner->name : "Нет названия", "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getPartnerRecvizit/".$data->id, "_blank", "400", "350")))',
                'filter' => CHtml::textField("Partner[partners_id]", $order_model->partners_id, array("class" => "input-mini")),
                'cssClassExpression' => '"table_link_cell"',
            ),
            array(
                'class' => 'CLinkColumn',
                'header' => 'Заказы',
                'labelExpression' => '"Заказ"',
                'urlExpression' => '"/admin/order/id/".$data->id."/view/"',
                'cssClassExpression' => '"table_link_cell"',
            ),
            array(
                'header' => 'Cумма заказа',
                'value' => 'Order::totalPriceAdmin($data->sum,$data->forbonus, true)',
            ),
            array(
                'name' => 'date',
                'value' => '$data->FormatDate()',
                'filter' => CHtml::textField("Order[date]", $order_model->date, array("class" => "input-mini", 'style' => 'width:54px;')),
            ),
            array(
                'type' => 'raw',
                'header' => 'Адрес',
                'value' => '$data->city.", ".$data->street',
                'headerHtmlOptions' => array('style' => 'width:10px; textdecoration:none'),
            ),
            array(
                'header' => 'Время заказа',
                'name' => 'approved_site',
                'type' => 'html',
                'value' => '$data->approvedHtml',
                'htmlOptions' => array('class' => 'appr-td'),
                'filter' => false,
            ),
            'comment',
            array(
                'name' => 'order_source',
                'htmlOptions' => array('style' => 'background: rgba(245, 245, 245, 1);color:#000000;', 'width' => '10px'),
                'value' => 'Order::getSourceName($data->order_source)',
                'filter' => CHtml::activeDropDownList($order_model, 'status', array(Order::$SOURCE_ORDER_DESKTOP => 'Компьютер', Order::$SOURCE_ORDER_MOBILE => 'Мобильник', Order::$SOURCE_ORDER_ANDROID_APP => 'Android', Order::$SOURCE_ORDER_IOS_APP => 'IOS'), array('empty' => '', 'style' => 'width:80px')),
            ),
            array(
                'header' => 'Статус заказа',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'background: rgba(245, 245, 245, 1);color:#000000;'),
                'value' => 'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor", "style"=>"width:120px", "options"=>array(Order::$APPROVED_SITE=> $data->status == Order::$APPROVED_SITE ? array("disabled"=>"disabled", "selected"=>"selected") : array("disabled"=>"disabled"))))',
                'name' => 'status',
                'filter' => CHtml::activeDropDownList($order_model, 'status', ZHtml::enumItem($order_model, 'status'), array('empty' => '', 'style' => 'width:120px')),
            ),
            array(
                'name' => 'order_time',
                'htmlOptions' => array('style' => 'max-width:80px;'),
                'value' => '$data->order_time ? date("H.i.s d-m-Y",$data->order_time-10800) : "Как можно скорее"',
                'filter' => CHtml::textField("Order[order_time]", $order_model->order_time, array("class" => "input-mini", 'style' => 'max-width:54px;')),
            ),
            array(
                'name' => 'sms_status',
                'value' => '$data->get_sms_status()',
                'filter' => CHtml::textField("Order[sms_status]", $order_model->sms_status, array("class" => "input-mini", 'style' => 'max-width:54px;')),
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
        ),
    )); ?>
</div>

<?
Diva::linkMainCsss();
Diva::linkMainJss();
?>
<style>
    .r_button {
        margin-bottom: 15px !important;
        margin-left: 10px !important;
    }
    .cancel_reason span {
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
        <input type='radio' name="reason" class='r_button' id='<?= count(Order::getReasons()) + 1 ?>'><span
            id="r_text_<?= count(Order::getReasons()) + 1 ?>">Другое</span><br>
    </div>
    <input type="text" id="r_text" style="width: 700px;margin-left:15px;margin-top:10px;display: none"><br>
    <button class="btn btn-success" style="margin: 15px;width: 50px;" id="set_reason">Ok</button>
</div>

<script>
    var prev_val = "";
    var p_id = "";
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
        p_id = $(this).attr('id');
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
                    //cache:false,
                    data: {"product": p_id, "status": status, "reason": r_id, "reason_text": r_text},
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

    var lastActionId = <?=Action::getLastId()?>;
    setInterval(function () {
        $.ajax({
            url: "/admin/partner/chackOrders",
            type: "post",
            dataType: "json",
            data: {'lastActionId': lastActionId},
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
    $("body").on('click', '.cancel_reason span', function () {
        $(this).parent().find("input").click();
    });
</script>
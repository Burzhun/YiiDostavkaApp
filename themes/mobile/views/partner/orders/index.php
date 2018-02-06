<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
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

<style type="text/css">
    .mobileAdmin {
        font-size: 24px;
    }

    .mobileAdmin td {
        vertical-align: middle;
        padding: 30px;
        line-height: 1;
    }
</style>

<?
Diva::linkMainCsss();
Diva::linkMainJss();
?>

<style type="text/css">
    .table table {
        width: 100%;
    }
    .items thead {
        display: none;
    }
</style>

<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php if (!$model->status) { ?>
        Данная страница будет доступна поле активации.
    <?php } else { ?>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'order-grid',
            'dataProvider' => $order_model->search(array('one_partners_id' => $model->id)),
            'filter' => false,
            'rowCssClassExpression' => 'Order::getRowColor($data->status,$data->forbonus)',
            'emptyText' => 'Ничего не найдено',
            'htmlOptions' => array('class' => 'table table-bordered mobileAdmin', 'style' => 'width:100%;'),
            'summaryText' => '',

            'columns' => array(
                array(
                    'type' => 'raw',
                    'header' => 'Заказчик',
                    'name' => 'user_id',
                    'value' => 'CHtml::link($data->customer_name, "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getUserRecvizit/".$data->id, "_blank", "400", "350")))',
                    'filter' => CHtml::textField("User[user_id]", $order_model->user_id, array("class" => "input-mini")),
                    'cssClassExpression' => '"table_link_cell"',
                    'filter' => false,
                ),
                array(
                    'class' => 'CLinkColumn',
                    'header' => 'Статус заказа',
                    'labelExpression' => '$data->status',
                    'urlExpression' => '"/partner/orders/".$data->id',
                ),
            ),
        )); ?>
    <?php } ?>
</div>

<script>
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
            url: "/partner/orders/id/" + p_id + "/changeStatus?status=" + status,
            type: "post",
            //cache:false,
            data: {"product": p_id, "status": status},
            success: function (data) {
                $.fn.yiiGridView.update("order-grid");
            }
        });
    });

    /*setInterval(function () {
        $.fn.yiiGridView.update("order-grid");
        $.ajax({
            url: "/partner/orders/checkNewOrderForMusik",
            type: "post",
            //cache:false,
            success: function (data) {
                if (data == 1) {
                    playAudio();
                }
            }
        });
        $.ajax({
            url: "/partner/orders/overdueOrders",
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
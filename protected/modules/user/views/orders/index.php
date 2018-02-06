<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="Userbonus"><span>У Вас</span>    <?= User::getBonus($model->id); ?> <span>баллов</span></div>
    <div class="Aboutbonus"><a href="/bonus">Что мне дают баллы?</a></div>
    <div class="blok">
        <?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
        <br><br>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'order-grid',
            'dataProvider' => $order_model->search(array('user_id' => Yii::app()->user->id)),
            'filter' => $order_model,
            'emptyText' => 'Ничего не найдено',
            'htmlOptions' => array('class' => 'table_order'),
            'rowCssClassExpression' => 'Order::getRowColor($data->status,$data->forbonus)',
            'summaryText' => '',
            'columns' => array(
                array(
                    'name' => 'id',
                    'type' => 'raw',
                    'header' => '№ Заказа',
                    'value' => '$data->id',
                    'filter' => CHtml::textField('Order[id]', $order_model->id, array('class' => 'input-mini', 'style' => 'width:60px')),
                ),
                /*array(
                    'type'=>'raw',
                    'header'=>'Заказчик',
                    'name'=>'user_id',
                    'value'=>'$data->customer_name',
                    'filter'=>CHtml::textField("User[user_id]", $order_model->user_id, array("class"=>"input-mini")),
                ),*/
                array(
                    'name' => 'partners_id',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data->partner ? $data->partner->name : "", array("/restorany/".$data->partner->tname))',
                    'filter' => CHtml::textField("Partner[partners_id]", $order_model->partners_id, array("class" => "input-mini")),
                ),
                array(
                    'class' => 'CLinkColumn',
                    'header' => 'Заказы',
                    'labelExpression' => '"Заказ"',
                    'urlExpression' => '"/user/orders/".$data->id',
                ),
                /*array(
                    'header'=>'Статус оплаты(функция временно отключена)',
                    'value'=>' $data->pay==1?"Оплачен":'.$this->renderPartial("_formPay",false,false),
                    ),*/
                /*array(
                    'header'=>'Cумма заказа',
                    'value'=>'Order::totalPrice($data->id)." <?php echo City::getMoneyKod();?>"',
                    //'filter'=>CHtml::textField("Order[date]", $order_model->date, array("class"=>"input-mini", 'style'=>'width:110px;')),
                ),*/
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
                /*array(
                    'name'=>'approved_site',
                    'filter'=>CHtml::textField("Order[approved_site]", $order_model->approved_site, array("class"=>"input-mini")),
                ),
                array(
                    'name'=>'approved_partner',
                    'filter'=>CHtml::textField("Order[approved_partner]", $order_model->approved_partner, array("class"=>"input-mini")),
                ),
                array(
                    'name'=>'delivered',
                    'filter'=>CHtml::textField("Order[delivered]", $order_model->delivered, array("class"=>"input-mini")),
                ),
                array(
                    'name'=>'cancelled',
                    'filter'=>CHtml::textField("Order[cancelled]", $order_model->cancelled, array("class"=>"input-mini")),
                ),*/
                array(
                    'header' => 'Статус заказа',
                    'type' => 'raw',
                    'value' => '$data->status',
                    'name' => 'status',
                    'filter' => CHtml::activeDropDownList($order_model, 'status', ZHtml::enumItem($order_model, 'status'), array('empty' => '', 'style' => 'width:150px')),
                ),
            ),
        ));
        ?>
    </div>
</div>
<script>
    $(window).ready(function () {
        $(".how_to_get_bonus").click(function () {
            $('#invite_friend_layer').show();
            $("#invite_friend").show();
        });
    });
</script>
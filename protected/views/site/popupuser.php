<header class="not_fixed">
    <ol>
        <li class="main">
            <i style="background-image: url(/images/touch-icon-ipad.png)"></i>Реквизиты пользователя		</li>
        <li>
            <dl>
                <dt>Имя</dt>
                <dd><?php echo $model->customer_name;?></dd>
            </dl>
        </li>
    </ol>
</header>
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <section class="main">
        <div class="row-fluid">
            <div class="span6">
                <label>Имя:</label> - <?php echo $model->customer_name ?><br>
                <label>Телефон:</label> - <?php echo $model->phone ?><br>
                <label>Город:</label> - <?php echo $model->city ?><br>
                <label>Улица:</label> - <?php echo $model->street ?><br>
                <label>Дом:</label> - <?php echo $model->house ?><br>
                <label>Этаж:</label> - <?php echo $model->storey ?><br>
                <label>Номер квартиры/офиса:</label> - <?php echo $model->number ?><br>

                <label>Комментарии к заказу:</label> - <?php echo $model->info ?><br>
            </div>
            <br><br>
        </div>
<style>
    label{
        display: inline-block;

    }
</style>
<? if(Yii::app()->user->getRole()=='admin'){?>
    <?
    $order_data=Order::model()->findAll(array("condition"=>"phone='".$model->phone."'","order" =>"id desc"));
    $orders=new CActiveDataProvider('Order',array('data'=>$order_data));
        ?>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'order-grid',
        'dataProvider' => $orders,
        //'cssFile'=>'css/gridView/partnerOrderGrid.css',
        'rowCssClassExpression' => 'Order::getRowColor($data->status,$data->forbonus)',
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'partners_id',
                'type' => 'raw',
                'value' => 'CHtml::link($data->partner ? $data->partner->name : "Нет названия", "javascript:void(0)", array("onclick"=>Diva::popupJs("/site/getPartnerRecvizit/".$data->id, "_blank", "400", "350")))',
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
            ),
            array(
                'type' => 'raw',
                'header' => 'Адрес',
                'value' => '$data->city.", ".$data->street',
                'headerHtmlOptions' => array('style' => 'width:10px; textdecoration:none'),
            ),
        ),
    )); ?>
<?}?>
    </section>
<? $this->widget('DivaPopupFooterWidget', array(
    'rightBar' => array(
        array('type' => 'button', 'preset' => 'close')
    )
)); ?>
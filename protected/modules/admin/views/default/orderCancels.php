<div class="h1-box">
    <div class="well">
        <h1>Комментарии к отмененным заказам</h1>
    </div>
</div>
<div class="well well-bottom">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'orderComment-grid',
        'dataProvider' => $dataProvider,
        'ajaxUpdate' => false,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'type' => 'raw',
                'name' => 'id',
                'header' => 'Номер заказа',
                'value' => 'CHtml::link($data->id, "/admin/partner/id/".$data->partners_id."/orders/".$data->id)',
            ),
            'date',
            array(
                'header' => "Причина отмены",
                'value' => '$data->getCancelReasonText()'
            ),
            array(
                'type' => 'raw',
                'header' => 'Заказчик',
                'name' => 'user_id',
                'value' => '$data->customer_name ? $data->customer_name : "Нет имени"',
            ),
            array(
                'type' => 'raw',
                'header' => 'Телефон заказчика',
                'value' => '$data->phone',
            ),
            array(
                'name' => 'partners_id',
                'type' => 'raw',
                'value' => 'CHtml::link($data->partner ? $data->partner->name : "Нет названия", "/admin/partner/id/".$data->partners_id."/info")',
            ),
        ),
    )); ?>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'ajaxUpdate' => TRUE,
    'id' => 'orderDebtList-grid',
    'htmlOptions' => array('class' => 'table table-bordered', 'style' => 'background-color:#ffffff'),
    'summaryText' => '',
    'enableSorting' => false,
    'columns' => array(
        //'id',
        array(
            'type' => 'raw',
            'header' => 'Имя заказчика',
            'name' => 'user_id',
            'value' => '$data->user ? $data->user->name : $data->user_name',
        ),
        'date',
        'address',
        array(
            'type' => 'raw',
            'name' => 'sum',
            'value' => '$data->sum." ".City::getMoneyKod()',
        ),
        array(
            'type' => 'raw',
            'header' => 'Отчисления',
            'value' => 'floor($data->sum*(Partner::model()->findByPk($data->partner_id)->procent_deductions/100))." ".City::getMoneyKod()',
        ),
    ),
));
?>
<br><br>
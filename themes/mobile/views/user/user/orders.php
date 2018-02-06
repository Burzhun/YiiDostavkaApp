<?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $order_model->search(array('user_id' => $model->id)),
    'filter' => $order_model,
    'columns' => array(
        'id',
        'date',
        array(
            'class' => 'CLinkColumn',
            'header' => 'Партнер',
            'labelExpression' => '$data->partner->name',
            'urlExpression' => '"/admin/partner/id/".$data->partner->id."/orders"',
        ),
        array(
            'header' => 'Email',
            'value' => '$data->user->email',
        ),
        'phone',
        'city',
        'street',
        array(
            'header' => 'Общая сумма заказа',
            'value' => 'Order::totalPriceAdmin($data->sum,$data->forbonus, true)',
        ),
        'status',
        array(
            'class' => 'CLinkColumn',
            'header' => 'Заказ',
            'labelExpression' => '"→"',
            'urlExpression' => '"/admin/user/id/".$data->user->id."/orders/".$data->id',
        ),
    ),
));
?>
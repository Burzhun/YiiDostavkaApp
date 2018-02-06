<h1>Users</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        array(
            'class' => 'CLinkColumn',
            'header' => 'Имя',
            'labelExpression' => '$data->name',
            'urlExpression' => '"/admin/user/id/".$data->id."/orders/"',
        ),
        'phone',
        'email',
        'reg_date',
        'last_visit',
        'total_order',
        array(
            'class' => 'CLinkColumn',
            'header' => 'Партнер',
            'labelExpression' => '$data->partner_id != 0 ? $data->partner->name : ""',
            'urlExpression' => '"/admin/partner/id/".$data->partner_id."/info"',
        ),
    ),
));
?>
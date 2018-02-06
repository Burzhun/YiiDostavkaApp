<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'emptyText' => 'Ничего не найдено',
    'htmlOptions' => array('class' => 'table table-bordered'),
    'summaryText' => '',
    'columns' => array(
        array(
            'header' => 'Действие',
            'value' => '$data->action',
            'name' => 'action',
            'filter' => ZHtml::enumDropDownList($model, 'action', array()),
        ),
        array(
            //'class' => 'CLinkColumn',
            'type' => 'raw',
            'header' => 'Пользователь',
            'value' => '$data->user ? CHtml::link($data->user->name, array("/admin/user/id".$data->user->id."/profile/")) : ""',
            //'name'=>'$data->user ? $data->user->name : ""',
            //'labelExpression'=>'$data->user ? $data->user->name : ""',
            //'urlExpression'=>'$data->user ? "/admin/user/id/".$data->user->id."/profile/" : ""',
        ),
        array(
            'class' => 'CLinkColumn',
            'header' => 'Партнер',
            'labelExpression' => '$data->partner ? $data->partner->name : ""',
            'urlExpression' => '$data->partner ? "/admin/partner/id/".$data->partner->id."/orders/" : ""',
        ),
        'info',
        'date',
    ),
));
?>
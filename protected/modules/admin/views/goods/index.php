<h1>Товары</h1>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'name',
        array(
            'name' => 'img',
            'type' => 'image',
            'value' => '"' . '/upload/goods/small$data->img"',
        ),
        'price',

        array(
            'class' => 'CLinkColumn',
            'header' => 'Заказы',
            'labelExpression' => 'Goods::getOrderCount($data->id)',
            'urlExpression' => '"/admin/goods/id/".$data->id."/order/"',
        ),
    ),
));
?>
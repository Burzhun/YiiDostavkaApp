<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $actions->search(),
    'filter' => $actions,
    'columns' => array(
        array(
            'header' => 'Действие',
            'value' => '$data->action',
            'name' => 'action',
            'filter' => ZHtml::enumDropDownList($actions, 'action', array()),
        ),
        array(
            'class' => 'CLinkColumn',
            'header' => 'Пользователь',
            'labelExpression' => '$data->user ? $data->user->name : ""',
            'urlExpression' => '$data->user ? "/admin/user/id/".$data->user->id."/profile/" : ""',
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

<br><br>
<b>За день</b>
Заказов:<?php echo $orders['today']; ?><br>
Посещений:<?php echo $visits['today'] ?><br>
Новых пользователей:<?php echo $newUser['today'] ?><br>
<br>
<b>За неделю</b>
Заказов:<?php echo $orders['week'] ?><br>
Посещений:<?php echo $visits['week'] ?><br>
Новых пользователей:<?php echo $newUser['week'] ?><br>
<br>
<b>За месяц</b>
Заказов:<?php echo $orders['month'] ?><br>
Посещений:<?php echo $visits['month'] ?><br>
Новых пользователей:<?php echo $newUser['month'] ?><br>
<br>
<div class="well">
    <!-- Главная таблица -->
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search('', $this->domain),
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered', 'style' => 'float:left;'),
        'summaryText' => '',
        'columns' => array(
            'id',
            array(
                'header' => 'Действие',
                'value' => '$data->action',
                'name' => 'action',
                'filter' => ZHtml::enumDropDownList($model, 'action', array('empty' => '', 'style' => 'width:100px')),
            ),
            array(
                'type' => 'raw',
                'header' => 'Пользователь',
                'value' => '$data->user_link()',
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
    <div style="padding-left:10px;float:left;">
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
    </div>
</div>
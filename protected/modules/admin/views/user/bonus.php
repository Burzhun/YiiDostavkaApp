<div style="padding: 20px;">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'order-grid',
        'dataProvider' => $user_bonus->search(array('user_id' => $user_id)),
        'filter' => $user_bonus,
        'emptyText' => 'Ничего не найдено',
        'summaryText' => '',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'columns' => array(
            array(
                'header' => 'Дата активности',
                'value' => 'date("Y.m.d",$data->date)'
            ),
            array(
                'header' => 'Описание',
                'value' => '$data->info',
            ),
            array(
                'header' => 'Количество',
                'value' => '$data->sum_in_start',
            ),
        ),
    ));
    ?>
</div>

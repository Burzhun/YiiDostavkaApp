<h1>Районы</h1>
<div>
    <a class="btn btn-primary" href="/admin/partner_rayon/create">Добавить район</a>
</div>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        'dataProvider' => $model,
        'summaryText' => '',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            array(
                'header' => 'Партнер',
                'value' => '$data->partner->name',
            ),
            array(
                'header' => 'Район',
                'value' => '$data->rayon->name."(".$data->rayon->city->name.")"',
            ),
            'min_sum',
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
            ),
        ),
    ));
    ?>
</div>
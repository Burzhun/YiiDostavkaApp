<h1>Районы</h1>

<div>
    <a class="btn btn-primary" href="/admin/rayon/create">Добавить район</a>
</div>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        'dataProvider' => $model,
        'summaryText' => '',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            'name',
            array(
                'header' => 'Город',
                'value' => '$data->getCityName()',
            ),
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
            ),
        ),
    )); ?>
</div>
<h1>Города</h1>

<div>
    <a class="btn btn-primary" href="/admin/city/create">Добавить город</a>
</div>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'summaryText' => '',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            'name',
            'alias',
            array(
                'header'=>'Домен',
                'value' => '$data->domain->name'
            ),
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
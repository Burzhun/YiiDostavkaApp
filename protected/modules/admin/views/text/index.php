<h1>Тексты</h1>

<div>
    <a class="btn btn-primary" href="/admin/text/create">Добавить текст</a>
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
            'domain.name',
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{update}',
                'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
            ),
        ),
    ));
    ?>
</div>
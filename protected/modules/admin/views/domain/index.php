<h1>Домены</h1>

<div>
    <a class="btn btn-primary" href="/admin/domain/create">Добавить домен</a>
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
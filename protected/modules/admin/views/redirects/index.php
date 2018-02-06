<h1>Редиректы</h1>

<div>
    <a class="btn btn-primary" href="/admin/redirects/create">Добавить редирект</a>
</div>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        // 'dataProvider'=>$dataProvider,
        'dataProvider' => $model,
        'summaryText' => '',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            'old_url',
            'new_url',
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
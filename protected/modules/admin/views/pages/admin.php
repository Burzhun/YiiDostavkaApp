<div class="h1-box">
    <div class="well">
        <h1>Страницы</h1>
    </div>
</div>

<div class="well well-bottom">
    <?php echo CHtml::link('Добавить страницу', array('create'), array('class' => "btn btn-primary")); ?>

    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'pages-grid2',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'columns' => array(
            'id',
            'name',
            //'shorttext',
            //'text',
            'uri',
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
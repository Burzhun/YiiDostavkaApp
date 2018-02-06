<div class="h1-box">
    <div class="well">
        <h1>Блог</h1>
    </div>
</div>

<div class="well well-bottom">
    <div>
        <a class="btn btn-primary" href="/admin/post/id/0/create">Добавить пост</a>
    </div>
    <br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'filter' => $model,
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'id',
                'filter' => '<input style="width:20px;" name="Post[id]" type="text">',
            ),
            array(
                'name' => 'title',
            ),
            'date',
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'headerHtmlOptions' => array('style' => 'width:40px; textdecoration:none'),
                'template' => '{update}{delete}',
            ),
        ),
    ));
    ?>
</div>
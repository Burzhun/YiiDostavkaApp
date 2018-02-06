<div class="h1-box">
    <div class="well">
        <h1>Бонусы</h1>
    </div>
</div>
<div class="well well-bottom">
    <a class="btn btn-primary" href="/admin/bonus/id/0/create">Добавить бонус</a>
    <br><br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'id' => 'my-grid',
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            'id',
            'name',
            'shorttext',
            array(
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
            ),
        ),
    ));
    ?>
</div>
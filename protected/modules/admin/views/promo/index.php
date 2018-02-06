<h1>Промокоды</h1>

<div>
    <a class="btn btn-primary" href="/admin/promo/create">Добавить промокод</a>
</div>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        // 'dataProvider'=>$dataProvider,
        'dataProvider' => $model->search(),
        'filter' => $model,
        'summaryText' => '',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            'name',
            array(
                'header' => 'Партнер',
                'value' => '$data->GetPartnerName()'
            ),
            'kod',
            array(
                'header' => 'Использовали',
                'value' => '$data->usedCount()'
            ),
            'count',
            'from',
            'until',
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
<style>
    td {
        max-width: 250px;
    }
</style>
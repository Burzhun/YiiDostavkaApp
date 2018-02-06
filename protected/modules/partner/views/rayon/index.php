<? $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<h1>Районы доставки</h1>

<div>
    <a class="btn btn-primary" href="/partner/rayon/create">Добавить район</a>
</div>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        // 'dataProvider'=>$dataProvider,
        'dataProvider' => $partner,
        'summaryText' => '',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            array(
                'header' => 'Партнер',
                'value' => '$data->partner->name',
            ),
            array(
                'header' => 'Район',
                'value' => '$data->rayon->name',
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
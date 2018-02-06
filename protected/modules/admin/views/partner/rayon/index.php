<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<h1>Районы</h1>

<div>
    <a class="btn btn-primary" href="/admin/partner/id/<?= $model->id; ?>/rayoncreate">Добавить район</a>
</div>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        // 'dataProvider'=>$dataProvider,
        'dataProvider' => $rayon,
        'summaryText' => '',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            array(
                'header' => 'Партнер',
                'value' => '$data->partner->name',
            ),
            array(
                'header' => 'Район',
                'value' => '$data->rayon->name."(".$data->rayon->city->name.")"',
            ),
            'min_sum',
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
                'buttons' => array(
                    'update' => array(
                        'url' => '"rayonupdate?rayon_id=".$data->id'
                    ),
                    'delete' => array(
                        'url' => '"rayondelete?rayon_id=".$data->id'
                    ),
                ),
            ),
        ),
    ));
    ?>
</div>
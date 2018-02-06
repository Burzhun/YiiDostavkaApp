<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $address_model->search(array('user_id' => $model->id)),
        'filter' => $address_model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'header' => 'Город',
                'value' => '$data->city->name',
            ),
            'street',
            'house',
            'podezd',
            'storey',
            'number',
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                'updateButtonUrl' => '"/admin/user/id/".$data->user_id."/update/address/".$data->id',
                'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
                'deleteButtonUrl' => '"/admin/user/id/".$data->user_id."/delete/address/".$data->id',
            ),
        ),
    ));

    ?>
</div>
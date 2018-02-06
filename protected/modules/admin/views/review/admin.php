<?php
/* @var $this ReviewController */
/* @var $model Review */

$this->breadcrumbs = array(
    'Все',
);
/*
$this->menu=array(
	array('label'=>'List Review', 'url'=>array('index')),
	array('label'=>'Create Review', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#review-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
"); */
?>

<h1><?= $this->adminH1; ?></h1>
<br>
<div>
    <?php
    $this->widget('bootstrap.widgets.BootButton',
        array(
            'url' => array('create'),
            'type' => 'primary',
            'icon' => 'plus white',
            'label' => $this->addLabel)
    );
    ?></div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'review-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'review',
        'partner_id',
        'visible',
        'content',
        'user_id',
        /*
        'created',
        */
        array(
            'header' => 'Операции',
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
        ),
    ),
)); ?>

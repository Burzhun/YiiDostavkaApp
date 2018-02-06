<?//@TODO Нужен ли этот файл? Если нет, то удали его ?>
<?php
/* @var $this ReviewController */
/* @var $model Review */

$this->breadcrumbs = array(
    'Reviews' => array('index'),
    $model->id,
);

/*
$this->menu=array(
	array('label'=>'List Review', 'url'=>array('index')),
	array('label'=>'Create Review', 'url'=>array('create')),
	array('label'=>'Update Review', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Review', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Review', 'url'=>array('admin')),
); */
?>

<h1><?= $this->viewH1; ?></h1>
<br>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'review',
        'partner_id',
        'visible',
        'content',
        'user_id',
        'created',
    ),
)); ?>

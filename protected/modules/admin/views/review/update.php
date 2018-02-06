<?php
/* @var $this ReviewController */
/* @var $model Review */

$this->breadcrumbs = array(
    'Все' => array('admin'),
    $model->id . ' - Редактирование',
);
/*
$this->menu=array(
	array('label'=>'List Review', 'url'=>array('index')),
	array('label'=>'Create Review', 'url'=>array('create')),
	array('label'=>'View Review', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Review', 'url'=>array('admin')),
); */
?>

    <h1><?= $this->updateH1; ?></h1>
    <br>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
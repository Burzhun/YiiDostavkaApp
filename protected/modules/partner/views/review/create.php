<?php
/* @var $this ReviewController */
/* @var $model Review */

$this->breadcrumbs = array(
    'Все' => array('admin'),
    'Создание',
);
/*
$this->menu=array(
	array('label'=>'List Review', 'url'=>array('index')),
	array('label'=>'Manage Review', 'url'=>array('admin')),
);*/
?>

    <h1><?= $this->createH1; ?></h1>
    <br>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
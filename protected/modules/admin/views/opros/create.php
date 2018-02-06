<?php
/* @var $this OprosController */
/* @var $model Opros */

/*$this->breadcrumbs=array(
	'Админка'=>array('/admin'),
	'Опросы' => array('/admin/opros'),
	'Добавить',
);?>

<h1>Добавление опроса</h1>*/ ?>

<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
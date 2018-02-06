<?
/*$this->breadcrumbs=array(
	'Опросы'=>array('index'),
	'Редактирование Ответов',
);

<h1>Редактирование</h1>*/ ?>

<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<?php echo $this->renderPartial('_form2', array('model' => $model)); ?>
<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>


<? /* $this->breadcrumbs=array(
	'Опросы'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактирование',
);*/ ?>

<? /*<h1>Редактирование - Опрос #<?php echo $model->id; ?> </h1>*/ ?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
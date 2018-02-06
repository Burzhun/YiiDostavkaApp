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

<div class="h1-box">
    <div class="well"><h1><?= $this->H1; ?></h1></div>
</div>
<br>
<? /*
<div>
	<?php
	$this->widget('bootstrap.widgets.BootButton', 
		array(
			'url'=>array('create'),
			'type'=>'primary',
			'icon'=>'plus white',
			'label'=> $this->addLabel)
		); 
	?></div> */ ?>
<? $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'itemsTagName' => 'ul',
    'itemsCssClass' => 'review',

    'summaryText' => '', //summary text
    'emptyText' => 'Нет отзывов',
//		    'template'=>',, {items} and {pager}.', //template
    'pagerCssClass' => 'pagers',//contain class

    'pager' => array(
        //	'class' => 'myPager',
        //   		'cssFile'=>false,//disable all css property
        'header' => '',//text before it
        // 'firstPageLabel'=>'',//overwrite firstPage lable
        // 'lastPageLabel'=>'',//overwrite lastPage lable
        // 'nextPageLabel'=>'&nbsp',//overwrite nextPage lable
        // 'prevPageLabel'=>'<li class="prev"><a href="#" title="">&nbsp</a></li>',

    )
)); ?>

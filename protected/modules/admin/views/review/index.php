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
    <div class="well"><h1><?= $this->adminH1; ?></h1></div>
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
	?></div> */

Yii::app()->clientScript->registerScript('published', '
	$("#published").live("click",function () {
		var thisobj = this;
		var id = $(this).attr("data-id");
		$.getJSON("/admin/review/togglepub?id="+id,function(data){
			if(data.error)
				alert(data.msg);
			else{
					if(data.pub){
						$(thisobj).children("img").attr("src","/iconset/check.gif");
						$(thisobj).attr("title", "Опубликовано");
					}	
					else{
						$(thisobj).children("img").attr("src","/iconset/delete.gif");
						$(thisobj).attr("title", "Не опубликовано");
					}	
			}
		})
		return false;
	});
	', CClientScript::POS_READY);
?>
<div class="well well-bottom">
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'review-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'columns' => array(
            'id',
            array(
                'name' => 'user_id',
                'value' => '$data->user->name',
            ),
            array(
                'name' => 'partner_id',
                'value' => '$data->partner->name',
            ),
            array(
                'name' => 'review',
                'value' => '($data->review==1)?"<img src=\"/images/reviewLike.png\"/>":"<img src=\"/images/reviewDisLike.png\"/>"',
                'type' => 'raw',
                'filter' => array(1 => 'Положительный', 2 => 'Отрицательный'),
                'htmlOptions' => array('style' => 'text-align: center;'),
            ),
            'content',
            //'visible',
            array(
                'header' => 'Состояние',
                'name' => 'visible',
                'value' => '($data->visible) ? "<a style=\'display: block; width:100%; height: 100%; padding-top:7px; padding-bottom:7px;\' id=\'published\' data-id=\'$data->id\' href=\'#\' title=\"Опубликовано\" ><img src=\"/iconset/check.gif\"/></a>" : "<a style=\'display: block; width:100%; height: 100%; padding-top:7px; padding-bottom:7px;\' id=\'published\' data-id=\'$data->id\' href=\'#\' title=\"Не опубликовано\" ><img src=\"/iconset/delete.gif\"/></a>" ;',
                'filter' => array('Не опубликовано', 'Опубликовано'),
                'type' => 'raw',
                'htmlOptions' => array('align' => 'center', 'style' => 'text-align: center; vertical-align: middle; padding:0;'),
            ),
            array(
                'name' => 'created',
                'value' => 'date("d-m-Y",$data->created)',
            ),


            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{delete}',
            ),
        ),
    )); ?>
</div>

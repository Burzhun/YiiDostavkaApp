<div class="h1-box">
    <div class="well">
        <h1>Опросы</h1>
    </div>
</div>
<br>
<?
/*$this->breadcrumbs=array(
	'Админка'=>array('/admin'),
	'Опросы',
);*/ ?>


<? /*$this->widget('bootstrap.widgets.BootButton',
	array(
		'url'=>array('create'),
		'type'=>'primary',
		'icon'=>'plus white',
		'label'=>'Добавить опрос')
	);
*/ ?>
<div class="well well-bottom">
    <?php echo CHtml::link('Добавить опрос', array('create'), array('class' => "btn btn-primary")); ?>
    <br>
    <br>
    <? $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'opros-grid',
        'dataProvider' => $model->search(),
        'emptyText' => 'Ничего не найдено',
        'summaryText' => '',
        'htmlOptions' => array('class' => 'table table-bordered'),
        //'filter'=>$model,
        'columns' => array(
            'id',
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, array("opros/view/id/".$data->id))',
            ),
            array(
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
            ),
        ),
    )); ?>
</div>
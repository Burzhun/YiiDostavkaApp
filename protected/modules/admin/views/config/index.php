<?php
/*$this->breadcrumbs=array(
	$this->name,
);
$this->pageTitle=$this->name." - ". Yii::app()->name;

?>

<h1><?=$this->name?></h1>


<?php echo $this->renderPartial('_form', array('models'=>$models)); ?>

*/ ?>
<h1>Настройки</h1>
<? foreach(Domain::model()->findAll('id<>4') as $domain){?>
    <a href="/admin/config?domain_id=<?=$domain->id;?>" class="links <?=$domain->id==$_GET['domain_id'] ? 'selected' :'';?>"><?=$domain->name;?></a>
<?}?>
<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'summaryText' => '',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            'description',
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'value',
                'editable' => array(
                    'title' => 'Название',
                    'type' => 'textarea',
                    'url' => $this->createUrl('/admin/config/UpdateAjax'),
                ),
            ),
        ),
    )); ?>
</div>
<style>
    a.links{
        display: inline-block;
        background-color: #15447d;

        height: 22px;
        text-align: center;
        color: white;
        padding: 2px 5px;
        font-size: 15px;
        margin: 5px 6px;
    }
    .links.selected{
        background-color: rgb(215, 215, 215);
        color: black;
        cursor: pointer;
    }
</style>
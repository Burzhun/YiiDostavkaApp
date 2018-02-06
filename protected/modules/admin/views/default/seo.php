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
<a class="add" href="/admin/default/seo_add">Добавить</a>
<br>
<div class="links">
    <?$cities = City::model()->findAll();
    foreach ($cities as $city) { ?>
        <a  href="/admin/default/seo<?=$city->id!=1 ? '?city_id='.$city->id:''?>"><?=$city->name?></a>
    <? } ?>
</div>
<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        'dataProvider' => $model->search(),
        'summaryText' => '',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'url',
                'editable' => array(
                    'title' => 'Url',
                    'type' => 'textarea',
                    'url' => $this->createUrl('/admin/default/UpdateSeoAjax'),
                ),
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'name',
                'editable' => array(
                    'title' => 'Название',
                    'type' => 'textarea',
                    'url' => $this->createUrl('/admin/default/UpdateSeoAjax'),
                ),
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'value',
                'editable' => array(
                    'title' => 'Значение',
                    'type' => 'textarea',
                    // 'source' => array(1 => 'да', 0 => 'нет'),
                    'url' => $this->createUrl('/admin/default/UpdateSeoAjax'),
                ),
            ),
        ),

    ));
    ?>
</div>
<style>
    div.links a{
        display: inline-block;
        background-color: #15447d;
        width: 80px;
        height: 22px;
        text-align: center;
        color: white;
        padding-top: 2px;
        font-size: 15px;
        margin: 5px 6px;
    }
    .links .selected{
        background-color: rgb(215, 215, 215);
        color: black;
        cursor: pointer;
    }
    .add{
        display: inline-block;
        margin: 10px;
        font-size: 16px;
        background-color: rgb(33, 120, 176);
        padding: 5px;
        color: white;
    }
</style>
<script>
    $(document).ready(function(){
        $("div.links a").each(function(){
            var url="<?=$_SERVER['REQUEST_URI'];?>";
            if($(this).attr('href')==url){
                $(this).addClass('selected');
            }
        });
    });
</script>
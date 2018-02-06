<?php
$str_js = "
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
			addSortable();
            return ui;
        };

        function addSortable()
        {
	        $('#menu-grid table.items tbody').sortable({
	            forcePlaceholderSize: true,
	            forceHelperSize: true,
	            items: 'tr',
	            update : function () {
	                serial = $('#menu-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class'});

	                $.ajax({
	                    'url': '/partner/menu/id/" . $model->id . "/sortmenu',
	                    'type': 'post',
	                    'data': serial,
	                    'success': function(data){
	                    },
	                    'error': function(request, status, error){
	                        alert('К сожалению при сортировке возникает ошибка.Попробуйте снова!!!');
	                    }
	                });
	            },
	            helper: fixHelper
	        }).disableSelection();
		}
		addSortable();
    ";
Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerScript('sortable-project', $str_js);
?>
<div class="h1-box">
    <div class="well">
        <h1>Разделы</h1>
    </div>
</div>
<div class="well well-bottom" style="padding-left: 10px;">
    <div>
        <?$cities = City::model()->findAll();
        foreach ($cities as $city) { ?>
            <a class="domain_link <?=Yii::app()->session['section_city_id']==$city->id ? 'selected' : '';?>" href="/admin/section/?city_id=<?=$city->id?>"><?=$city->name?></a>
        <? } ?>
    </div>
    <a class="btn btn-primary" href="/admin/section/create">Добавить раздел</a>
    <br><br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'id' => 'my-grid',
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered grid-view grid-size'),
        'summaryText' => '',
        'columns' => array(
            'id',
            'name',
            'tname',
            'title',
            array(
                'type' => 'raw',
                'header' => 'Изображение(mobile)',
                'value' => '$data->getMobileImage()=="" ? "":CHtml::image($data->getMobileImage()."?r=".rand(0, 1000),"",array("width"=>"200px"))',
            ),
            array(
                'type' => 'raw',
                'header' => 'Изображение(app)',
                'value' => '$data->getAppImage()=="" ? "":CHtml::image($data->getAppImage()."?r=".rand(0, 1000),"",array("width"=>"200px"))',
            ),
            'keywords',
            'description',
            'h1',
            'text',
            array(
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
            ),

        ),
    ));
    ?>
</div>
<style>
    .domain_link{
        display: inline-block;
        padding: 5px;
        margin-bottom: 10px;
        cursor: pointer;
    }
    .domain_link.selected{
        background-color: green;
        color:white;
        border-radius: 5px;
    }

</style>
<script>
    $(window).ready(function(){
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            addSortable();
            return ui;
        };

        function addSortable()
        {
            $('#my-grid table.items tbody').sortable({
                forcePlaceholderSize: true,
                forceHelperSize: true,
                items: 'tr',
                update : function () {
                    var a=new Array();
                    $("#my-grid table.items tbody tr").each(function(el){
                        var a1=new Array();
                        a1.push($(this).index());
                        a1.push($(this).find('td').first().text());
                        a.push(a1);
                    });
                    console.log(a);
                    $.ajax({
                        'url': '/admin/section/sort',
                        'type': 'post',
                        'data': $.extend({}, a),
                        'success': function(data){
                        },
                        'error': function(request, status, error){
                            alert('К сожалению при сортировке возникает ошибка.Попробуйте снова!!!');
                        }
                    });
                },
                helper: fixHelper
            }).disableSelection();
        }
        addSortable();
    });
</script>
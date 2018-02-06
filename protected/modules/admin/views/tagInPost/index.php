<div class="breadcrumbs">
    <a href="/admin/post">Блог</a> / <span>Теги</span>
</div>

<div class="h1-box">
    <div class="well">
        <h1>Теги</h1>
    </div>
</div>
<?php
$str_js = "
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };
        
        $('#project-grid table.items tbody').sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'tr',
            update : function () {
                serial = $('#project-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class'});
                $.ajax({
					'url': '" . "/admin/tagInPost/id/0/sort',
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
    ";
Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerScript('sortable-project', $str_js);
?>

<div class="well well-bottom">
    <br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model,
        'id' => 'project-grid',
        'summaryText' => '',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            'name',
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'headerHtmlOptions' => array('style' => 'width:40px; textdecoration:none'),
                'template' => '{update}{delete}',
            ),
        ),
    ));
    ?>
</div>







<h1>Страницы</h1>
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
                    'url': '/admin/pages/sort',
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
Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
Yii::app()->clientScript->registerScript('sortable-project', $str_js);
?>

<div class="well">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'project-grid',
        'dataProvider' => $dataProvider,
        'summaryText' => '',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'grid-view rounded'),
        'columns' => array(
            'label',
            'link',
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{update}',
                'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
            ),
        ),

    ));
    ?>
</div>
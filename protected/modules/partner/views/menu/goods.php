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
	        $('#goods-grid table.items tbody').sortable({
	            forcePlaceholderSize: true,
	            forceHelperSize: true,
	            items: 'tr',
	            update : function () {
	                serial = $('#goods-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class'});
	               
	                $.ajax({
	                    'url': '/partner/menu/sortgoods',
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
Yii::app()->clientScript->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerScript('sortable-project', $str_js);
?>

<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <a class="btn btn-primary"
       href="/partner/menu/addgoods/<?php echo $_GET["id"]; ?>">Добавить товар</a>
    <br><br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'goods-grid',
        'dataProvider' => $list->search(array('parent_id' => $_GET["id"])),
        //'filter'=>$list,
        'emptyText' => 'Ничего не найдено',
        //'htmlOptions' => array('class' => 'table table-bordered'),
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'rounded table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'class' => 'editable.EditableColumn',
                'header' => 'Название',
                'name' => 'name',
                'value' => $data->name,
                'htmlOptions' => array('width' => '80px'),
                'editable' => array(
                    'title' => 'Изменить',
                    'type' => 'textarea',
                    'model' => $data,
                    'attribute' => 'name',
                    'url' => $this->createUrl('/partner/profile/UpdateAjax'),
                ),
            ),
            array(
                'type' => 'raw',
                'name' => 'img',
                'value' => 'CHtml::image("/upload/goods/".$data->img, "$data->name", array("style"=>"max-width:200px;max-height:200px"))',
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'text',
                'value' => $data->text,
                'editable' => array(
                    'title' => 'Изменить',
                    'type' => 'textarea',
                    'model' => $data,
                    'attribute' => 'text',
                    'url' => $this->createUrl('/partner/profile/UpdateAjax'),
                ),
            ),
            array(
                'class' => 'editable.EditableColumn',
                'header' => 'Изменить цену',
                'name' => 'price',
                'value' => $list->price,
                //'headerHtmlOptions' => array('style' => 'width: 100px'),
                'editable' => array(
                    'title' => 'Изменить',
                    'type' => 'text',
                    'model' => $list,
                    'attribute' => 'price',
                    //'source'  => array(1 => 'Да', 0 => 'Нет'),
                    'url' => $this->createUrl('menu/UpdateAjax'),
                ),
                'filter' => false,
            ),
            array(
                'type' => 'raw',
                'header' => 'Скрыть',
                'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->publication ? "publ_goods" : "unpubl_goods"))',
            ),
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                'updateButtonUrl' => '"/partner/menu/updateproduct/".$data->id',
                'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
                'deleteButtonUrl' => '"/partner/menu/deleteproduct/".$data->id',
            ),
        ),
    ));
    ?>
</div>

<script>
    $("td").on('click', '.unpubl_goods, .publ_goods', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/partner/menu/id/" + p_id + "/changePublication",
            type: "post",
            success: function (data) {
                $(local_this).removeClass("publ_goods");
                $(local_this).removeClass("wait_publ_goods");
                $(local_this).removeClass("unpubl_goods");
                $(local_this).addClass(data);
                //$.fn.yiiGridView.update("my-grid");
            },
            beforeSend: function (data) {
                $(local_this).removeClass("publ_goods");
                $(local_this).removeClass("unpubl_goods");
                $(local_this).addClass("wait_publ_goods");
            }
        });
        return false;
    });
    $("td").on('click', '.standart_goods, .favorite_goods', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/partner/menu/id/" + p_id + "/changeFavorite",
            type: "post",
            success: function (data) {
                $(local_this).removeClass("favorite_goods");
                $(local_this).removeClass("wait_favorite_goods");
                $(local_this).removeClass("standart_goods");
                $(local_this).addClass(data);
            },
            beforeSend: function (data) {
                $(local_this).removeClass("favorite_goods");
                $(local_this).removeClass("standart_goods");
                $(local_this).addClass("wait_favorite_goods");
            }
        });
        return false;
    });
</script>
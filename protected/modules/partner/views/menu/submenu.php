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
	                    'url': '/partner/menu/sortmenu',
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
    <?php if (!Menu::model()->find(array('condition' => 'parent_id=' . $_GET['id']))) { ?>
        <div style="float:left;">
            <div id="addBtnBlock">
                <a class="btn btn-primary" id="showBtn">Добавить категорию меню</a>
                <br><br>
            </div>
            <div id="addBlock">
                <form method="post" action="/partner/menu/id/<? echo $model->id; ?>/AjaxAddMenuCatalog/?status=status">
                    <input type="text" style="width:250px;margin-bottom:0px;" name="name">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="parent_id"
                           value="<? echo $_GET["id"] ?>">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="have_subcatalog" value="0">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="partner_id"
                           value="<? echo $model->id ?>">
                    <input class="btn btn-primary" type="submit" width="150px">
                </form>
            </div>
        </div>
        &nbsp&nbsp или &nbsp&nbsp&nbsp
        <a class="btn btn-primary"
           href="/partner/menu/addgoods/<?php echo $_GET["id"]; ?>">Добавить товар</a>
    <?php } else { ?>
        <div>
            <div id="addBtnBlock">
                <a class="btn btn-primary" id="showBtn">Добавить категорию меню</a>
                <br><br>
            </div>
            <div id="addBlock">
                <form id="formAddMenu" name="formAddMenu" method="post">
                    <input id="addFormInput" type="text" style="width:250px;margin-bottom:0px;" name="name">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="parent_id"
                           value="<? echo $_GET["id"] ?>">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="have_subcatalog" value="0">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="partner_id"
                           value="<? echo $model->id ?>">
                    <input id="addBtn" class="btn btn-primary" type="submit" width="150px">
                </form>
            </div>
        </div>
    <? } ?>
    <br><br>

    <p>Для сортировки просто перетащите поле</p>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'menu-grid',
        'dataProvider' => $menu->search(array('parent_id' => $_GET["id"])),
        'emptyText' => 'Ничего не найдено',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'rounded table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            'id',
            array(
                'type' => 'html',
                'name' => 'name',
                'header' => 'Название',
                'value' => '"<a href=\"/partner/menu/".$data->id."\">".$data->name."</a>&nbsp&nbsp
						<span style=\"float:right;\">
							<a class=\"update\" href=\"/partner/menu/updatemenu/".$data->id."\">
								<img src=\"/iconset/edit.gif\">
							</a>
							<a class=\"delete\" href=\"/partner/menu/deletemenu/".$data->id."\">
								<img src=\"/iconset/delete.gif\">
							</a>
						</span>"',
                'headerHtmlOptions' => array('style' => 'min-width:200px;max-width:250px;'),
            ),
            array(
                'header' => 'Товары',
                'value' => '$data->have_subcatalog == 0 ? count(Goods::model()->findAll(array("condition"=>"parent_id=".$data->id))) : ""',
            ),
            array(
                'type' => 'raw',
                'header' => 'Скрыть',
                'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->publication ? "publ_menu" : "unpubl_menu"))',
            ),
        ),
    ));
    ?>
</div>

<script>
    $("td").on('click', '.unpubl_menu, .publ_menu', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/partner/menu/id/" + p_id + "/changePublicationMenu?status=status",
            type: "post",
            success: function (data) {
                $(local_this).removeClass("publ_menu");
                $(local_this).removeClass("wait_publ_menu");
                $(local_this).removeClass("unpubl_menu");
                $(local_this).addClass(data);
            },
            beforeSend: function (data) {
                $(local_this).removeClass("publ_menu");
                $(local_this).removeClass("unpubl_menu");
                $(local_this).addClass("wait_publ_menu");
            }
        });
        return false;
    });

    $("#showBtn").click(function () {
        $("#addBtnBlock").fadeOut(1);
        $("#addBlock").fadeIn(500);
        return false;
    });

    $("#addBtn").click(function () {
        $.ajax({
            url: "/partner/menu/id/<?echo $model->id?>/ajaxAddMenuCatalog?status=status",
            type: "post",
            data: $("#formAddMenu").serialize(),
            beforeSend: function (data) {
                $("#addBlock").fadeOut(1);
            },
            success: function (data) {
                $.fn.yiiGridView.update("menu-grid");
                $("#addBtnBlock").fadeIn(500);
                $("#addFormInput").val("");
            }
        });
        return false;
    });
</script>


<script type="text/javascript">
    jQuery(function ($) {
        jQuery('#menu-grid a.delete').live('click', function () {
            if (!confirm('Вы уверены, что хотите удалить данный элемент?')) return false;
            var th = this;
            var afterDelete = function () {
            };
            $.fn.yiiGridView.update('menu-grid', {
                type: 'POST',
                url: $(this).attr('href'),
                success: function (data) {
                    $.fn.yiiGridView.update('menu-grid');
                    afterDelete(th, true, data);
                },
                error: function (XHR) {
                    return afterDelete(th, false, XHR);
                }
            });
            return false;
        });
    });
</script>
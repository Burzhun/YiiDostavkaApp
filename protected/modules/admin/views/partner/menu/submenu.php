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
	                    'url': '/admin/partner/id/" . $model->id . "/sortmenu',
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

<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php if (!Menu::model()->find(array('condition' => 'parent_id=' . $_GET['actionId']))) { ?>
        <div style="float:left;">
            <div id="addBtnBlock">
                <a class="btn btn-primary" id="showBtn">Добавить категорию меню</a>
                <br><br>
            </div>
            <div id="addBlock">
                <form method="post" action="/admin/partner/id/<? echo $model->id; ?>/AjaxAddMenuCatalog/?status=status">
                    <input type="text" style="width:250px;margin-bottom:0px;" name="name">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="parent_id"
                           value="<? echo $_GET["actionId"] ?>">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="have_subcatalog" value="0">
                    <input type="hidden" style="width:250px;margin-bottom:0px;" name="partner_id"
                           value="<? echo $model->id ?>">
                    <input class="btn btn-primary" type="submit" width="150px">
                </form>
            </div>
        </div>
        &nbsp&nbsp или &nbsp&nbsp&nbsp
        <a class="btn btn-primary"
           href="/admin/partner/id/<?php echo $model->id; ?>/addgoods/<?php echo $_GET["actionId"]; ?>">Добавить
            товар</a>
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
                           value="<? echo $_GET["actionId"] ?>">
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
    $menu_search = $menu->search(array('parent_id' => $_GET["actionId"]));
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'menu-grid',
        'dataProvider' => $menu_search,
        'filter' => $menu,
        'emptyText' => 'Данное меню пусто. Вы можете добавить сюда либо подкатегории товаров, например: безалкогольные напитки, горячие закуски; или сами товары: Сок "Я", Суп харчо...',
        'summaryText' => '',
        'rowCssClassExpression' => '"items[]_{$data->id}"',
        'htmlOptions' => array('class' => 'rounded table table-bordered'),
        'columns' => array(
            array(
                'name' => 'id',
                'filter' => CHtml::textField('Menu[id]', $menu->id, array('style' => 'width:27px')),
            ),
            array(
                'type' => 'html',
                'name' => 'name',
                'header' => 'Название',
                'value' => '"<a href=\"/admin/partner/id/".$data->partner->id."/menu/".$data->id."\">".$data->name."</a>&nbsp&nbsp
								<span style=\"float:right;\">
									<a class=\"update\" href=\"/admin/partner/id/".$data->partner_id."/update/menu/".$data->id."\">
										<img src=\"/iconset/edit.gif\">
									</a>
									<a class=\"delete\" href=\"/admin/partner/id/".$data->partner_id."/delete/menu/".$data->id."\">
										<img src=\"/iconset/delete.gif\">
									</a>
								</span>"',
                'headerHtmlOptions' => array('style' => 'min-width:200px;max-width:250px;'),
            ),
            array(
                'header' => 'Кол. товаров',
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

<style>
    .empty {
        color: red;
    }
</style>

<script>
    $("td").on('click', '.unpubl_menu, .publ_menu', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/admin/partner/id/" + p_id + "/changePublicationMenu?status=status",
            type: "post",
            success: function (data) {
                $(local_this).removeClass("publ_menu");
                $(local_this).removeClass("wait_publ_menu");
                $(local_this).removeClass("unpubl_menu");
                $(local_this).addClass(data);
                //$.fn.yiiGridView.update("my-grid");
            },
            beforeSend: function (data) {
                $(local_this).removeClass("publ_menu");
                $(local_this).removeClass("unpubl_menu");
                //$(local_this).removeClass("wait_vip_partner");
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
            url: "/admin/partner/id/<?echo $model->id?>/ajaxAddMenuCatalog?status=status",
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
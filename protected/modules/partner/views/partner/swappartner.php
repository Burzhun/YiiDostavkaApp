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
Yii::app()->clientScript->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerScript('sortable-project', $str_js);
?>

<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <div id="addBtnBlock">
        <a class="btn btn-primary" id="showBtn">Объеденить партнеров</a>
        <br><br>
    </div>
    <div id="addBlock">
        <form id="formAddMenu" name="formAddMenu" method="post">
            <? $partner_list = Partner::model()->findAll(array('condition' => 'user_id NOT IN (SELECT user_id FROM tbl_relation_partner) AND user_id NOT IN (SELECT owner_id FROM tbl_relation_partner)')) ?>
            <select name="select_partner_id" id="addFormInput" style="width:250px;margin-bottom:0px;">
                <? foreach ($partner_list as $p) { ?>
                    <option value="<?= $p->user_id ?>"><?= $p->name ?></option>
                <? } ?>
            </select>
            <input type="hidden" style="width:250px;margin-bottom:0px;" name="parent_id"
                   value="<? echo isset($_GET["actionId"]) ? $_GET["actionId"] : "" ?>">
            <input type="hidden" style="width:250px;margin-bottom:0px;" name="have_subcatalog" value="1">
            <input type="hidden" style="width:250px;margin-bottom:0px;" name="partner_id" value="<? echo $model->id ?>">
            <input id="addBtn" class="btn btn-primary" type="submit" width="150px" value="Объединить">
        </form>
    </div>

    <?php
    $owner = new CActiveDataProvider('Relationpartner', array(
        'criteria' => array(
            'condition' => 'owner_id=' . $model->user->id,
        ),
        'pagination' => array(
            'pageSize' => 500,
        ),
    ));

    $user = Relationpartner::model()->find(array('condition' => 'user_id=' . $model->user->id));

    if ($owner->data) {
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'partner-grid',
            'dataProvider' => $owner,
            'summaryText' => '',
            'htmlOptions' => array('class' => 'rounded table table-bordered'),
            'columns' => array(
                array(
                    'name' => 'owner_id',
                    'type' => 'raw',
                    'value' => '$data->owner->partner->name',
                ),
                array(
                    'name' => 'user_id',
                    'type' => 'raw',
                    'value' => '$data->user->partner->name',
                ),
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{delete}',
                    'header' => 'Операции',
                    'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
                    'deleteButtonUrl' => '"/admin/partner/id/".$data->owner_id."/deleterelparner/?status=".$data->user_id',
                ),
            )
        ));
    } elseif ($user) {
        echo "<style>#addBtnBlock{display:none;}#addBlock{display:none;}</style>";
        echo "Партнер объеденен с <a href='/admin/partner/id/" . $user->owner->partner->id . "/swappartner'>" . $user->owner->partner->name . "</a>";
    } elseif (!$user && !$owner->data) {
        echo "Партнер еще ни с кем не объеденен";
    }
    ?>
</div>

<style>
    .empty {
        color: red;
    }
</style>

<script>
    $("#showBtn").click(function () {
        $("#addBtnBlock").fadeOut(1);
        $("#addBlock").fadeIn(500);
        return false;
    });

    $("#addBtn").click(function () {
        $.ajax({
            url: "/admin/partner/id/<?echo $model->user_id?>/ajaxRelatedParner?status=status",
            type: "post",
            data: $("#formAddMenu").serialize(),
            beforeSend: function (data) {
                $("#addBlock").fadeOut(1);
            },
            success: function (data) {
                $.fn.yiiGridView.update("partner-grid");
                $("#addBtnBlock").fadeIn(500);
                $("#addFormInput").val("");
            },
        });
        return false;
    });
</script>
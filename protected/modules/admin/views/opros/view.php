<?
/*$this->breadcrumbs=array(
	'Админка'=>array('/admin'),
	'Опросы'=>array('/admin/opros'),
	'Опрос #'.$model->data[0]->parent_id,
);?>

<h1>Опрос #<?=$model->data[0]->parent_id;?></h1>
<br>
<div id="addAnswer">
	<form id="formAddAnswer" name="formAddAnswer" method="post">
		<input id="addFormInput" type="text" style="width:250px;margin-bottom:0px;" name="answer">
		<input type="hidden" style="width:250px;margin-bottom:0px;" name="parent_id" value="<?echo isset($_GET["id"]) ? $_GET["id"] : ""?>">
		<input id="addBtn" class="btn btn-primary" type="submit" width="150px">
	</form>
</div>*/ ?>

<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <div id="addAnswer">
        <form id="formAddAnswer" name="formAddAnswer" method="post">
            <input id="addFormInput" type="text" style="width:250px;margin-bottom:0px;" name="answer">
            <input type="hidden" style="width:250px;margin-bottom:0px;" name="parent_id"
                   value="<? echo isset($_GET["id"]) ? $_GET["id"] : "" ?>">
            <input id="addBtn" class="btn btn-primary" type="submit" width="150px">
        </form>
    </div>

    <? $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'opros-grid',
        'dataProvider' => $dataProvider,
        'summaryText' => '',
        'htmlOptions' => array('class' => 'table table-bordered'),
        //'filter'=>$model,
        'columns' => array(
            'id',
            'answer',
            'sum',
            array(
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'updateButtonUrl' => '"/admin/opros/updateAnswer/id/".$data->id',
                'deleteButtonUrl' => '"/admin/opros/deleteAnswer/id/".$data->id',
            ),
        ),
    )); ?>

    <script>
        $("#showBtn").click(function () {
            $("#addBtnBlock").fadeOut(1);
            $("#addBlock").fadeIn(500);
            return false;
        });
        $("#addBtn").click(function () {
            $.ajax({
                url: "/admin/opros/addAnswer",
                type: "post",
                data: $("#formAddAnswer").serialize(),
                beforeSend: function (data) {
                    $("#addBlock").fadeOut(1);
                },
                success: function (data) {
                    $.fn.yiiGridView.update("opros-grid");
                    $("#addBtnBlock").fadeIn(500);
                    $("#addFormInput").val("");
                },
            });
            return false;
        });
    </script>
</div>
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
						'url': '/admin/partner/id/" . $model->id . "/sortgoods',
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
	<a class="btn btn-primary"
	   href="/admin/partner/id/<?php echo $model->id; ?>/addgoods/<?php echo $_GET["actionId"]; ?>">Добавить
		товар</a>
	<br><br>
	<style>
		.price_field span {
			text-decoration: underline;
			cursor: pointer;
		}
	</style>
	<script>
		$("body").on('click', '.price_field span', function () {
			var id = $(this).attr("id");
			var old_price = $(this).text();
			var price = prompt('Введите новую цену', old_price);
			if (!price) {
				price = old_price;
			}
			if (price != old_price && !price.match(/[^0-9]+/)) {
				$.post();
			}
		});
	</script>
	<p>Для сортировки просто перетащите поле</p>
	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'goods-grid',
		'dataProvider' => $list->search(array('parent_id' => $_GET["actionId"])),
		'filter' => $list,
		'ajaxUpdate' => false,
		'emptyText' => 'Ничего не найдено',
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
					'url' => $this->createUrl('partner/UpdateAjax'),
				),
			),
			array(
				'type' => 'raw',
				'name' => 'img',
				'value' => '$data->image != "" ? "<img src=\"".$data->image."\" style=\"max-width:150px;max-height:150px;\">" : "<img src=\"/images/default.jpg\" style=\"max-width:150px;max-height:150px;\">"',
				'filter' => false,
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
					'url' => $this->createUrl('partner/UpdateAjax'),
				),
			),
			
			array(
				'class' => 'editable.EditableColumn',
				'header' => 'Цена',
				'name' => 'price',
				'value' => $list->price,
				'editable' => array(
					'title' => 'Изменить',
					'type' => 'text',
					'model' => $list,
					'attribute' => 'price',
					'url' => $this->createUrl('partner/UpdateAjax'),
				),
				'filter' => false,
			),
			array(
				'class' => 'editable.EditableColumn',
				'name' => 'tag_id',
				'value' => '$data->tag->name',
				'editable' => array(
					'title' => 'Изменить',
					'type' => 'select',
					'model' => $data,
					'attribute' => 'name',
					'source'  => array('0' => 'Не выбран') + Tag::getTagList(),
					'url' => $this->createUrl('partner/UpdateAjax'),
				),
				'filter' => array('0' => 'Не выбран') + Tag::getTagList(),

			),
			array(
				'header' => 'Операции',
				'class' => 'CButtonColumn',
				'template' => '{update}{delete}',
				'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
				'updateButtonUrl' => '"/admin/partner/id/".$data->partner_id."/update/product/".$data->id',
				'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
				'deleteButtonUrl' => '"/admin/partner/id/".$data->partner_id."/delete/product/".$data->id',
			),
			array(
				'type' => 'raw',
				'header' => 'Скрыть',
				'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->publication ? "publ_goods" : "unpubl_goods"))',
			),
			array(
				'type' => 'raw',
				'header' => 'Популярное',
				'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->favorite ? "favorite_goods" : "standart_goods"))',
			),

		),
	));
	function print2($data)
	{
		return '<span>' . CHtml::encode($data) . '</span>';
	} ?>
</div>
<style>
	.button-column .update{
		margin: 0px 6px;
	}
</style>

<script>
	$("td").on('click', '.unpubl_goods, .publ_goods', function (event) {
		var local_this = this;
		var p_id = $(this).attr('id');
		$.ajax({
			url: "/admin/partner/id/" + p_id + "/changePublication?status=status",
			type: "post",
			success: function (data) {
				$(local_this).removeClass("publ_goods");
				$(local_this).removeClass("wait_publ_goods");
				$(local_this).removeClass("unpubl_goods");
				$(local_this).addClass(data);
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
			url: "/admin/partner/id/" + p_id + "/changeFavorite?status=status",
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
<style>
	#goods-grid_c4{
		max-width: 80px;
	}
	select[name="Goods[tag_id]"] {
		max-width: 80px;
	}
</style>
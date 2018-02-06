<?
$this->breadcrumbs = array(
    'Админка' => array('/admin'),
    'Заказы' => array('admin'),
    'Графики' => array('graph'),
); ?>

<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>

<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <div style="display: inline-block;vertical-align: top;margin-right: 20px;width: 135px;">
        <? $this->renderPartial('statistic/column_nav', array('model' => $model)); ?>
    </div>
    <div style="display: inline-block">
        <h1>
            Заказываемые товары
        </h1>

        <br><br>

        <? $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'project-grid',
            'dataProvider' => $dataProvider,
            'htmlOptions' => array('class' => ''),
            'summaryText' => '',
            'columns' => array(
                array(
                    'type' => 'raw',
                    'header' => 'Количество купленных     ',
                    'value' => '$data["sum_goods"]',
                ),
                array(
                    'class' => 'CLinkColumn',
                    'header' => 'Товары',
                    'labelExpression' => 'Goods::model()->cache(40000)->findByPk($data["goods_id"])->name ? Goods::model()->cache(40000)->findByPk($data["goods_id"])->name : "--Товар удален--"',
                    'urlExpression' => '"/partner/menu/product/" . $data["goods_id"]',
                    'linkHtmlOptions' => array('target' => '_blank'),
                ),
                array(
                    'type' => 'raw',
                    'header' => 'Изображение',
                    'value' => '"<img style=max-width:150px;max-height:150px; src=/upload/goods/".Goods::model()->cache(40000)->findByPk($data["goods_id"])->img.">"',
                ),
                /*array(
                    'class' => 'CLinkColumn',
                    'header'=>'Название',
                    'labelExpression'=>'$data->name',
                    'urlExpression'=> '"/admin/catalog/index/parent_id/".$data->id',
                ),
                array(
                    'name' => 'actions',
                    'header' => 'Количество подкатегорий',
                    'value' => 'count($data->Sub)',
                    'filter' => false,
                ),
                array(
                    'header' => 'Операции',
                    'class' => 'CButtonColumn',
                    'template'=>'{update}{delete}',
                    'buttons'=>array (
                        'update' => array (
                            'label'=>'Редактировать',//Text label of the button.
                            'imageUrl'=>false,  //Image URL of the button.
                            'options'=>array('style'=>'margin-bottom: 5px;', 'class'=>'btn btn-success')
                        )
                    )
                ),*/
            ),
        )); ?>
        <a href="/partner/statistic/goods?page_size=10000"></a>
    </div>
</div>
<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php echo CHtml::link('Редактировать', '/partner/menu/updateproduct/' . $_GET['id'], array('class' => "btn btn-primary")); ?>

    <?php echo CHtml::link('Удалить', '/partner/menu/deleteproduct/' . $_GET['id'], array('class' => "btn btn-danger", 'onclick' => "return confirm('Удалить?')")); ?>
    <br><br>

    <img src="<?php if ($product_model->img) {
        echo '/upload/goods/' . $product_model->img;
    } else { ?>/images/defaultproduct.jpg<?php } ?>" style="max-width:500px;"><br><br>
    Название: <?php echo $product_model->name; ?><br><br>
    Цена: <?php echo $product_model->price; ?><br><br>
    Ед. измерения: <?php echo $product_model->unit; ?><br><br>
</div>
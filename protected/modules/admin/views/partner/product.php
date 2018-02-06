<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php echo CHtml::link('Редактировать', '/admin/partner/id/' . $_GET['id'] . '/update/product/' . $_GET['actionId'], array('class' => "btn btn-primary")); ?>
    <?php echo CHtml::link('Удалить', '/admin/partner/id/' . $_GET['id'] . '/delete/product/' . $_GET['actionId'], array('class' => "btn btn-danger", 'onclick' => "return confirm('Удалить?')")); ?>
    <br><br>

    <img src="<?php if ($product_model->img) {
        echo '/upload/goods/' . $product_model->img;
    } else { ?>/images/default.jpg<?php } ?>" style="max-width:500px;"><br><br>
    Название: <?php echo $product_model->name; ?><br><br>
    Цена: <?php echo $product_model->price; ?><br><br>
    Ед. измерения: <?php echo $product_model->unit; ?><br><br>
</div>
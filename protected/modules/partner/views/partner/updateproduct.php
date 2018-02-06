<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($goods_model); ?>


    <?php echo $form->labelEx($goods_model, 'name'); ?>
    <?php echo $form->textField($goods_model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($goods_model, 'name'); ?>

    <?php echo $form->labelEx($goods_model, 'img'); ?>
    <?php echo $form->fileField($goods_model, 'img', array('value' => '' . $goods_model->img)); ?>
    <?php echo $form->error($goods_model, 'img'); ?>

    <?php echo $form->labelEx($goods_model, 'price'); ?>
    <?php echo $form->textField($goods_model, 'price', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($goods_model, 'price'); ?>

    <? echo $form->labelEx($goods_model, 'old_price'); ?>
    <? echo $form->textField($goods_model, 'old_price', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'old_price'); ?>

    <br><span>Акция</span>
    <? echo $form->checkBox($goods_model, 'action', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'action'); ?>


    <? /*php echo $form->labelEx($goods_model,'shorttext'); ?>
		<?php echo $form->textField($goods_model,'shorttext',array('class'=>'txt','size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($goods_model,'shorttext'); */ ?>

    <?php echo $form->labelEx($goods_model, 'text'); ?>
    <?php echo $form->textArea($goods_model, 'text', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($goods_model, 'text'); ?>

    <?php echo $form->labelEx($goods_model, 'related_products'); ?>
    <?php echo $form->textField($goods_model, 'related_products', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($goods_model, 'related_products'); ?>

    <?php echo $form->labelEx($goods_model, 'parent_id'); ?>
    <?php $parent_of_parent = Menu::model()->findByPk($goods_model->parent_id)->parent_id;
    $other_parents = Menu::model()->findAll(array('condition' => 'partner_id=' . $goods_model->partner_id . ' AND id NOT IN(SELECT parent_id FROM tbl_menu WHERE parent_id<>0)'));
    echo $form->dropDownList($goods_model, 'parent_id', CHtml::listData($other_parents, 'id', 'name')); ?>
    <?php echo $form->error($goods_model, 'parent_id'); ?>

    <input type="hidden" value="<?php echo $goods_model->parent_id ?>" name="oldparent" id="oldparent">
    <?php echo $form->hiddenField($goods_model, 'partner_id', array('value' => $goods_model->partner_id)); ?>
    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
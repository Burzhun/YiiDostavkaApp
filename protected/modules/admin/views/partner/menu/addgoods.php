<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <? $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <? echo $form->errorSummary($goods_model); ?>

    <? echo $form->labelEx($goods_model, 'name'); ?>
    <? echo $form->textField($goods_model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'name'); ?>

    <? echo $form->labelEx($goods_model, 'img'); ?>
    <? echo $form->fileField($goods_model, 'img'); ?>
    <? echo $form->error($goods_model, 'img'); ?>

    <? echo $form->labelEx($goods_model, 'price'); ?>
    <? echo $form->textField($goods_model, 'price', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'price'); ?>

    <? echo $form->labelEx($goods_model, 'old_price'); ?>
    <? echo $form->textField($goods_model, 'old_price', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'old_price'); ?>

    <br><span>Акция</span>
    <? echo $form->checkBox($goods_model, 'action', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'action'); ?>


    <? /*echo $form->labelEx($goods_model,'shorttext'); ?>
		<? echo $form->textField($goods_model,'shorttext',array('class'=>'txt','size'=>60,'maxlength'=>255)); ?>
		<? echo $form->error($goods_model,'shorttext'); */ ?>

    <? echo $form->labelEx($goods_model, 'text'); ?>
    <? echo $form->textArea($goods_model, 'text', array('class' => 'txt1', 'rows'=>'6', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'text'); ?>

    <? echo $form->labelEx($goods_model, 'related_products'); ?>
    <? echo $form->textField($goods_model, 'related_products', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($goods_model, 'related_products'); ?>

    <? echo $form->hiddenField($goods_model, 'parent_id', array('value' => $_GET['actionId'])); ?>
    <? echo $form->hiddenField($goods_model, 'partner_id', array('value' => $_GET['id'])); ?>

    <br><br>
    <? echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <? $this->endWidget(); ?>
</div>
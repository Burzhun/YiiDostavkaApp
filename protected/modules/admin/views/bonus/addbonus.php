<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'bonus-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'name'); ?>

    <br><br>
    <? if ($model->img) { ?>
        <img style="width:100px;" src="/upload/bonus/<?= $model->img ?>">
    <? } ?>
    <?php echo $form->labelEx($model, 'img'); ?>
    <?php echo $form->fileField($model, 'img'); ?>
    <?php echo $form->error($model, 'img'); ?>
    <br><br>
    <?php echo $form->labelEx($model, 'price'); ?>
    <?php echo $form->textField($model, 'price', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'price'); ?>

    <?php echo $form->labelEx($model, 'shorttext'); ?>
    <?php echo $form->textField($model, 'shorttext', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'shorttext'); ?>

    <?php echo $form->labelEx($model, 'text'); ?>
    <?php echo $form->textField($model, 'text', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'text'); ?>

    <br><br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
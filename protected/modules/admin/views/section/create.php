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

    <?php echo $form->labelEx($model, 'tname'); ?>
    <?php echo $form->textField($model, 'tname', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'tname'); ?>

    <br><br>
    <?php echo $form->labelEx($model, 'city_id'); ?>
    <?php echo $form->dropDownList($model, 'city_id', CHtml::ListData(City::model()->findAll(), 'id', 'name')); ?>
    <?php echo $form->error($model, 'city_id'); ?>
    <br><br>
    <?php echo $form->labelEx($model, 'image'); ?>
    <? if ($model->image) { ?>
        <img style="width:100px;" src="<?=$model->getMobileImage()?>">
    <? } ?>
    <?php echo $form->fileField($model, 'image'); ?>
    <?php echo $form->error($model, 'image'); ?>
    <br><br>

    <?php echo $form->labelEx($model, 'image_min'); ?>
    <? if ($model->image_min) { ?>
        <img style="width:100px;" src="<?=$model->getAppImage()?>">
    <? } ?>
    <?php echo $form->fileField($model, 'image_min'); ?>
    <?php echo $form->error($model, 'image_min'); ?>
    <br><br>

    <?php echo $form->labelEx($model, 'title'); ?>
    <?php echo $form->textField($model, 'title', array('class' => 'txt', 'size' => 120, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'title'); ?>

    <?php echo $form->labelEx($model, 'keywords'); ?>
    <?php echo $form->textArea($model, 'keywords', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'keywords'); ?>

    <?php echo $form->labelEx($model, 'description'); ?>
    <?php echo $form->textArea($model, 'description', array('class' => 'txt', 'size' => 120, 'maxlength' => 500)); ?>
    <?php echo $form->error($model, 'description'); ?>

    <?php echo $form->labelEx($model, 'h1'); ?>
    <?php echo $form->textArea($model, 'h1', array('class' => 'txt', 'size' => 120, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'h1'); ?>

    <?php echo $form->labelEx($model, 'text'); ?>
    <?php echo $form->textArea($model, 'text', array('class' => 'txt', 'size' => 120, 'maxlength' => 500)); ?>
    <?php echo $form->error($model, 'text'); ?>

    <?php echo $form->labelEx($model, 'direction_id'); ?>
    <?php echo $form->dropDownList($model, 'direction_id', Direction::getList()); ?>
    <?php echo $form->error($model, 'direction_id'); ?>

    <?/*<input  name="Specialization[direction_id]"  value="1" type="hidden">*/?>
    <br><br>

    <?if($model->isNewRecord){?>
        <?php echo $form->labelEx($model, 'pos'); ?>
        <?php echo $form->textField($model, 'pos', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'pos'); ?>

        <br><br>
    <?}?>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
<style>
    input[type='text'],textarea{
        width:450px;
    }
</style>
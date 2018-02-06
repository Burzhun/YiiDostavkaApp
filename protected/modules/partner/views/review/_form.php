<?php
/* @var $this ReviewController */
/* @var $model Review */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'review-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченные как <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <div>
        <?php echo $form->labelEx($model, 'id'); ?>
        <?php echo $form->textField($model, 'id'); ?>
        <?php echo $form->error($model, 'id'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'review'); ?>
        <?php echo $form->textField($model, 'review'); ?>
        <?php echo $form->error($model, 'review'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'partner_id'); ?>
        <?php echo $form->textField($model, 'partner_id'); ?>
        <?php echo $form->error($model, 'partner_id'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'visible'); ?>
        <?php echo $form->textField($model, 'visible'); ?>
        <?php echo $form->error($model, 'visible'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'content'); ?>
        <?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'content'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'user_id'); ?>
        <?php echo $form->textField($model, 'user_id'); ?>
        <?php echo $form->error($model, 'user_id'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'created'); ?>
        <?php echo $form->textField($model, 'created'); ?>
        <?php echo $form->error($model, 'created'); ?>
    </div>

    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
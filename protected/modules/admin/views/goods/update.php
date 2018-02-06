<h1>Отзывы</h1>


<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data')
)); ?>

<p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

<?php echo $form->errorSummary($model); ?>

Автор: <?php echo $model->user->name; ?><br>

<div class="row">
    <?php echo $form->labelEx($model, 'question'); ?>
    <?php echo $form->textArea($model, 'question'); ?>
    <?php echo $form->error($model, 'question'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'answer'); ?>
    <?php echo $form->textArea($model, 'answer'); ?>
    <?php echo $form->error($model, 'answer'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'date'); ?>
    <?php echo $form->textField($model, 'date', array('class' => 'txt', 'size' => 15, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'date'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'status'); ?>
    <?php echo $form->dropDownList($model, 'status', ZHtml::enumItem($model, 'status', array())); ?>
    <?php echo $form->error($model, 'status'); ?>
</div>

<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
</div>

<?php $this->endWidget(); ?>
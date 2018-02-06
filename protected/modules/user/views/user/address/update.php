<?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data')
)); ?>

<p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <?php echo $form->labelEx($address_model, 'city_id'); ?>
    <?php echo $form->dropDownList($address_model->city, 'id',
        CHtml::listData(City::model()->findAll(), 'id', 'name')); ?>

    <?php //echo $form->textField($address_model->city,'name',array('class'=>'txt','size'=>60,'maxlength'=>255)); ?>
    <?php echo $form->error($address_model, 'city_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($address_model, 'street'); ?>
    <?php echo $form->textField($address_model, 'street', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($address_model, 'street'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($address_model, 'house'); ?>
    <?php echo $form->textField($address_model, 'house', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($address_model, 'house'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($address_model, 'storey'); ?>
    <?php echo $form->textField($address_model, 'storey', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($address_model, 'storey'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($address_model, 'number'); ?>
    <?php echo $form->textField($address_model, 'number', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($address_model, 'number'); ?>
</div>

<div class="row buttons">
    <?php echo CHtml::submitButton($address_model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
</div>

<?php $this->endWidget(); ?>
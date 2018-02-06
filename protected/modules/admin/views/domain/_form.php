<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <? //php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'name'); ?>


    <?php echo $form->labelEx($model, 'alias'); ?>
    <?php echo $form->textField($model, 'alias', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'alias'); ?>


    <?php echo $form->labelEx($model, 'logo'); ?>
    <?php echo $form->textField($model, 'logo', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'logo'); ?>


    <?php echo $form->labelEx($model, 'footer_logo'); ?>
    <?php echo $form->textField($model, 'footer_logo', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'footer_logo'); ?>

    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    <?php $this->endWidget(); ?>

</div>

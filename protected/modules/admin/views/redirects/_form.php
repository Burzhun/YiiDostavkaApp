<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <? //php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'old_url'); ?>
    <?php echo $form->textField($model, 'old_url', array('size' => 100, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'old_url'); ?>
    <br>
    <?php echo $form->labelEx($model, 'new_url'); ?>
    <?php echo $form->textField($model, 'new_url', array('size' => 100, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'new_url'); ?>




    <br><br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    <?php $this->endWidget(); ?>

</div>

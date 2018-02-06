<div class="well">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'opros-otvet-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <?php echo $form->errorSummary($model); ?>

    <div>
        <?php echo $form->labelEx($model, 'answer'); ?>
        <?php echo $form->textField($model, 'answer', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'answer'); ?>
    </div>

    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
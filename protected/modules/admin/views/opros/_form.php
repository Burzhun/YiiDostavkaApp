<div class="well">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'opros-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <?php echo $form->errorSummary($model); ?>

    <div>
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'pos'); ?>
        <?php echo $form->textField($model, 'pos'); ?>
        <?php echo $form->error($model, 'pos'); ?>
    </div>

    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
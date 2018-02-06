<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($menu_model); ?>

    <div>
        <?php echo $form->labelEx($menu_model, 'name'); ?>
        <?php echo $form->textField($menu_model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255, 'style' => 'float:left;margin-right:10px;')); ?>
        <?php echo $form->error($menu_model, 'name'); ?>

        <?php if ($_GET['id'] != 0) {
            $have_subcatalog = 0;
        } else {
            $have_subcatalog = 1;
        } ?>
        <?php echo $form->hiddenField($menu_model, 'have_subcatalog', array('value' => $have_subcatalog)); ?>
        <?php echo $form->hiddenField($menu_model, 'parent_id', array('value' => $_GET['id'])); ?>
        <?php echo $form->hiddenField($menu_model, 'pos', array('value' => '0')); ?>
        <?php echo $form->hiddenField($menu_model, 'partner_id', array('value' => $model->id)); ?>

        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'btn btn-primary')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
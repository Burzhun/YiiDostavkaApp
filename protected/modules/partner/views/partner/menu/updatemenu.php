<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

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

    <?php echo $form->labelEx($menu_model, 'name'); ?>
    <?php echo $form->textField($menu_model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255, 'style' => 'margin:0px;')); ?>
    <?php echo $form->error($menu_model, 'name'); ?>

    <input type="hidden" value="<?php echo $menu_model->parent_id ?>" name="oldparent" id="oldparent">

    <?php if ($menu_model->parent_id != 0) { ?>
        <?php echo $form->labelEx($menu_model, 'parent_id'); ?>
        <?php echo $form->dropDownList($menu_model, 'parent_id', CHtml::listData(Menu::model()->findAll(array('condition' => 'parent_id=0 AND partner_id=' . $menu_model->partner_id)), 'id', 'name')); ?>
        <?php echo $form->error($menu_model, 'parent_id'); ?>
    <?php } ?>

    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
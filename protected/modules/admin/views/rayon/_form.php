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

    <? $cities = City::model()->findAll('');
    $cities_list = CHtml::listData($cities, 'id', 'name');

    ?>
    <br><br>
    Выберите город<br>
    <?php echo CHTML::dropDownList('Rayon[city_id]', $model->city_id, $cities_list); ?>


    <? /*php echo $form->labelEx($model,'logo'); ?>
	<?php echo $form->textField($model,'logo',array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'logo'); ?>


	<?php echo $form->labelEx($model,'footer_logo'); ?>
	<?php echo $form->textField($model,'footer_logo',array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'footer_logo');*/ ?>

    <br><br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    <?php $this->endWidget(); ?>

</div>

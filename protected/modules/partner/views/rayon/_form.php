<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <?

    $rayon = Rayon::model()->findAll();
    $rayon_list = CHtml::listData($rayon, 'id', 'name');
    ?>


    <br><br>
    Выберите район<br>
    <?php echo CHTML::dropDownList('PartnerRayon[rayon_id]', $partner->rayon_id, $rayon_list); ?>
    <br><br>
    Минимальная сумма<br>
    <input type="text" name="PartnerRayon[min_sum]" value="<?= $partner->min_sum; ?>" required="true">


    <? /*php echo $form->labelEx($model,'logo'); ?>
	<?php echo $form->textField($model,'logo',array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'logo'); ?>


	<?php echo $form->labelEx($model,'footer_logo'); ?>
	<?php echo $form->textField($model,'footer_logo',array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'footer_logo');*/ ?>

    <br>
    <?php echo CHtml::submitButton($partner->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    <?php $this->endWidget(); ?>

</div>

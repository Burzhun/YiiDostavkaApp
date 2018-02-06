<div style="padding:10px;">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'config-form',
        'enableAjaxValidation' => false,
    )); ?>

    <? foreach ($models as $model) {
        if ($model->id == 27) {
            continue;
        } ?>
        <? $i++; ?>

        <?php echo $form->hiddenField($model, 'id'); ?>
        <?php echo $form->errorSummary($model); ?>

        <div style="font-weight:bold;">
            <?= $model->description ?>
        </div>
        <? //if($model->id==13||$model->id==25||$model->id==26||$model->id==1||$model->id==2||$model->id==3){?>
        <div>
            <?php echo $form->textArea($model, 'value', array('style' => 'width:540px;height:100px;', 'name' => 'Config[value][' . $model->id . ']')); ?>
            <?php echo $form->error($model, 'value'); ?>
        </div>
        <? /*}else{?>
				<div>
					<?php echo $form->textField($model,'value',array('style'=>'width:400px;', 'name'=>'Config[value]['.$model->id.']')); ?>
					<?php echo $form->error($model,'value'); ?>
				</div>
		<?}**/ ?>
    <? } ?>


    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
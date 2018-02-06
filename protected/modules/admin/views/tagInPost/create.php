<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'post-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'title'); ?>
    <?php echo $form->textField($model, 'title', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'title'); ?>
    <br><br>
    <?php echo $form->labelEx($model, 'img'); ?>
    <? if (!empty($model->img)) { ?>
        <img src="/upload/post/<?= $model->img ?>" style="max-width:100px;max-height:100px;"><br><br>
    <? } ?>
    <?php echo $form->fileField($model, 'img'); ?>
    <?php echo $form->error($model, 'img'); ?>
    <br><br><br>
    <?php echo $form->labelEx($model, 'shorttext'); ?>
    <?php echo $form->textField($model, 'shorttext', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'shorttext'); ?>

    <?php echo $form->labelEx($model, 'text'); ?>
    <?php echo $form->textField($model, 'text', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'text'); ?>
    <br><br>
    <?
    $model->date = $model->isNewRecord ? date('Y-m-d H:i:s') : $model->date;
    $this->widget('application.extensions.timepicker.EJuiDateTimePicker', array(
        'model' => $model,
        'attribute' => 'date',
        //'language' => 'ru-RU',
        'options' => array(
            'defaultValue' => 'now()',
            'showAnim' => 'slide',
            'showSecond' => true,
            //'ampm'=>true,
            'timeFormat' => 'hh:mm:ss',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
        ),
    )); ?>
    <br>
    <br>
    <?php echo $form->labelEx($model, 'tags'); ?>
    <? echo CHtml::textField('Post[tags]', $model->selectedListData(), array('id' => 'test', 'style' => 'width: 220px;'));
    $this->widget('ext.select2.ESelect2', array(
        'name' => 'Post[tags]',
        'selector' => '#test',
        //'data'=>TegInPost::listData(),
        'htmlOptions' => array(
            'multiple' => 'multiple',
        ),
        'options' => array(
            'tags' => TagInPost::listData(),
        ),
    ));
    ?>

    <? /*php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date',array('class'=>'txt','size'=>60,'maxlength'=>255, 'value'=> $model->isNewRecord ? date('Y-m-d') : $model->date)); ?>
		<?php echo $form->error($model,'date'); */ ?>

    <? /*php echo $form->hiddenField($model,'parent_id',array('value' => $_GET['actionId'])); ?>
		<?php echo $form->hiddenField($model,'partner_id',array('value' => $_GET['id'])); */ ?>

    <br><br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
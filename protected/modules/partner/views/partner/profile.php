<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <? $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>
    <br>
    <? echo $form->errorSummary($model); ?>
    <br>
    <? echo $form->labelEx($model->user, 'name'); ?>
    <? echo $form->textField($model->user, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model->user, 'name'); ?>

    <? echo $form->labelEx($model->user, 'email'); ?>
    <? echo $form->textField($model->user, 'email', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model->user, 'email'); ?>

    <? echo $form->labelEx($model, 'email_order'); ?>
    <? echo $form->textField($model, 'email_order', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model, 'email_order'); ?>

    <? echo $form->labelEx($model, 'phone_sms', array('label' => 'Номер для уведомления по смс. Шаблон 79884004466')); ?>
    <? echo $form->textField($model, 'phone_sms', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model, 'phone_sms'); ?>


    <? if (User::getPartnerListWith2phone($model->id)) { ?>
        <? echo $form->labelEx($model, 'phone_sms2', array('label' => 'Дополнительный номер для уведомления по смс. Шаблон 79884004466')); ?>
        <? echo $form->textField($model, 'phone_sms2', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <? echo $form->error($model, 'phone_sms2'); ?>
    <? } ?>

    <? echo $form->labelEx($model->user, 'phone'); ?>
    <? echo $form->textField($model->user, 'phone', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model->user, 'phone'); ?>

    <? echo $form->labelEx($model->user, 'pass'); ?>
    <? echo $form->textField($model->user, 'pass', array('class' => 'txt', 'size' => 60, 'maxlength' => 255, 'value' => '')); ?>
    <? echo $form->error($model->user, 'pass'); ?>

    <br><br>
    Дата регистрации :<br>
    <? echo $model->user->reg_date; ?>
    <br><br>
    Дата последнего визита :<br>
    <? echo $model->user->last_visit; ?>
    <br><br>
    <? echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <? $this->endWidget(); ?>
</div>
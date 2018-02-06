<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well" style="padding-left: 10px;">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>
    <br><br>
    <?php echo CHtml::link(UserModule::t('Изменить пароль'), array('changepassword'), array('class' => 'btn btn-primary')); ?>
    <br><br>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>
    <br>
    <?php echo $form->errorSummary($model); ?>
    <br>

    <?php echo $form->labelEx($model->user, 'name'); ?>
    <?php echo $form->textField($model->user, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model->user, 'name'); ?>

    <?php echo $form->labelEx($model->user, 'email'); ?>
    <?php echo $form->textField($model->user, 'email', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model->user, 'email'); ?>

    <?php echo $form->labelEx($model, 'email_order'); ?>
    <?php echo $form->textField($model, 'email_order', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'email_order'); ?>

    <?php echo $form->labelEx($model, 'email_order2'); ?>
    <?php echo $form->textField($model, 'email_order2', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'email_order2'); ?>

    <?php echo $form->labelEx($model, 'phone_sms', array('label' => 'Номер для уведомления по смс. Шаблон 79884004466')); ?>
    <?php echo $form->textField($model, 'phone_sms', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'phone_sms'); ?>
    <br><br>
    <span>Отправлять смс-уведомления о новых заказах</span>
    <? echo $form->checkBox($model, 'sms_enabled'); ?>
    <? echo $form->error($model, 'sms_enabled'); ?>
    <br><br>
    <span>Отправлять емайл уведомления о заказах на почту</span>
    <? echo $form->checkBox($model, 'send_email'); ?>
    <? echo $form->error($model, 'send_email'); ?>
    <br><br>
    <? echo $form->labelEx($model, 'allow_bonus'); ?>
    <? echo $form->checkBox($model, 'allow_bonus'); ?>
    <? echo $form->error($model, 'allow_bonus'); ?>
    <br><br>
    <? if (User::getPartnerListWith2phone($model->id)) { ?>
        <? echo $form->labelEx($model, 'phone_sms2', array('label' => 'Дополнительный номер для уведомления по смс. Шаблон 79884004466')); ?>
        <? echo $form->textField($model, 'phone_sms2', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <? echo $form->error($model, 'phone_sms2'); ?>
    <? } ?>
    <?php echo $form->labelEx($model->user, 'phone'); ?>
    <?php echo $form->textField($model->user, 'phone', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model->user, 'phone'); ?>
    <br><br>
    Дата регистрации :<br>
    <?php echo $model->user->reg_date; ?>
    <br><br>
    Дата последнего визита :<br>
    <?php echo $model->user->last_visit; ?>
    <br><br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>

    <?php $this->endWidget(); ?>
</div>
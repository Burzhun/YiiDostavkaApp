<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'links' => array('Партнеры' => array('/admin/partner'), 'Добавление партнера'),
));
?>
<div class="well">
    <h1>Добавление партнера</h1>
</div>

<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($userModel); ?>
    <?php echo $form->errorSummary($partnerModel); ?>

    <h2>Профиль</h2>
    <br>
    <?php echo $form->labelEx($userModel, 'name'); ?>
    <?php echo $form->textField($userModel, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($userModel, 'name'); ?>
    <br>
    <?php echo $form->labelEx($partnerModel, 'email_order'); ?>
    <?php echo $form->textField($partnerModel, 'email_order', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($partnerModel, 'email_order'); ?>

    <br>
    <?php echo $form->labelEx($userModel, 'email'); ?>
    <?php echo $form->textField($userModel, 'email', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($userModel, 'email'); ?>

    <br>
    <?php echo $form->labelEx($partnerModel, 'phone_sms'); ?>
    <?php echo $form->textField($partnerModel, 'phone_sms', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($partnerModel, 'phone_sms'); ?>

    <br>
    <?php echo $form->labelEx($userModel, 'phone'); ?>
    <?php echo $form->textField($userModel, 'phone', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($userModel, 'phone'); ?>

    <br>
    <?php echo $form->labelEx($userModel, 'pass'); ?>
    <?php echo $form->textField($userModel, 'pass', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($userModel, 'pass'); ?>

    <br>
    <?php echo $form->hiddenField($userModel, 'reg_date', array('value' => strftime('%Y-%m-%d', time()))); ?>

    <br>
    <?php echo $form->hiddenField($userModel, 'last_visit', array('value' => strftime('%Y-%m-%d %H:%M:%S', time()))); ?>

    <br><br>

    <h2>Информация партнера</h2>
    <br>
    <?php echo $form->labelEx($partnerModel, 'img'); ?>
    <?php echo $form->fileField($partnerModel, 'img'); ?>
    <?php echo $form->error($partnerModel, 'img'); ?>

    <br>
    <?php echo $form->labelEx($partnerModel, 'name'); ?>
    <?php echo $form->textField($partnerModel, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($partnerModel, 'name'); ?>

    <br>
    <?php echo $form->labelEx($partnerModel, 'city_id'); ?>
    <?php echo $form->dropDownList($partnerModel, 'city_id', CHtml::listData(City::model()->findAll(), 'id', 'name')); ?>
    <?php echo $form->error($partnerModel, 'city_id'); ?>

    <br>
    <?php echo $form->labelEx($partnerModel, 'min_sum'); ?>
    <?php echo $form->textField($partnerModel, 'min_sum', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($partnerModel, 'min_sum'); ?>

    <br>
    <?php echo $form->labelEx($partnerModel, 'delivery_cost'); ?>
    <?php echo $form->textField($partnerModel, 'delivery_cost', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($partnerModel, 'delivery_cost'); ?>

    <br>
    <?php echo $form->labelEx($partnerModel, 'delivery_duration'); ?>
    <?php echo $form->dropDownList($partnerModel, 'delivery_duration', ZHtml::enumItem($partnerModel, 'delivery_duration', array())); ?>
    <?php echo $form->error($partnerModel, 'delivery_duration'); ?>

    <br>
    Рабочее время
    <?php echo $form->textField($partnerModel, 'work_begin_time', array('class' => 'txt', 'size' => 15, 'maxlength' => 255)); ?>
    -
    <?php echo $form->textField($partnerModel, 'work_end_time', array('class' => 'txt', 'size' => 15, 'maxlength' => 255)); ?>
    <?php echo $form->error($partnerModel, 'work_begin_time'); ?>
    <?php echo $form->error($partnerModel, 'work_end_time'); ?>

    <br>
    <style>
        .inline li {
            list-style: none;
            display: inline;
            float: left;
            padding-right: 15px;
        }
    </style>
    Рабочие дни :<br>
    <ul class="inline">
        <li>
            <?php echo $form->labelEx($partnerModel, 'day1'); ?>
            <?php echo $form->checkBox($partnerModel, 'day1'); ?>
            <?php echo $form->error($partnerModel, 'day1'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($partnerModel, 'day2'); ?>
            <?php echo $form->checkBox($partnerModel, 'day2'); ?>
            <?php echo $form->error($partnerModel, 'day2'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($partnerModel, 'day3'); ?>
            <?php echo $form->checkBox($partnerModel, 'day3'); ?>
            <?php echo $form->error($partnerModel, 'day3'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($partnerModel, 'day4'); ?>
            <?php echo $form->checkBox($partnerModel, 'day4'); ?>
            <?php echo $form->error($partnerModel, 'day4'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($partnerModel, 'day5'); ?>
            <?php echo $form->checkBox($partnerModel, 'day5'); ?>
            <?php echo $form->error($partnerModel, 'day5'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($partnerModel, 'day6'); ?>
            <?php echo $form->checkBox($partnerModel, 'day6'); ?>
            <?php echo $form->error($partnerModel, 'day6'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($partnerModel, 'day7'); ?>
            <?php echo $form->checkBox($partnerModel, 'day7'); ?>
            <?php echo $form->error($partnerModel, 'day7'); ?>
        </li>
    </ul>

    <br><br><br>
    <?php echo $form->labelEx($partnerModel, 'text'); ?>
    <?php echo $form->textArea($partnerModel, 'text'); ?>
    <?php echo $form->error($partnerModel, 'text'); ?>

    <br>
    <?php echo CHtml::submitButton('Добавить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
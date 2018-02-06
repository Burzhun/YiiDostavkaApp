<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>


<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data')
)); ?>

<p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

<?php echo $form->errorSummary($model); ?>

<?php if ($model->img != "") { ?>
    <img src="/upload/partner/<?php echo $model->img ?>"
         style="max-width:200px;max-height:200px">
<?php } ?>
<div class="row">
    <?php echo $form->labelEx($model, 'img'); ?>
    <?php echo $form->fileField($model, 'img'); ?>
    <?php echo $form->error($model, 'img'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'name'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'city_id'); ?>
    <?php echo $form->dropDownList($model, 'city_id', CHtml::listData(City::model()->findAll(), 'id', 'name')); ?>
    <?php echo $form->error($model, 'city_id'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'min_sum'); ?>
    <?php echo $form->textField($model, 'min_sum', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'min_sum'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'delivery_cost'); ?>
    <?php echo $form->textField($model, 'delivery_cost', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'delivery_cost'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'delivery_duration'); ?>
    <?php echo $form->dropDownList($model, 'delivery_duration', ZHtml::enumItem($model, 'delivery_duration', array())); ?>

    <?php echo $form->error($model, 'delivery_duration'); ?>
</div>

<div class="row">
    Дата регистрации :<br>
    <?php echo $model->user->reg_date; ?>
</div>

<div class="row">
    Рабочее время:<br>
    <?php echo $form->textField($model, 'work_begin_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10)); ?>
    -
    <?php echo $form->textField($model, 'work_end_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10)); ?>
    <?php echo $form->error($model, 'work_begin_time'); ?>
    <?php echo $form->error($model, 'work_end_time'); ?>
</div>

<div class="row">
    Рабочие дни :<br>
    <ul>
        <li>
            <?php echo $form->labelEx($model, 'day1'); ?>
            <?php echo $form->checkBox($model, 'day1'); ?>
            <?php echo $form->error($model, 'day1'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($model, 'day2'); ?>
            <?php echo $form->checkBox($model, 'day2'); ?>
            <?php echo $form->error($model, 'day2'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($model, 'day3'); ?>
            <?php echo $form->checkBox($model, 'day3'); ?>
            <?php echo $form->error($model, 'day3'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($model, 'day4'); ?>
            <?php echo $form->checkBox($model, 'day4'); ?>
            <?php echo $form->error($model, 'day4'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($model, 'day5'); ?>
            <?php echo $form->checkBox($model, 'day5'); ?>
            <?php echo $form->error($model, 'day5'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($model, 'day6'); ?>
            <?php echo $form->checkBox($model, 'day6'); ?>
            <?php echo $form->error($model, 'day6'); ?>
        </li>
        <li>
            <?php echo $form->labelEx($model, 'day7'); ?>
            <?php echo $form->checkBox($model, 'day7'); ?>
            <?php echo $form->error($model, 'day7'); ?>
        </li>
    </ul>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'text'); ?>
    <?php echo $form->textArea($model, 'text', array('maxlength' => 300)); ?>
    <?php echo $form->error($model, 'text'); ?>
</div>

<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить'); ?>
</div>

<?php $this->endWidget(); ?>
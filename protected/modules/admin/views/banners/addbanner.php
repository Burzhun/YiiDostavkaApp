<? Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerCssFile('/css/datepicker.css'); ?>
<script type="text/javascript">
    jQuery(function ($) {
        $.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
                'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
            dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
            dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
            dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            weekHeader: 'Нед',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ru']);
    });
</script>
<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'banner-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>



    <br><br>
    <? if ($model->image) { ?>
        <img style="width:100px;" src="/themes/mobile/img/banners/<?= $model->image ?>">
    <? } ?>
    <?php echo $form->labelEx($model, 'image'); ?>
    <?php echo $form->fileField($model, 'image'); ?>
    <?php echo $form->error($model, 'image'); ?>
    <br><br>

    <?/*<select name="Banner[domain_id]" id="sel">
        <?
        $domains = Domain::model()->findAll() ?>
        <option value="0">Выберите домен</option>
        <? foreach ($domains as $domain) { ?>
            <? if ($model->domain_id == $domain->id) { ?>
                <option selected value="<?= $domain->id; ?>"><?= $domain->name; ?></option>
            <? } else { ?>
                <option value="<?= $domain->id; ?>"><?= $domain->name; ?></option>
            <? } ?>
        <? } ?>
    </select>
    <br><br>*/?>
    <select name="Banner[partner_id]" id="sel">
        <?
        $partners = Partner::model()->findAll() ?>
        <option value="0">Выберите партнера</option>
        <? foreach ($partners as $partner) { ?>
            <? if ($model->partner_id == $partner->id) { ?>
                <option selected value="<?= $partner->id; ?>"><?= $partner->name; ?></option>
            <? } else { ?>
                <option value="<?= $partner->id; ?>"><?= $partner->name; ?></option>
            <? } ?>
        <? } ?>
    </select>

    <?php echo $form->labelEx($model, 'city_id'); ?>
    <?php echo $form->dropDownList($model, 'city_id', CHtml::ListData(City::model()->findAll(), 'id', 'name')); ?>
    <?php echo $form->error($model, 'city_id'); ?>

    <?php echo $form->labelEx($model, 'url'); ?>
    <?php echo $form->textField($model, 'url', array('class' => 'txt', 'size' => 100, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'url'); ?>


    <br><br>

    <?php echo $form->labelEx($model, 'text'); ?>
    <?php echo $form->textField($model, 'text', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'text'); ?>


    <br><br>
    <?php echo $form->labelEx($model, 'date_begin'); ?>
    <?php echo $form->textField($model, 'date_begin', array('class' => 'date', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'date_begin'); ?>


    <br><br>
    <?php echo $form->labelEx($model, 'date_stop'); ?>
    <?php echo $form->textField($model, 'date_stop', array('class' => 'date', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'date_stop'); ?>


    <br><br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
<style>
    .well {
        padding-left: 20px;
    }

    .well input {
        width: 400px;
    }
</style>
<script>
    $(document).ready(function () {
        $(".date").datepicker();
    });

</script>
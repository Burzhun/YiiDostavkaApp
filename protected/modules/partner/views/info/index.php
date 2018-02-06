<?php /**
 * @var CActiveForm $form
 * @var Partner $model
 */ ?>
<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <div style="min-width:800px;">
        <div style="float:left;">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'user-form',
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data')
            )); ?>
            <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>
            <br>
            <?php echo $form->errorSummary($model); ?>
            <b>Название - <?= $model->name; ?></b>
            <br>
            <br>
            <?php echo $form->labelEx($model, 'address'); ?>
            <?php echo $form->textField($model, 'address', array('size' => 60, 'type' => 'number', 'min' => 'undefined', 'max' => 'undefined')); ?>
            <?php echo $form->error($model, 'address'); ?>
            <br>
            <?php echo $form->labelEx($model, 'min_sum'); ?>
            <?php echo $form->textField($model, 'min_sum', array('size' => 60, 'type' => 'number', 'min' => 'undefined', 'max' => 'undefined')); ?>
            <?php echo $form->error($model, 'min_sum'); ?>
            <br>
            <?php echo $form->labelEx($model, 'delivery_cost'); ?>
            <?php echo $form->textField($model, 'delivery_cost', array('class' => 'txt', 'size' => 60, 'type' => 'number', 'min' => 'undefined', 'max' => 'undefined')); ?>
            <?php echo $form->error($model, 'delivery_cost'); ?>
            <br>
            <?php echo $form->labelEx($model, 'delivery_duration'); ?>
            <?php echo $form->dropDownList($model, 'delivery_duration', ZHtml::enumItem($model, 'delivery_duration', array())); ?>
            <?php echo $form->error($model, 'delivery_duration'); ?>
            <br><br>

            <span>Акция</span>
            <? echo $form->checkBox($model, 'action', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <? echo $form->error($model, 'action'); ?>
            <br><br>

            Дата регистрации :<br>
            <?php echo $model->user->reg_date; ?>
            <br><br>
            Рабочее время:<br>
            <?php echo $form->textField($model, 'work_begin_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10, 'value' => substr($model->work_begin_time, 0, 5))); ?>
            -
            <?php echo $form->textField($model, 'work_end_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10, 'value' => substr($model->work_end_time, 0, 5))); ?>
            <?php echo $form->error($model, 'work_begin_time'); ?>
            <?php echo $form->error($model, 'work_end_time'); ?>
            <br><br>
            <style>
                .inline li {
                    padding-right: 5px;
                    list-style: none;
                    display: inline;
                    float: left;
                }
            </style>
            Рабочие дни :<br><br>
            <ul class="inline">
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
            <br><br><br><br>
            Специализация:
            <br><br>
            <?php /** @var Direction[] $direction */
            $direction = Direction::model()->findAll(); ?>
            <?php foreach ($direction as $d) { ?>
                <div style="float:left;padding-right:55px;">
                    <b><?php echo $d->name; ?></b>
                    <br><br>
                    <?php foreach (Specialization::model()->findAll(array('condition' => 'direction_id=' . $d->id." and city_id=".$model->city_id, 'order' => 'pos')) as $s) { ?>
                        <?php echo $form->checkbox($s, 'name', array('class' => 'cbl' . $d->name, 'name' => 'Spec[' . $s->id . ']', 'checked' => in_array($s->id, $specs) ? 'checked' : '')); ?> <?php echo $s->name; ?>
                        <br>
                    <?php } ?>
                </div>
            <?php } ?>
            <div style="clear:both;float:none;"></div>
            <br><br><br>

            <div class='switch4'>
                <img src="/images/bg_switchf.png" alt="">
            </div>
            <?php echo $form->checkBox($model, 'self_status'); ?>&nbsp
            <?php echo $form->labelEx($model, 'self_status', array('label' => 'Скрыть/Открыть заведение', 'style' => 'display:inline')); ?>
            <?php echo $form->error($model, 'self_status'); ?>
            <br><br>

            <div style="clear:both;float:none;"></div>
            <br><br><br>
            <?php echo $form->labelEx($model, 'text'); ?>
            <?php echo $form->textArea($model, 'text', array('maxlength' => 300, 'style' => 'width:400px;height:150px;')); ?>
            <?php echo $form->error($model, 'text'); ?>
            <br><br>
            <?php echo CHtml::submitButton('Сохранить изменения', array('class' => "btn btn-primary")); ?>

            <?php $this->endWidget(); ?>
        </div>
        <div
            style="float:right;text-align:right;max-width:215px;background-color:#46C7A3;border-radius:10px;padding:15px;color:white">
            <h2>Менеджер</h2>

            <div><img src="/images/manager.jpg" style="max-width:130px;"></div>
            <br>Наш менеджер всегда готов ответить на любые ваши вопросы<br><br>
            <b>Email: info@dostavka05.ru</b><br><br>
            <b>Тел.: 8 (928) 218 40 30</b>
        </div>
    </div>
</div>

<script language="JavaScript">
    jQuery(document).ready(function () {
        if ($('#Partner_self_status').is(':checked') == false) {
            $('.switch4').addClass("switch-class");
            jQuery(".switch4").toggle(
                function () {
                    $('#Partner_self_status').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                },
                function () {
                    $('#Partner_self_status').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                }
            )
        }
        else {
            $('.switch4').css("backgroundPosition", "0");
            jQuery(".switch4").toggle(
                function () {
                    $('#Partner_self_status').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                },
                function () {
                    $('#Partner_self_status').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                }
            )
        }
    });
</script>
<? $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well" style="padding-left: 10px;">
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

    <?php echo $form->labelEx($model, 'email_order2'); ?>
    <?php echo $form->textField($model, 'email_order2', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'email_order2'); ?>

    <? echo $form->labelEx($model, 'phone_sms', array('label' => 'Номер для уведомления по смс. Шаблон 79*********')); ?>
    <? echo $form->textField($model, 'phone_sms', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model, 'phone_sms'); ?>


    <? echo $form->labelEx($model, 'phone_sms2', array('label' => 'Дополнительный номер для уведомления по смс. Шаблон 79*********')); ?>
    <? echo $form->textField($model, 'phone_sms2', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model, 'phone_sms2'); ?>



    <div>
        Дополнительный номер <button type="button" class="btn-success" id="add_partner_info">Добавить</button><br><br>
        <? foreach($model->partner_info as $key=>$info){?>
            <div>
                Имя <input type="text" name="additional_info[<?=$key;?>][0]" value="<?=$info->name;?>">
                Телефон <input type="text" name="additional_info[<?=$key;?>][1]" value="<?=$info->phone;?>">
                Должность <input type="text" name="additional_info[<?=$key;?>][2]" value="<?=$info->occupation;?>">
                <button class="btn-danger" style="vertical-align: top; height: 27px; margin-left: 10px;">Удалить</button>
            </div>

        <?}?>
        <script>
            $(function(){
                var index=<?=count($model->partner_info);?>;
               $("#add_partner_info").click(function(){
                   $(this).parent().append("<div>Имя <input type='text' name='additional_info["+index+"][0]' value=''>"+
                   "Телефон <input type='text' name='additional_info["+index+"][1]' value=''>"+
                   "Должность <input type='text' name='additional_info["+index+"][2]' value=''></div>");
                   index++;
                           return false;
               });
                $('body').on('click','.btn-danger',function(){
                    $(this).parent().remove();
                });
            });
        </script>
    </div><br>
    <? echo $form->labelEx($model->user, 'phone'); ?>
    <? echo $form->textField($model->user, 'phone', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <? echo $form->error($model->user, 'phone'); ?>

    <? echo $form->labelEx($model->user, 'pass'); ?>
    <? echo $form->textField($model->user, 'pass', array('class' => 'txt', 'size' => 60, 'maxlength' => 255, 'value' => '')); ?>
    <? echo $form->error($model->user, 'pass'); ?>
    <br><br>
    <span>Отправлять смс-уведомления о новых заказах</span>
    <? echo $form->checkBox($model, 'sms_enabled'); ?>
    <? echo $form->error($model, 'sms_enabled'); ?>
    <br><br>
    <span>Отправлять емайл уведомления о заказах на почту</span>
    <? echo $form->checkBox($model, 'send_email'); ?>
    <? echo $form->error($model, 'send_email'); ?>
    <br><br>
    <span>Позволить покупать за баллы</span>
    <? echo $form->checkBox($model, 'allow_bonus'); ?>
    <? echo $form->error($model, 'allow_bonus'); ?>
    <br><br>
    <?php
    // @TODO Перед тем как раскомментировать чекбокс, разрешающий покупать через Яндекс.Кассу, необходимо протестировать (сделать заказ, изменить статусы, отправка SMS и т.д.) модель Temp_Order, а лучше вообще от нее избавиться и оставить одну модель Order
    /*
    <span>Разрешить покупать через яндекс кассу</span>
    <? echo $form->checkBox($model, 'use_kassa'); ?>
    <? echo $form->error($model, 'use_kassa'); ?>
    <br><br>
    */?>
    <? echo $form->labelEx($model, 'sms_provider'); ?>
    <? echo $form->dropDownList($model, 'sms_provider', array('1' => 'sms.ru', '2' => 'smsaero.ru', '3' => 'unisender.com'), array('options' => array($model->sms_provider => array('selected' => true)))); ?>

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
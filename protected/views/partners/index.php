<?php
/**
 * @var PartnerRegistration $partnerreg
 * @var CActiveForm $form
 */ ?>
<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <p class="crumbs"><a href="/">главная</a> / Регистрация партнера</p>

        <div class="page_basket">
            <div class="basket_home">
                <h1>Регистрация партнера</h1>

                <div class="well">
                    <?php if (Yii::app()->user->hasFlash('loginMessage')): ?>
                        <div class="success">
                            <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="order_left">
                        <?php $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'partnerregistration-form',
                            //'enableAjaxValidation'=>true,
                            //'enableClientValidation'=>false,
                            'htmlOptions' => array(
                                'enctype' => 'multipart/form-data')
                        )); ?>
                        <br>
                        <table>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'city_id'); ?></b></td>
                                <td><?php echo $form->dropDownList($partnerreg, 'city_id', CHtml::listData(City::getCityList($this->domain->id), 'id', 'name'), array('style' => 'width:273px')); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'partnername'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'partnername'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'partnername'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'username'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'username'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'username'); ?></td>
                            </tr>

                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'email'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'email'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'email'); ?></td>
                            </tr>

                            <tr>
                                <td><?php echo $form->error($partnerreg, 'city_id'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'address'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'address', array('placeholder' => 'Пример: пр.Ленина 1')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'address'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'smsphone'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'smsphone', array('placeholder' => 'Пример: 79601234567')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'smsphone'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'contactphone'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'contactphone', array('placeholder' => 'Пример: 79601234567')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'contactphone'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'min_sum'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'min_sum'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'min_sum'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'delivery_cost'); ?></b></td>
                                <td><?php echo $form->textField($partnerreg, 'delivery_cost'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'delivery_cost'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'delivery_duration'); ?></b></td>
                                <td><?php echo $form->dropDownList($partnerreg, 'delivery_duration', Partner::getDeliveryDurationList(), array('style' => 'width:273px')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'delivery_duration'); ?></td>
                            </tr>
                            <tr>
                                <td><b>Рабочие дни :</b></td>
                                <style>
                                    .inline li {
                                        padding-right: 5px;
                                        list-style: none;
                                        display: inline;
                                        float: left;
                                    }

                                    .inline li input {
                                        width: 15px;
                                        height: 15px;
                                    }
                                </style>
                                <td>
                                    <ul class="inline">
                                        <li>
                                            <?php echo $form->labelEx($partnerreg, 'day1'); ?>
                                            <?php echo $form->checkBox($partnerreg, 'day1', array('checked' => 'checked', "style" => "width:13px;margin-left:0px")); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partnerreg, 'day2'); ?>
                                            <?php echo $form->checkBox($partnerreg, 'day2', array('checked' => 'checked', "style" => "width:13px;margin-left:0px")); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partnerreg, 'day3'); ?>
                                            <?php echo $form->checkBox($partnerreg, 'day3', array('checked' => 'checked', "style" => "width:13px;margin-left:0px")); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partnerreg, 'day4'); ?>
                                            <?php echo $form->checkBox($partnerreg, 'day4', array('checked' => 'checked', "style" => "width:13px;margin-left:0px")); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partnerreg, 'day5'); ?>
                                            <?php echo $form->checkBox($partnerreg, 'day5', array('checked' => 'checked', "style" => "width:13px;margin-left:0px")); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partnerreg, 'day6'); ?>
                                            <?php echo $form->checkBox($partnerreg, 'day6', array('checked' => 'checked', "style" => "width:13px;margin-left:0px")); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partnerreg, 'day7'); ?>
                                            <?php echo $form->checkBox($partnerreg, 'day7', array('checked' => 'checked', "style" => "width:13px;margin-left:0px")); ?>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Рабочее время:</b></td>
                                <td><?php echo $form->textField($partnerreg, 'work_begin_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10, 'placeholder' => 'Начало - чч:мм', 'value' => isset($partnerreg->work_begin_time)? $partnerreg->work_begin_time:'09:00', 'style' => 'width:100px;float:left;margin-right:5px')); ?>
                                    <?php echo $form->textField($partnerreg, 'work_end_time', array('class' => 'txt', 'size' => 2, 'maxlength' => 10, 'placeholder' => 'Конец - чч:мм', 'value' =>isset($partnerreg->work_end_time)? $partnerreg->work_end_time: '18:00', 'style' => 'width:100px;float:left')); ?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'work_begin_time'); ?></td>
                                <td><?php echo $form->error($partnerreg, 'work_end_time'); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Специализация:</b>
                                </td>
                            </tr>
                            <tr>
                                <?php $direction = Direction::model()->findAll(); ?>
                                <?php foreach ($direction as $d) { ?>
                                    <td>
                                        <div>
                                            <b><?php echo $d->name; ?></b>
                                            <br><br>
                                            <?php foreach (Specialization::model()->findAll(array('condition' => 'direction_id=' . $d->id." and city_id=".City::getUserChooseCity(), 'order' => 'pos')) as $s) { ?>
                                                <div style="margin:-2px 0;">
                                                    <span style=""><?php echo $s->name; ?></span>
                                                    <?php
                                                    $checked = false;
                                                    if (isset($_POST['Spec'])) {
                                                        if ($_POST['Spec'][$s->id] == 1) {
                                                            $checked = true;
                                                        }
                                                    }
                                                    ?>
                                                    <?php echo $form->checkbox($s, 'name', array('checked' => $checked, 'class' => 'cbl' . $d->name, 'name' => 'Spec[' . $s->id . ']', 'style' => 'height:13px;width:25px')); ?>
                                                </div><br>
                                            <?php } ?>
                                            <br>
                                        </div>
                                    </td>
                                <?php } ?>

                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'text'); ?></b></td>
                                <td><?php echo $form->textArea($partnerreg, 'text', array('style' => 'padding:5px;border-color:rgba(255, 0, 0, 0)')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'text'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'img'); ?></b></td>
                                <td><?php echo $form->fileField($partnerreg, 'img', array('style' => 'line-height:0px;')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'img'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'pass'); ?></b></td>
                                <td><?php echo $form->passwordField($partnerreg, 'pass'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'pass'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partnerreg, 'verifyPassword'); ?></b></td>
                                <td><?php echo $form->passwordField($partnerreg, 'verifyPassword'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partnerreg, 'verifyPassword'); ?></td>
                            </tr>
                            <tr>
                                <td style="width:275px;">
                                    <div style="width:275px"></div>
                                </td>
                                <td><?php echo CHtml::submitButton('Вперед >>', array("style" => "height:26px;width:273px;cursor:pointer")); ?></td>
                            </tr>
                        </table>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#PartnerRegistration_city_id").change(function(){
        var city_id=$(this).val();
        var city_id2="<?=City::getUserChooseCity();?>";
        if(city_id!=city_id2){
            if(city_id==1) window.location.href='http://www.dostavka05.ru/partners';
            if(city_id==2) window.location.href='http://kaspiysk.dostavka05.ru/partners';
            if(city_id==3) window.location.href='http://www.dostavka.az/partners';
            if(city_id==4) window.location.href='http://derbent.dostavka05.ru/partners';
            if(city_id==5) window.location.href='http://edostav.ru/partners';
            if(city_id==6) window.location.href='http://vladikavkaz.edostav.ru/partners';
        }
    });
</script>
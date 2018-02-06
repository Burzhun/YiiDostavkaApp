<div class="page">
    <div class="blok">
        <p class="crumbs"><a href="">главная</a> / <a class="crumbs_a" href="">Регистрация партнера</a></p>

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
                            'id' => 'user-form',
                            'enableAjaxValidation' => false,
                            'enableClientValidation' => false,
                            'htmlOptions' => array(
                                'enctype' => 'multipart/form-data')
                        )); ?>
                        <br>

                        <table>
                            <tr>
                                <td><b><?php echo $form->labelEx($partner, 'name'); ?></b></td>
                                <td><?php echo $form->textField($partner, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'name'); ?></td>
                            </tr>
                            <!-- tr>
									<td>
										Специализация:
										<br><br>
										<?php $direction = Direction::model()->findAll(); ?>
										<?php foreach ($direction as $d) { ?>
											<div style="float:left;padding-right:55px;">
												<b><?php echo $d->name; ?></b>
												<br><br>
												<?php foreach ($d->specialization as $s) { ?>
													<?php echo $form->checkbox($s, 'name', array('class' => 'cbl' . $d->name, 'name' => 'Spec[' . $s->id . ']', '')); ?> <label for="<?php echo "Spec_" . $s->id ?>"><?php echo $s->name; ?></label><br>
												<?php } ?>
											</div>
										<?php } ?>
									</td>
								</tr-->
                            <tr>
                                <td><b><?php echo $form->labelEx($partner, 'city_id'); ?></b></td>
                                <td><?php echo $form->dropDownList($partner, 'city_id', CHtml::listData(City::model()->findAll(), 'id', 'name')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'city_id'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partner, 'min_sum'); ?></b></td>
                                <td><?php echo $form->textField($partner, 'min_sum', array('size' => 60, 'type' => 'number', 'min' => 'undefined', 'max' => 'undefined')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'min_sum'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partner, 'delivery_cost'); ?></b></td>
                                <td><?php echo $form->textField($partner, 'delivery_cost', array('class' => 'txt', 'size' => 60, 'type' => 'number', 'min' => 'undefined', 'max' => 'undefined')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'delivery_cost'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partner, 'delivery_duration'); ?></b></td>
                                <td><?php echo $form->dropDownList($partner, 'delivery_duration', ZHtml::enumItem($partner, 'delivery_duration', array())); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'delivery_duration'); ?></td>
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
                                            <?php echo $form->labelEx($partner, 'day1'); ?>
                                            <?php echo $form->checkBox($partner, 'day1', array('checked' => 'checked')); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partner, 'day2'); ?>
                                            <?php echo $form->checkBox($partner, 'day2', array('checked' => 'checked')); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partner, 'day3'); ?>
                                            <?php echo $form->checkBox($partner, 'day3', array('checked' => 'checked')); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partner, 'day4'); ?>
                                            <?php echo $form->checkBox($partner, 'day4', array('checked' => 'checked')); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partner, 'day5'); ?>
                                            <?php echo $form->checkBox($partner, 'day5', array('checked' => 'checked')); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partner, 'day6'); ?>
                                            <?php echo $form->checkBox($partner, 'day6', array('checked' => 'checked')); ?>
                                        </li>
                                        <li>
                                            <?php echo $form->labelEx($partner, 'day7'); ?>
                                            <?php echo $form->checkBox($partner, 'day7', array('checked' => 'checked')); ?>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Рабочее время:</b></td>
                                <td><?php echo $form->textField($partner, 'work_begin_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10, 'placeholder' => 'Начало - чч:мм', 'width' => '100px')); ?></td>
                                <td><?php echo $form->textField($partner, 'work_end_time', array('class' => 'txt', 'size' => 2, 'maxlength' => 10, 'placeholder' => 'Конец - чч:мм')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'work_begin_time'); ?></td>
                                <td><?php echo $form->error($partner, 'work_end_time'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partner, 'phone_sms'); ?></b></td>
                                <td><?php echo $form->textField($partner, 'phone_sms', array('class' => 'txt', 'size' => 60, 'type' => 'number')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'phone_sms'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($user, 'phone'); ?></b></td>
                                <td><?php echo $form->textField($user, 'phone', array('class' => 'txt', 'size' => 60, 'type' => 'number')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($user, 'phone'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($user, 'name'); ?></b></td>
                                <td><?php echo $form->textField($user, 'name', array('class' => 'txt', 'size' => 60, 'type' => 'number')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($user, 'name'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($user, 'email'); ?></b></td>
                                <td><?php echo $form->textField($user, 'email', array('class' => 'txt', 'size' => 60, 'type' => 'number')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($user, 'email'); ?></td>
                            </tr>
                            <tr>
                                <td><b>Пароль</b></td>
                                <td><?php echo CHtml::passwordField('pass', "", array('class' => 'txt', 'size' => 60, 'type' => 'password')); ?></td>
                            </tr>
                            <tr>
                                <td><?php //echo CHtml::error('pass', ""); ?></td>
                            </tr>
                            <tr>
                                <td><b>Повторите пароль</b></td>
                                <td><?php echo CHtml::passwordField('pass2', "", array('class' => 'txt', 'size' => 60)); ?></td>
                            </tr>
                            <tr>
                                <td><?php //echo $form->error('pass2'); ?></td>
                            </tr>
                            <tr>
                                <td><b><?php echo $form->labelEx($partner, 'text'); ?></b></td>
                                <td><?php echo $form->textArea($partner, 'text', array('class' => 'txt', 'size' => 60, 'type' => 'number', 'min' => 'undefined', 'max' => 'undefined')); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'text'); ?></td>
                            </tr>
                            <tr>
                                <td><b>Изображение (Логотип)</b></td>
                                <td><?php echo $form->fileField($partner, 'img'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $form->error($partner, 'img'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo CHtml::submitButton('Зарегистрироваться', array('class' => "btn btn-primary")); ?></td>
                            </tr>
                        </table>





                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
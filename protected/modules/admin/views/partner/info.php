<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

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

            <?php echo $form->errorSummary($model); ?>


            <div style='float:left;width:300px;'>

                <strong>Лого партнера</strong><br><br>

                <?php if ($model->img != "") { ?>
                    <img src="/upload/partner/<?php echo $model->img ?>"
                         style="max-width:200px;max-height:200px">
                <?php } ?>

                <?php echo $form->labelEx($model, 'img'); ?>
                <?php echo $form->fileField($model, 'img'); ?>
                <?php echo $form->error($model, 'img'); ?>

            </div>

            <div style='float:left;width:300px;'>
                <strong>Лого на главной странице</strong><br><br>

                <?php if ($model->logo != "") { ?>
                    <img src="/upload/partner/<?php echo $model->logo ?>"
                         style="max-width:200px;max-height:200px">
                <?php } ?>
                <?php echo $form->labelEx($model, 'logo'); ?>
                <?php echo $form->fileField($model, 'logo'); ?>
                <?php echo $form->error($model, 'logo'); ?>

            </div>

            <div style='clear:both'></div>
            <br>

            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'name'); ?>

            <?php echo $form->labelEx($model, 'city_id'); ?>
            <?php echo $form->dropDownList($model, 'city_id', CHtml::listData(City::model()->findAll(), 'id', 'name')); ?>
            <?php echo $form->error($model, 'city_id'); ?>

            <?php echo $form->labelEx($model, 'address'); ?>
            <?php echo $form->textField($model, 'address', array('class' => 'txt', 'size' => 60)); ?>
            <?php echo $form->error($model, 'address'); ?>

            <?php echo $form->labelEx($model, 'position'); ?>
            <?php echo $form->textField($model, 'position', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'position'); ?>

            <?php echo $form->labelEx($model, 'min_sum'); ?>
            <?php echo $form->textField($model, 'min_sum', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'min_sum'); ?>

            <?php echo $form->labelEx($model, 'bonus_procent'); ?>
            <?php echo $form->textField($model, 'bonus_procent', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'bonus_procent'); ?>

            <?php echo $form->labelEx($model, 'free_delivery_sum'); ?>
            <?php echo $form->textField($model, 'free_delivery_sum', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'free_delivery_sum'); ?>

            <?php echo $form->labelEx($model, 'delivery_cost'); ?>
            <?php echo $form->textField($model, 'delivery_cost', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'delivery_cost'); ?>

            <?php echo $form->labelEx($model, 'delivery_duration'); ?>
            <?php echo $form->dropDownList($model, 'delivery_duration', ZHtml::enumItem($model, 'delivery_duration', array())); ?>
            <?php echo $form->error($model, 'delivery_duration'); ?>

            <br><br>
            <span>Акция</span>
            <? echo $form->checkBox($model, 'action', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <? echo $form->error($model, 'action'); ?>

            <br><br>
            Дата регистрации :
            <?php echo $model->user->reg_date; ?>
            <br><br>
            Рабочее время:<br>
            <?php echo $form->textField($model, 'work_begin_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10, 'value' => substr($model->work_begin_time, 0, 5))); ?>
            -
            <?php echo $form->textField($model, 'work_end_time', array('class' => 'txt', 'size' => 10, 'maxlength' => 10, 'value' => substr($model->work_end_time, 0, 5))); ?>
            <?php echo $form->error($model, 'work_begin_time'); ?>
            <?php echo $form->error($model, 'work_end_time'); ?>

            <br><br>
            Специализация:
            <br><br>
            <?php $direction = Direction::model()->findAll(); ?>
            <?php /*$specs = array(); $dirs = array();
						foreach($model->specialization as $s){
							$specs[] = $s->id;
							$dirs[$s->direction_id] = $s->direction_id;
						}*/ ?>
            <?php foreach ($direction as $d) { ?>
                <div style="float:left;padding-right:55px;">
                    <b><?php echo $d->name; ?></b>
                    <br><br>
                    <?php foreach (Specialization::model()->findAll(array('condition' => 'direction_id=' . $d->id." and city_id=".$model->city_id, 'order' => 'pos')) as $s) { ?>
                        <?php echo $form->checkbox($s, 'name', array('class' => 'cbl' . $s->name, 'name' => 'Spec[' . $s->id . ']', 'checked' => in_array($s->id, $specs) ? 'checked' : '')); ?> <?php echo $s->name; ?>
                        <br>
                    <?php } ?>
                </div>
            <?php } ?>
            <div style="clear:both;float:none;"></div>


            <!-- div class="row">
				<?php /*echo CHtml::checkBoxList(SpecPartner::model(),
												'spec_id',
												array('qwer'=>'1', 'adsasd'=>'0'));
												//Specialization::model()->findAll(array('condition'=>'direction_id=1')),
												?>
				<?php //echo $form->dropDownList($model,'spec', ZHtml::enumItem($model,  'delivery_duration', array())); ?>
				<?php echo $form->error($model,'delivery_duration'); */ ?>
			</div-->
            <br><br>
            <style>
                .inline li {
                    list-style: none;
                    display: inline;
                    float: left;
                }
            </style>
            Рабочие дни :<br>
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






            <?php echo $form->labelEx($model, 'procent_deductions'); ?>
            <?php echo $form->textField($model, 'procent_deductions', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($model, 'procent_deductions'); ?>
            <br><br>


            <br><br>

            <div class='switch'>
                <img src="/images/bg_switchf.png" alt="">
            </div>
            <?php echo $form->checkBox($model, 'status'); ?>&nbsp
            <?php echo $form->labelEx($model, 'status', array('label' => 'Активировать/Заблокировать Партнера', 'style' => 'display:inline')); ?>
            <?php echo $form->error($model, 'status'); ?>
            <br><br>


            <div class='switch4'>
                <img src="/images/bg_switchf.png" alt="">
            </div>
            <?php echo $form->checkBox($model, 'self_status'); ?>&nbsp
            <?php echo $form->labelEx($model, 'self_status', array('label' => 'Сам партнер Скрыл/Открыл заведение', 'style' => 'display:inline')); ?>
            <?php echo $form->error($model, 'self_status'); ?>
            <br><br>


            <div class='switch2'>
                <img src="/images/bg_switchf.png" alt="">
            </div>
            <?php echo $form->checkBox($model->user, 'status'); ?>&nbsp
            <?php echo $form->labelEx($model->user, 'status', array('label' => 'Активировать/Заблокировать учетную запись', 'style' => 'display:inline')); ?>
            <?php echo $form->error($model->user, 'status'); ?>
            <br><br>


            <div class='switch3'>
                <img src="/images/bg_switchf.png" alt="">
            </div>
            <br><br>

            <?php echo $form->checkBox($model, 'soon_opening'); ?>&nbsp
            <?php echo $form->labelEx($model, 'soon_opening', array('label' => 'Скоро открытие', 'style' => 'display:inline')); ?>
            <?php echo $form->error($model, 'soon_opening'); ?>




            <br><br><br>
            <?php echo $form->labelEx($model, 'text'); ?>
            <?php echo $form->textArea($model, 'text', array('maxlength' => 300, "rows" => "5", "cols" => "50", "style" => 'width:400px')); ?>
            <?php echo $form->error($model, 'text'); ?>

            <br><br><br>
            <?php echo $form->labelEx($model, 'seo_text'); ?>
            <?php echo $form->textArea($model, 'seo_text', array('maxlength' => 300, "rows" => "5", "cols" => "50", "style" => 'width:400px', 'class' => 'ckeditor')); ?>
            <?php echo $form->error($model, 'seo_text'); ?>

            <br><br><br>
            <?php echo $form->labelEx($model, 'seo_title'); ?>
            <?php echo $form->textArea($model, 'seo_title', array('maxlength' => 300, "rows" => "5", "cols" => "50", "style" => 'width:400px')); ?>
            <?php echo $form->error($model, 'seo_title'); ?>

            <br><br><br>
            <?php echo $form->labelEx($model, 'seo_keywords'); ?>
            <?php echo $form->textArea($model, 'seo_keywords', array('maxlength' => 300, "rows" => "5", "cols" => "50", "style" => 'width:400px')); ?>
            <?php echo $form->error($model, 'seo_keywords'); ?>

            <br><br><br>
            <?php echo $form->labelEx($model, 'seo_description'); ?>
            <?php echo $form->textArea($model, 'seo_description', array('maxlength' => 300, "rows" => "5", "cols" => "50", "style" => 'width:400px')); ?>
            <?php echo $form->error($model, 'seo_description'); ?>
            <br><br>
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>
            <br><br>
            <?php $this->endWidget(); ?>
        </div>
        <div
            style="float:right;text-align:right;max-width:215px;background-color:#46C7A3;border-radius:10px;padding:15px;color:white">
            <h2>Менеджер</h2>

            <div><img src="/images/manager.jpg" style="max-width:130px;"></div>
            <br>Наш менеджер всегда готов ответить на любые ваши вопросы<br><br>
            <b>Email: info@dostavka05.ru</b><br><br>
            <b>Тел.: 8 (928) 045-1650</b>
        </div>
    </div>
</div>


<script language="JavaScript">
    jQuery(document).ready(function () {
        if ($('#Partner_status').is(':checked') == false) {
            $('.switch').addClass("switch-class");
            jQuery(".switch").toggle(
                function () {
                    $('#Partner_status').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                },
                function () {
                    $('#Partner_status').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                }
            )
        } else {
            $('.switch').css("backgroundPosition", "0");
            jQuery(".switch").toggle(
                function () {
                    $('#Partner_status').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                },
                function () {
                    $('#Partner_status').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                }
            )
        }
    });
</script>
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
<script language="JavaScript">
    jQuery(document).ready(function () {
        if ($('#User_status').is(':checked') == false) {
            $('.switch2').addClass("switch-class");
            jQuery(".switch2").toggle(
                function () {
                    $('#User_status').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                },
                function () {
                    $('#User_status').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                }
            )
        }
        else {
            $('.switch2').css("backgroundPosition", "0");
            jQuery(".switch2").toggle(
                function () {
                    $('#User_status').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                },
                function () {
                    $('#User_status').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                }
            )
        }
    });
</script>
<script language="JavaScript">
    jQuery(document).ready(function () {
        if ($('#Partner_soon_opening').is(':checked') == false) {
            $('.switch3').addClass("switch-class");
            jQuery(".switch3").toggle(
                function () {
                    $('#Partner_soon_opening').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                },
                function () {
                    $('#Partner_soon_opening').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                }
            )
        }
        else {
            $('.switch3').css("backgroundPosition", "0");
            jQuery(".switch3").toggle(
                function () {
                    $('#Partner_soon_opening').removeAttr('checked');
                    jQuery(this).animate({backgroundPosition: '-53'}, 100);
                },
                function () {
                    $('#Partner_soon_opening').attr("checked", "disabled");
                    jQuery(this).animate({backgroundPosition: '0'}, 100);
                }
            )
        }
    });
</script>
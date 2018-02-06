<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well" style="padding-left: 10px;position: relative">
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
    <br>
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'name'); ?>

    <?php echo $form->labelEx($model, 'email'); ?>
    <?php echo $form->textField($model, 'email', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'email'); ?>

    <?php echo $form->labelEx($model, 'phone'); ?>
    <?php echo $form->textField($model, 'phone', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'phone'); ?>

    <?php echo $form->labelEx($model, 'pass'); ?>
    <?php echo $form->passwordField($model, 'pass', array('class' => 'txt', 'size' => 60, 'maxlength' => 255, 'value' => '')); ?>
    <?php echo $form->error($model, 'pass'); ?>

    <?php echo $form->labelEx($model, 'status'); ?>
    <?php echo $form->checkBox($model, 'status'); ?>
    <?php echo $form->error($model, 'status'); ?>
    <br>
    <? $sql = "select role from tbl_users where id=" . $model->id;
    $r = Yii::app()->db->createCommand($sql)->queryAll();
    $role = $r[0]['role'];
    ?>
    <? if (Yii::app()->user->name == 'admin' || Yii::app()->user->id == 989) { ?>
        <?php echo $form->labelEx($model, 'role'); ?>
        <?php echo $form->dropDownList($model, 'role', array('' => 'Пользователь','operator'=>'Оператор', 'admin' => 'Админ'), array(
            'options' => array(
                $role => array('selected' => true)
            ))); ?>
        <?php echo $form->error($model, 'role'); ?>
    <? } ?>
    <? echo $model->role; ?>
    <br><br>
    Дата регистрации :<br>
    <?php echo $model->reg_date; ?>
    <br><br>
    Дата последнего визита :<br>
    <?php echo $model->last_visit; ?>
    <br><br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
    <div style="position: absolute; left: 400px; top: 89px;">
        <div>
            <?=(int)User::getBonus($model->id);?> баллов
        </div>
        <button class="btn-success" onclick="$('.bonus_form').slideToggle();">Списать баллы</button>
        <div class="bonus_form" style="display: none"><br>
            <input type="text" style="min-width: 400px;">
            <button class="btn-primary" style="vertical-align: top; height: 28px; margin-left: 6px; padding: 0px 11px;">Ok</button>
        </div>
    </div>
</div>
<style>
    .btn-success{
        margin-top:5px;
        font-size: 16px;
    }
</style>
<script>
    $(document).ready(function(){
        $(".bonus_form button").click(function(){
            var text=$(".bonus_form input").val();
            if(text*1<0) alert('564554');
            window.location.href="/admin/user/id/<?=$model->id;?>/profile?bonus_minus="+text;
        });
    });
</script>
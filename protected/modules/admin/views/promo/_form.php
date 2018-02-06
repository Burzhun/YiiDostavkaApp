<? Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerCssFile('/css/datepicker.css'); ?>
<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
    )); ?>
    <script>
        $(function () {
            $("#Promo_from").datepicker();
            $("#Promo_until").datepicker();
        });

    </script>
    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <? //php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'name'); ?>
    <br>
    <? $partners=Partner::model()->findAll();?>

    <? $p=array();
        foreach ($partners as $partner) {
            $p[]=$partner->name;
        }
        $selected="";
        foreach($model->partners as $partner_sel){
            $selected.=$partner_sel->name.",";
        }
    ?>
    <?php echo $form->labelEx($model, 'partners'); ?>
    <? echo CHtml::textField('Promo[partners]', substr($selected, 0, strlen($selected)-1), array('id' => 'test', 'style' => 'width: 220px;'));
    $this->widget('ext.select2.ESelect2', array(
        'name' => 'Promo[partners]',
        'selector' => '#test',
        'htmlOptions' => array(
            'multiple' => 'multiple',
        ),
        'options' => array(
            'tags' => $p,
        ),
    ));
    ?>

    <?php echo $form->labelEx($model, 'kod'); ?>
    <?php echo $form->textField($model, 'kod', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'kod'); ?>

    <?php echo $form->labelEx($model, 'count'); ?>
    <?php echo $form->textField($model, 'count', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'count'); ?>
    <p>Время действия</p>
    <?php echo $form->labelEx($model, 'from'); ?>
    <?php echo $form->textField($model, 'from', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'from'); ?>

    <?php echo $form->labelEx($model, 'until'); ?>
    <?php echo $form->textField($model, 'until', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'until'); ?>

    <? /*php echo $form->labelEx($model,'logo'); ?>
	<?php echo $form->textField($model,'logo',array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'logo'); ?>


	<?php echo $form->labelEx($model,'footer_logo'); ?>
	<?php echo $form->textField($model,'footer_logo',array('size'=>60,'maxlength'=>100)); ?>
	<?php echo $form->error($model,'footer_logo');*/ ?>

    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    <?php $this->endWidget(); ?>

</div>
<style>
    #partners{
        height:300px;
    }
</style>

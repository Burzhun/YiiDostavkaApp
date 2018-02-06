<div class="h1-box">
    <div class="well">
        <h1>Бегущая строка</h1>
    </div>
</div>

<div class="well well-bottom">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>


    <?php echo $form->labelEx($model, 'text'); ?>
    <?php echo $form->textArea($model, 'text', array('rows' => 8, 'cols' => 40, 'style' => 'width:500px;')); ?>
    <?php echo $form->error($model, 'text'); ?>

    <br>
    <?php echo CHtml::submitButton('Сохранить'); ?>


    <?php $this->endWidget(); ?>

</div>
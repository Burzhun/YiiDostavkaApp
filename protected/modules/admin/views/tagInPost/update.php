<div class="breadcrumbs">
    <a href="/admin/post">Блог</a> / <a href="/admin/tagInPost">Теги</a> / <span>Редактирование</span>
</div>

<div class="h1-box">
    <div class="well">
        <h1>Редактирование</h1>
    </div>
</div>

<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('class' => 'txt')); ?>
    <?php echo $form->error($model, 'name'); ?>
    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'btn btn-primary')); ?>

    <?php $this->endWidget(); ?>
</div>
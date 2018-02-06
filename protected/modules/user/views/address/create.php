<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
        <br><br>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'form_cre')
        )); ?>

        <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>
        <br>
        <?php echo $form->errorSummary($address_model); ?>
        <br>
        <?php echo $form->labelEx($address_model, 'city_id'); ?>
        <?php echo $form->dropDownList($address_model->city = new City(), 'id',
            CHtml::listData(City::model()->findAll(), 'id', 'name')); ?>

        <?php //echo $form->textField($address_model->city,'name',array('class'=>'txt','size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($address_model, 'city_id'); ?>
        <br><br>
        <?php echo $form->labelEx($address_model, 'street'); ?>
        <?php echo $form->textField($address_model, 'street', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'street'); ?>
        <br><br>
        <?php echo $form->labelEx($address_model, 'house'); ?>
        <?php echo $form->textField($address_model, 'house', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'house'); ?>
        <br><br>
        <?php echo $form->labelEx($address_model, 'podezd'); ?>
        <?php echo $form->textField($address_model, 'podezd', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'podezd'); ?>
        <br><br>
        <?php echo $form->labelEx($address_model, 'storey'); ?>
        <?php echo $form->textField($address_model, 'storey', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'storey'); ?>
        <br><br>
        <?php echo $form->labelEx($address_model, 'number'); ?>
        <?php echo $form->textField($address_model, 'number', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'number'); ?>
        <br><br>
        <?php echo $form->hiddenField($address_model, 'user_id', array('value' => Yii::app()->user->id)); ?>
        <br>
        <?php echo CHtml::submitButton($address_model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

        <?php $this->endWidget(); ?>
    </div>
</div>
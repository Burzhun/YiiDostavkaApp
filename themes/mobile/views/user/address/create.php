<div class="mainBox shop">

    <div class="OfficeNav">
        <a href="/user/profile"> профиль </a>
        <a href="/user/orders"> заказы</a>
        <a href="/user/address" class='shopOpenNavActive'> адреса </a>

    </div>
</div>

<div class="shopOpenInfo mainBox">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
            'class' => 'form_cre')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($address_model); ?>

    <div class="shopOpenInfoBlock">
			<span>	  		
			<?php echo $form->labelEx($address_model, 'city_id'); ?>
			</span>
        <?php echo $form->dropDownList($address_model->city = new City(), 'id',
            CHtml::listData(City::model()->findAll(), 'id', 'name')); ?>

        <?php //echo $form->textField($address_model->city,'name',array('class'=>'txt','size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($address_model, 'city_id'); ?>
    </div>
    <div class="shopOpenInfoBlock">
			<span>
			<?php echo $form->labelEx($address_model, 'street'); ?>
			</span>
        <?php echo $form->textField($address_model, 'street', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'street'); ?>
    </div>
    <div class="shopOpenInfoBlock">
			<span>		
			<?php echo $form->labelEx($address_model, 'house'); ?>
			</span>
        <?php echo $form->textField($address_model, 'house', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'house'); ?>
    </div>
    <div class="shopOpenInfoBlock">
			<span> 		
			<?php echo $form->labelEx($address_model, 'storey'); ?>
			</span>
        <?php echo $form->textField($address_model, 'storey', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'storey'); ?>
    </div>
    <div class="shopOpenInfoBlock">
			<span>  		
			<?php echo $form->labelEx($address_model, 'number'); ?>
			</span>
        <?php echo $form->textField($address_model, 'number', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($address_model, 'number'); ?>
    </div>
    <div class="shopOpenInfoBlock">
        <?php echo $form->hiddenField($address_model, 'user_id', array('value' => Yii::app()->user->id)); ?>
    </div>
    <br>
    <?php echo CHtml::submitButton($address_model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>

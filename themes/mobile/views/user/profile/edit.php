<div class="mainBox shop">

    <div class="OfficeNav">
        <a href="/user/profile" class='shopOpenNavActive'> профиль </a>
        <a href="/user/orders"> заказы</a>
        <a href="/user/address"> адреса </a>
        <a href="/user/bonus"> баллы </a>

    </div>
</div>


<div class="shopOpenInfo mainBox">
    <?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
        <div class="success">
            <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
        </div>
        <br><br>
    <?php endif; ?>

    <? /*<div class="editNav">
			<a href="#" class=""><img src="/img/editIcon.png" alt=""></a>
			<a href="#" class=""><img src="/img/deleteIcon.png" alt=""></a>		  			
  		</div>*/ ?>
    <?php $form = $this->beginWidget('UActiveForm', array(
        'id' => 'profile-form',
        'enableAjaxValidation' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )); ?>

    <p class="note">
        <?php echo UserModule::t('Поля с  * обязательны для заполнения.'); ?></p>

    <br>
    <?php echo $form->errorSummary(array($model)); ?>


    <?php echo CHtml::link(UserModule::t('Изменить пароль'), array('changepassword'), array('class' => 'btn btn-primary')); ?>
    <div class="shopOpenInfoBlock">
        <?php echo $form->labelEx($model, 'name'); ?><br>
        <?php echo $form->textField($model, 'name', array('size' => 20, 'maxlength' => 20)); ?>
        <?php echo $form->error($model, 'name'); ?>

    </div>
    <div class="shopOpenInfoBlock">
        <?php echo $form->labelEx($model, 'email'); ?><br>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <center>
        <?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Зарегистрировать') : UserModule::t('Сохранить'), array('class' => 'btn btn-primary')); ?>
        <center>
            <br>
            <?php $this->endWidget(); ?>

            <? /*<div class="shopOpenInfoBlock">
	  	<span>Имя Фамилия::</span>
	  	<?php echo CHtml::encode($model->name); ?>
  	</div>

		<div class="shopOpenInfoBlock">	
	  	<span>E-mail:</span>		 
		 <?php echo CHtml::encode($model->email); ?>
	</div>
		
	 <div class="shopOpenInfoBlock">	
	   	<span>Дата регистрации:</span>		 
	 <?php echo CHtml::encode($model->reg_date); ?>  
	</div>
	
	<?/* <div class="shopOpenInfoBlock">	
	   	<span>E-mail:</span>		 
	 	 <input type="text">  
	</div>

	 	

	 	<center>
	 		<a href="/user/profile/edit" class='submit'>Редактировать данные</a>
		</center>
	  */ ?>

</div>



<? /*<div class="body-bg bg-none"></div>


<div class="page" id="page">
	<div class="blok">
		<?php $this->renderPartial('../default/header', array('model'=>$model, 'breadcrumbs'=>$breadcrumbs, 'h1'=>$h1))?>
		<br><br>
		<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
			<div class="success">
				<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
			</div>
			<br><br>
		<?php endif; ?>
		
		
		<?php echo CHtml::link(UserModule::t('Изменить пароль'),array('changepassword'), array('class'=>'btn btn-primary')); ?>
		<br><br>
		<div class="forma">
		<?php $form=$this->beginWidget('UActiveForm', array(
			'id'=>'profile-form',
			'enableAjaxValidation'=>true,
			'htmlOptions' => array('enctype'=>'multipart/form-data'),
		)); ?>
		
			<p class="note">
			<?php echo UserModule::t('Поля с  <span class="required">*</span> обязательны для заполнения.'); ?></p>
			
			<br>
		
			<?php echo $form->errorSummary(array($model)); ?>
			<br>
			<br>
			<?php echo $form->labelEx($model,'name'); ?><br>
				<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
				<?php echo $form->error($model,'name'); ?>
			<br>
			<?php echo $form->labelEx($model,'email'); ?><br>
				<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($model,'email'); ?>
			<br><br>
			<?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Зарегистрировать') : UserModule::t('Сохранить'), array('class'=>'btn btn-primary')); ?>
			<br>
		<?php $this->endWidget(); ?>
		</div><!-- form -->
	</div>
</div>*/ ?>
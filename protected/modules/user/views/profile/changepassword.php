<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <div class="form">
            <?php //$this->renderPartial('../default/header', array('model'=>$model, 'breadcrumbs'=>$breadcrumbs, 'h1'=>$h1))?>
            <br><br>
            <?php $form = $this->beginWidget('UActiveForm', array(
                'id' => 'changepassword-form',
                'enableAjaxValidation' => true,
            )); ?>
            <br>

            <p class="note"><?php echo UserModule::t('Поля с <span class="required">*</span> обязательны для заполнения.'); ?></p>
            <?php echo CHtml::errorSummary($model_pass); ?>
            <br>
            <?php echo $form->labelEx($model_pass, 'password'); ?>
            <?php echo $form->passwordField($model_pass, 'password'); ?>
            <?php echo $form->error($model_pass, 'password'); ?>
            <p class="hint">
                <?php echo UserModule::t("Пароль должен содержать более 4 символов."); ?>
            </p>
            <br>
            <br>
            <?php echo $form->labelEx($model_pass, 'verifyPassword'); ?>
            <?php echo $form->passwordField($model_pass, 'verifyPassword'); ?>
            <?php echo $form->error($model_pass, 'verifyPassword'); ?>
            <br><br>
            <?php echo CHtml::submitButton(UserModule::t("Сохранить"), array('class' => 'btn btn-primary')); ?>

            <?php $this->endWidget(); ?>
        </div>
        <!-- form -->
    </div>
</div>
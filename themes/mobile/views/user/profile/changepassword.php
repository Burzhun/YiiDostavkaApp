<div class="mainBox shop">

    <div class="OfficeNav">
        <a href="/user/profile" class='shopOpenNavActive'> профиль </a>
        <a href="/user/orders"> заказы</a>
        <a href="/user/address"> адреса </a>
        <a href="/user/bonus"> баллы </a>

    </div>
</div>

<div class="shopOpenInfo mainBox">
    <?php //$this->renderPartial('../default/header', array('model'=>$model, 'breadcrumbs'=>$breadcrumbs, 'h1'=>$h1))?>
    <?php $form = $this->beginWidget('UActiveForm', array(
        'id' => 'changepassword-form',
        'enableAjaxValidation' => true,
    )); ?>
    <br>

    <p class="note">
        <?php echo UserModule::t('Поля с <span class="required">*</span> обязательны для заполнения.'); ?></p>
    <?php echo CHtml::errorSummary($model_pass); ?>
    <br>

    <div class="shopOpenInfoBlock">
        <?php echo $form->labelEx($model_pass, 'password'); ?>
        <?php echo $form->passwordField($model_pass, 'password'); ?>
        <?php echo $form->error($model_pass, 'password'); ?>
    </div>
    <p class="hint">
        <?php echo UserModule::t("Пароль должен содержать более 4 символов."); ?>
    </p>

    <div class="shopOpenInfoBlock">
        <?php echo $form->labelEx($model_pass, 'verifyPassword'); ?>
        <?php echo $form->passwordField($model_pass, 'verifyPassword'); ?>
        <?php echo $form->error($model_pass, 'verifyPassword'); ?>
    </div>

    <?php echo CHtml::submitButton(UserModule::t("Сохранить"), array('class' => 'btn btn-primary')); ?>

    <?php $this->endWidget(); ?>
</div><!-- form -->

<div class="body-bg bg-none"></div>
<div id="page" class="page">
    <div class="blok">
        <p class="crumbs"><a href="/">главная</a> / Восcтановление пароля</p>

        <div class="page_basket">
            <h1 class="h1_pass" style="margin-left:21px;">Воcстановление пароля</h1>

            <div class="pass-content">
                <?php if (Yii::app()->user->hasFlash('recoveryMessage')): ?>
                    <div class="success" style="padding-left:22px;">
                        <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
                    </div>
                <?php else: ?>
                    <?php echo CHtml::beginForm(); ?>
                    <?php echo CHtml::errorSummary($form); ?>
                    <?php echo CHtml::activeTextField($form, 'login_or_email', array('class' => 'pass-field', "placeholder" => "e-mail")) ?>
                    <?php echo CHtml::submitButton(UserModule::t("Восстановить пароль"), array('class' => 'rec-pass-button')); ?>
                    <?php echo CHtml::endForm(); ?>
                <?php endif; ?>
                <p class="date">Введите ваш e-mail, зарегистрированный на сайте и мы вышлем вам пароль</p>
            </div>
        </div>
    </div>
</div>
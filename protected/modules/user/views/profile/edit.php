<div class="body-bg bg-none"></div>


<div class="page" id="page">
    <div class="blok">
        <?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
        <br><br>
        <?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
            <div class="success">
                <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
            </div>
            <br><br>
        <?php endif; ?>


        <?php echo CHtml::link(UserModule::t('Изменить пароль'), array('changepassword'), array('class' => 'btn btn-primary')); ?>
        <br><br>

        <div class="forma">
            <?php $form = $this->beginWidget('UActiveForm', array(
                'id' => 'profile-form',
                'enableAjaxValidation' => true,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
            )); ?>

            <p class="note">
                <?php echo UserModule::t('Поля с  <span class="required">*</span> обязательны для заполнения.'); ?></p>

            <br>

            <?php echo $form->errorSummary(array($model)); ?>
            <br>
            <br>
            <?php echo $form->labelEx($model, 'name'); ?><br>
            <?php echo $form->textField($model, 'name', array('size' => 20, 'maxlength' => 20)); ?>
            <?php echo $form->error($model, 'name'); ?>
            <br><br>
            <?php echo $form->labelEx($model, 'email'); ?><br>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 128)); ?>
            <?php echo $form->error($model, 'email'); ?>
            <br><br>
            <?php echo $form->labelEx($model, 'phone'); ?><br>
            <?php echo $form->textField($model, 'phone', array('size' => 60, 'maxlength' => 128)); ?>
            <?php echo $form->error($model, 'phone'); ?>
            <br><br>
            <tr>
                <label for="pol">Пол</label>
                <br>

                <select id="pol" name="User[pol]">
                    <option value="m" <?= $model->pol == 'm' ? 'selected' : '' ?>>Мужской</option>
                    <option value="w" <?= $model->pol == 'w' ? 'selected' : '' ?>>Женский</option>
                </select>
            </tr>
            <br><br>
            <tr>
                <label for="birthdate">Дата рождения</label>
                <br>

                <select id="birthdate" name="User[birthdate1]">
                    <option value="" selected></option>
                    <? for ($i = 1; $i <= 31; $i++) { ?>
                        <option value="<?= $i > 9 ? $i : '0' . $i; ?>"><?= $i ?></option>
                    <? } ?>
                </select>
                <select id="birthdate" name="User[birthdate2]">
                    <option value="" selected>Выберите месяц</option>
                    <option value="01">Январь</option>
                    <option value="02">Февраль</option>
                    <option value="03">Март</option>
                    <option value="04">Апрель</option>
                    <option value="05">Май</option>
                    <option value="06">Июнь</option>
                    <option value="07">Июль</option>
                    <option value="08">Август</option>
                    <option value="09">Сентябрь</option>
                    <option value="10">Октябрь</option>
                    <option value="11">Ноябрь</option>
                    <option value="12">Декабрь</option>
                </select>
                <select id="birthdate" name="User[birthdate3]">
                    <option value="" selected></option>
                    <? for ($i = 2016; $i > 1950; $i--) { ?>
                        <option value="<?= $i; ?>"><?= $i ?></option>
                    <? } ?>
                </select>
            </tr>
            <br><br>
            <?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Зарегистрировать') : UserModule::t('Сохранить'), array('class' => 'btn btn-primary')); ?>
            <br>
            <?php $this->endWidget(); ?>
            <style>
                select {
                    font-size: 18px;
                }
            </style>
        </div>
        <!-- form -->
    </div>
</div>
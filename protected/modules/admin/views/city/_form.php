<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <? //php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'name'); ?>
    <br><br>
    <select name="City[domain_id]" id="sel">
        <?
        $domains = Domain::model()->findAll() ?>
        <option value="0">Выберите домен</option>
        <? foreach ($domains as $domain) { ?>
            <? if ($model->domain_id == $domain->id) { ?>
                <option selected value="<?= $domain->id; ?>"><?= $domain->name; ?></option>
            <? } else { ?>
                <option value="<?= $domain->id; ?>"><?= $domain->name; ?></option>
            <? } ?>
        <? } ?>
    </select>

    <?php echo $form->labelEx($model, 'alias'); ?>
    <?php echo $form->textField($model, 'alias', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'alias'); ?>

    <?php echo $form->labelEx($model, 'region'); ?>
    <?php echo $form->textField($model, 'region', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'region'); ?>

    <?php echo $form->labelEx($model, 'postfix'); ?>
    <?php echo $form->textField($model, 'postfix', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'postfix'); ?>

    <?php echo $form->labelEx($model, 'time_zone'); ?>
    <?php echo $form->textField($model, 'time_zone', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'time_zone'); ?>


    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    <?php $this->endWidget(); ?>

</div>

<div style="margin: 20px;">
    <?php
    /** @var CActiveForm $form
     *  @var Seo $model
     */
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'config-form',
        'enableAjaxValidation' => false,
    )); ?>
    <?php echo $form->labelEx($model, 'url'); ?>
    <?php echo $form->textField($model, 'url', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'url'); ?>
    <br>
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('class' => 'txt txt2', 'size' => 160, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'name'); ?>
    <br>
    <?php echo $form->labelEx($model, 'value'); ?>
    <?php echo $form->textArea($model, 'value', array('class' => 'txt', 'size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'value'); ?><br>

    <select name="Seo[city_id]" id="sel">
        <?
        $cities = city::model()->findAll() ?>
        <option value="">Выберите город</option>
        <? foreach ($cities as $city) { ?>
            <? if ($model->city_id == $city->id) { ?>
                <option selected value="<?= $city->id; ?>"><?= $city->name; ?></option>
            <? } else { ?>
                <option value="<?= $city->id; ?>"><?= $city->name; ?></option>
            <? } ?>
        <? } ?>
    </select>
    <?php echo $form->error($model, 'city_id'); ?><br>
    <br><br>
    <div class="buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
<style>
    .txt{
        width: 400px;
    }
</style>
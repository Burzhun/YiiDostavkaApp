<?/** @var Pages $model 
 *    @var CActiveForm $form
 */?>
<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'pages-form',
        'enableAjaxValidation' => false,
    )); ?>

    <p class="note">Поля отмеченые звездочкой <span class="required">*</span> обязательны.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'name'); ?>


    <?php echo $form->labelEx($model, 'shorttext'); ?>
    <?php echo $form->textField($model, 'shorttext', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'shorttext'); ?>


    <?php echo $form->labelEx($model, 'text'); ?>
    <?php echo $form->textArea($model, 'text', array('rows' => 8, 'cols' => 10, 'maxlength' => 400)); ?>
    <?php echo $form->error($model, 'text'); ?>


    <?php echo $form->labelEx($model, 'uri'); ?>
    <?php echo $form->textField($model, 'uri', array('size' => 60, 'maxlength' => 100)); ?>
    <?php echo $form->error($model, 'uri'); ?>

    <?php echo $form->labelEx($model, 'domain'); ?>
    <?php echo $form->dropDownList($model, 'domain', Pages::$domains); ?>
    <?php echo $form->error($model, 'domain'); ?>

    <br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('Pages[text]');
        CKEDITOR.config.width = 800;

    </script>

    <?php $this->endWidget(); ?>

</div>

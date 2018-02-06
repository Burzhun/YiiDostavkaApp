<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => $breadcrumbs,
));
?>

<div class="h1-box">
    <div class="well">
        <h1>Редактировать тег</h1>
    </div>
</div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
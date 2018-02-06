<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Район' => array('/admin/rayon'), 'Добавление'),
));
?>

<div class="h1-box">
    <div class="well">
        <h1>Добавление района</h1>
    </div>
</div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
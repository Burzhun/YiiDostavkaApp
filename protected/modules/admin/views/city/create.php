<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Город' => array('/admin/сшен'), 'Добавление'),
));
?>

    <div class="h1-box">
        <div class="well">
            <h1>Добавление города</h1>
        </div>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
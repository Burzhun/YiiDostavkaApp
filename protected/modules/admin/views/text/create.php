<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Домен' => array('/admin/domain'), 'Добавление'),
));
?>

<div class="h1-box">
    <div class="well">
        <h1>Добавление домен</h1>
    </div>
</div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>

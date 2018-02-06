<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Район доставки' => array('/admin/partner_rayon'), 'Добавление'),
));
?>

<div class="h1-box">
    <div class="well">
        <h1>Добавление района доставки</h1>
    </div>
</div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
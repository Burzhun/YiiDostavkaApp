<? $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    //'links'=>array('Район'=>array('/partner/rayon'), 'Добавление'),
));
?>

    <div class="h1-box">
        <div class="well">
            <h1>Добавление района</h1>
        </div>
    </div>

<?php echo $this->renderPartial('_form', array('partner' => $partner_rayon)); ?>
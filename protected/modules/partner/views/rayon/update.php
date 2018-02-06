<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Районы' => array('/partner/partner_rayon'), 'Редактирование'),
));
?>
    <style>
        .well {
            margin-left: 20px;
        }
    </style>
    <div class="well">
        <h1>Редактирование района</h1>
    </div>


<?php echo $this->renderPartial('_form', array('partner' => $partner_rayon)); ?>
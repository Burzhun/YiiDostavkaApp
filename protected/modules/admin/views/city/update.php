<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Город' => array('/admin/city'), 'Редактирование'),
));
?>
<style>
    .well {
        margin-left: 20px;
    }
</style>
<div class="well">
    <h1>Редактирование города</h1>
</div>


<?php echo $this->renderPartial('_form', array('model' => $model)); ?>

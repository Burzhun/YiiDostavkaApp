<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Домены' => array('/admin/domain'), 'Редактирование'),
));
?>
<style>
    .well {
        margin-left: 20px;
    }
</style>
<div class="well">
    <h1>Редактирование домена</h1>
</div>


<?php echo $this->renderPartial('_form', array('model' => $model)); ?>

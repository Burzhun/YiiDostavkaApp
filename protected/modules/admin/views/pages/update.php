<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Страницы' => array('/admin/pages'), 'Редактирование'),
));
?>
<style>
    .well {
        margin-left: 20px;
    }
</style>
<div class="well">
    <h1>Редактирование страницы</h1>
</div>


<?php echo $this->renderPartial('_form', array('model' => $model)); ?>

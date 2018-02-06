<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => $breadcrumbs,
));
?>

<div class="h1-box">
    <div class="well">
        <h1><?php echo $h1 ?></h1>
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'items' => array(

                array('label' => 'Информация', 'url' => array('/admin/partner/id/' . $model->id . '/info'), 'active' => $this->action->id == 'profile' ? true : false),
                array('label' => 'Меню', 'url' => array('/admin/partner/id/' . $model->id . '/menu'), 'active' => $this->action->id == 'orders' ? true : false),
                array('label' => 'Заказы', 'url' => array('/admin/partner/id/' . $model->id . '/orders'), 'active' => $this->action->id == 'address' ? true : false),
                array('label' => 'Профиль', 'url' => array('/admin/partner/id/' . $model->id . '/address'), 'active' => $this->action->id == 'profile' ? true : false),
            ),
            'htmlOptions' => array('class' => "nav nav-pills"),
        ));
        ?>
        <?php print_r($this->action->id); ?>
    </div>
</div>
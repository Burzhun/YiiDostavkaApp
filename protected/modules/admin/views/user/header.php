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

                array('label' => 'Профиль', 'url' => array('/admin/user/id/' . $model->id . '/profile'), 'active' => $this->action->id == 'profile' ? true : false),
                array('label' => 'Заказы', 'url' => array('/admin/user/id/' . $model->id . '/orders'), 'active' => $this->action->id == 'orders' || $this->action->id == 'ordersView' ? true : false),
                array('label' => 'Адреса', 'url' => array('/admin/user/id/' . $model->id . '/address'), 'active' => $this->action->id == 'address' || $this->action->id == 'addressUpdate' ? true : false),
                array('label' => 'Баллы', 'url' => array('/admin/user/id/' . $model->id . '/bonus'), 'active' => $this->action->id == 'bonus' ? true : false),
            ),
            'htmlOptions' => array('class' => "nav nav-pills"),
        ));
        ?>
    </div>
</div>
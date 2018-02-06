<div class="page">
    <div class="blok">
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'homeLink' => false,
            'separator' => ' / ',
            'links' => $breadcrumbs,
        ));
        ?>
        <h1><?php echo $h1 ?></h1>
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'items' => array(

                array('label' => 'Профиль', 'url' => array('/user/profile'), 'active' => $this->id == 'profile' ? true : false),
                array('label' => 'Заказы', 'url' => array('/user/orders'), 'active' => $this->id == 'orders' ? true : false),
                array('label' => 'Адреса', 'url' => array('/user/address'), 'active' => $this->id == 'address' ? true : false),
                array('label' => 'Баллы', 'url' => array('/user/bonus'), 'active' => $this->id == 'bonus' ? true : false),

            ),
            'htmlOptions' => array('class' => "nav-pills"),
        ));
        ?>
    </div>
</div>
<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => $breadcrumbs,
));
?>
<div class="h1-box">
    <div class="well">
        <h1><?php echo $h1 ?></h1>
        <ul class="nav nav-pills">
            <li><a href="/partner/info/">Информация</a></li>
            <li><a href="/partner/menu/">Меню</a></li>
            <li><a href="/partner/orders/">Заказы</a></li>
            <li><a href="/partner/profile/">Профиль</a></li>
        </ul>
    </div>
</div>
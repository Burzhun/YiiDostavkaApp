<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'links' => $breadcrumbs,
));
?>
<h1><?php echo $h1 ?></h1>
<span><a href="/admin/user/id/<?php echo $model->id ?>/profile/">Профиль</a></span>
<span><a href="/admin/user/id/<?php echo $model->id ?>/orders/">Заказы</a></span>
<span><a href="/admin/user/id/<?php echo $model->id ?>/address/">Адреса</a></span>
<span><a href="/admin/user/id/<?php echo $model->id ?>/bonus/">Баллы</a></span>

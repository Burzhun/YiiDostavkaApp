<div class="body-bg bg-none"></div>

<div class="page" id="page">
    <div class="Userbonus"><span>У Вас</span>    <?= User::getBonus($model->id); ?> <span>баллов</span></div>
    <div class="Aboutbonus"><a href="/bonus">Что мне дают баллы?</a></div>
    <div class="blok">
        <?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
        <br><br>
        <a class="btn btn-primary" href="/user/profile/edit">Редактировать данные</a>
        <br><br>
        <table class="table_profile">
            <tr>
                <th><?php echo CHtml::encode($model->getAttributeLabel('name')); ?>
                </th>
                <td><?php echo CHtml::encode($model->name); ?>
                </td>
            </tr>
            <tr>
                <th><?php echo CHtml::encode($model->getAttributeLabel('email')); ?>
                </th>
                <td><?php echo CHtml::encode($model->email); ?>
                </td>
            </tr>
            <tr>
                <th><?php echo CHtml::encode($model->getAttributeLabel('reg_date')); ?>
                </th>
                <td><?php echo CHtml::encode($model->reg_date); ?>
                </td>
            </tr>
            <tr>
                <th><?php echo CHtml::encode($model->getAttributeLabel('phone')); ?>
                </th>
                <td><?php echo CHtml::encode($model->phone); ?>
                </td>
            </tr>
            <tr>
                <th><label for="pol">Пол</label>
                </th>
                <td>
                    <? echo $model->pol == 'm' ? 'Мужской' : ''; ?>
                    <? echo $model->pol == 'w' ? 'Женский' : ''; ?>
                </td>
            </tr>
            <tr>
                <th><label for="pol">Дата рождения</label>
                </th>
                <td>
                    <? $months = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябр', 'Октябрь', 'Ноябрь', 'Декабрь'); ?>
                    <? $date = explode('-', $model->birthdate); ?>
                    <?= $date[2] . ' ' . $months[((int)$date[1] - 1)] . ' ' . $date[0]; ?>
                </td>
            </tr>
        </table>
        <div >
            <a href="/restorany">

                <img  src="/images/userpage_image.jpg">
            </a>
        </div>
    </div>
</div>

<script>
    $(window).ready(function () {
        $(".how_to_get_bonus").click(function () {
            $('#invite_friend_layer').show();
            $("#invite_friend").show();
        });
    });
</script>
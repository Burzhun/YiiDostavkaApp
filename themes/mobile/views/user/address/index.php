<div class="mainBox shop">
    <div class="OfficeNav">
        <a href="/user/profile"> профиль </a>
        <a href="/user/orders"> заказы</a>
        <a href="/user/address" class='shopOpenNavActive'> адреса </a>
        <a href="/user/bonus"> баллы </a>
    </div>
</div>

<? foreach ($userAddresses as $data) { ?>
    <div class="shopOpenInfo mainBox">
        <div class="editNav">
            <a href='<?= "/user/address/update/" . $data->id ?>' class=""><img
                    src="<?= Yii::app()->theme->baseUrl; ?>/img/editIcon.png" alt=""></a>
            <a href='<?= "/user/address/delete/" . $data->id ?>' class=""><img
                    src="<?= Yii::app()->theme->baseUrl; ?>/img/deleteIcon.png" alt=""></a>
        </div>
        <div class="shopOpenInfoBlock">
            <span>Город:</span>
            <?= $data->city->name ?>
        </div>
        <div class="shopOpenInfoBlock">
            <span>Улица:</span>
            <?= $data->street ?>
        </div>

        <div class="shopOpenInfoBlock">
            <span>Дом:</span>
            <?= $data->house ?>
        </div>

        <div class="shopOpenInfoBlock">
            <span>Этаж:</span>
            <?= $data->storey ?>
        </div>
        <div class="shopOpenInfoBlock">
            <span>Номер квартиры/офиса:</span>
            <?= $data->number ?>
        </div>
    </div>
<? } ?>
<br>
<br>
<center>
    <a href="/user/address/create" class='submit'>Добавить адрес</a>
</center>
<br>
<br>
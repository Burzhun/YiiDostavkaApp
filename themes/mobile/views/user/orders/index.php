<div class="mainBox shop">
    <div class="OfficeNav">
        <a href="/user/profile"> профиль </a>
        <a href="/user/orders" class='shopOpenNavActive'> заказы</a>
        <a href="/user/address"> адреса </a>
        <a href="/user/bonus"> баллы </a>
    </div>
</div>


<? foreach ($order_model->search(array('user_id' => Yii::app()->user->id)) as $data) { ?>
    <div class="orderHead">
        Заказ №: <?= $data->id ?>
        <span><?= $data->date ?></span>
    </div>

    <div class="padding30 mainBox mainBoxOrder">
        <ul class="orderList">
            <? foreach ($data->orderItems as $value) { ?>
                <li>
                    <div class="orderListLeft">
                        <a href="#"><?= $value->goods->name ?></a>
                        <span class="orderlistPrice"><?= $value->price_for_one ?> р.</span>
                    </div>
                    <div class="orderListRight">
                        <span>
                            <?= $value->quantity ?>
                        </span>
                    </div>
                </li>
            <? } ?>
        </ul>

        <div class="orderItog">
            <div class="orderListTop">
                <span class="orderlistPrice">Сумма заказа:</span>
                <span class="orderlistCoutn"><?php echo Order::totalPriceAdmin($data->sum,$data->forbonus, true) ?></span>
            </div>
        </div>
        <div class="orderStatus"><?= $data->status ?></div>
        <div class="orderAdd">По адресу:  <?= $data->city . ", " . $data->street ?></div>
        <div class="footerBox"></div>
    </div>
<? } ?>
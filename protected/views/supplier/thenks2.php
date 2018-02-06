<div id="pop-up">
    <div class="pop-header">
        <div id="close-pop-up3"></div>
        <h3>Заказ принят!</h3>
    </div>
    <? if ($user_registrated) { ?>
        <p class="thanks-order">Спасибо за регистрацию! Для завершения регистрации пройдите по ссылке оправленной на ваш
            почтовый адрес.</p>
    <? } ?>
    <? $text = str_replace('телефон', 'телефон ' . $phone, Config::model()->find("name='order_message'")->value); ?>
    <p class="thanks-order"><?= $text; ?></p>
    <script>
        $(document).ready(function(){
            var id=<?=$order_id;?>;
            var price=<?=$order_price;?>;
            var delivery=<?=$order_delivery;?>;
            addOrder(id,price,delivery);
        });
    </script>
</div><?php
/**
 * Created by PhpStorm.
 * User: Garry Osborn
 * Date: 08.04.2016
 * Time: 14:08
 */
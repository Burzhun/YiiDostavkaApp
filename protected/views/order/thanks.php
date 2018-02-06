<div class="page">
    <div class="blok">
        <p class="crumbs"><a href="">главная</a> / <a class="crumbs_a" href="">о нас</a></p>

        <div class="page_basket">
            <div class="basket_home">
                <div class="thank_you"><p>Спасибо за заказ!</p></div>
                <div class="blok_div"><p>Ваш заказ принят. В ближайшее время с вами свяжется оператор для уточнения
                        заказа.</p></div>
                <p>Вернуться на <a href="/restorany/<?php echo $partner_tname; ?>">страницу
                        партнера</a></p>
                <?php if (Yii::app()->user->role == User::USER) { ?>
                    <p>Перейти в <a href="/user/orders">свои заказы</a></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
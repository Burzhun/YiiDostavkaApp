<div class="page">
    <div class="blok">
        <p class="crumbs"><a href="">главная</a> / <a class="crumbs_a" href="">о нас</a></p>

        <div class="page_basket">
            <div class="basket_home">
                <div class="thank_you"><p>Поздравляем, вы зарегистрировались!</p></div>
                <div class="blok_div"><p>Чтобы закончить регистрацию на сайте, перейдите по ссылке находящейся в письме
                        отправленном на почту указанную при регистрации </p></div>
                <p><a href="/"><- Вернуться на главную страницу</a></p>
                <?php if (Yii::app()->user->role == User::USER) { ?>
                    <p>Перейти в <a href="/user/orders">свои заказы</a></p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
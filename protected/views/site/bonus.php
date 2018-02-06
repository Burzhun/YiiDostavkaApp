<div class="body-bg bg-none"></div>
<script>
    $(document).ready(function () {
        $(".page-up").click(function () {
            $('html, body').animate({scrollTop: 0}, 500);
        });
    });
</script>


<div id="page" class="page" style="">
    <br>

    <p style="font-weight:bold;">Как начислить баллы?</p>
    <ul>
        <li>- С каждого заказа на личный счет пользователя начисляется  до 10% от суммы заказа.</li>
        <li>- Баллы начисляются в течении 24 часов после подтверждения заказа.</li>
        <li>- Каждые 4 балла приравниваются к 1 (одному) рублю.</li>
        <li>- Накопленные баллы действительны в течение 180 дней с момента начисления.</li>
    </ul>
    <br>

    <p style="font-weight:bold;">Куда потратить баллы?</p>
    <ul>
        <li>- Баллы можно потратить на полную оплату своего заказа на сайте dostavka05.ru.</li>
        <li>- Баллы можно потратить на один из представленных призов на сайте dostavka05.ru в разделе Бонусы</li>
        <li>- Баллы списываются автоматически в момент подтверждения оплаты баллами своего заказа на сайте dostavka05.ru
            или любого другого приза.
        </li>
    </ul>
    <br>

    <div id="invite_friend" style="position: relative;">
        <div class="text">
            <img src="/images/invite_friend.png">

            <div style="font-size: 21px;font-weight: bold;margin-top: 30px;">
                <span style="display: block;">Пригласите друга</span>
                <span style="font-size: 33px;line-height: 27px;">и получите</span>
            </div>
            <div style="font-weight: bold;">
                <span style="font-size: 57px;display: inline-block;width: 95px;">200</span>
                <span style="font-size: 22px;display: inline-block;width: 84px;">баллов на счет</span>
            </div>
            <div style="margin-top: 20px;">
                Расскажите о <span>dostavka05.ru</span> своим друзьям и близким и получайте бонусы.
            </div>
            <div style="margin-top: 10px;padding-left: 5px;">
                Как только приглашенный вами друг зарегистрируется и сделает первый
                заказ вы получите 200 баллов на личный счет
            </div>
            <div style="margin-top: 10px;">
                И так за каждого приглашенного Вами друга!
            </div>
        </div>
        <div class="form">
            <div class="form_text">Пригласить друга по электронной почте</div>
            <div>
                <input type="text" placeholder="E-mail">
                <button>Пригласить</button>
            </div>
            <div class="error"></div>
            <div class="form_text">Пригласить друзей из социальных сетей</div>
        </div>
    </div>
</div>
<? return ;?>
<div id="page" class="page page_bonus">
    <div class='bonus_content'>
        <br>
        <a href='#' class='rules_bonus'>
            Правила начисления баллов
        </a>
        <br>

        <div class='bonus_text'>
            Зарегистрируйтесь и заказывайте еду и продукты в сотнях ресторанах и магазинах через личный кабинет! С
            каждым новым заказом мы будем начислять вам бонусные баллы, которые можно обменивать на ценные призы из
            каталога, а также на еду от ресторанов-партнеров.
        </div>

        <ul class='bonus_goods'>
            <? foreach ($model as $m) { ?>
                <li>
                    <div class='light'>
                    </div>
                    <div class='bonus_goods_img_box'>
                        <div class='bonus_goods_img'>
                            <img src='/upload/bonus/small<?= $m->img ?>'>
                        </div>
                    </div>

                    <div class='bonus_goods_shadow'>
                    </div>
                    <br>

                    <div class='bonus_goods_text'>
                        <?= $m->name ?><br>
                        <?= $m->price ?> баллов
                    </div>
                </li>
            <? } ?>
        </ul>
    </div>
</div>

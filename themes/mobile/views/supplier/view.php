<? /**
 * @var Partner $partner
 * @var Menu[] $menu
 */ ?><?
$warning = $partner->isClosed();
$DaysString = Partner::getWorkDays($partner->id);
$DaysString = str_replace('  ', ' ', $DaysString);
$DaysString = Partner::getWorkDaysString($DaysString);
?>
<div class="topNav">
    <?if(!$warning){?>
    <a href="/cart" class="cartIcon">
        <span><?= CartItem::countCartItem(); ?></span>
    </a>
    <?}?>
    <a href='<?= Yii::app()->request->urlReferrer ?>' class="backLink">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/arrowBack.png" alt=""> Назад
    </a>
</div>

<main class="content">
    <div class="mainBox shop">
        <?
        //Проверяем, работает ли партнер в этом районе
        if ($this->domain->id == 1) {
            $p_id = $partner->id;
            $r_id = Yii::app()->request->cookies['rayon']->value;
            if (!isset(Yii::app()->request->cookies['rayon'])) {
                $pr = false;

            } else {
                $r_id = Yii::app()->request->cookies['rayon']->value;
                $pr = PartnerRayon::model()->find('partner_id=:p_id and rayon_id=:r_id', array(':p_id' => $p_id, ':r_id' => $r_id));
            }
        }
        ?>
        <? if ($this->domain->id == 1 && !$pr && isset(Yii::app()->request->cookies['rayon'])) { ?>
            <div class="partner-disable-msg-block-mobile">
                <img src="/images/zamok.png">

                <div class="msg-block">
                    <p class="msg-title">
                        "<?= $partner->name ?>" не работает в вашем районе
                    </p>

                    <p class="msg-link"><a href="/restorany">Смотреть другие!</a></p>
                </div>
                <div style="text-align: center;">
                    <?
                    /** @var Partner[] $partners */
                    $partners = $partner->getPartnersForRayon(Yii::app()->request->cookies['rayon']->value);
                    foreach ($partners as $partner1) { ?>
                        <a class="blocked-partner-other" href="<?= $partner1->createUrl() ?>">
                            <img src="/upload/partner/<?= $partner1->img; ?>">

                            <p>
                                <?= $partner1->name; ?>
                            </p>
                        </a>
                    <? } ?>
                </div>

                <style>
                    .partner-disable-msg-block-mobile a.blocked-partner-other {
                        display: inline-block;
                        width: 65px;
                        text-align: center;
                        margin-top: 20px;
                        text-decoration: none;
                        overflow: hidden;
                        vertical-align: top;
                    }

                    .partner-disable-msg-block-mobile a.blocked-partner-other img {
                        float: none;
                        position: relative;
                        top: 0;
                        width: auto;
                        height: 60px;
                    }

                    .blocked-partner-msg-title {
                        font-size: 13px;
                        margin-top: 13px;
                    }

                    .blocked-partner-other p {
                        font-family: 'PT Sans';
                        color: black;
                        font-weight: bold;
                        overflow: hidden;
                    }

                </style>
            </div>
            <hr>
        <? } ?>
        <? if ($partner->status == 0 || $partner->self_status == 0) { ?>
            <div class="partner-disable-msg-block-mobile">
                <img src="/images/zamok.png">

                <div class="msg-block">
                    <p class="msg-title">
                        "<?= $partner->name ?>" временно заблокировано
                    </p>

                    <p class="msg-link"><a href="/restorany">Смотреть другие!</a></p>
                </div>
                <p class="blocked-partner-msg-title">
                    Попробуйте сделать заказ в других круглосуточных кафе и ресторанах.
                </p>

                <div style="text-align: center;">
                    <?
                    // @TODO Ниже код повторяется
                    /** @var Partner[] $partners */
                    $partners = $partner->getOpenedPartners();
                    foreach ($partners as $partner1) { ?>
                        <a class="blocked-partner-other" href="<?= $partner1->createUrl() ?>">
                            <img src="/upload/partner/<?= $partner1->img; ?>">

                            <p>
                                <?= $partner1->name; ?>
                            </p>
                        </a>
                    <? } ?>
                </div>

                <style>
                    .partner-disable-msg-block-mobile a.blocked-partner-other {
                        display: inline-block;
                        width: 65px;
                        text-align: center;
                        margin-top: 20px;
                        text-decoration: none;
                        overflow: hidden;
                        vertical-align: top;
                    }

                    .partner-disable-msg-block-mobile a.blocked-partner-other img {
                        float: none;
                        position: relative;
                        top: 0;
                        width: auto;
                        height: 60px;
                    }

                    .blocked-partner-msg-title {
                        font-size: 13px;
                        margin-top: 13px;
                    }

                    .blocked-partner-other p {
                        font-family: 'PT Sans';
                        color: black;
                        font-weight: bold;
                        overflow: hidden;
                    }

                </style>
            </div>
            <hr>
        <? } elseif ($warning) { ?>
            <div class="partner-disable-msg-block-mobile">
                <img src="/images/clock2.png">

                <div class="msg-block">
                    <p class="msg-title">
                        Время работы "<?= $partner->name ?>" с <?= substr($partner->work_begin_time, 0, 5); ?>
                        до <?= substr($partner->work_end_time, 0, 5); ?>
                    </p>

                    <p class="msg-link"><a href="/restorany">Смотреть другие!</a></p>
                </div>
                <p class="blocked-partner-msg-title">
                    Попробуйте сделать заказ в других круглосуточных кафе и ресторанах.
                </p>

                <div style="text-align: center;">
                    <?
                    /** @var Partner[] $partners */
                    $partners = $partner->getOpenedPartners();
                    foreach ($partners as $partner1) { ?>
                        <a class="blocked-partner-other" href="<?= $partner1->createUrl() ?>">
                            <img src="/upload/partner/<?= $partner1->img; ?>">

                            <p>
                                <?= $partner1->name; ?>
                            </p>
                        </a>
                    <? } ?>
                </div>

                <style>
                    .partner-disable-msg-block-mobile a.blocked-partner-other {
                        display: inline-block;
                        width: 90px;
                        height: 60px;
                        text-align: center;
                        margin-top: 20px;
                        margin-right: 2%;
                    }

                    .partner-disable-msg-block-mobile a.blocked-partner-other img {
                        float: none;
                        width: 90px;
                    }

                    .blocked-partner-msg-title {
                        font-size: 13px;
                        margin-top: 13px;
                    }
                </style>
            </div>
            <hr>

        <? } ?>

        <div class="padding10">
            <div class="shopBlock">
                <a href="#" class="shopImg">
                    <? if (!empty($partner->img)) { ?>
                        <img style="max-width:70px;" src="/upload/partner/<? echo $partner->img ?>"
                             alt="доставка еды, махачкала, дагестан, <? echo $partner->name ?>"/>
                    <? } else { ?>
                        <img style="max-width:70px;" src="/images/default.jpg"
                             alt="доставка еды, махачкала, дагестан, <? echo $partner->name ?>"/>
                    <? } ?>
                </a>

                <div class="shopRight">
                    <a href="#" class="shopTitle"><?= $partner->name; ?></a>

                    <div class="shopShort"><?= $partner->text; ?></div>
                    <div style='clear:both'></div>
                    <div class="shopAttr">
                        <span><? echo $partner->min_sum ? $partner->min_sum . ' ' . City::getMoneyKod($this->domain) : 'Нет' ?></span>
                        Минимальная <br>
                        сумма заказа
                    </div>
                    <div class="shopAttr">
                        <span><? echo $partner->delivery_cost ? $partner->delivery_cost . ' ' . City::getMoneyKod($this->domain) : 'Бесплатно' ?></span>
                        Стоимость <br>
                        доставки
                    </div>
                    <div class="shopAttr">
                        <span><? echo $partner->delivery_duration ?></span>
                        Время <br>
                        доставки
                    </div>
                </div>
            </div>
        </div>

        <? //if ($warning) return; // @TODO что тут происходит? ?>
        <div class="shopOpenNav">
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>" class='shopOpenNavActive'>Меню</a>
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>/info">Доп. инфо</a>
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>/review">Отзывы</a>
        </div>
    </div>
    <? if ($warning) { ?>
        <div class="supplier_warning"><br>
            В настоящее время <span>«<?= $partner->name ?>»</span> заказы не
            принимает.</p><br>
            Ресторан откроется через <span style="color:#408aa7;"><?= $partner->howLongWill(); ?></span><br><br>
        </div>
    <? } ?>
    <ul class="shopOpenList">
        <? $free_goods=Goods::model()->findAll("price=0 and partner_id=".$partner->id);
        if($free_goods){ ?>
            <div>
                <li><a href="#">Акции</a></li>
                <?foreach($free_goods as $free_good){
                    $this->renderPartial('_view_goods', array('goods' => $free_good, 'partner' => $partner,'warning'=>$warning));
                }?>
            </div>
            <div style="clear:both;"></div>
        <?}?>
        <? if ($partner->favorite_goods) { ?>
            <li><a href="#">Популярное</a></li>
            <? foreach ($partner->favorite_goods as $favorite) {
                $this->renderPartial('_view_goods', array('goods' => $favorite, 'partner' => $partner,'warning'=>$warning));
            } ?>
        <? } ?>
        <? foreach ($menu as $m) { // вывод каталога товаров?>
            <? if ($m->have_subcatalog) { ?>
                <li data-id='<?= $m->id ?>' class='haveSub'
                    id="header_menu_<? echo $m->id // задаем id для того чтобы сюда можно было попасть нажав на пункт меню?>">
                    <a href="#"><?= $m->name; ?></a>
                </li>
            <? } ?>
            <? if ($m->have_subcatalog) { // проверяем наличие подкаталога?>
                <div class="dropShopOpenListBox">
                    <? foreach ($m->submenu as $submenu) { // выводим все подменю?>
                        <li data-id='<?= $submenu->id ?>' class='ajaxGoodsLink'
                            id="header_menu_<? echo $submenu->id // задаем id для того чтобы сюда можно было попасть нажав на пункт меню?>">
                            <a href="#">&nbsp;&nbsp;&nbsp;&nbsp;<?= $submenu->name; ?></a>
                        </li>
                        <div class="ajaxGoodsBox"></div>
                    <? } ?>
                </div>
            <? } else { // если нет подкаталога сразу выводим товары?>
                <li data-id='<?= $m->id; ?>' class='ajaxGoodsLink'
                    id="header_menu_<? echo $m->id // задаем id для того чтобы сюда можно было попасть нажав на пункт меню?>">
                    <a href="#"><?= $m->name; ?></a>
                </li>
                <div class="ajaxGoodsBox"></div>
            <? } ?>
        <? } ?>
    </ul>

    <script type="text/javascript">
        $(document).ready(function () {
            PartnerClick(<?=$partner->id;?>,'<?=$partner->name;?>','Партнер');
            $(document).on("click", ".haveSub", function () {
                $(this).next(".dropShopOpenListBox").slideToggle(100);
                return false
            });

            $(document).on("click", ".ajaxGoodsLink", function () {
                var id = $(this).data('id');
                var $this = $(this);
                if ($this.hasClass("ajaxGoodsLinkActive")) {
                    $(".ajaxGoodsBox").empty();
                    $(".ajaxGoodsLink").removeClass("ajaxGoodsLinkActive")
                } else {
                    $(".ajaxGoodsLink").removeClass("ajaxGoodsLinkActive");
                    $this.addClass("ajaxGoodsLinkActive");
                    var url = '/restorany/ajaxGoods/id/' + id;
                    $.ajax({
                        url: url,
                        type: "post",
                        beforeSend: function () {
                            $this.append('<img id="imgcode" src="/themes/mobile/img/loading.gif">');
                        },
                        success: function (data) {
                            $(".ajaxGoodsBox").empty();
                            $this.next(".ajaxGoodsBox").html(data);
                            $('body').animate({scrollTop: $this.next(".ajaxGoodsBox").offset().top - 100}, 1000);
                            $("#imgcode").remove();

                            // window.history.pushState('url2', 'Title', '/article/'+id+'/'+alias+'?open=1');
                        }
                    });
                }
                return false;
            });
        });
    </script>

</main><!-- .content -->

<script>
    //функция обрабатывает заказ
    function order(p_id) {
        //получаем id заказываемого товара
        var value = $('#productcount_' + p_id).attr('value');
        $.ajax({
            url: '/cart/add',
            type: "post",
            cache: false,
            data: {"product": p_id, "count": value},
            success: function (data) {
                getBasket();
            }
        });
    }

    function getMobileBasket() {
        $.ajax({
            url: '/cart/mobileBasket',
            type: "post",
            cache: false,
            data: {"partner":<? echo $partner->id?>},
            success: function (basketData) {
                if (basketData != false) {
                    //$(".bascet").css('display', 'block');
                    $(".cartIcon").html(basketData);
                }
            }
        });
    }
</script>
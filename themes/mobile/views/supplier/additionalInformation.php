<div class="topNav">
    <a href="/cart" class="cartIcon">
        <span><?= CartItem::countCartItem(); ?></span>
    </a>
    <? /*<a href="" class="shortLIcon"></a>
	<a href="" class="searchIcon"></a>*/ ?>

    <a href='<?= Yii::app()->request->urlReferrer ?>' class="backLink">
        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/arrowBack.png" alt=""> Назад
    </a>
</div>

<main class="content">
    <div class="mainBox shop">
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
                        сумма доставки
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
        <div class="shopOpenNav">
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>">Меню</a>
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>/info" class='shopOpenNavActive'>Доп.
                инфо</a>
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>/review">Отзывы</a>
        </div>
    </div>

    <div class="shopOpenInfo mainBox">
        <div class="shopOpenInfoBlock">
            <span>Время работы:</span>
            <? if ($partner->day1) { ?> пн <? } ?>
            <? if ($partner->day2) { ?> вт <? } ?>
            <? if ($partner->day3) { ?> ср <? } ?>
            <? if ($partner->day4) { ?> чт <? } ?>
            <? if ($partner->day5) { ?> пт <? } ?>
            <? if ($partner->day6) { ?> сб <? } ?>
            <? if ($partner->day7) { ?> вс <? } ?>
            <br>
            рабочее время: с <? echo date('H:i', strtotime($partner->work_begin_time)); ?>
            до <? echo date('H:i', strtotime($partner->work_end_time)); ?>
        </div>

        <? /*<div class="shopOpenInfoBlock">
		  	<span>Специализация:</span>		 
			  Пицца 
		</div>*/ ?>

        <div class="shopOpenInfoBlock">
            <span>Адрес:</span>
            <? echo $partner->address; ?>
        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on("click", ".haveSub", function () {

                $(this).next(".dropShopOpenListBox").slideToggle(100);
                return false

            });

            $(document).on("click", ".ajaxGoodsLink", function () {


                var id = $(this).data('id')
                var $this = $(this);
                if ($this.hasClass("ajaxGoodsLinkActive")) {
                    $(".ajaxGoodsBox").empty();
                    $(".ajaxGoodsLink").removeClass("ajaxGoodsLinkActive")
                } else {

                    $(".ajaxGoodsLink").removeClass("ajaxGoodsLinkActive")
                    $this.addClass("ajaxGoodsLinkActive")


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


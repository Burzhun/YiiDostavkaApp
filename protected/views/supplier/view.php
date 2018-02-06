<?

/**
 * @var Partner $partner
 * @var Menu $menu
 * @var SupplierController $this
 * @var boolean $warning
 * @var string $timeOut
 */
Yii::app()->clientScript->registerScriptFile('/js/cusel.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.mousewheel.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.jscrollpane.js');
Yii::app()->clientScript->registerScriptFile('/js/sticky.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.mousewheel.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.jscrollpane.js');
Yii::app()->clientScript->registerCssFile('/js/scroll/jquery.jscrollpane.css');
?>
<script type="text/javascript">
    $(document).ready(function () {
        jQuery(function () {
            jQuery('.scroll-pane').jScrollPane();
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.bascet').stickyfloat();
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        PartnerClick(<?=$partner->id;?>, '<?=$partner->name;?>', 'Партнер');
        $('.button_order').click(function () {
            if ($('.tooltip').is(':animated') == false) {
                $('.tooltip').css('display', 'block')
                $('.tooltip').animate({
                    bottom: '70px'
                }, 300, function () {
                    $('.tooltip').animate({opacity: 1}, 1000, function () {
                        $('.tooltip').animate({bottom: '-90px'}, 100, function () {
                            $('.tooltip').css('display', 'none')
                        })
                    })
                })
            }
        });
    });
</script>

<div class="body-bg bg-none"></div>
<div id="page">
    <? if (Yii::app()->user->role == 'admin') { ?>
        <div class="openVoiceImage">
        </div>
    <? } ?>
    <div id="popup">
        <? $hour = date('H'); ?>
        <? //Проверяем, работает ли партнер в этом районе
        if ($this->domain->id == 1) {
            $p_id = $partner->id;

            if (!isset(Yii::app()->request->cookies['rayon'])) {
                $pr = false;
            } else {
                $r_id = Yii::app()->request->cookies['rayon']->value;
                $pr = PartnerRayon::model()->find('partner_id=:p_id and rayon_id=:r_id', array(':p_id' => $p_id, ':r_id' => $r_id));
            }
        }
        if ($this->domain->id == 1 && !$pr && isset(Yii::app()->request->cookies['rayon'])) { ?>
            <? $this->renderPartial('_popUpWrongRayon', array('partner' => $partner)); ?>
            <script>
                $("#parent_popup").css("display", "block");
            </script>
        <? } ?>
        <? if ($partner->status == 0 || $partner->self_status == 0) { ?>
            <? $this->renderPartial('_popUpBlockedPartner', array('partner' => $partner)); ?>
            <script>
                $("#parent_popup").css("display", "block");
            </script>
        <? }
        elseif ($warning) { ?>
        <? $this->renderPartial('_popUpClosedPartner', array('partner' => $partner)); ?>
            <script>
                $("#parent_popup").css("display", "block");
            </script>
        <? } ?>
    </div>

    <div class="track"><a href="/">главная</a> / <a href="/restorany">Все рестораны</a> / <? echo $partner->name ?>
    </div>
    <div id="name-cafe">
        <div id="cafe-logo">
            <img src="<? echo $partner->getImage(); ?>" alt="<? echo $partner->name ?>"/>
        </div>

        <div id="cafe-info">
            <h1><? echo $partner->name ?></h1>

            <p><? echo $partner->text ?></p>
            <ul>
                <li>Минимальная сумма заказа<br/>
                    <? if ($this->domain->id == 1 && isset(Yii::app()->request->cookies['rayon'])){ ?>
                    <? $partner_rayon = PartnerRayon::model()->find("rayon_id=" . Yii::app()->request->cookies['rayon']->value . " and partner_id=" . $partner->id); ?>
                    <p class="price"><?= $partner_rayon->min_sum . ' ' . City::getMoneyKod(); ?></p></li>
                <? } else { ?>
                    <p class="price"><? echo $partner->min_sum ? $partner->min_sum . ' ' . City::getMoneyKod() : 'Нет' ?></p></li>
                <? } ?>
                <li>Стоимость доставки<br/>

                    <p class="price"><? echo $partner->delivery_cost ? $partner->delivery_cost . ' ' . City::getMoneyKod() : 'Бесплатно' ?></p>
                </li>
                <li>Время доставки<br/><br/>
                    <p class="price"><? echo $partner->delivery_duration ?></p></li>
                <? if ($partner->use_kassa) { ?>
                    <li style="margin-top:14px;">
                        Оплата картой онлайн
                        <p>
                            <img width="65px" class="visa_mastercard_logo" src="/images/visa-master.png">
                        </p>
                    </li>
                <? } ?>
            </ul>
        </div>

        <?=$this->renderPartial('_addressPanel', ['partner' => $partner, 'region' => $this->citySite->region]);?>

        <div class='link10'>
            <a href="/restorany/<?= $partner->tname ?>" title="Вернуться в меню" title="" class='activeLink10'>Меню</a>
            <a href="/restorany/<?= $partner->tname ?>/review" title="Отзывы">Отзывы</a>
        </div>
    </div>
    <div id="content-page">
        <script type="text/javascript">
            $(document).ready(function () {
                var topPadding = 15;
                var offset = $(".blok_category2").offset();
                var list_view_block = $("#content-page").offset().left;

                $(window).scroll(function () {
                    resize_menu_block();
                    h = $('.blok_category2').height();
                    h2 = $('.page_the_category2').height() - 100;
                    htop = $(window).scrollTop() - offset.top + topPadding;
                    t = h + htop;
                    t2 = h2 - h;
                    if (($(window).scrollTop() > offset.top - topPadding) && (h2 > t - topPadding)) {
                        $('.blok_category2').stop().css('position', 'fixed').css('top', '0');
                    }
                    else {
                        if (h2 <= t) {
                            $('.blok_category2').css('position', 'absolute').css('top', t2).css('left', '0');
                        } else $('.blok_category2').stop().css('position', 'static').css('top', 'auto');
                    }
                });
            });

            function resize_menu_block() {
                var blok_category2 = document.getElementById('blok_category2');
                var page = document.getElementById('page');
                var coords_menu = blok_category2.getBoundingClientRect();
                var coords_page = page.getBoundingClientRect();
                var menu_left = coords_menu.left;
                var page_left = coords_page.left + 31;
                $('.blok_category2').css('left', page_left)
            }
        </script>


        <div class="blok_category2" id='blok_category2'>
            <div id="firstpane" class="menu_list">
                <? if ($partner->favorite_goods) { //вывод популярных товаров?>
                    <p class="menu_head menu_items" id="menu_favorite" style="background:none;">Популяное</p>
                <? } ?>

                <? foreach ($menu as $m) { //вывод меню?>
                    <p class="menu_head menu_items"
                       id="<? echo $m->id // задаем id для яваскрипта, чтоб потом можно было перейти на соответствующие товары в списке?>"
                        <? if ($m->have_subcatalog) { //проверяем есть ли подменю, для оформления пункта?>
                            style="background:url( /images/down.png) center right no-repeat" <? //стрелка сбоку указывает что есть подменю?>
                        <? } else { ?>
                            style="background:none;" <? // стрелки не будет?>
                        <? } ?>
                    >
                        <? echo $m->name // ввывод названия?>
                    </p>
                    <? if ($m->have_subcatalog) { // опять проверяем наличие подкаталогов, уже с целью вывести подменю?>
                        <div class="menu_body">
                            <? foreach ($m->submenu as $submenu) { // проходимся по всем пунктам подменю?>
                                <a class="menu_items" id="<? echo $submenu->id ?>"
                                   style="cursor:pointer"><? echo $submenu->name ?></a>
                            <? } ?>
                        </div>
                    <? } ?>
                <? } ?>
            </div>
            <div class="SeoText"
                 style="width: 180px; float: right; text-align: justify;margin-top: 40px;"><?= $partner->seo_text ?> </div>
        </div>
        <div class="page_the_category2">
            <? if (Yii::app()->user->role == User::ADMIN) { ?>
                <input id="search_goods">
                <script type="text/javascript">
                    $('#search_goods').on('keyup', function () {
                        var searchText = $(this).val();
                        $('.blok_order').each(function () {
                            var pos1 = $(this).find('.goodsInfoText').text().toLowerCase().indexOf(searchText);
                            var pos2 = $(this).find('.goodsNameText').text().toLowerCase().indexOf(searchText);
                            if (pos1 >= 0 || pos2 >= 0) {
                                $(this).css('display', 'block');
                                if ($(this).position().left < 230) {
                                    $(this).find(".img_order").attr('onmouseout', 'mouse_out_left(this)').attr('onmouseover', 'mouse_over_left(this)');
                                    $(this).find(".productinfoblockright").removeClass("productinfoblockright").addClass("productinfoblockleft");
                                }
                            } else {
                                $(this).css('display', 'none');
                            }
                        });
                    });
                </script>
            <? } ?>
            <? if ($warning) { ?>
                <div class="shopClose">
                    В настоящее время «<?= $partner->name ?>» заказы не принимает. Возможен только предварительный
                    заказ.<br><br>
                    Ресторан откроется через <span> <?= $timeOut ?></span>
                </div>
            <? } ?>
            <? $i = 0; ?>
            <? $free_goods = Goods::model()->findAll("price=0 and publication=1 and partner_id=" . $partner->id);
            if ($free_goods) { ?>
                <div>
                    <h2 id="header_menu_favorite">Акции</h2>
                    <? foreach ($free_goods as $free_good) {
                        $this->renderPartial('_view_goods', array('goods' => $free_good, 'partner' => $partner, 'i' => $i));
                        $i++;
                    } ?>
                </div>
                <div style="clear:both;"></div>
            <? } ?>
            <? if ($partner->favorite_goods){ ?>
            <div style="float:none;clear:both"></div>
            <h2 id="header_menu_favorite">Популярное</h2>
        <? $i = 0;
        foreach ($partner->favorite_goods as $favorite) {
            $this->renderPartial('_view_goods', array('goods' => $favorite, 'partner' => $partner, 'i' => $i));
            $i++;
        } ?>
            <p class="menu_head menu_items" id="menu_favorite" style="background:none;">
                <? } ?>
                <? foreach ($menu as $m) { // вывод каталога товаров?>
                <? if ($m->have_subcatalog) { // проверяем наличие подкаталога?>
            <div style="float:none;clear:both"></div>
            <p type="hidden" id="header_menu_<? echo $m->id ?>"></p>
        <? foreach ($m->cache(1000)->submenu as $submenu) { // выводим все подменю?>
            <div style="float:none;clear:both"></div>

            <h2 id="header_menu_<? echo $submenu->id /* задаем id для того чтобы сюда можно было попасть нажав на пункт меню*/ ?>">
                <? echo $submenu->name ?>
            </h2>
            <? $i = 0; ?>
            <? foreach ($submenu->cache(1000)->goods as $goods) { // вывод товаров
                if ($goods->price) {
                    $this->renderPartial('_view_goods', array('goods' => $goods, 'partner' => $partner, 'i' => $i));
                }
                $i++;
            }
        }
        } else { // если нет подкаталога сразу выводим товары?>
            <div style="float:none;clear:both"></div>
            <h2 id="header_menu_<? echo $m->id ?>"><? echo $m->name ?></h2>
            <? $i = 0; ?>
            <? foreach ($m->cache(1000)->goods as $goods) {
                if ($goods->price) {
                    $this->renderPartial('_view_goods', array('goods' => $goods, 'partner' => $partner, 'i' => $i));
                }
                $i++;
            }
        }
        } ?>
            <div class="SeoText"> <!-- SeoTextBottom --> </div>
            <div id="page-up" class="up" href="javascript:void(0)">
                <a href="#">
                    <img src="/images/up_img.png" class="page-up">
                </a>
            </div>
            <div id="page-up" class="up" href="javascript:void(0)" style="left:4%">
                <a href="#">
                    <img src="/images/up_img.png" class="page-up" style="left:0">
                </a>
            </div>
        </div>
    </div>

    <div class='bascet'>

        <div class="tooltip">
            <span class="content">
                <span class="handle"></span>

                <div class="added">Добавлено в корзину</div>
            </span>
        </div>
        <div id="bascet">
        </div>
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        var params = { //TODO Марат, че тут происходит?
            changedEl: ".lineForm select",
            visRows: 5,
            scrollArrows: true
        };
        cuSel(params);
        var params = {
            changedEl: "#city",
            scrollArrows: false
        };
        cuSel(params);
        jQuery("#showSel").click(
            function () {
                jQuery(this).prev().fadeIn();
                params = {
                    refreshEl: "#city2, #city20, #city30", /* перечисляем через запятую id селектов, которые нужно обновить */
                    visRows: 4
                };
                cuSelRefresh(params);
            }
        );
    });
</script>

<style type="text/css">
    .home {
        height: 734px;
        margin: 0 auto;
    }
</style>

<script>
    $(document).ready(function () {
        $(document).on("click", "#bascet-button, .edit-order", function (event) {
            $("#popup").html("");
            getCart();
            $("#parent_popup").css("display", "block");
            $(".bascet").css("display", "none");
            return false;
        });
        $(document).on("click", "#bascet-button", function (event) {
            $("#pop-up-bascet").addClass('popupz-index');//.animate({opacity: 1}, 500);
            $(".bascet").css("display", "none");
            return false;
        });
        $(document).on("click", "#bascet-button", function (event) {
            var win_h3 = $(window).scrollTop();
            $("#pop-up-bascet,#pop-up,#pop-up-order,#popup").css('top', win_h3 - 100);
            var scroll = $(window).scrollTop() + $(window).height() + 250;
            var heightD = $(document).height();
            var heightW = $(window).height();

            if (heightW < 750 && scroll > heightD) {
                $("#pop-up-bascet,#pop-up,#pop-up-order,#popup").css('top', win_h3 - 400);
            }

            $(".bascet").css("display", "none");
            return false;
        });
        $(document).on("click", "#close-pop-up1, #close-pop-up2, #close-pop-up3", function (event) {
            getBasket();
            $("#popup").html("");
            $("#parent_popup").css("display", "none");
            return false;
        });
        $(document).on("click", ".checkout", function (event) {
            getOrder('checkout');
            return false;
        });
    });

    function getWarning() {
        $.ajax({
            url: '/restorany/action/warning',
            type: "post",
            cache: false,
            data: {"partner":<? echo $partner->id?>},
            success: function (basketData) {
                $("#popup").html(basketData);
            }
        });
    }

    function getCart() {
        $.ajax({
            url: '/restorany/action/cart',
            type: "post",
            cache: false,
            data: {"partner":<? echo $partner->id?>},
            success: function (basketData) {
                $("#popup").append(basketData);
            }
        });
    }

    function getOrder(target) {
        var warning = <?echo $warning ? 1 : 0; ?>;
        $.ajax({
            url: '/restorany/action/order',
            type: "post",
            dataType: 'JSON',
            cache: false,
            data: {"partner":<? echo $partner->id?>},
            beforeSend: function () {
                $(".loader_cart").show();
            },
            success: function (data) {
                //нада поставить false true
                if (data['error']) {
                    $("#popup_order_errors").html("");
                    $("#popup_order_errors").append(data['error']);
                    $(".loader_cart").hide();
                } else {
                    var cart = JSON.parse(data['cart_items']);
                    addCart(cart);
                    if (warning && target == 'checkout') {
                        getWarning();
                        $(".loader_cart").hide();
                    } else {
                        $(".loader_cart").hide();
                        if (data['page']) {
                            $("#popup").append(data['page']);
                            $("#pop-up-bascet").remove();
                            $(".loader_cart").show();
                        }
                    }
                }

            }
        });
    }

    function removePopup() {
        alert("Удали ненужную функцию");
        $("#pop-up-bascet").remove();
    }

    $(document).ready(function () {
        $('#page').click(function (event) {
            if (event.target.className != "showed_product_counter" && event.target.className != "update_count_product_container" && event.target.className != 'plus' && event.target.className != "minus" && event.target.className != "updatecount_ok" && event.target.className != "new_count" && event.target.className != "img_order")

                $('.productinfoblockright,.productinfoblockleft,.update_count_product_container').css('display', 'none')
        });
    });

    $(document).on("click", "#bascet-button", function (event) {
        $("#pop-up-bascet").addClass('popupz-index');//.animate({opacity: 1}, 500);
        $(".bascet").css("display", "none");
        return false;
    });

    $(document).on("click", ".predzakaz", function (event) {
        getOrder('predzakaz');
        return false;
    });

    jQuery(function ($) {
        $('body').on('click', '#yt555', function () {
            jQuery.ajax({
                'type': 'post',
                'dataType': 'JSON',
                'success': function (dataAdr) {
                    if (dataAdr['error']) {
                        $("#popup_order_errors").html("");
                        $("#popup_order_errors").append(dataAdr['error']);
                    }
                    if (dataAdr['page']) {
                        $("#newaddress").hide();
                        $("#popup_order_errors").html("");
                        $("#Order_address").html(dataAdr['page']);
                    }
                },
                'url': '/restorany/action/addAdress',
                'cache': false,
                'data': jQuery("#addAddressForm").serialize()
            });
            return false;
        });
    });
    $('body').on('click', "#payment_method button", function () {
        $("#payment_method button.selected").removeClass('selected');
        $(this).addClass("selected");
    });
    var kassa_check_function = null;
    function check_kassa() {
        $.post('/YandexKassa/check_kassa', {}, function (data) {
            if (data == 'payed') {
                clearInterval(kassa_check_function);
                $.post('/restorany/action/GetThanksPage', {}, function (data) {
                    $("#popup").append(data);
                    $("#pop-up-order").remove();
                });
            }
            if (data == 'cancelled') {
                clearInterval(kassa_check_function);
            }
        });
    }
    $('body').on('click', '#yt1000', function () {
        var type = $("#payment_method button.selected").attr('id');
        if (type != 'use_card') {
            jQuery.ajax({
                'type': 'post',
                'dataType': 'JSON',
                beforeSend: function (data) {
                    $(".loader_order").show();
                },
                'success': function (data) {
                    $(" .loader_order").hide();
                    if (data['error']) {
                        $("#popup_order_errors").html("");
                        $("#popup_order_errors").append(data['error']);
                    }
                    if (data['page']) {

                        $("#popup").append(data['page']);
                        $("#pop-up-order").remove();
                        $(".bascet").css("display", "none");
                        //return false;
                        //$("#newaddress").hide();
                        //$("#Order_address").html(data);
                    }
                },
                'url': '/restorany/action/order',
                'cache': false,
                'data': jQuery("#orderForm").serialize()
            });
        } else {
            var newWin = window.open('', "hello", "width=600,height=600");
            jQuery.ajax({
                'type': 'post',
                'dataType': 'JSON',
                beforeSend: function (data) {
                    $(".loader_order").show();
                },
                'success': function (data) {
                    $(" .loader_order").hide();

                    if (data['page']) {
                        $(".errorSummary").hide();
                        var url = "/yandex_kassa.php?order_id=" + data['order_id'] + "&sum=" + data['sum'];
                        newWin.location = url;
                        kassa_check_function = setInterval(check_kassa, 2000);
                        return false;
                        $("#newaddress").hide();
                        $("#Order_address").html(data);
                    } else {
                        if (data['error']) {
                            $("#popup_order_errors").html("");
                            $("#popup_order_errors").append(data['error']);
                            newWin.close();
                        }
                    }
                },
                'url': '/restorany/action/orderkassa',
                'cache': false,
                'data': jQuery("#orderForm").serialize()
            });
        }

        return false;
    });

    $('body').on('click', '#yt1001', function () {
        jQuery.ajax({
            'type': 'post',
            'dataType': 'JSON',
            beforeSend: function (data) {
                $(".loader_order").show();
            },
            'success': function (data) {
                $(" .loader_order").hide();
                if (data['error']) {
                    $("#popup_order_errors").html("");
                    $("#popup_order_errors").append(data['error']);
                }
                if (data['page']) {

                    $("#popup").append(data['page']);
                    $("#pop-up-order").remove();
                    $(".bascet").css("display", "none");

                }
            },
            'url': '/restorany/action/order',
            'cache': false,
            'data': jQuery("#orderForm").serialize() + "&forbonus=1"
        });
        return false;
    });
    $('body').on('click', '#yt1002', function () {
        jQuery.ajax({
            'type': 'post',
            'dataType': 'JSON',
            beforeSend: function (data) {
                $(".loader_order").show();
            },
            'success': function (data) {
                $(" .loader_order").hide();
                if (data['error']) {
                    $("#popup_order_errors").html("");
                    $("#popup_order_errors").append(data['error']);
                }
                if (data['page']) {

                    $("#popup").append(data['page']);
                    $("#pop-up-order").remove();
                    $(".bascet").css("display", "none");
                    //return false;
                    //$("#newaddress").hide();
                    //$("#Order_address").html(data);
                }
            },
            'url': '/restorany/action/order',
            'cache': false,
            'data': jQuery("#orderForm").serialize() + "&register=1"
        });
        return false;
    });
    //создаем строку для подстановки в запрос : если заказывает зареганный то ищем по ID, если нет, то по сессии
    <? $condition = Yii::app()->user->role != User::USER ? " AND session_id='" . Yii::app()->session->sessionId . "'" : " AND user_id='" . Yii::app()->user->id . "'";?>
    //проверяем есть ли в корзине товары от данного поставщика
    <? if(CartItem::model()->count(array("condition" => "partner_id=" . $partner->id . $condition))){?>
    getBasket();
    <? } ?>

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

    function orderDrink(p_id) {
        //получаем id заказываемого товара
        var value = 1;
        $.ajax({
            url: '/cart/add',
            type: "post",
            cache: false,
            dataType: 'json',
            data: {"product": p_id, "count": value, 'type': 'drink'},
            beforeSend: function () {
                $('#pop-up-bascet').append('<img src="/images/loader2.gif" class="drink-loader">');
            },
            success: function (data) {
                setTimeout(function () {
                    $('.bascet-list').html(data.list);
                    $('#sum_cart').html(data.sumCart);
                    $('#sum_itogo').html(data.sumCart);
                    $('.drink-loader').remove();
                }, 500);
            }
        });
    }

    function getBasket() {
        $.ajax({
            url: '/cart/basket',
            type: "post",
            cache: false,
            data: {"partner":<? echo $partner->id?>},
            success: function (basketData) {
                if (basketData != false) {
                    $(".bascet").css('display', 'block');
                    $("#bascet").html(basketData);
                }
            }
        });
    }

    function mouse_over_right(p_ob) {
        var block = $(p_ob).parent().parent().find(".productinfoblockright");
        // - p_ob.width
        block.show();
        block.css('margin-left', -block.width() - 30 + 'px');
    }

    function mouse_out_right(p_ob) {
        var block = $(p_ob).parent().parent().find(".productinfoblockright");
        block.hide();
    }

    function mouse_over_left(p_ob) {
        var block = $(p_ob).parent().parent().find(".productinfoblockleft");
        // - p_ob.width
        block.show();
        //block.css('margin-left', -block.width() - 30 + 'px');
    }

    function mouse_out_left(p_ob) {
        var block = $(p_ob).parent().parent().find(".productinfoblockleft");
        block.hide();
    }

    $(".showed_product_counter").click(function () {
        $('.update_count_product_container').hide();
        var update_count_product_container = $(this).parent().parent().find('.update_count_product_container');
        var productcounter = $(this).parent().parent().find('.productcounter');
        update_count_product_container.find(".new_count").val(parseInt(productcounter.val()));
        update_count_product_container.show();
    });

    $(".updatecount_ok").click(function () {
        var update_count_product_container = $(this).parent();//здесь находится контейнер с плюсами и минусами
        var productcountercontainer = update_count_product_container.parent().find('.product_counter_container');//здесь храниться `p` контейнер, у которого внутри "1_шт"
        var showedproductcounter = productcountercontainer.find('.showed_product_counter');//здесь храниться `a` контейнер, у которого внутри "1_шт"
        var productcounter = productcountercontainer.parent().find('.productcounter');//здесь храниться инпут из которого и формируется количество закидываемого в корзину количества продукта
        var showcount = showedproductcounter.find('.showcount');
        var new_count = update_count_product_container.find(".new_count");//это тот счетчик который отображается при редактировании
        showcount.text(parseInt(new_count.val()));
        productcounter.val(parseInt(new_count.val()));
        order(parseInt($(this).attr('product_id')));
        update_count_product_container.hide();
    });

    $(".minusupdate").click(function () {
        var update_count_product_container = $(this).parent();
        var productcounter = update_count_product_container.parent().find(".productcounter");
        var new_count = update_count_product_container.find(".new_count");
        new_count.val(parseInt(new_count.val()) > 1 ? parseInt(new_count.val()) - 1 : 1);
    });

    $(".plusupdate").click(function () {
        var update_count_product_container = $(this).parent();
        var productcounter = update_count_product_container.parent().find(".productcounter");
        var new_count = update_count_product_container.find(".new_count");
        new_count.val(parseInt(new_count.val()) < 99 ? parseInt(new_count.val()) + 1 : 99);
    });

    function updatecount() // увеличиваем количество товара закидоваемого разом в корзину
    {
        //var p = $(this).parent();
        $(this).hide();
    }
</script>

<script type="text/javascript">
    $(function () {
        $(".middle-nav li img ").hover(function () {
            $(this).animate({marginTop: '-10px'}, 200).animate({marginTop: '0px'}, 200);
            returm:false //TODO Марат, тут точно должно быть так?
        }, function () {
            $(this).animate({marginTop: '0px'}, 200);
            returm:false
        });
    });
</script>

<script>
    function scrollToMenu(id) {
        $('html, body').animate({
            scrollTop: $("#header_" + id).offset().top
        }, 1000);
    }

    $(window).load(function () {
        if (window.location.hash) {
            scrollToMenu(window.location.hash.substr(1));
        }
    })

    $(document).ready(function () {
        $(".menu_items").live('click', function () {
            var menu_id = 'menu_' + $(this).attr("id");
            scrollToMenu(menu_id);
            history.pushState({param: 'Value'}, '', '#' + menu_id);
        });
        $(".page-up").live('click', function () {
            $('html, body').animate({scrollTop: 0}, 500);
            return false
        });
    });
</script>

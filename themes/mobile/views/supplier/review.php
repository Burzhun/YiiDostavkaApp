<?
//Yii::app()->clientScript->registerScriptFile('//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js');
Yii::app()->clientScript->registerScriptFile('/js/cusel.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.mousewheel.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.jscrollpane.js');
Yii::app()->clientScript->registerScriptFile('/js/sticky.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.mousewheel.js');
Yii::app()->clientScript->registerScriptFile('/js/scroll/jquery.jscrollpane.js');
Yii::app()->clientScript->registerCssFile('/js/scroll/jquery.jscrollpane.css');
Yii::app()->clientScript->registerScriptFile('/js/message/Flash.js');
Yii::app()->clientScript->registerCssFile('/js/message/message.css');
//	Yii::app()->clientScript->registerScriptFile('/js/jquery.noty.js');

$warning = $partner->isClosed(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        jQuery(function () {
            jQuery('.scroll-pane').jScrollPane();
        });
    });
</script>

<script type="text/javascript">
    // $(document).ready(function (){
    // 	$('.bascet').stickyfloat();
    // });
</script>


<script type="text/javascript">
    $(document).ready(function () {
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
        <? if ($partner->status == 0 && $partner->self_status == 0) { ?>
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
                    /** @var Partner[] $partners */
                    $partners = $partner->getOpenedPartners();
                    foreach ($partners as $partner1) { ?>
                        <a class="blocked-partner-other" href="<?= $partner1->createUrl() ?>">
                            <img src="/upload/partner/<?= $partner1->img; ?>">

                            <p style="font-family: Tahoma;">
                                <?= $partner1->name; ?>
                            </p>
                        </a>
                    <? } ?>
                </div>

                <style>
                    .partner-disable-msg-block-mobile a.blocked-partner-other {
                        display: inline-block;
                        width: 60px;
                        height: 70px;
                        text-align: center;
                        margin-top: 20px;
                        margin-right: 4%;
                        text-decoration: none;
                    }

                    .partner-disable-msg-block-mobile a.blocked-partner-other img {
                        float: none;
                        width: 70px;
                    }

                    .blocked-partner-msg-title {
                        font-size: 13px;
                        margin-top: 13px;
                    }

                    .blocked-partner-other p {
                        font-family: 'PT Sans';
                        color: black;
                        font-weight: bold;
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
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>/info">Доп. инфо</a>
            <a href="/restorany/<?= Yii::app()->request->getParam('supplerName') ?>/review" class='shopOpenNavActive'>Отзывы</a>
        </div>

    </div>
</main>
<? if ($warning) return; // @TODO Что тут происходит ?>
<div class="body-bg bg-none"></div>
<div id="page">
    <div id="content-page">

        <form id="review-form-front" action="" method="POST" class="shopOpenList">
            <div class="reviewLeft">

                <div class="reviewForm">
                    <div class="contentBlock">
                        <textarea class="contentContainer" name="Review[content]"></textarea>
                    </div>
                </div>
            </div>

            <div class="reviewRight">
                <div class="dBlock">
                    <div class="golosBlock reviewBlock">
                        <div class="reviewTooltip">

                            <div class="strelka"></div>
                        </div>
                        <label class="positive reviewContainer"><input type="radio" name="Review[review]"
                                                                       value="1"/></label>
                        <label class="negative reviewContainer"><input type="radio" name="Review[review]"
                                                                       value="2"/></label>

                        <input id="submit" class="button_order" type="submit" name="" value="отправить"
                               style="float: right;">
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <input type="hidden" name="supplerName" value="<?= $partner->name; ?>">
        </form>

        <div style="clear:both"></div>

        <div class="reviewLeft">
            <div id="reviewBlock">
                <div id="reviewContent">
                    <? $this->renderPartial('_reviewPartial', array('dataProvider' => $dataProvider)) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notify begin -->
<div class="success-mess message" id="notify_success">
    <span class="icon"></span>
	 
	 <span class="success-message">
		Ваш отзыв успешно сохранен и ожидает модерации!
	</span>
</div>
<div class="error-mess message" id="notify_error">
    <span class="icon"></span>
	 
	 <span class="error-message">
		<ul>
        </ul>
	</span>
</div>
<!-- Notify end -->

<script type="text/javascript">

    $('.authLink').click(function () {
        if ($('.user').hasClass('active-pull'))
            $('.user').click();
    });


    $('.regLink').click(function () {
        if ($('.user').hasClass('active-pull'))
            $('.user').click();
        setTimeout(function () {
            $('.reg-button-top').trigger('click')
        }, 500);
    });

    $(document).ready(function () {
        $(document).on('click', '.golosBlock label.positive', function () {//alert('1');
            $('.golosBlock label').removeClass('golosCurent');
            $('.golosBlock label.positive').addClass('golosCurent');
        })

        $('.golosBlock label.negative').click(function () {//alert('2');
            $('.golosBlock label').removeClass('golosCurent');
            $('.golosBlock label.negative').addClass('golosCurent');
        })
    })

    var partner_id = <?=$partner->id;?>;
    $('#submit').click(function () {
        $.ajax({
            url: '/restorany/review/<?=$partner->name;?>',
            type: "post",
            cache: false,
            data: $('#review-form-front').serialize(),
            dataType: "json",
            success: function (data) { //console.log(data);
                if (data.status == "ok") {
                    $('.reviewForm textarea').val('');
                    $("#review-form-front").hide();
                    $("#notify_success").notify();
                } else if (data.status == "er") {
                    for (i in data.errors) { //alert('#'+i+'Block');
                        $('.' + i + 'Container').css({'border-color': 'red'});
                        $("." + i + 'Block .reviewTooltip').fadeIn(); //console.log("."+i+'Block');
                    }
                }
            }
        });
        return false;
    });

    $('.contentContainer').on('input', function () { //console.log($(this).css('border-color'));
        if ($(this).css('border-color') == 'rgb(255, 0, 0)') {
            $(this).css({'border-color': '#dbdbdb'});
            $('.contentBlock .reviewTooltip').fadeOut();
        }
    })

    $('.reviewContainer').on('click', function () { //console.log($(this).css('border-color'));
        if ($('.reviewContainer').css('border-color') == 'rgb(255, 0, 0)') {
            $('.reviewContainer').css({'border-color': '#dbdbdb'});
            $('.reviewBlock .reviewTooltip').fadeOut();
        }
    })

    //--filterReview begin------
    $('.likeReview').click(function () {
        filterReview('positive', this)
        return false
    })

    $('.disLikeReview').click(function () {
        filterReview('negative', this)
        return false
    })

    $('.allReview').click(function () {
        filterReview('all', this);
        return false;
    })

    function filterReview(review, clickObj) {
        $.ajax({
            url: '/restorany/filterReview',
            type: "get",
            cache: false,
            data: review + "&partner_id=" + partner_id,
            dataType: "html",
            beforeSend: function () {
                $('#loadMask').show();
            },
            success: function (data) { //console.log(data);
                $('#loadMask').hide();
                $('.reviewNav a').removeClass('activeReview');
                $(clickObj).addClass('activeReview');
                $('#reviewContent').html(data);
            }
        });
    }
    //--filterReview end------
</script>

<script type="text/javascript">

    jQuery(document).ready(function () {
        var params = {
            changedEl: ".lineForm select",
            visRows: 5,
            scrollArrows: true
        }
        cuSel(params);
        var params = {
            changedEl: "#city",
            scrollArrows: false
        }
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
            <?if(!$warning){?>
            getOrder();
            <?}else{?>
            getWarning();
            <?}?>
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
                $("#popup").append(basketData);
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

    function getOrder() {
        $.ajax({
            url: '/restorany/action/order',
            type: "post",
            dataType: 'JSON',
            cache: false,
            data: {"partner":<? echo $partner->id?>},
            success: function (data) {
                //нада поставить false true
                if (data['error']) {
                    $("#popup_order_errors").html("");
                    $("#popup_order_errors").append(data['error']);
                }
                if (data['page']) {

                    $("#popup").append(data['page']);
                    $("#pop-up-bascet").remove();
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

    $('body').on('click', '#yt1000', function () {
        jQuery.ajax({
            'type': 'post',
            'dataType': 'JSON',
            'success': function (data) {
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
            'data': jQuery("#orderForm").serialize()
        });
        return false;
    });


    //создаем строку для подстановки в запрос : если заказывает зареганный то ищем по ID, если нет, то по сессии
    <? $condition = Yii::app()->user->role != User::USER ? " AND session_id='".Yii::app()->session->sessionId."'" : " AND user_id='".Yii::app()->user->id."'";?>
    //проверяем есть ли в корзине товары от данного поставщика
    <? if(CartItem::model()->count(array("condition"=>"partner_id=".$partner->id.$condition))){?>
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
        block.show();
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
        $(this).hide();
    }
</script>

<script type="text/javascript">
    $(function () {
        $(".middle-nav li img ").hover(function () {
            $(this).animate({marginTop: '-10px'}, 200).animate({marginTop: '0px'}, 200)
            returm:false
        }, function () {
            $(this).animate({marginTop: '0px'}, 200)
            returm:false
        });
    });
</script>
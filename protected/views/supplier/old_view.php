<div class="page">
    <div class="blok">
        <p class="crumbs"><a href="/">главная</a> / <?php echo $partner->name ?></p>

        <div class="restaurant_cart">
            <div class="restaurant_cart_left">
                <div class="logo_preview">
                    <?php if (!empty($partner->img)) { ?>
                        <img src="/upload/partner/<?php echo $partner->img ?>"
                             alt="доставка еды, махачкала, дагестан, <?php echo $partner->name ?>"/>
                    <?php } else { ?>
                        <img src="/images/default.jpg"
                             alt="доставка еды, махачкала, дагестан, <?php echo $partner->name ?>"
                             style='max-width:200px;max-height:200px'/>
                    <?php } ?>
                </div>
                <div class="info">
                    <h3><?php echo $partner->name ?></h3>
                    <span class="categories"><p><?php echo $partner->text ?></p></span>
                    <ul>
                        <li>Минимальная сумма доставки<br/>

                            <p class="price"><?php echo $partner->min_sum ? $partner->min_sum . ' ' . City::getMoneyKod() : ''; ?>
                                : 'Нет'?></p></li>
                        <li>Стоимость достаки<br/>

                            <p class="price"><?php echo $partner->delivery_cost ? $partner->delivery_cost . ' ' . City::getMoneyKod() : ''; ?>
                                : 'Бесплатно'?></p></li>
                        <li>Время достаки<br/><br/>

                            <p class="price"><?php echo $partner->delivery_duration ?><?php echo City::getMoneyKod(); ?></p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="restaurant_cart_right">
                <div class="address_mag">
                    <p> адрес: Дагестан, г.<?php echo $partner->city->name; ?><br>
                        <?php echo $partner->address; ?><br>
                        рабочие дни:<?php if ($partner->day1) { ?> пн <?php } ?>
                        <?php if ($partner->day2) { ?> вт <?php } ?>
                        <?php if ($partner->day3) { ?> ср <?php } ?>
                        <?php if ($partner->day4) { ?> чт <?php } ?>
                        <?php if ($partner->day5) { ?> пт <?php } ?>
                        <?php if ($partner->day6) { ?> сб <?php } ?>
                        <?php if ($partner->day7) { ?> вс <?php } ?>
                        <br>
                        рабочее время: с <?php echo date('H:i', strtotime($partner->work_begin_time)); ?>
                        до <?php echo date('H:i', strtotime($partner->work_end_time)); ?>
                    </p>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $(".menu_items").click(function () {
                    $('html, body').animate({
                        scrollTop: $("#header_" + $(this).attr("id")).offset().top
                    }, 500);
                });
                $("#page-up").click(function () {
                    $('html, body').animate({scrollTop: 0}, 500);
                });
            });
        </script>

        <div class="blok_category2">
            <div id="firstpane" class="menu_list">
                <?php foreach ($menu as $m) { //вывод меню?>
                    <p <? if ($m->have_subcatalog) { //проверяем есть ли подменю, для оформления пункта?>
                        class="menu_head"
                        style="background:url(../images/down.png) center right no-repeat" <?php //стрелка сбоку указывает что есть подменю?>
                    <?php } else { ?>
                        class="menu_head menu_items" <?php // menu_items это кликабельный пункт?>
                        style="background:none;" <?php // стрелки не будет?>
                        id="menu_<?php echo $m->id // задаем id для яваскрипта, чтоб потом можно было перейти на соответствующие товары в списке?>"
                    <?php } ?>>
                        <?php echo $m->name // ввывод названия?>
                    </p>
                    <?php if ($m->have_subcatalog) { // опять проверяем наличие подкаталогов, уже с целью вывести подменю?>
                        <div class="menu_body">
                            <?php foreach ($m->submenu as $submenu) { // проходимся по всем пунктам подменю?>
                                <a class="menu_items" id="menu_<?php echo $submenu->id ?>"
                                   style="cursor:pointer"><?php echo $submenu->name ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <!--Code for menu ends here-->
        </div>
        <div class="page_the_category2">
            <?php foreach ($menu as $m) { // вывод каталога товаров?>
                <?php if ($m->have_subcatalog) { // проверяем наличие подкаталога?>
                    <?php foreach ($m->submenu as $submenu) { // выводим все подменю?>
                        <div style="float:none;clear:both"></div>
                        <h1 id="header_menu_<?php echo $submenu->id /* задаем id для того чтобы сюда можно было попасть нажав на пункт меню*/ ?>">
                            <?php echo $submenu->name ?>
                        </h1>
                        <?php $i = 0; ?>
                        <?php foreach ($submenu->goods as $goods) { // вывод товаров?>
                            <?php $is_right = $i % 3; ?>
                            <div class="blok_order" id="button1">
                                <div style="text-align:-webkit-center">
                                    <img class="img_order"
                                         src="/upload/<?php echo $goods->img == "" ? ($partner->img == "" ? "/images/default.jpg" : "partner/" . $partner->img) : "goods/" . $goods->img; ?>" <?php if ($is_right){ ?>
                                         onmouseover="mouse_over_right(this)" onmouseout="mouse_out_right(this)"
                                         <?php }else{ ?>onmouseover="mouse_over_left(this)"
                                         onmouseout="mouse_out_left(this)" <?php } ?>">
                                </div>
                                <h4><?php echo $goods->name ?></h4>

                                <form>
                                    <p class="product_counter_container"
                                       id="product_<?php echo $goods->id ?>_counter_container">
                                        <b><?php echo $goods->price; ?></b> <?= City::getMoneyKod(); ?> ;?><a
                                            class="showed_product_counter"
                                            href="javascript:void(0);"><span
                                                class="showcount">1</span> <?php echo $goods->unit ?></a>
                                    </p>

                                    <div class="update_count_product_container">
                                        <span class="minusupdate"><img
                                                src="/images/basket_minus.png"
                                                style="padding:0"></span>
                                        <input class="new_count" type="text" value="1" style="width:30px"
                                               maxlength="2">
                                        <span class="plusupdate" width="100px"><img
                                                src="/images/basket_plus.png"
                                                style="padding:0"></span>
                                        <button type="button" class="updatecount_ok"
                                                product_id="<?php echo $goods->id; ?>"> Ok
                                        </button>
                                    </div>
                                    <input class="productcounter" id="productcount_<?php echo $goods->id; ?>"
                                           type="hidden" value="1">

                                    <a class="button_order" href="javascript:void(0)"
                                       onclick="order(<?php echo $goods->id; ?>)"></a>
                                </form>
                                <div
                                    class="<?php echo $is_right ? "productinfoblockright" : "productinfoblockleft"; ?>"><?php //появляющаяся сбоку карточка товара?>
                                    <?php if ($goods->img) { ?>
                                        <img
                                            src="/upload/goods/<?php echo $goods->img; ?>">
                                    <?php } else { ?>
                                        <?php if (!empty($partner->img)) { ?>
                                            1<img
                                                src="/upload/partner/<?php echo $partner->img; ?>">
                                        <?php } else { ?>
                                            <img src="/images/default.jpg">
                                        <?php } ?>
                                    <?php } ?>
                                    <div style="clear:both;float:none;"></div>
                                    <div>
                                        <p><?php echo $goods->text; ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php $i++; ?>
                        <?php } ?>
                    <?php } ?>
                <?php } else { // если нет подкаталога сразу выводим товары?>
                    <div style="float:none;clear:both"></div>
                    <h1 id="header_menu_<?php echo $m->id ?>"><?php echo $m->name ?></h1>
                    <?php $i = 0; ?>
                    <?php foreach ($m->goods as $goods) { ?>
                        <?php $is_right = $i % 3; ?>
                        <div class="blok_order" id="button1">
                            <div style="text-align:-webkit-center">
                                <img class="img_order"
                                     src="<?php echo $goods->img == "" ? ($partner->img == "" ? "/images/default.jpg" : "/upload/partner/" . $partner->img) : "/upload/goods/" . $goods->img; ?>" <?php if ($is_right){ ?>
                                     onmouseover="mouse_over_right(this)" onmouseout="mouse_out_right(this)"
                                     <?php }else{ ?>onmouseover="mouse_over_left(this)"
                                     onmouseout="mouse_out_left(this)" <?php } ?>">
                            </div>
                            <h4><?php echo $goods->name ?></h4>

                            <form>
                                <p class="product_counter_container"
                                   id="product_<?php echo $goods->id ?>_counter_container">
                                    <b><?php echo $goods->price; ?></b> <?= City::getMoneyKod(); ?> ;?><a
                                        class="showed_product_counter"
                                        href="javascript:void(0);"><span
                                            class="showcount">1</span> <?php echo $goods->unit ?></a>
                                </p>

                                <div class="update_count_product_container">
                                    <span class="minusupdate"><img
                                            src="/images/basket_minus.png"
                                            style="padding:0"></span>
                                    <input class="new_count" type="text" value="1" style="width:30px" maxlength="2">
                                    <span class="plusupdate"><img
                                            src="/images/basket_plus.png"
                                            style="padding:0"></span>
                                    <button type="button" class="updatecount_ok" product_id="<?php echo $goods->id; ?>">
                                        Ok
                                    </button>
                                </div>
                                <input class="productcounter" id="productcount_<?php echo $goods->id; ?>" type="hidden"
                                       value="1">

                                <a class="button_order" href="javascript:void(0)"
                                   onclick="order(<?php echo $goods->id; ?>)"></a>
                            </form>
                            <div
                                class="<?php echo $is_right ? "productinfoblockright" : "productinfoblockleft"; ?>"><?php //появляющаяся сбоку карточка товара?>
                                <?php if ($goods->img) { ?>
                                    <img
                                        src="/upload/goods/<?php echo $goods->img; ?>">
                                <?php } else { ?>
                                    <?php if (!empty($partner->img)) { ?>
                                        <img
                                            src="/upload/partner/<?php echo $partner->img; ?>">
                                    <?php } else { ?>
                                        <img src="/images/default.jpg">
                                    <?php } ?>
                                <?php } ?>
                                <div style="clear:both;float:none;"></div>
                                <div>
                                    <p><?php echo $goods->text; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <div style="float:none;clear:both"></div>
            <a id="page-up" href="javascript:void(0)"><img src="/images/up.png"></a>
        </div>
    </div>
</div>

<?php //здесь размещается выплывающая панелька корзины?>
<div id="basket" class="basket"></div>

<script>
    //создаем строку для подстановки в запрос : если заказывает зареганный то ищем по ID, если нет, то по сессии
    <?php $condition = Yii::app()->user->role != User::USER ? " AND session_id='" . Yii::app()->session->sessionId . "'" : " AND user_id='" . Yii::app()->user->id . "'";?>
    //проверяем есть ли в корзине товары от данного поставщика
    <?php if(CartItem::model()->count(array("condition" => "partner_id=" . $partner->id . $condition))){?>
    getBasket();
    <?php }?>

    //функция обрабатывает заказ
    function order(p_id) {
        //получаем id заказываемого товара
        var value = $('#productcount_' + p_id).attr('value');
        $.ajax({
            url: '<?php echo CController::createUrl('/cart/add');?>',
            type: "post",
            cache: false,
            data: {"product": p_id, "count": value},
            success: function (data) {
                //обновляем корзину
                getBasket();
            }
        });
    }

    function getBasket() {
        $.ajax({
            url: '<?php echo CController::createUrl('/cart/basket');?>',
            type: "post",
            cache: false,
            data: {"partner":<?php echo $partner->id;?>},
            success: function (basketData) {
                $("#basket").html(basketData);
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
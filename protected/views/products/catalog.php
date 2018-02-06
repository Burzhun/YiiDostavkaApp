<?
/**
 * @var Specialization[] $specs
 * @var Partner[] $model
 * @var string $h1
 */
?>
<?php $ajaxCheckSpecsUrl = CController::createUrl('/products/ajaxCheckSpecs'); ?>
<div class="body-bg bg-none"></div>
<?php Yii::app()->clientScript->registerScriptFile('/js/jquery.checkbox.js'); ?>
<script>
    $(document).ready(function () {
        $(".page-up").click(function () {
            $('html, body').animate({scrollTop: 0}, 500);
        });
    });
</script>
<div id="page" class="page">
    <div class="blok">
        <form id="filter">
            <p class="crumbs" style=""><a href="/">главная</a> / <a
                    class="crumbs_a"
                    style="max-width: 195px; overflow: hidden; display: inline-block; height: 16px;"><?php echo $h1 ?></a>
            </p>
            <div class="blok_category">
                <ul class="dishes">
                    <input type="hidden" name="Direction" value="<?php echo $direction ?>">
                    <?php foreach ($specs as $s) { ?>
                        <li>
                            <label>
                                <span class="niceCheck" onclick="js:changeCheck(this)"
                                      style="background-position: 0 <?php echo (!empty($_GET) && $_GET['id'] == $s->tname) ? "-19px" : "0px" ?>">
                                    <?php echo CHtml::checkBox('Spec[' . $s->id . ']', !empty($_GET) && $_GET['id'] == $s->tname ? true : false, array(
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => $ajaxCheckSpecsUrl,
                                            'update' => '#suppliers',
                                        ),
                                        'class' => 'filter_supplier'
                                    )); ?>
                                    <span><?php echo $s->name; ?></span>
                                </span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <div class="line_cat"></div>
                <p class="h2">Критерии</p>
                <ul class="dishes">
                    <li>
                        <label>
                            <span class="niceCheck" onclick="js:changeCheck(this)" id="niceCheckbox7">
                                <?php echo CHtml::checkBox('Criteria[1]', '', array(
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => $ajaxCheckSpecsUrl,
                                        'update' => '#suppliers',
                                    ),
                                    'class' => 'filter_supplier'
                                )); ?>
                                <span>с бесплатной доставкой</span>
                            </span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <span class="niceCheck" onclick="js:changeCheck(this)" id="niceCheckbox8">
                                <?php echo CHtml::checkBox('Criteria[2]', '', array(
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => $ajaxCheckSpecsUrl,
                                        'update' => '#suppliers',
                                    ),
                                    'class' => 'filter_supplier',
                                )); ?>
                                <span>без мин. суммы заказа</span>
                            </span>
                        </label>
                    </li>
                    <li>
                        <label>
                            <span class="niceCheck" onclick="js:changeCheck(this)" id="niceCheckbox9">
                                <?php echo CHtml::checkBox('Criteria[3]', '', array(
                                    //'name'=>$s->id."_2",
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => $ajaxCheckSpecsUrl,
                                        'update' => '#suppliers',
                                    ),
                                    'class' => 'filter_supplier',
                                )); ?>
                                <span>халяль</span>
                            </span>
                        </label>
                    </li>
                </ul>
                <br><br>

                <div id="SeoTextLeft">
                    <? if ($this->domain->id > 1) { ?>
                        <h1 style="font-size:16px;margin-top:0px;margin-bottom:0px;"><? echo $h1 ? $h1 : "Заказ и доставка еды по
                            Махачкале"; ?></h1>
                    <? } else { ?>
                        <h1 style="font-size:16px;margin-top:0px;margin-bottom:0px;"><? echo $h1 ? $h1 : "Заказ и доставка еды по
                            Баку"; ?></h1>
                    <? } ?>
                    <p><? if ($seo_spec_text) {
                            echo $seo_spec_text;
                        } else { ?>
                            Доставка еды на дом или доставка еды в офис — это прекрасный
                            способ быстро, вкусно и полезно пообедать на работе
                            с коллегами или поужинать дома после тяжелого дня.
                            Заказав еду на дом, можно также организовать настоящий
                            романтический вечер с любимым человеком, не тратя время
                            на приготовление ужина. А еда в офис поможет скрасить
                            трудности рабочего дня за вкусным обедом в компании коллег.
                            Сайт Доставка05 помогает оперативно выполнить каждый поступивший
                            заказ на доставку великолепной еды на дом или в офис в
                            <? if ($this->domain->id > 1) { ?>
                                Махачкале.
                            <? } else { ?>
                                Баку.
                            <? } ?>
                        <? } ?>
                    </p>
                </div>
                <h2>Поделиться с друзьями</h2>
                <script type="text/javascript">
                    (function () {
                        if (window.pluso)if (typeof window.pluso.start == "function") return;
                        if (window.ifpluso == undefined) {
                            window.ifpluso = 1;
                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                            s.type = 'text/javascript';
                            s.charset = 'UTF-8';
                            s.async = true;
                            s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                            var h = d[g]('body')[0];
                            h.appendChild(s);
                        }
                    })();
                </script>
                <div class="pluso" data-background="transparent"
                     data-options="medium,square,line,horizontal,nocounter,theme=04"
                     data-services="vkontakte,facebook,google,odnoklassniki,email"></div>
            </div>
            <div style="float:none;clear:both"></div>
        </form>
        <div class="nav-rest">
            <span id="opened_rest"
                <? if (Yii::app()->request->cookies['open_rest']->value == 'opened'){ ?>class="rest-active"<? } ?>>Открытые</span>
            |
            <span id="all_rest"
                <? if (Yii::app()->request->cookies['open_rest']->value == 'all'){ ?>class="rest-active"<? } ?>>Все</span>
        </div>
        <div class="page_the_category" id="suppliers">
            <?php echo $this->renderPartial('supplierList', array('model' => $model)); ?>
        </div>
    </div>
</div>
<script>
    $(document).on("click", "#all_rest", function (event) {
        if (!$("#all_rest").hasClass("rest-active")) {
            $.ajax({
                url: '<? echo CController::createUrl('/site/allRest')?>',
                type: "post",
                dataType: "json",
                cache: false,
                data: $("#filter").serialize(),
                success: function (data) {
                    $("#all_rest").toggleClass("rest-active");
                    $("#opened_rest").toggleClass("rest-active");
                    $.ajax({
                        url: '<? echo $ajaxCheckSpecsUrl?>',
                        type: "post",
                        cache: false,
                        data: $("form").serialize(),
                        success: function (data2) {
                            $("#suppliers").html(data2);
                        }
                    });
                }
            });
        }
    });

    $(document).on("click", "#opened_rest", function (event) {
        if (!$('#opened_rest').hasClass('rest-active')) {
            $.ajax({
                url: '<? echo CController::createUrl('/site/openRest')?>',
                type: "post",
                dataType: "json",
                cache: false,
                data: $("#filter").serialize(),
                success: function (data) {
                    $("#opened_rest").toggleClass("rest-active");
                    $("#all_rest").toggleClass("rest-active");
                    $.ajax({
                        url: '<? echo $ajaxCheckSpecsUrl?>',
                        type: "post",
                        cache: false,
                        data: $("form").serialize(),
                        success: function (data2) {
                            //alert(data);
                            $("#suppliers").html(data2);
                        }
                    });
                }
            });
        }
    });
</script>
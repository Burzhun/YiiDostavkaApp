<?
/** @var Partner[] $model */
foreach ($model as $m) {
    $isClosed = $m->isClosed();
    if (isset(Yii::app()->request->cookies['open_rest']) && Yii::app()->request->cookies['open_rest']->value == 'opened' && $isClosed) {
        continue;
    }?>
    <a href="/restorany/<? echo $m->tname ?>" class="mainBox shop <? if ($m->soon_opening) {
        echo "soon_opening";
    } ?>">
        <? if ($m->action) { ?>
            <img src="/images/action.png" style="position: absolute;left: -4px;top:-4px;z-index: 3000;width:60px;">
        <? } ?>
        <div class="padding10">
            <div class="shopBlock">
                <div class="shopImg">
                    <img style="max-width:70px;" src="<? if ($m->img) {
                        echo "/upload/partner/" . $m->img;
                    } else {
                        echo "/images/default.jpg";
                    } ?>" alt="доставка еды, махачкала, дагестан, <? echo $m->name ?>"
                         title="<? echo $m->name ?>, доставка еды, махачкала, дагестан">
                </div>

                <div class="shopRight">
                    <div class="shopTitle"><?= $m->name ?></div>
                    <div class="shopShort"><?= $m->text ?></div>

                    <div style='clear:both'></div>

                    <div class="shopAttr">
                        <span><?= $m->min_sum ? $m->min_sum . ' ' . City::getMoneyKod($this->domain) : 'Нет' ?></span>
                        Минимальная <br> сумма доставки
                    </div>

                    <div class="shopAttr">
                        <span><?= $m->delivery_cost ? $m->delivery_cost . ' ' . City::getMoneyKod($this->domain) : 'Бесплатно' ?></span>
                        Стоимость <br> доставки
                    </div>

                    <div class="shopAttr">
                        <span><?= $m->delivery_duration ?></span>
                        Время <br> доставки
                    </div>
                    <? if ($m->use_kassa) { ?>
                        <div class="shopAttr">
                            <img width="50px" class="visa_mastercard_logo" src="/images/visa-master.png"
                                 style="margin-bottom: 3px;"><br>
                            Оплата картой<br> онлайн
                        </div>
                    <? }
                    if ($isClosed) { ?>
                        <div class="cafe-warning-mobile">
                            Ресторан откроется через <span><?= $m->howLongWill(); ?></span>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
    </a>
<? } ?>
<? if (Yii::app()->request->cookies['open_rest']->value == 'opened') { ?>
    <div style="text-align: center;"><br>
        <span style="font-size: 20px;color:#444444;">Некоторые рестораны еще закрыты</span><br><br><br>
        <button id="show_all_button">Показать все</button>
    </div>
    <script>
        $("#show_all_button").click(function () {
            $.ajax({
                url: '<? echo CController::createUrl('/site/allRest')?>',
                type: "post",
                //dataType:"json",
                cache: false,
                //data:$("#filter").serialize(),
                success: function (data) {
                    $("#all_rest").toggleClass("rest-active");
                    $("#opened_rest").toggleClass("rest-active");
                    $.ajax({
                        url: '<? echo CController::createUrl('/products/ajaxCheckSpecsMobile')?>',
                        type: "post",
                        cache: false,
                        data: $("form").serialize(),
                        success: function (data2) {
                            $(".content").html(data2);
                        }
                    });
                }
            });
        });
    </script>
<? } ?>
<div class="home_hover">
    <div class="home">
        <div id='qq' class="center_page center_page_fon">
            <div class="price-left-block">
                <a href="/restorany">
                    <div class="logo_catalog logo_catalog1"></div>
                </a>
                <a href="/restorany"><p>ВСЕ РЕСТОРАНЫ</p></a>
                <img src="/images/002img.png" width="91" height="83">
                <ul class="price-left">
                    <li><a href="/restorany/pizza">Пицца</a></li>
                    <li><a href="/restorany/shashlyk">Шашлыки</a></li>
                    <li><a href="/restorany/sushi">Суши</a></li>
                    <li><a href="/restorany/rolly">Роллы</a></li>
                    <li><a href="/restorany/burger">Бургеры</a></li>
                </ul>
            </div>

            <div class="price-center">
                <a href='#1' class='centerleft'>
                    <img src='images/sh1.png' class='sh1'>
                    <img src='images/centerleft.png'>
                </a>

                <div class="centercenter">
                    <div class="order-box">
                        <p class="zakaz">ЗАКАЗОВ </p>

                        <div class='zakazCount'>
                            <?php echo $this->orders_today; ?>
                        </div>
                        <p class="zakaz">НА СЕГОДНЯ</p>
                    </div>
                </div>

                <a href='#2' class='centerright'>
                    <img src='images/sh2.png' class='sh2'>
                    <img src='images/centerright.png'>
                </a>
            </div>
            <div class="price-right-block">

                <a href="/magaziny">
                    <div class="logo_catalog logo_catalog3"></div>
                </a>
                <a href="/magaziny"><p>ВСЕ МАГАЗИНЫ</p></a><img
                    src="/images/001img.png" height="83">
                <ul class="price-right">
                    <li><a href="/magaziny/fermer">Фермерские</a></li>
                    <li><a href="/magaziny/molochnye">Молочные</a></li>
                    <li><a href="/magaziny/delicatessen">Деликатесы</a></li>
                    <li><a href="/magaziny/confectionery">Выпечка</a></li>
                    <li><a href="/magaziny/gastronomyiya">Гастрономия</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class='page_bottom_content '>
    <? $vip_partners = Partner::model()->cache(10000)->findAll(array('condition' => 'vip=1 AND city_id = ' . City::getUserChooseCity())) ?>
    <div class='partner-box' id="vip_rest">
        <? if ($vip_partners) { ?>
            <div class='head2'>Рестораны</div>
            <div class='partnerBlock'>
                <ul>
                    <? foreach ($vip_partners as $v) { ?>
                        <li>
                            <a href='/restorany/<?php echo $v->tname ?>'>
								<span class='partner_img'>
								<?php if ($v->logo) { ?>
                                    <img style="max-width:100px;max-height:100px;border-radius:4px;"
                                         src="/upload/partner/<?php echo $v->logo ?>"
                                         alt="доставка еды, махачкала, дагестан, <?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>, доставка еды, махачкала, дагестан"/>
                                <?php } else { ?>
                                    <img style="max-width:100px;max-height:100px;border-radius:4px;"
                                         src="/images/default.jpg"
                                         alt="доставка еды, махачкала, дагестан, <?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>, доставка еды, махачкала, дагестан"/>
                                <?php } ?>
								</span>
                            </a>
                        </li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>
    </div>
    <div id="contact-forms" class="partner-box">
        <div>
            <a href="https://itunes.apple.com/us/app/dostavka05/id999363875?l=ru&ls=1&mt=83" target="_blank">
                <img src="/images/appleAppPC.png">
            </a>
            <a href="https://play.google.com/store/apps/details?id=ru.diit.dostavka05" target="_blank">
                <img src="/images/googleAppPC.png">
            </a>
        </div>
    </div>
    <div id="contact-forms" class="partner-box">
        <div>
            <div id="callme" class="ds-form"></div>
            <a href="#" data-reveal-id="vopros" class="zvopros">Задать вопрос</a>

            <div style="clear: both"></div>
            <div style='color:#fff; font-size:14pt; margin-top: 15px; width: 490px; margin-left: -40px; '>Есть вопросы?
                Мы с радостью Вас проконсультируем!
            </div>
        </div>
    </div>
    <? $vip_rest_partners = Partner::model()->findAll(array('condition' => 'vip_rest=1 AND city_id = ' . City::getUserChooseCity())) ?>
    <div class='partner-box' id="vip_mag">
        <? if ($vip_rest_partners) { ?>
            <div class='head2'>Магазины</div>
            <div class='partnerBlock'>
                <ul>
                    <? foreach ($vip_rest_partners as $v) { ?>
                        <li>
                            <a href='/restorany/<?php echo $v->tname ?>'>
								<span class='partner_img'>
								<?php if ($v->logo) { ?>
                                    <img style="max-width:100px;max-height:100px;border-radius:4px;"
                                         src="/upload/partner/<?php echo $v->logo ?>"
                                         alt="доставка еды, махачкала, дагестан, <?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>, доставка еды, махачкала, дагестан"/>
                                <?php } else { ?>
                                    <img style="max-width:100px;max-height:100px;border-radius:4px;"
                                         src="/images/default.jpg"
                                         alt="доставка еды, махачкала, дагестан, <?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>, доставка еды, махачкала, дагестан"/>
                                <?php } ?>
								</span>
                            </a>
                        </li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>
    </div>

    <? $opros = Opros::model()->find(array('order' => 'RAND()')) ?>
    <? if (Yii::app()->session->get("stat_" . $opros->id) != Yii::app()->session->getSessionID()) { ?>
        <div id='output-opros' class='partner-box'>
            <form method="POST">
                <div class="oprosBox oprosLeft">
                    <?= $opros->name ?>
                </div>
                <div class="oprosBox oprosCenter">
                    <? foreach ($opros->otvety as $otvet) { ?>
                        <label>
                            <input type="radio" name="radio_name" value="<?= $otvet->id ?>"><?= $otvet->answer; ?>
                        </label>
                    <? } ?>
                </div>
                <div class="oprosBox oprosRight">
                    <? //<input type="submit" value='Проголосовать'>?>
                    <? echo CHtml::ajaxSubmitButton('Проголосовать', 'site/opros', array(
                        'type' => 'POST',
                        'update' => '#output-opros',
                    ),
                        array(
                            'type' => 'submit',
                        )); ?>
                </div>
            </form>
        </div>
    <? } ?>

    <div class="page_bottom">
        <div class="center_page_bottom">
            <div class="blok_page_boottom-left">
                <div class="logo_page_boottom"></div>
                <p>Dostavka 05.ru—Объединяет сотни служб доставки пиццы, суши, еды и продуктов в Единую Систему
                    Заказов.</p>

                <p>Бесплатный сервис призван— обеспечить жителям Махачкалы и Каспийска наилучшие условия ,для быстрого,
                    удобного и выгодного осуществления заказов.</p>
            </div>
            <div class="blok_page_boottom-center">
                <div class="blok_page_boottom-center-content">
                    <h6 class="o-nas"><a href="/pages/about">О НАС</a>

                        <p>подробная информация о нашем проекте</p></h6>
                </div>
                <div class="blok_page_boottom-center-content">
                    <h6 class="bonus"><a href="/bonus">БОНУСЫ</a>

                        <p>большой каталог приятных призов</p></h6>
                </div>
                <div class="blok_page_boottom-center-content">
                    <h6 class="blog"><a href="/blog">БЛОГ</a>

                        <p>актуальные новости из жизни проекта</p></h6>
                </div>
            </div>
            <div class="blok_page_boottom-right">
                <p class="p_title">Мы в интернете</p>
                <a href="http://ok.ru/group/54309315018757" target="_blank"><img src="./images/ok-img.png" width="45"
                                                                                 height="44" alt="ok"></a>
                <a href="http://vk.com/dostavka_05" target="_blank"><img
                        src="/images/vk-img.png" width="45" height="44" alt="ok"></a>
                <a href="https://twitter.com/dostavka_05" target="_blank"><img
                        src="/images/t-img.png" width="45" height="44" alt="ok"></a>
                <a href="http://instagram.com/dostavka05.ru" target="_blank"><img
                        src="/images/instagram.png" width="45" height="44"
                        alt="ok"></a>
                <a href="https://www.facebook.com/groups/788532444565542/" target="_blank"><img
                        src="/images/facebook.png" width="45" height="44"
                        alt="ok"></a>
                <a href="http://www.youtube.com/channel/UChSVTro_aZe6bwD26RMCOsg/feed" target="_blank"><img
                        src="/images/youtube.png" width="45" height="44" alt="ok"></a>
                <a href="/pages/company">КОМПАНИЯ</a><br> <a
                    href="/pages/partner-page">Раздел для партнеров</a>
            </div>
            <br>

            <div style="clear:both"></div>
            <div class="text_bottom" style="color:#fff">
                <h1>Доставка еды в Баку</h1>

                <p>Служба доставки еды Доставка05 представляет собой единую систему, которая позволяет делать заказы из
                    десятков кафе и ресторанов. Поисковой алгоритм учитывает заданные критерии. Сервис работает
                    бесплатно, без обязательной регистрации аккаунта.</p>

                <p>Мы предлагаем:</p>
                <ul>
                    <li>максимум простоты и удобства при заказе еды на дом — в базе представлены организации,
                        оказывающие услуги доставки;
                    </li>
                    <li>возможность сравнить условия и выбрать наиболее соответствующие и удобные для вас;</li>
                    <li>помощь и своевременное решение возникающих вопросов;</li>
                    <li>премии и бонусы за регулярное пользование услугами сервиса (вне зависимости от выбранной службы
                        доставки продуктов);
                    </li>
                    <li>реальные отзывы клиентов;</li>
                    <li>разнообразие кухонь и продуктов;</li>
                    <li>акции и спецпредложения от Доставки05 и компаний-партнеров.</li>
                </ul>
                <p><strong>Доставка еды</strong> осуществляется на условиях ресторана или пиццерии, в которой сделан
                    заказ, по Махачкале и Каспийску. Для каждой организации создана персональная страница, где можно
                    ознакомиться со сроками, расценками и т. д.</p>

                <h2>Как заказать еду с доставкой?</h2>

                <p>Полный перечень компаний, доставляющих продуктовые товары к дому клиента, перечислен на странице
                    «Еда». Используя фильтр, можно отсортировать организации по кухне, рецепту, выбрать бесплатную
                    доставку и т. д. </p>

                <h2>Приглашаем к сотрудничеству</h2>

                <p>Мы заинтересованы в расширении списка компаний, принимающих заказы еды на дом. Наши партнеры получают
                    собственное представительство в сети, рекламу на ТВ и в печатной прессе, счет только по числу
                    реальных клиентов. Подробности можно оговорить лично с оператором Доставки05. </p>
                <br>

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
        </div>
    </div>
</div>

<div style='display:none;background:#fff;color:#000;text-align:center'>
</div>
<div id="vopros" class="ds-form"></div>
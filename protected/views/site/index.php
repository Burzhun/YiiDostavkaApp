<div class="home_hover">
    <div class="home">
        <div id='qq' class="center_page">
            <div class="price-left-block">
                <a href="/restorany">
                    <div class="logo_catalog logo_catalog1"></div>
                </a>
                <a href="/restorany"><p>ВСЕ РЕСТОРАНЫ</p></a>
                <img src="/images/002img.png" width="91" height="83">
                <ul class="price-left">
                    <?$specs=Specialization::model()->findAll(array("order"=>"pos",'condition'=>"direction_id=1 and city_id=".City::getUserChooseCity(),"limit" =>5));
                    foreach($specs as $spec){?>
                        <li><a href="/<?=$spec->tname;?>"><?=$spec->name?></a></li>
                    <?}?>
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
    <?
    $cityList = City::getCityList($this->domain->id);

    if (City::getUserChooseCity() != '') {
        $city_id = City::getUserChooseCity();
    } else {
        $city_id = $cityList[0]->id;
    } ?>
    <? $vip_partners = Partner::model()->cache(10000)->findAll(array('condition' => 'vip=1 AND city_id = ' . $city_id)); ?>

    <div class='partner-box' id="vip_rest">
        <? if ($vip_partners) { ?>
            <div class='head2'>Рестораны</div>
            <div class='partnerBlock'>
                <ul>
                    <? foreach ($vip_partners as $v) { ?>
                        <li>
                            <a href='/restorany/<?php echo strtolower($v->tname) ?>'>
								<span class='partner_img'>
								<?php if ($v->logo) { ?>
                                    <img style="max-width:100px;max-height:100px;border-radius:4px;"
                                         src="/upload/partner/<?php echo $v->logo ?>"
                                         alt=" <?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>"/>
                                <?php } else { ?>
                                    <img style="max-width:100px;max-height:100px;border-radius:4px;"
                                         src="/images/default.jpg"
                                         alt="<?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>"/>
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

    <? $cityList = City::getCityList($this->domain->id);

    if (City::getUserChooseCity() != '') {
        $city_id = City::getUserChooseCity();
    } else {
        $city_id = $cityList[0]->id;
    } ?>
    <? $vip_rest_partners = Partner::model()->findAll(array('condition' => 'vip_rest=1 AND city_id = ' . $city_id)) ?>
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
                                         alt="<?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>"/>
                                <?php } else { ?>
                                    <img style="max-width:100px;max-height:100px;border-radius:4px;"
                                         src="/images/default.jpg"
                                         alt="<?php echo $v->name ?>"
                                         title="<?php echo $v->name ?>"/>
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
        <? if (isset($opros->otvety)) { ?>
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
    <? } ?>

    <div class="page_bottom">
        <div class="center_page_bottom">
            <div class="blok_page_boottom-left">
                <div class="logo_page_boottom"></div>
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
                <? if (Config::getSoc('odnoklassniki', $this->domain->id)) { ?>
                    <a href="<?= Config::getSoc('odnoklassniki', $this->domain->id) ?>" target="_blank"><img
                            src="/images/ok-img.png"  width="45" height="44" alt="ok"></a>
                <? } ?>
                <? if (Config::getSoc('vk', $this->domain->id)) { ?>
                    <a href="<?= Config::getSoc('vk', $this->domain->id) ?>" target="_blank"><img
                            src="/images/vk-img.png" width="45" height="44" alt="ok"></a>
                <? } ?>

                <? if (Config::getSoc('twitter', $this->domain->id)) { ?>
                    <a href="<?= Config::getSoc('twitter', $this->domain->id) ?>" target="_blank"><img
                            src="/images/t-img.png" width="45" height="44"
                            alt="ok"></a>
                <? } ?>

                <? if (Config::getSoc('instagram', $this->domain->id)) { ?>
                    <a href="<?= Config::getSoc('instagram', $this->domain->id) ?>" target="_blank"><img
                            src="/images/instagram.png" width="45" height="44"
                            alt="ok"></a>
                <? } ?>

                <? if (Config::getSoc('facebook', $this->domain->id)) { ?>
                    <a href="<?= Config::getSoc('facebook', $this->domain->id) ?>" target="_blank"><img
                            src="/images/facebook.png" width="45" height="44"
                            alt="ok"></a>
                <? } ?>

                <? if (Config::getSoc('youtube', $this->domain->id)) { ?>
                    <a href="<?= Config::getSoc('youtube', $this->domain->id) ?>" target="_blank"><img
                            src="/images/youtube.png" width="45" height="44" alt="ok"></a>
                <? } ?>

                <a href="/pages/company">КОМПАНИЯ</a><br> <a
                    href="/pages/partner-page">Раздел для партнеров</a>
            </div>
            <br>

            <div style="clear:both"></div>
            <div class="text_bottom" style="color:#fff">
                <?= Seo::model()->cache(10000)->find("url='/' and name='text' and city_id=".City::getUserChooseCity())->value; ?>
                <h2>Поделиться с друзьями</h2>
                <script type="text/javascript">					(function() {
                        if (window.pluso)if (typeof window.pluso.start == "function") return;
                        if (window.ifpluso==undefined) { window.ifpluso = 1;
                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                        s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                            s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
                            var h=d[g]('body')[0];	h.appendChild(s);}	})();
                </script>
                <div class="pluso" data-background="transparent" data-options="medium,square,line,horizontal,nocounter,theme=04" data-services="vkontakte,facebook,google,odnoklassniki,email"></div>
            </div>
        </div>
    </div>
</div>

<div style='display:none;background:#fff;color:#000;text-align:center'>
</div>
<div id="vopros" class="ds-form"></div>
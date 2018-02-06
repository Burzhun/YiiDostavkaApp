<?
$city_id = City::getUserChooseCity();
/** @var City $city */
$city = City::model()->findByPk($city_id);
?>
<script src="/js/swiper.jquery.min.js"></script>
<link rel="stylesheet" href="/css/swiper.min.css">
<ul class="catNav">
    <li data-id='1'>
        <a href="#" class='catNavActive'>
			<span class="catNavIcon" style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/iconCat1.png)'>
			</span>
            <span class="catNavTitle">еда</span>
        </a>
    </li>
    <li data-id='2'>
        <a href="#">
			<span class="catNavIcon" style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/iconCat2.png)'>
			</span>
            <span class="catNavTitle">продукты</span>
        </a>
    </li>
    <? /*<li>
		<a href="#"  >
			<span class="catNavIcon" style='background-image:url(<?=Yii::app()->theme->baseUrl;?>/img/iconCat3.png)'>
			</span>
			<span class="catNavTitle">акции</span>
		</a>
	</li>*/ ?>
</ul>
<div id="layer"></div>
<? $listCity = City::getCityList($this->domain->id); ?>
<div id="choose_city_menu" city-id="<?= City::getUserChooseCity(); ?>">
    <span class="title">Выберите город</span>
    <? foreach ($listCity as $city1) { ?>
        <span class="item" city-id="<?= $city->id; ?>"><?= $city1->name; ?></span>
    <? } ?>
</div>


<div class="city_selector">
    <span class="label">Город:</span>
    <span class="city">
        <? echo $city->name; ?>
    </span>
</div>
<? if ($this->domain->id == 1) { ?>
    <div id="rayon_selector">
        <span class="label">Район:</span>
    <span class="rayon">
        <? if (isset(Yii::app()->request->cookies['rayon'])) { ?>
            <? $rayon = Rayon::model()->findByPk(Yii::app()->request->cookies['rayon']->value); ?>
            <span><?= $rayon->name; ?></span>
        <? } else { ?>
            <span>Выберите район</span>
        <? } ?>
        <script>
            $('body').on('click', '#city_selector .rayon', function () {
                $('#rayon_selector_layer').show();
                $('#choose_rayon_menu').show();

            });
        </script>
    </span>
    </div>
<? } else { ?>
    <style>
        .city_selector {
            float: none;
            width: 100%;
        }
    </style>
<? } ?>
<? $d = date('Y-m-d');

$banners = Banner::model()->findAll(array(
    "condition" => "date_begin<='" . $d . "' AND date_stop>'" . $d . "' AND city_id=:city", "order" => "rand()", 'params' => array('city' => City::getUserChooseCity())));
if ($banners) {
    ?>
    <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <? foreach ($banners as $banner) { ?>
                <div class="swiper-slide">
                    <a href="<?= $banner->url; ?>"><img src="/themes/mobile/img/banners/<?= $banner->image ?>"
                                                        style="max-width: 100%;max-height:400px;display: block;margin:0 auto;"></a>
                </div>
            <? } ?>

        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->

        <!-- If we need scrollbar -->
    </div>
<? } ?>

<div id="tab1" class='tabs tabShow'>
    <main class="content">
        <ul class="mainNav" id="list-food">
            <li>
                <a style='background-position: 0px -75px;background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/mainNav7.jpg)'
                   href="/restorany"><span>Все Рестораны</span></a></li>
            <? $specs = Specialization::model()->findAll(array("order" => "pos", 'condition' => "direction_id=1 and city_id=" . City::getUserChooseCity(), "limit" => 5));
            foreach ($specs as $spec) {
                ?>
                <li><a style='background-position: 0px -75px;background-image:url(<?= $spec->getMobileImage(); ?>)'
                       href="/<?= $spec->tname; ?>"><span><?= $spec->name ?></span></a></li>
            <? } ?>
        </ul>
    </main>
</div>

<div id="tab2" class="tabs">
    <main class="content">
        <ul class="mainNav" id="list-food">
            <li><a style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/mainNav1.png)'
                   href="/magaziny"><span>Все Магазины</span></a></li>
            <li><a style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/mainNav2.png)'
                   href="/magaziny/fermer"><span>Фермерские</span></a></li>
            <li><a style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/mainNav3.png)'
                   href="/magaziny/molochnye"><span>Молочные</span></a></li>
            <li><a style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/mainNav4.png)'
                   href="/magaziny/delicatessen"><span>Деликатесы</span></a></li>
            <li><a style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/mainNav5.png)'
                   href="/magaziny/confectionery"><span>Выпечка</span></a></li>
            <li><a style='background-image:url(<?= Yii::app()->theme->baseUrl; ?>/img/mainNav6.png)'
                   href="/magaziny/gastronomyiya"><span>Гастрономия</span></a></li>
        </ul>
    </main>
</div>

<!-- .content -->

<script>
    $(function () {
        $(".catNav li").click(function () {
            $(".catNav li a").removeClass("catNavActive")
            $(this).find("a").addClass("catNavActive")

            $(".tabs").removeClass("tabShow");
            $("#tab" + $(this).data("id")).addClass("tabShow");
        })
    });
    $(document).ready(function () {
        var mySwiper = new Swiper('.swiper-container', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            autoplay: 2000,
            pagination: '.swiper-pagination',
            paginationClickable: true
        })
    });
</script>
<style>
    .swiper-pagination-bullet {
        background: #808080 none repeat scroll 0% 0%;
    }

    .swiper-pagination-bullet-active {
        background: #ff5e4f none repeat scroll 0% 0%;
    }

    .swiper-container-horizontal > .swiper-pagination {
        width: 95%;
        text-align: right;
    }

    .swiper-slide a img {
        max-height: 500px;
        display: block;
        margin: 0px auto;
        width: 100%;
    }
</style>
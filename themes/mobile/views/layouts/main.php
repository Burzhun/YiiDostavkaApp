<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title><?= $this->title ?></title>
    <meta name="keywords" content="<?= $this->keywords ?>">
    <meta name="description" content="<?= $this->description ?>">
    <?php
    $cs = Yii::app()->clientScript;
    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.js');
    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/popBox.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/mainScript.js', CClientScript::POS_END);
    $cs->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.cookie.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/application.js', CClientScript::POS_END);

    $cs->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css');
    ?>

    <link rel="apple-touch-icon" sizes="57x57" href="/touch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/touch-icon-ipad-retina.png">
    <script type="text/javascript">
        $(function () {
            if (!$.cookie('apple')) {
                $('.apple').slideDown('fast');
            }

            if (!$.cookie('android')) {
                $('.android').slideDown('fast');
            }

            $('body').on('click', '.apple .closeBanner', function (event) {
                $.cookie("apple", 1);
                $('.apple').slideUp('fast');
                event.preventDefault();
                /* Act on the event */
            });

            $('body').on('click', '.android .closeBanner', function (event) {
                $.cookie("android", 1);
                $('.android').slideUp('fast');
                event.preventDefault();
                /* Act on the event */
            });
        })
    </script>

    <? $s = Config::getValue('site_metriks', $this->domain->id);
    if (Yii::app()->user->id) {
        $s = str_replace("11', 'auto');", "11',{ 'userId': '" . Yii::app()->user->id . "'});", $s);
    }
    echo $s;
    ?>
    <script src="/js/google_analytics.js"></script>
</head>
<? if (CHERRY05) { ?>
    <style type="text/css">
        .header, .city_selector, .menu, .userDropBox, .main-page-order-phone {
            display: none;
        }

        .topNav .backLink {
            visibility: hidden;
        }

        #page {
            margin-top: 15px;
        }
    </style>
<? } ?>
<body>
<div id="layer" closable="0"></div>
<? $listCity = City::getCityList($this->domain->id); ?>
<div id="choose_city_menu" city-id="<?= City::getUserChooseCity(); ?>">
    <span class="title">Выберите город</span>
    <? foreach ($listCity as $city) { ?>
        <span class="item" city-id="<?= $city->id; ?>"><?= $city->name; ?></span>
    <? } ?>
</div>
<?php $domain_id = Domain::getDomain(Yii::app()->request->serverName)->id;?>
<?php if (!isset(Yii::app()->request->cookies['city_chosen']) && $domain_id != 1 && $domain_id != 2) { ?>
    <? $city = City::getCityByIp(); /** @var City $city */ ?>
    <? if ($city) { ?>
        <div id="city_ip">
            <div class="city_name"><span>Ваш город </span><span><?= $city->name; ?>?</span></div>
            <button class="correct" city_id="<?= $city->id; ?>">Да, все верно</button>
            <button class="another">Выбрать другой</button>
        </div>
    <? } ?>
<? } ?>
<? if ($this->domain->id == 1) { ?>
    <? if (true || isset(Yii::app()->request->cookies['rayon']) || !isset(Yii::app()->request->cookies['city_chosen'])){ ?>
    <style>
        #choose_rayon_menu {
            display: none;
        }
    </style>
    <script>
        $(document).ready(function () {
            $("#layer").attr('closable', 1);
        });
    </script>
<? }?>
<? if (!isset(Yii::app()->request->cookies['rayon'])){ ?>
    <script>
        $(document).ready(function () {
            //$("#layer").show();
        });
    </script>
<? } ?>
    <div id="choose_rayon_menu">
        <span class="title">Выберите район</span>
        <a href="#" class="rayon_selector_link" rayon_id="0">
            <span>Все районы</span>
            <img src="/images/loader2.gif" width="25px">
        </a><br>
        <? $rayons = Rayon::model()->cache(5000)->findAll();
        foreach ($rayons as $rayon) { ?>
            <a href="#" class="rayon_selector_link" rayon_id="<?= $rayon->id; ?>">
                <span><?= $rayon->name; ?></span>
                <img src="/images/loader2.gif" width="25px">
            </a>
        <? } ?>
    </div>
<? } ?>
<? //if(!User::phoneConfirmed()||(!Order::FormatPhone(Yii::app()->user->phone)&&Yii::app()->user->phone!='')){?>
<?if(false){?>
    <div id="phConfLayer"></div>
    <div id="phoneConfirmed" >
        <? $phone=Order::FormatPhone(Yii::app()->user->phone);
        if($phone==null){?>
            <div>Вы не указали номер телефона или ваш номер телефона некорректный.Укажите номер телефона</div>
            <input type="text" placeholder="Ваш номер телефона">
            <div>
                <button id="confirmPhone2">Ок</button>
            </div>
        <?}else{?>
            <div>Вы указали этот номер <?=$phone;?> как основной</div><br>
            <div>Он же будет вашим логином для входа в личный кабинет</div><br>
            <strong>Вы хотите заменить номер телефона на другой?</strong>
            <div>
                <button id="phoneCorrect">Нет</button>
                <button id="phoneIncorrect" style="background-color: #b9b9b9;">Да</button>
            </div>
        <?}
        ?>
    </div>
    <script>
        $(window).ready(function(){
            $('body').on('click','#confirmPhone',function(){
                var phone=$("#phoneConfirmed input").val();
                if(phone!=''){
                    $("#phoneConfirmed .order_message_hint2").remove();
                    $.post('/order/checkphone?check_users=1', {phone: phone}, function (data) {
                        if (data == 'error') {
                            $("#phoneConfirmed input").after("<div class='order_message_hint2'>Ваш номер неверный</div>");
                        }
                        if(data=='error2'){
                            $("#phoneConfirmed input").after("<div class='order_message_hint2'>Этот номер уже зарегистрирован на сайте</div>");
                        }
                        if(data=='ok'){
                            $("#phoneConfirmed").hide();
                            $("#phConfLayer").hide();
                        }
                    });
                }
            });
            var time_to_send_sms;
            $("#phoneCorrect").click(function(){
                var phone='<?=Yii::app()->user->phone;?>';
                $.post('/order/confirmphone?send_token=1', {phone: phone}, function (data) {
                    if(data=='ok'){
                        var html='<div >Введите код, который пришел по смс</div>'+
                            '<input type="text" placeholder="Код из смс">'+
                            '<div class="sms_time_text">Запросить пароль повторно через <span class="sms_time">60</span></div>'+
                            '<div> <button id="confirmPhone3">Ок</button> </div>';
                        $(".sms_time_text").show();
                        var interval=setInterval(function(){
                            if(time_to_send_sms>0){
                                time_to_send_sms--;
                                $("#phoneConfirmed .sms_time").html(time_to_send_sms);
                            }else{
                                if(time_to_send_sms==0){
                                    $(".sms_time_text").hide();
                                    $("#phoneConfirmed input").after('<button class="new_sms">Отправить код еще раз</button>');
                                    clearInterval(interval);
                                }

                            }
                        },1000);
                        $("#phoneConfirmed").html(html);
                        time_to_send_sms=60;
                    }
                });
            });
            $("#phoneConfirmed").on('click','.new_sms',function(){
                var phone='<?=Yii::app()->user->phone;?>';
                $.post('/order/confirmphone?send_token=1', {phone: phone}, function (data) {
                    if(data=='ok'){
                        time_to_send_sms=60;
                        var html='<div >Введите код, который пришел по смс</div>'+
                            '<input type="text" placeholder="Код из смс">'+
                            '<div class="sms_time_text">Запросить пароль повторно через <span class="sms_time">60</span></div>'+
                            '<div> <button id="confirmPhone3">Ок</button> </div>';
                        $(".sms_time_text").show();
                        var interval=setInterval(function(){
                            if(time_to_send_sms>0){
                                time_to_send_sms--;
                                $("#phoneConfirmed .sms_time").html(time_to_send_sms);
                            }else{
                                if(time_to_send_sms==0){
                                    $(".sms_time_text").hide();
                                    $("#phoneConfirmed input").after('<div class="new_sms">Отправить код еще раз</div>');
                                    clearInterval(interval);
                                }

                            }
                        },1000);
                        $("#phoneConfirmed").html(html);
                        time_to_send_sms=60;
                    }
                });
            });
            $("#phoneIncorrect").click(function(){
                var html='<div >Укажите новый номер телефона,который также будет вашим логином для входа в личный кабинет</div>'+
                    '<input type="text" placeholder="Ваш номер телефона">'+
                    '<div> <button id="confirmPhone2">Ок</button> </div>';
                $("#phoneConfirmed").html(html);
            });
            $('body').on('click','#confirmPhone2',function(){
                var phone=$("#phoneConfirmed input").val();
                $.post('/order/confirmphone?send_token=1', {phone: phone}, function (data) {
                    if(data=='ok'){
                        var html='<div >Введите код, который пришел по смс</div>'+
                            '<input type="text" placeholder="Код из смс">'+
                            '<div class="sms_time_text">Запросить пароль повторно через <span class="sms_time">60</span></div>'+
                            '<div> <button id="confirmPhone3">Ок</button> </div>';
                        $(".sms_time_text").show();
                        var interval=setInterval(function(){
                            if(time_to_send_sms>0){
                                time_to_send_sms--;
                                $("#phoneConfirmed .sms_time").html(time_to_send_sms);
                            }else{
                                if(time_to_send_sms==0){
                                    $(".sms_time_text").hide();
                                    $("#phoneConfirmed input").after('<button class="new_sms">Отправить код еще раз</button>');
                                    clearInterval(interval);
                                }

                            }
                        },1000);
                        $("#phoneConfirmed").html(html);
                        time_to_send_sms=60;
                    }
                });
            });
            $('body').on('click','#confirmPhone3',function(){
                var token=$("#phoneConfirmed input").val();
                $.post('/order/confirmphone?get_token=1', {token: token}, function (data) {
                    if(data=='ok'){
                        var html='<div >Ваш номер подтвержден</div>'+
                            '<div> <button id="confirmPhone4">Ок</button> </div>';
                        $("#phoneConfirmed").html(html);
                    }else{
                        $("#phoneConfirmed input").after('<div class="order_message_hint2">Неправильный код</div>');
                    }
                });
            });
            $('body').on('click','#confirmPhone4',function(){
                $("#phoneConfirmed").hide();
                $("#phConfLayer").hide();
            });
        });
    </script>
<?}?>
<?if(!User::passwordConfirmed()&&false){?>
    <div id="passConfLayer"></div>
    <div id="passConfirmed" >
        <div style="margin:15px;">
            <div><strong>Ваш логин <?=Yii::app()->user->phone;?></strong></div><br>
            <div ><strong>Введите новый пароль</strong></div>
            <input type="password" placeholder="Ваш новый пароль" id="new_pass"><br><br>
            <div>
                <input type="radio" class="pol" name="pol" value="" style="display: none">
                <input type="radio" class="pol" name="pol" value="m" id="p1"><label for="p1">Мужчина </label>
                <input type="radio" class="pol" name="pol" value="w" id="p2"><label for="p2">Женщина </label>
            </div><br>
            <div class="birtdate_fields">
                <span>Дата рождения</span><br><br>
                <select name="birthday_day" id="day">
                    <option selected value="">День</option>
                    <? for($i=1;$i<=31;$i++){?>
                        <option value="<?=$i<10 ? '0'.$i : $i;?>"><?=$i;?></option>
                    <?}?>
                </select>
                <select  name="birthday_month" id="month" title="Месяц">
                    <option value="" selected="1">Месяц</option>
                    <option value="01">Январь</option>
                    <option value="02">Февраль</option><option value="03">Март</option>
                    <option value="04">Апрель</option><option value="05">Май</option>
                    <option value="06">Июнь</option><option value="07">Июль</option>
                    <option value="08">Август</option><option value="09">Сентябрь</option>
                    <option value="10">Октябрь</option><option value="11">Ноябрь</option>
                    <option value="12">Декабрь</option>
                </select>
                <select name="birthday_year" id="year">
                    <option selected value="">Год</option>
                    <? for($i=2005;$i>=1920;$i--){?>
                        <option value="<?=$i;?>"><?=$i;?></option>
                    <?}?>
                </select><br>
            </div><br>
            <div>
                <button id="confirmPassword">Сохранить</button>
            </div>
        </div>

    </div>
    <script>
        $(window).ready(function(){
            $('body').on('click','#confirmPassword',function(){
                $("#passConfirmed .order_message_hint2").remove();
                var password=$("#passConfirmed #new_pass").val();
                var pol=$("#passConfirmed .pol:checked").val();
                var day=$("#passConfirmed #day").val();
                var month=$("#passConfirmed #month").val();
                var year=$("#passConfirmed #year").val();
                if(password!=''&&pol!=''&&day!=''&&month!=''&&year!=''){
                    var date =day+'-'+month+'-'+year;
                    $("#passConfirmed .order_message_hint2").remove();
                    $.post('/order/updatepassword?check_users=1', {new_password: password,pol:pol,date:date}, function (data) {
                        if(data=='ok'){
                            var html='<div >Спасибо<br>Приятного пользования!</div>'+
                                '<div> <button id="confirmPhone2">Ок</button> </div>';
                            $("#passConfirmed").html(html);
//                            $("#passConfirmed").hide();
//                            $("#passConfLayer").hide();
                        }else{
                            $("#passConfirmed button").before("<div class='order_message_hint2'>"+data+"</div>");
                        }
                    });
                }else{
                    $("#passConfirmed button").before("<div class='order_message_hint2'>Заполните все поля</div>");
                }
            });
            $('body').on('click','#confirmPhone2',function(){
                window.location.href='/restorany';
            });
        });
    </script>
<?}?>
<? if (!Yii::app()->user->isGuest && Yii::app()->controller->action->id != 'bonus') { ?>
    <div id="invite_friend" style="display: none">
        <div class="text">
            <img src="/images/invite_friend.png">

            <div style="font-size: 21px;font-weight: bold;margin-top: 30px;">
                <span style="display: block;">Пригласите друга</span>
                <span style="font-size: 33px;line-height: 27px;">и получите</span>
            </div>
            <div style="font-weight: bold;">
                <span style="font-size: 57px;display: inline-block;width: 95px;">200</span>
                <span style="font-size: 22px;display: inline-block;width: 84px;">баллов на счет</span>
            </div>
            <div style="margin-top: 20px;">
                Расскажите о <span>dostavka05.ru</span> своим друзьям и близким и получайте бонусы.
            </div>
            <div style="margin-top: 10px;padding-left: 5px;">
                Как только приглашенный вами друг зарегистрируется, Вам будет начислено 100 баллов.
                Как только он сделает свой первый заказ вы получите еще 100 баллов на личный счет
            </div>
            <div style="margin-top: 10px;">
                И так за каждого приглашенного Вами друга!
            </div>
        </div>
        <div class="form">
            <div class="form_text">Пригласить друга по электронной почте</div>
            <div>
                <input type="text" placeholder="E-mail">
                <button>Пригласить</button>
            </div>
            <div class="error"></div>
        </div>
        <span class="close_button"><span></span></span>
    </div>
<? } ?>
<script>
    $(document).ready(function () {
        $("#city_ip .another").click(function () {
            $("#city_ip").hide();
            $("#choose_city_menu").show();
            $("#layer").show();
        });
        $("#city_ip .correct").click(function () {
            $("#city_ip").hide();
            var city_id = $(this).attr('city_id');
            $.get('/SetCityCookie/' + city_id);
            $("#layer").hide();
            $("#choose_rayon_menu").show();
        });
        if ($('#city_ip').is(':visible')) {
            $("#layer").show();

        }

        $('body').on('click', '.rayon_selector_link', function () {
            var id = $(this).attr('rayon_id');
            $(this).find('img').show();
            $.post('/products/SetRayon', {rayon_id: id}, function (data) {
                if (data == 'Ok') {
                    location.reload();
                }
            });
        });

        $("#layer").click(function () {
            if ($(this).attr('closable') == 0 && $("#choose_rayon_menu").is(":visible")) {
                return false;
            }
            $("#choose_city_menu").hide();
            $("#choose_rayon_menu").hide();
            if (!$('#city_ip').is(':visible')) {
                $("#layer").hide();
            }
        });
        $('#choose_city_menu .item').click(function () {
            var city_id = $(this).attr('city-id');
            // if(city_id!=$("#choose_city_menu").attr('city-id')){
            $.get('/site/SetCityCookie/' + city_id, function (data) {
                var url = window.location.href;
                if (url.indexOf('?') > 0) {
                    url = url.slice(0, url.indexOf('?'));
                }
                var host = 'www';
                if (data == '2') {
                    host = 'kaspiysk';
                }
                if (data == '4') {
                    host = 'derbent';
                }
                if (data ==<?=City::getUserChooseCity();?>) {
                    $("#layer").click();
                    return;
                }
                window.location.href = 'http://' + host + '.dostavka05.ru?save_city=1'; // @TODO а если это азербайджан?
            });
            return false;
            // }
        });
    });
</script>

<div class="wrapper">
    <header class="header">
        <div class="menuHead">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <a href="/" class="logo">
            <img src="<?= $this->domain->logo ?>" alt="logo">
        </a>

        <? if (Yii::app()->user->isGuest) { ?>
            <div class="menuHeadRight">
                <img src="<?= Yii::app()->theme->baseUrl; ?>/img/userIcon.png" alt="">

                <div>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        <? } else { ?>
            <? if (Yii::app()->user->role != User::ADMIN) { ?>
                <? if (Yii::app()->user->role == User::PARTNER) { ?>
                    <a href="/partner/orders" class="divan">
                        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/profile.png" alt="">
                    </a>
                <? } else { ?>
                    <a href="/user/profile" class="divan">
                        <img src="<?= Yii::app()->theme->baseUrl; ?>/img/profile.png" alt="">
                    </a>
                <? } ?>
            <? } else { ?>
                <a href="/admin" class="divan">
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/img/profile.png" alt="">
                </a>
            <? } ?>

        <? } ?>
    </header>
    <!-- .header-->

    <div class="main-page-order-phone">
        Заказ по телефону: <?= Config::getValue('order_phone_in_header', $this->domain->id); ?>
    </div>
    <? if (Yii::app()->controller->id != 'site') { ?>
        <? if ($this->domain->id == 1) { ?>
            <div id="rayon_selector">
                <span class="label">Район:</span>
                    <span class="rayon">
                        <? if (isset(Yii::app()->request->cookies['rayon'])) { ?>
                            <? $rayon = Rayon::model()->cache(5000)->findByPk(Yii::app()->request->cookies['rayon']->value); ?>
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
    <? } ?>
    <script>
        $(window).ready(function () {
            $(".city_selector .city").click(function () {
                $("#choose_city_menu").show();
                $("#layer").show();
            });
            $("#rayon_selector .rayon").click(function () {
                $("#choose_rayon_menu").show();
                $("#layer").show();
            });
        });
    </script>
    <div class="menu">
        <ul>
            <li>
                <a href="/pages/about">
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/img/iconMenu1.png" alt="">
                    о нас
                </a>
            </li>
            <li>
                <a href="/pages/contacts">
                    <img src="<?= Yii::app()->theme->baseUrl; ?>/img/iconMenu4.png" alt="">
                    контакты
                </a>
            </li>
        </ul>
    </div>

    <div class="userDropBox">

        <div class="userDropBoxHead">

            <div class="loginname">
                ЛИЧНЫЙ КАБИНЕТ:
            </div>
            <div class="regname" style="display:none;">
                ЗАрегистрироваться или
                <input style="width: 70px;" class="enterButton getloginform" id="enter-button" type="submit"
                       value="Войти">
            </div>
        </div>
        <div class="userDropBoxForm">
            <div id="reg-form" style="display:none;">

                <form action="" method="post" id="regForm">
                    <div class="loader"><img src="/images/ajax_loader_blue.gif"></div>
                    <span id="reg-error" style="color:#DD3333"></span>

                    <div class="row">
                        <label id="reg-name-field">ВАШЕ ИМЯ*</label><input name="RegistrationForm[name]"
                                                                           id="RegistrationForm_name" type="text">
                    </div>

                    <div class="row">
                        <label id="reg-pass-field">ПАРОЛЬ*</label><input name="RegistrationForm[password]"
                                                                         id="RegistrationForm_password" type="password">
                    </div>
                    <div class="row">
                        <label id="reg-phone-field">Телефон*</label><input name="RegistrationForm[phone]"
                                                                           id="RegistrationForm_phone" type="phone">
                    </div>
                    <button class="regButton">Регистрация</button>
                </form>
            </div>

            <form action="" id="loginform">

                <div id="error-login" name="error-login" style="color:#DD3333;margin:-10px 0 10px 0;"></div>
                <div class="loader"><img src="/images/ajax_loader_blue.gif"></div>
                <div class="row">
                    <label for="">Логин: </label><input name="login" id="UserLogin_username" type="text"
                                                        placeholder="Email или телефон">
                </div>
                <div class="row">
                    <label for="">Пароль:</label><input name="pass" id="UserLogin_password" type="password"
                                                        placeholder="Password">
                </div>
                <div style="display:none;"><input name="rememberMe" id="UserLogin_rememberMe" class='remember'
                                                  checked="true" type="checkbox">Запомнить меня
                </div>

                <div style='clear:both'></div>

                <div class="userDropBoxLink">

                    <script type="text/javascript">
                        $(function () {
                            $('#regtab, .getloginform').on('click', function () {
                                $('#reg-form, #loginform, #regButton, .loginname, .regname').slideToggle();
                                return false;
                            });
                        });

                        $(document).on("click", ".regButton", function (event) {
                            $.ajax({
                                url: '<? echo CController::createUrl('/site/registrationAjax');?>',
                                type: "post",
                                dataType: "json",
                                cache: false,
                                data: $("#regForm").serialize(),
                                beforeSend: function (data) {
                                    $('.loader').show();
                                    $('#reg-error').html('');
                                },
                                success: function (data) {
                                    $(' .loader').hide();
                                    if (data['error']) {
                                        $("#reg-error").html(data['error']);
                                    }
                                    if (data['redirect']) {
                                        //alert(111);
                                        window.location.href = '' + data['redirect'];
                                    }
                                }
                            });
                            return false;
                        });
                        $('body').on("click", "#enter-button", function () {
                            //alert('354');
                            $("#error-login").html('');
                            $.ajax({
                                url: '<? echo CController::createUrl('/site/loginAjax');?>',
                                type: "post",
                                dataType: "json",
                                cache: false,
                                data: {
                                    'login': $('#UserLogin_username').val(),
                                    'pass': $('#UserLogin_password').val(),
                                    'rememberMe': $('#UserLogin_rememberMe').attr('checked') ? 1 : 0
                                },
                                beforeSend: function (data) {
                                    $('.loader').show();
                                    $('#error-login').html('');
                                },
                                success: function (data) {
                                    $('.loader').hide();
                                    //alert(data);
                                    if (data['error']) {
                                        $("#error-login").html(data['error']);
                                    }
                                    if (data['redirect']) {
                                        //alert(111);
                                        window.location.href = '' + data['redirect'];
                                    }
                                }
                            });
                            return false;
                        });
                    </script>
                    <a href="/site/registration" class="regButton" id="regtab" onclick="">Регистрация</a>
                    <a href="/" class="enterButton" id="enter-button">Войти</a>
                    <script>
                        function asdf23() {
                            alert($('#UserLogin_username').attr('value'));
                        }
                    </script>
                </div>
                <div style="clear:both;"></div>
                <br><br>
                <center>
                    <a href="/user/recovery" class="submit">Забыли пароль?</a>
                </center>
                <br>
            </form>
        </div>
        <div class="userDropBoxHead">
            Войти через:
        </div>
        <div class="userDropBoxSocial">
            <? $cs->registerScriptFile('http://ulogin.ru/js/ulogin.js', CClientScript::POS_END); ?>
            <div id="uLogin80da037e"
                 data-ulogin="display=panel;fields=first_name,last_name,email;optional=phone;verify=1;mobilebuttons=0;lang=ru;providers=vkontakte,odnoklassniki,mailru,facebook,twitter,google;hidden=other;redirect_uri=http://www.dostavka05.ru/ulogin/login"></div>
        </div>
    </div>
    <? if (!Yii::app()->user->isGuest) { ?>
        <div id="invite_friend" style="display: none">
            <div class="text">
                <div
                    style="font-size: 25px;font-weight: bold;font-family: 'PT SansBold';margin-bottom: 5px;text-align: center;">
                    Пригласите друга и получите
                </div>
                <div style="font-weight: bold;text-align: center">
                    <span
                        style="font-size: 38px;font-weight: bold;font-family: 'PT SansBold';color:#ff4444">200 баллов</span>
                    <span style="font-size: 38px;font-weight: bold;font-family: 'PT SansBold';"> на счет</span>
                </div>
                <br>
                <div style="text-align: center"><img src="/themes/mobile/img/invite_friend_mobile.png"></div>
                <div style="margin-top: 20px;font-size: 20px;font-family: 'PT Sans';margin-bottom: 13px;">
                    Расскажите о <span style="color: #8aa924;text-transform: uppercase">dostavka05.ru</span>
                    своим друзьям и близким и получайте бонусы.<br><br>
                    Как только приглашенный вами друг зарегистрируется, Вам будет начислено 100 баллов.
                    Как только он сделает свой первый заказ вы получите еще 100 баллов на личный счет<br><br>

                    И так за каждого приглашенного Вами друга!
                </div>
            </div>
            <div class="form">
                <div class="form_text">Пригласить друга по электронной почте</div>
                <div>
                    <input type="text" placeholder="E-mail">
                    <button>Пригласить</button>
                </div>
                <div class="error"></div>
                <script>
                    $(document).ready(function () {
                        $("#invite_friend .form button").click(function () {
                            var email = $("#invite_friend .form input").val();
                            if (email != '') {
                                $("#invite_friend .error").html('');
                                $.post('/site/addInvite', {email: email}, function (data) {
                                    if (data == 'ok') {

                                    } else {
                                        $("#invite_friend .error").html(data);
                                    }
                                });
                            } else {
                                $("#invite_friend .error").html('Вы не ввели e-mail');
                            }
                        });
                        $("#invite_friend .close_button").click(function () {
                            $("#invite_friend").hide();
                            $("#invite_friend_layer").hide();
                        });
                    });
                </script>
            </div>
            <span class="close_button"><span></span></span>
        </div>
        <div id="invite_friend_layer"
             style="display:none;z-index:1000;position: fixed;background-color: #000000;opacity: 0.3;width: 100%;height: 100%;top:0;"></div>
    <? } ?>

    <?= $content; ?>


    <div style="clear:both;"></div>
    <br>

    <div class="blok_page_boottom-right" style="text-align: center">
        <? if (Config::getSoc('odnoklassniki', $this->domain->id)) { ?>
            <a href="<?= Config::getSoc('odnoklassniki', $this->domain->id) ?>" target="_blank"><img
                    src="/images/ok-img.png" width="45" height="44" alt="ok"></a>
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
        <!--LiveInternet counter-->
        <script
            type="text/javascript">document.write("<a href='//www.liveinternet.ru/click;dostavka05' target=_blank><img src='//counter.yadro.ru/hit;dostavka05?t45.2;r" + escape(document.referrer) + ((typeof(screen) == "undefined") ? "" : ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ? screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) + ";h" + escape(document.title.substring(0, 80)) + ";" + Math.random() + "' border=0 width=31 height=31 alt='' title='LiveInternet'><\/a>")</script>
        <!--/LiveInternet-->
    </div>
</div>
<!-- .wrapper -->
<footer class="footer">
</footer>
<!-- .footer -->

<script
    type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=RFLhb6aI5ZLfLtnjxjz1rEHa8xNa3EGDgnUn/vWJb2Dw7SFwoEHKE/5glssuyQ3Ne9vrXeozxwJp/GirCBQ7HH*GogYYiUXEwvgZhiW1EQkj5/1KYOV4EXmZSAHK9y*miGux9NAStj4mVu1XMj9zOQv*ohw/8Rk/tymBr5zgnEg-';</script>

<? if (!Yii::app()->request->cookies['open_ad']->value && (time() < mktime(23, 59, 59, 12, 16, 2015)) && ($this->domain->alias == 'www.dostavka05.ru')) { ?>
    <div id="parent_popup">
    </div>
    <div class="openAd popmodile" style="display:none;">
        <div class="close-add"></div>

        <div style="line-height: 0;position:relative;">
            <img src="/images/poop.jpg" alt="">

            <a target=_blank href="http://www.dostavka05.ru/blog/45" class="morePopAd">Подробнее</a>
        </div>
    </div>
<? } ?>

<? // Код Гугл аналитикс для edostav.ru ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-16587655-17', 'auto');
  ga('send', 'pageview');

</script>
<? // Код Гугл аналитикс для edostav.ru ?>

<script>
    ga('send', 'pageview');
</script>

</body>
</html>
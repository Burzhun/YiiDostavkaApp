<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="google-site-verification" content="P-RTNGsZZYUIjpZzlzyGKp2dSR6mjKT9qK3Ws_xvz7Y"/>
    <title><?= $this->title ?></title>
    <meta name="keywords" content="<?= $this->keywords ?>">
    <meta name="description" content="<?= $this->description ?>">
    <meta property="og:image" content="http://<?=$this->domain->alias.$this->domain->logo;?>"/>
    <LINK href="/favicon.ico" rel="SHORTCUT ICON">
    <?
    $cs = Yii::app()->clientScript;
    $cs->registerCoreScript('jquery', CClientScript::POS_END);
    $cs->registerScriptFile('/js/nav.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/cusel.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/script.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/google_analytics.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/jselect/jquery.formstyler.js', CClientScript::POS_END);
    $cs->registerScriptFile('/ds-comf/ds-form/js/dsforms.js', CClientScript::POS_END);
    $cs->registerScriptFile('/ds-comf/ds-form/js/jquery.jgrowl.min.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/application.js', CClientScript::POS_END);
    $cs->registerScriptFile('https://vk.com/js/api/share.js?93', CClientScript::POS_END);
    $cs->registerScriptFile('https://ulogin.ru/js/ulogin.js', CClientScript::POS_END);


    $cs->registerCssFile('/js/jselect/jquery.formstyler.css');
    $cs->registerCssFile('/ds-comf/ds-form/css/jquery.jgrowl.css');
    $cs->registerCssFile('/ds-comf/ds-form/css/dsforms.css');
    $cs->registerCssFile('/css/reset.css');
    $cs->registerCssFile('/css/style.css?t=4187514');
    $cs->registerCssFile('/css/nav.css');
    $cs->registerCssFile('/css/cusel2.css');
    $cs->registerCssFile('/font/stylesheet.css');
    $cs->registerCssFile('/css/style_ie6.css');
    ?>

    <link rel="apple-touch-icon" href="/images/touch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/touch-icon-ipad-retina.png">
    <meta name='yandex-verification' content='617ffe8f396fb63d'/>
    <meta name='yandex-verification' content='5a557d21d67133dc'/>

    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function () {
            FB.init({
                appId: '523325534501381',
                status: true, // check login status
                cookie: true, // enable cookies to allow the server to access the session
                xfbml: true // parse XFBML
            });
        };
        (function () {
            var e = document.createElement('script');
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
        }());
    </script>

    <script type="text/javascript">
        $(document).ready(function () {

            //$("iframe").last().css('position','absolute');
            $(".user, .bonus_reg_button").click(function (event) {
                if (event.target.className != "option")
                    if ($(".user").hasClass("active-pull")) {
                        $("#pulldown-menu").slideDown(500, function () {
                        });
                        $(".user, .bonus_reg_button").removeClass("active-pull");
                    } else {
                        $("#pulldown-menu").slideUp(500, function () {
                        });
                        $(".user").addClass("active-pull")
                    }
                return true
            });
            $(".search-button").click(function () {
                if ($(".search-button").hasClass("active-pull2")) {
                    $(".search-field").fadeIn(100, function () {
                    });
                    $(".user-name").fadeOut(10)
                    $(".search-button").removeClass("active-pull2");
                } else {
                    $(".search-field").fadeOut(100, function () {
                    });
                    $(".search-button").addClass("active-pull2")
                    $(".user-name").fadeIn(100)
                }
                return false
            });
            $(".user-name").click(function () {
                if ($(".user-name").hasClass("active-pull3")) {
                    $(".user-option").slideDown(300, function () {
                    });
                    $(".user-name").removeClass("active-pull3");
                }
                else {
                    $(".user-option").slideUp(300, function () {
                    });
                    $(".user-name").addClass("active-pull3")
                }
                return false
            });

            $("#wrapper").click(function (event) {
                if (event.target.className != "option")
                    $(".user-option").slideUp(300, function () {
                    });
                $(".user-name").addClass("active-pull3")
            });

            $("#hide").click(function () {
                $(".user").addClass("active-pull");
                $("#pulldown-menu").slideUp(500, function () {
                });
                return false
            });


            $(".reg-button-top").click(function () {
                $("#reg-block").fadeOut(1);
                $("#reg-form").fadeIn(500);
                return false;
            });

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

        $(document).on("click", ".reg-button-top-b", function (event) {
            $("#reg-error").html('');
            $.ajax({
                url: '<? echo CController::createUrl('/site/registrationAjax');?>',
                type: "post",
                dataType: "json",
                cache: false,
                data: $("#regForm").serialize(),
                success: function (data) {
                    if (data['error']) {
                        $("#reg-error").html(data['error']);
                        //$("#reg-error").remove();
                    }
                    if (data['redirect']) {
                        //alert(111);
                        window.location.href = '' + data['redirect'];
                    }
                }
            });
            return false;
        });

        $(document).on("change", "#city", function (event) {
            var value = $('#city').attr('value');
            //alert(value);
            $.ajax({
                url: '<? echo CController::createUrl('/site/updateCity')?>',
                type: "post",
                cache: false,
                data: "id=" + value,
                success: function (data) {
                    <? if(Yii::app()->controller->id == "products"){?>
                    $.ajax({
                        url: '<? echo CController::createUrl('/products/ajaxCheckSpecs')?>',
                        type: "post",
                        cache: false,
                        data: $("form").serialize(),
                        success: function (data2) {
                            $("#suppliers").html(data2);
                        }
                    });
                    <? } elseif(Yii::app()->controller->id == "site" && Yii::app()->controller->action->id == "index") { ?>
                    $.ajax({
                        url: '<? echo CController::createUrl("/site/ajaxVipPartner")?>',
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success: function (data2) {
                            $("#vip_rest").replaceWith(data2['vip_partner']);
                            $("#vip_mag").replaceWith(data2['vip_rest_partner']);
                            //console.log(data2);
                        }
                    });
                    <? } ?>
                }
            });
        });

        jQuery(document).ready(function () {
            var params = {
                changedEl: ".lineForm select",
                visRows: 5,
                scrollArrows: true
            };
            cuSel(params);
            params = {
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
                });
        });
    </script>

    <style type="text/css">
        .home {
            height: 734px;
            margin: 0 auto;
        }
    </style>

    <? $s = Config::getValue('site_metriks', $this->domain->id);
    if (Yii::app()->user->id) {
        $s = str_replace("11', 'auto');", "11',{ 'userId': '" . Yii::app()->user->id . "'});", $s);
    }
    echo $s; ?>
</head>

<body>
<!-- Oneretarget container -->
<script type="text/javascript">   (function (w, d) {
        var ts = d.createElement("script");
        ts.type = "text/javascript";
        ts.async = true;
        var domain = window.location.hostname;
        ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//tag.oneretarget.com/1305_" + domain + ".js";
        var f = function () {
            var s = d.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(ts, s);
        };
        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(window, document);</script>
<div id="vko1ntakte"></div>
<? if ($this->id == "site" && $this->action->id == "index") { ?>
    <!--[if IE 7]>
    <link href="/css/style_ie7-main.css" rel="stylesheet" media="all"/><![endif]-->

    <div class="body-bg">
        <div class="body-img" style='background:url(/images/body-bg3.jpg) center top fixed; background-size:cover'>
        </div>
    </div>
<? } else {
    ?>
    <!--[if IE 7]>
    <link href="/css/style_ie7.css" rel="stylesheet" media="all"/><![endif]-->
    <!--[if IE 8]>
    <link href="/css/style_ie.css" rel="stylesheet" media="all"/><![endif]-->
<? } ?>
<? if (CHERRY05) { ?>
    <style type="text/css">
        #header, #header_info {
            display: none;
        }

        #page {
            margin-top: 15px;
        }
    </style>
<? }?>
<?if(!User::passwordConfirmed()){?>
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
<? //if(!User::phoneConfirmed()||(!Order::FormatPhone(Yii::app()->user->phone)&&Yii::app()->user->phone!='')){?>
<?if(false){?>
    <div id="phConfLayer"></div>
    <div id="phoneConfirmed" >
        <? $phone=Order::FormatPhone(Yii::app()->user->phone);
           if($phone==null){?>
               <div >Вы не указали номер телефона или ваш номер телефона некорректный.Укажите номер телефона</div>
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
                            '<input type="text" placeholder="Код из смс"><br>'+
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
                                    $("#phoneConfirmed input").after('<br><button class="new_sms">Отправить код еще раз</button>');
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
                            '<input type="text" placeholder="Код из смс"><br>'+
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
                                    $("#phoneConfirmed input").after('<br><button class="new_sms">Отправить код еще раз</button>');
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
                            '<input type="text" placeholder="Код из смс"><br>'+
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
                                    $("#phoneConfirmed input").after('<br><button class="new_sms">Отправить код еще раз</button>');
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
<div id="invite_friend_layer"
     style="display:none;z-index:1000;position: fixed;background-color: #000000;opacity: 0.3;width: 100%;height: 100%;"></div>

<div id="wrapper">
    <div id="parent_popup">
    </div>
    <? if (Yii::app()->user->isGuest) { ?>
        <div id="pulldown-menu">
            <div id="pulldown-menu-content">
                <div id="reg-form">
                    <form action="" method="post" id="regForm">
                        <p class="reg-title">РЕГИСТРАЦИЯ</p>
                        <span id="reg-error" style="color:#DD3333"></span>

                        <p><span id="reg-name-field">ВАШЕ ИМЯ*</span><input name="RegistrationForm[name]"
                                                                            id="RegistrationForm_name" type="text"></p>

                        <p><span id="reg-pass-field">ПАРОЛЬ*</span><input name="RegistrationForm[password]"
                                                                          id="RegistrationForm_password"
                                                                          type="password"></p>

                        <p><span id="reg-phone-field">Телефон*</span><input name="RegistrationForm[phone]"
                                                                            id="RegistrationForm_phone" type="phone">
                        </p>

                        <button class="reg-button-top-b"></button>
                    </form>
                </div>
                <span id="hide"><a href="#">Скрыть</a></span>

                <div id="reg-block">
                    <div class="h5">РЕГИСТРАЦИЯ НА САЙТЕ:</div>
                    <p>Быстрая регистрация позволит
                        вам зарабатывать бонусные баллы
                        и получать приятные подарки.</p>

                    <div class="reg-button-top"></div>
                    <img src="/images/logo-top-menu.png" width="54" height="53" alt="" class="logo-top-menu">
                </div>
                <div class='socic'>
                    <div class="h5">ВОЙТИ С ПОМОЩЬЮ:</div>
                    <div style="width:300px;">
                        <div id="uLogin80da037e"
                             data-ulogin="display=panel;fields=first_name,last_name,email;optional=phone;verify=1;mobilebuttons=0;lang=ru;providers=vkontakte,odnoklassniki,mailru,facebook,twitter,google;hidden=other;redirect_uri=http://<?= $this->domain->alias; ?>/ulogin/login"></div>
                    </div>
                </div>
                <div id="vhod">
                    <div class="h5">ЛИЧНЫЙ КАБИНЕТ:</div>
                    <form>
                        <div class="loader"><img src="/images/ajax_loader_blue.gif"></div>
                        <div id="error-login" name="error-login" style="color:#DD3333;margin:-10px 0 10px 0;"></div>


                        <input name="UserLogin[username]" id="UserLogin_username" type="text" placeholder="Email или телефон">
                        <input name="UserLogin[password]" id="UserLogin_password" type="password"
                               placeholder="Password">

                        <div><input name="UserLogin[rememberMe]" id="UserLogin_rememberMe" class='remember'
                                    type="checkbox" checked="true">Запомнить меня
                        </div>
                        <? echo CHtml::ajaxSubmitButton('',
                            CHtml::normalizeUrl(array('/site/loginAjax')),
                            array(
                                'type' => 'post',
                                'dataType' => 'json',
                                'data' => "js:{'login':$('#UserLogin_username').attr('value'), 'pass':$('#UserLogin_password').attr('value'), 'rememberMe':$('#UserLogin_rememberMe').attr('checked') ? 1 : 0}",
                                'beforeSend' => "js:function(){
                                            $('#vhod .loader').show();
                                            $('#error-login').html('');
                                        }",
                                'success' => "js:function(data){
												if(data['error'] != '')
												{
													$('#error-login').html(data['error']);
												}
												if(data['redirect'] != '')
												{
													window.location.href=''+data['redirect'];
												}
												$('#vhod .loader').hide();
											}",
                            ),
                            array(
                                'id' => 'enter-button',
                                'style' => 'width:75px;height:37px;border:0px;',
                            )
                        );
                        ?>
                        <a href="/user/recovery" id="forgot-password">ЗАБЫЛИ ПАРОЛЬ?</a>
                    </form>
                </div>
            </div>
        </div>

        <?php
        $cs->registerScriptFile('/js/text_change.js', CClientScript::POS_END);
        $cs->registerScriptFile('http://code.jquery.com/ui/1.11.2/jquery-ui.js', CClientScript::POS_END);

        $cs->registerCssFile('http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
        ?>

        <script>
            // Функция автокомплита для поиска
            $(function () {
                $('#tags').bind('hastext', function () {
                    var availableTags = [];
                    var data = {query: $(this).val()}
                    $.getJSON('/search/complete', data, function (result) {
                        for (var i = 0; i < result.length; i++) availableTags.push(result[i]);
                    });

                    $("#tags").autocomplete({
                        source: availableTags
                    });
                });

                $('#tags2').bind('hastext', function () {
                    var availableTags = [];
                    var data = {query: $(this).val()}
                    $.getJSON('/search/complete', data, function (result) {
                        for (var i = 0; i < result.length; i++) availableTags.push(result[i]);
                    });

                    $("#tags2").autocomplete({
                        source: availableTags
                    });
                });
            });
        </script>
    <? } ?>


    <? if ($this->id == "site" && $this->action->id == "index") { ?>
    <div id="header">
        <? }else{ ?>
        <div id="header">
            <? } ?>
            <div class="header_center">
                <a href="/" class="logo-link">
                    <img src="<?= $this->domain->logo ?>" alt="logo" class="logo">
                </a>

                <div id="search-box">
                    <form action="/search" method="get">

                        <input name="query" type="text" id='tags' class="search-field" placeholder="Поиск...">
                        <span name="" type="submit" class="search-button active-pull2"></span>
                        <input name="" type="submit" class="search-link">

                    </form>
                    <? if (!Yii::app()->user->isGuest){ ?>
                    <div class="user black_fon active-pull">
                        <? }else{ ?>
                        <div class="user  active-pull">
                            <? } ?>
                            <? if (!Yii::app()->user->isGuest) { ?>
                                <div class="user-name active-pull3">
                                    <? echo User::model()->findByPk(Yii::app()->user->id)->name ?>
                                </div>
                                <? if (Yii::app()->user->role == User::USER) {
                                    $profileUrl = "/user/profile";
                                    $menuUrl = "";
                                    $orderUrl = "/user/orders";
                                    $infoUrl = "/user/address";
                                    $infoName = "Адреса";
                                }

                                if (Yii::app()->user->role == User::PARTNER) {
                                    $profileUrl = "/partner/profile";
                                    $menuUrl = "/partner/menu";
                                    $orderUrl = "/partner/orders";
                                    $infoUrl = "/partner/info";
                                    $infoName = "Информация";
                                }
                                if (Yii::app()->user->role == User::ADMIN) {
                                    $profileUrl = "/admin/user/id/" . Yii::app()->user->id . "/profile";
                                    $menuUrl = "/partner/menu";
                                    $orderUrl = "/admin/user/id/" . Yii::app()->user->id . "/orders";
                                    $infoUrl = "/admin/user/id/" . Yii::app()->user->id . "/address";
                                    $infoName = "Адреса";
                                } ?>
                                <ul class="user-option">
                                    <li><a href="<? echo $infoUrl; ?>" class="option"><? echo $infoName; ?></a></li>
                                    <? if (Yii::app()->user->role == User::PARTNER) { ?>
                                        <li><a href="<? echo $menuUrl; ?>" class="option">Меню</a></li>
                                    <? } ?>
                                    <li><a href="<? echo $orderUrl; ?>" class="option">Заказы</a></li>
                                    <li><a href="<? echo $profileUrl; ?>" class="option">Профиль</a></li>

                                    <? if (Yii::app()->user->role == User::ADMIN) { ?>
                                        <li><a href="/admin" class="option">Управление</a></li>
                                    <? } ?>
                                    <li><a href="/user/logout" class="option">Выход</a></li>
                                </ul>
                            <? } ?>
                        </div>
                        <div class="lineForm3">
                            <?php $listCity = City::getCityList($this->domain->id); ?>
                            <?php $domain_id = Domain::getDomain(Yii::app()->request->serverName)->id;?>
                            <? if (!isset(Yii::app()->request->cookies['city_chosen']) && $domain_id != 1 && $domain_id != 2) { ?>
                                <? $city = City::getCityByIp(); /** @var City $city */ ?>
                                <? if ($city) { ?>
                                    <div id="city_choose_window">
                                        <div class="city_name">Ваш город:<span><?= $city->name; ?></span></div>
                                        <button class="another">Выбрать другой</button>
                                        <button class="correct" city_id="<?= $city->id; ?>">Да, все верно</button>
                                    </div>
                                <? } ?>
                            <? } else {
                                $city = City::model()->cache(1000)->findByPk(City::getUserChooseCity());
                            } ?>
                        </div>

                    </div>
                </div>
            </div>
            <!-- #header-->

            <div id="header_info">
                <div id="header_info_container">
                    <div id="city_selector">Город: <span id="city_selector_link"><?= $city->name; ?></span><span
                            class="arrow_down"></span></div>
                    <div id="rayon_name">
                        <? if ($this->domain->id == 1) { ?>
                            <? if (isset(Yii::app()->request->cookies['rayon'])) {
                            $rayon = Rayon::model()->findByPk(Yii::app()->request->cookies['rayon']->value); ?>
                            Район:<span><?= $rayon->name; ?></span>
                        <? }else{ ?>
                            Район:<span><? //= $rayon->name; ?>Выбрать район</span>
                        <? } ?>
                            <script>
                                $('body').on('click', '#rayon_name', function () {
                                    $('#rayon_selector_layer').show();
                                    $('#rayon_selector').show();

                                });
                            </script>
                        <? } ?>
                    </div>
                    <div id="order_phone">
                        <img src="/images/phone_image.png">
                        <span><?= Config::getValue('order_phone_in_header', $this->domain->id); ?></span>
                        <span2>Заказ по телефону</span2>
                    </div>
                    <div id="select_div_popup">
                        <? $listCity = City::getCityList($this->domain->id);//City::model()->findAll();?>
                        <? foreach ($listCity as $city) { ?>
                            <span city_id="<?= $city->id; ?>"><?= $city->name; ?></span>
                        <? } ?>
                    </div>

                </div>

            </div>
            <? if ($this->domain->id == 1) { ?>
                <? if (isset(Yii::app()->request->cookies['rayon']) || true){ ?>
                <style>
                    #rayon_selector, #rayon_selector_layer {
                        display: none;
                    }
                </style>
                <script>
                    $(document).ready(function () {
                        $("#rayon_selector_layer").attr('closable', 1);

                    });

                </script>
            <? } ?>

                <div id="rayon_selector_layer" closable=0></div>
                <div id="rayon_selector">
                    <div class="title">
                        <span>В каком районе вы находитесь?</span>
                    </div>
                    <div id="rayon_list">
                        <div><a href="#" class="rayon_selector_link"
                                rayon_id="0"><span>Все районы</span>
                                <img src="/images/loader2.gif" width="20px">
                            </a>
                        </div>
                        <br>
                        <? $rayons = Rayon::model()->findAll();
                        foreach ($rayons as $rayon) { ?>
                            <div><a href="#" class="rayon_selector_link"
                                    rayon_id="<?= $rayon->id; ?>"><span><?= $rayon->name; ?></span>
                                    <img src="/images/loader2.gif" width="20px">
                                </a>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <script>
                    $('body').on('click', '.rayon_selector_link', function () {
                        var id = $(this).attr('rayon_id');
                        $(this).find('img').show();
                        $.post('/products/SetRayon', {rayon_id: id}, function (data) {
                            if (data == 'Ok') {
                                location.reload();
                            }
                        });
                    });
                </script>
            <? } ?>
            <div id="content">

            </div>
            <!-- #content-->
            <? echo $content; ?>
        </div>
        <!-- #wrapper -->
        <!--text_content-->
        <div id="footer" <? if ($this->id != "site" && $this->action->id != "index"){ ?>class="footer"<? } ?>>
            <div class="center_footer">

                <? if ($this->domain->id == 'ru') { ?>
                    <div class="logo_footer">
                        <p class="copy_right">© 2014-2015 “Доставка05”
                        </p>

                        <p style="margin-left: 6px;"><a style="color: #504e49;" href="/pages/sitemap">Карта сайта</a>
                        </p>
                    </div>
                <? } else { ?>
                    <p style="margin-left: 6px;"><a style="color: #504e49;" href="/pages/sitemap">Карта сайта</a></p>
                <? } ?>
                <br>
                <!--LiveInternet counter-->
                <script
                    type="text/javascript">document.write("<a href='//www.liveinternet.ru/click;dostavka05' target=_blank><img src='//counter.yadro.ru/hit;dostavka05?t45.2;r" + escape(document.referrer) + ((typeof(screen) == "undefined") ? "" : ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ? screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) + ";h" + escape(document.title.substring(0, 80)) + ";" + Math.random() + "' border=0 width=31 height=31 alt='' title='LiveInternet'><\/a>")</script>
                <!--/LiveInternet-->
            </div>
        </div>
    </div>
    <!-- #footer -->

    <div id="test"></div>
    <script>
        $(document).ready(function () {
            $("#city_choose_window .another").click(function () {
                $("#city_choose_window").hide();
                $("#header_info #select_div_popup").show();
                $("#header_info #city_selector_link").trigger('click');
                $("#header_info #header_info_container #city_selector .arrow_down").css('display', 'inline-block');
                return false;
            });
            $("#city_choose_window .correct").click(function () {
                $("#city_choose_window").hide();
                var city_id = $(this).attr('city_id');
                $.get('/SetCityCookie/' + city_id);
            });
            $("#header_info #select_div_popup span").click(function () {<?//@TODO переписать процесс редиректа при выборе города ?>
                var city_id = $(this).attr('city_id');
                $.get('/SetCityCookie/' + city_id, function (data) {
                    var url = window.location.href;
                    if (url.indexOf('?') > 0) {
                        url = url.slice(0, url.indexOf('?'));
                    }
                    var host = 'www';
                    var domain = 'dostavka05.ru';
                    if (data == '2') {
                        host = 'kaspiysk';
                    }
                    if (data == '3') {
                        domain = 'dostavka.az';
                    }
                    if (data == '4') {
                        host = 'derbent';
                    }
                    if (data == '5') {
                        domain = 'edostav.ru';
                    }
                    if (data == '6') {
                        host = 'vladikavkaz';
                        domain = 'edostav.ru';
                    }
                    if (data ==<?=City::getUserChooseCity();?>) {
                        return;
                    }
                    window.location.href = 'http://' + host + '.' + domain + '?save_city=1';
                });
            });
            $(".cusel-scroll-wrap").attr('trigger', '');
            $("#layer").click(function () {
                $("#choose_city_menu").hide();
                if (!$('#city_ip').is(':visible')) {
                    $("#layer").hide();
                }
            });
            $("#rayon_selector_layer").click(function () {
                if ($(this).attr('closable') == 0) {
                    return false;
                }
                //alert($("#rayon_selector_layer").attr('closable'));
                $("#rayon_selector").hide();
                $(this).hide();
            });
            $("#city_selector span").click(function () {
                $("#header_info #select_div_popup").show();
                $("#header_info #header_info_container #city_selector .arrow_down").css('display', 'inline-block');
            });
            $(document).click(function (e) {
                if (e.target.id != 'select_div_popup' && e.target.id != 'city_selector_link') {
                    $("#select_div_popup").hide();
                    $("#header_info #header_info_container #city_selector .arrow_down").hide();
                }
            });
            $('#invite_friend_layer').click(function () {
                $("#invite_friend").hide();
                $(this).hide();
                $("#water").hide();
            });
        });
    </script>


    <script
        type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=RFLhb6aI5ZLfLtnjxjz1rEHa8xNa3EGDgnUn/vWJb2Dw7SFwoEHKE/5glssuyQ3Ne9vrXeozxwJp/GirCBQ7HH*GogYYiUXEwvgZhiW1EQkj5/1KYOV4EXmZSAHK9y*miGux9NAStj4mVu1XMj9zOQv*ohw/8Rk/tymBr5zgnEg-';</script>

    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script type='text/javascript'>
        (function () {
            var widget_id = 'WyzPa0aT6c';
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = '//code.jivosite.com/script/widget/' + widget_id;
            var ss = document.getElementsByTagName('script')[0];
            ss.parentNode.insertBefore(s, ss);
        })();</script>
    <!-- {/literal} END JIVOSITE CODE -->


    <? if (!Yii::app()->request->cookies['open_ad']->value && (time() < mktime(23, 59, 59, 12, 16, 2015)) && ($this->domain->alias == 'www.dostavka05.ru')) { ?>
        <div class="openAd" style="display:none;">
            <div class="close-add"></div>

            <div style="line-height: 0;position:relative;">
                <img src="/images/poop.jpg" alt="">

                <a target="_blank" href="http://www.dostavka05.ru/blog/45" class="morePopAd">Подробнее</a>
            </div>
        </div>
    <? } ?>
    <script>
        $(window).load(function () {
            ga('send', 'pageview');
        });
    </script>
</body>
</html>

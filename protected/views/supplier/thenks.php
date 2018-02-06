<meta property="og:image" content="http://www.dostavka05.ru/images/logo.png" />
<?
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery', CClientScript::POS_END);
?>

<div id="pop-up">
    <div class="pop-header">
        <div id="close-pop-up3"></div>
    </div>
    <div class="order_success_image" >
        <div style="background-color: #90a548">
            <span class="title">Спасибо! мы приступили <br> к выполнению вашего заказа</span><br>
            <span style="margin-top: -7px;">
                В ближайшее время с вами свяжется оператор <br> для обсуждения доставки.
            </span>
            <span>
                Проследите, чтобы ваш телефон <?=$phone;?> <br> был доступен.
            </span>
        </div>
    </div>
    <? if ($user_registrated) { ?>
        <div class="thanks-order-registration">
            <img src="/images/someimage12.jpg">
            <span >Для завершения <b>регистрации</b> пройдите по ссылке оправленной на ваш
            почтовый адрес.</span>
        </div>
    <? } ?>
    <? $text = str_replace('телефон', 'телефон ' . $phone, Config::model()->find("name='order_message'")->value); ?>
    <div class="thanks-order"><?= $text; ?></div>
    <div id="share_buttons">    
        <span>Поделиться с друзьями</span>
        <img id="facebook_button" src="/images/share_icons/facebook.png">
        <img id="vk_button" src="/images/share_icons/vk.png">
        <a class="share_twitter_button" title="Сделал заказ на dostavka05.ru"
           href="dostavka05.ru" target="_blank">
            <img class="share_button_image" src="/images/share_icons/twitter.png" style="top: -2px;">
        </a>
        <a target="_blank" href="https://www.youtube.com/channel/UCvHhwEzD_xJcC1NimgvzA8w" style="width: 40px; position: relative; top: -6px; left: -4px;">
            <img style="width: 39px" src="/images/youtube2.png">
        </a>
       <?// <div id="ok_shareWidget"></div>?>
    </div>

</div>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId : '523325534501381',
            status : true, // check login status
            cookie : true, // enable cookies to allow the server to access the session
            xfbml : true // parse XFBML
        });
    };
    (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    }());
</script>
<script>
    $("body").append(VK.Share.button({
        url: 'http://dostavka05.ru',
        title: 'Сделал заказ на dostavka05.ru',
        description: 'Я только что, быстро и удобно, сделал заказ еды на dostavka05.ru',
        image: 'http://dostavka05.ru/images/dostavka_2_1.jpg',
        noparse: true
    }));
    $(document).ready(function(){
        <? $orders_array='';
        foreach($orders_info as $order_info){
            $orders_array.="[".$order_info["id"].",'".$order_info["name"]." ','".$order_info["category"]." ',".$order_info["price"].",".$order_info["quantity"]."],";
        }?> 
       // console.log("<?=$t;?>");
        //alert('<?=substr($orders_array, 0, -1);?>');
        var  orders=[<?=substr($orders_array, 0, -1);?>];
        var id=<?=$order_id;?>;
        var price=<?=$order_price;?>;
        var delivery=<?=$order_delivery;?>;
        addOrder(orders,id,price,delivery);
        $("#facebook_button").click(function(){
            FB.ui(
                {
                    method: 'feed',
                    name: "Сделал заказ на dostavka05.ru",
                    link: "http://dostavka05.ru/",
                    picture: 'http://dostavka05.ru/images/dostavka_2_1.jpg',
                    caption: 'Dostavka05',
                    description: 'Я только что, быстро и удобно, сделал заказ еды на dostavka05.ru'
                });
        });

        $("#vk_button").click(function(){
            $("#vkshare0 a").first().trigger('click');
        });
        $('body').on('click','a.share_twitter_button',function(e){
            //We tell our browser not to follow that link
            e.preventDefault();
            //We get the URL of the link
            var loc = $(this).attr('href');
            //We get the title of the link
            var title  = "Я только что, быстро и удобно, сделал заказ еды на dostavka05.ru";
            var tags='';
            //We trigger a new window with the Twitter dialog, in the middle of the page
            window.open('http://twitter.com/share?hashtags='+tags+'&url=' + loc + '&text=' + title + '&', 'twitterwindow', 'height=450, width=550, top='+($(window).height()/2 - 225) +', left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
        });
    });
</script>
<script>
    !function (d, id, did, st) {
        var js = d.createElement("script");
        js.src = "https://connect.ok.ru/connect.js";
        js.onload = js.onreadystatechange = function () {
            if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                if (!this.executed) {
                    this.executed = true;
                    setTimeout(function () {
                        OK.CONNECT.insertShareWidget(id,did,st);
                    }, 0);
                }
            }};
        d.documentElement.appendChild(js);
    }(document,"ok_shareWidget","https://dostavka05.ru","{width:70,height:70,st:'oval',sz:70,nt:1,nc:1}");
</script>
<style>
    #share_buttons{
        border-top: 1px solid rgb(216, 216, 216);
        width: 576px;
        height: 40px;
        margin-left: 300px;
        margin-top: 26px;
        padding-top: 14px;
    }
    #share_buttons span{
        font-family: ProximaNova-Semibold;
        font-size: 14px;
        vertical-align: top;
        display: inline-block;
        padding-top: 6px;
        margin-right: 20px;
    }
    #share_buttons a,#share_buttons img{
        margin-right: 10px;
        cursor: pointer;
        width: 31px;
        overflow: hidden;
        display: inline-block;
        vertical-align: top;
    }
    #ok_shareWidget{
        transform: scale(0.45, 0.45);
        display: inline-block;
        vertical-align: top;
        position: relative;
        top: -20px;
        left: -19px;
    }
</style>
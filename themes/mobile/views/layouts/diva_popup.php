<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

    <? /*<link rel="stylesheet" href="/css/normalize.css">*/ ?>
    <? /*<link rel="stylesheet" href="/css/main.css">*/ ?>
    <? /*<script src="/js/vendor/modernizr-2.6.1.min.js"></script>*/ ?>

    <? // TWITTER BOOTSTRAP  ?>
    <? /*<link rel="stylesheet" href="/resources/bootstrap/css/bootstrap.css">*/ ?>

    <?
    // MAIN CSS

    Diva::linkMainCsss();
    ?>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser
    today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better
    experience this site.</p>
<![endif]-->

<div class="diva">
    <div class="popup">
        <?php echo $content; ?>
    </div>
</div>


<? /*<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.js"></script>*/ ?>
<? /*<script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.0.min.js"><\/script>')</script>*/ ?>
<? /*<script src="/js/plugins.js"></script>*/ ?>
<? /*<script src="/js/main.js"></script>*/ ?>


<? // TWITTER BOOTSTRAP  ?>
<? /*<script src="/resources/bootstrap/js/bootstrap.js"></script>*/ ?>


<?
// MAIN JS
Diva::linkMainJss();
?>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<? /* <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script> */ ?>
</body>
</html>

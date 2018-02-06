<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Административная панель сайта Доставка05.ru</title>
    <link href="/images/favicon.ico" rel="SHORTCUT ICON">

    <!-- JQUERY -->
    <!--script src="/js/jquery-1.7.2.min.js"></script -->
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <!-- BOOTSTRAP -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <script src="/js/bootstrap.js"></script>
</head>
<style>
    .appr-site,
    .appr-partner,
    .appr-delivered{
        display: block;
    }

    .appr-site{
        background: url("/images/blue-fon.png") repeat-x;
        background-size: contain;
        color: #ffffff;
        height: 33%;
    }
    .appr-partner{
        background: url("/images/yellow-fon.png") repeat-x;
        background-size: contain;
        color: #000000;
        height: 33%;
    }
    .appr-delivered{
        background: url("/images/green-fon.png") repeat-x;
        background-size: contain;
        color: #ffffff;
        height: 33%;
    }
    .table tbody .appr-td{
        text-align: center;
        padding: 0;
    }
    .appr-div{
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>
<body>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="/">Доставка 05</a>
        </div>
    </div>
    <!-- /navbar-inner -->
</div>
<!-- /navbar -->
<?php echo $content; ?>
</body>
</html>
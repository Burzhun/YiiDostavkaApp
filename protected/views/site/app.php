<html>
<head>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body style="margin:0">
<?
$ios = array('image' => '/images/iphoneApp.png', 'link' => Config::getValue('app_ios', $this->domain->id));
$android = array('image' => '/images/nexusApp.png', 'link' => Config::getValue('app_android', $this->domain->id));

$current = Yii::app()->mobileDetect->is('iOS') ? $ios : (Yii::app()->mobileDetect->is('AndroidOS') ? $android : array());
?>
<div style="text-align:center">
    <div style="background: url(/images/chooseAppBg.jpg) no-repeat;background-size: cover;width:100%;height:70%;">
        <img src="<?= $current['image']; ?>" style="height: 85%;margin: 0 auto;padding-top: 30px;display: block;"/>
    </div>
    <div>
        <a href="<?= $current['link'] ?>" style="background: #709024;
		    border-radius: 5px;
		    margin: 25px auto 20px auto;
		    display: block;
		    padding: 9px;
		    width: 260px;
		    color: #ffffff;
		    font-size: 20px;
		    font-family: Arial;
		    text-decoration: none;">Установить приложение</a>
    </div>
    <div>
        <a href="/" style="font-size: 21px;
		    font-family: Arial;
		    color: #6c6c6c;
		    margin: 0 auto;
		    padding-top: 10px;
		    text-decoration: none;">Открыть мобильную версию</a>
    </div>
</div>
</body>
</html>
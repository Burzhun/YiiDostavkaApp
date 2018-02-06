<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <title>Административная панель сайта Доставка05.ru</title>
    <link href="/images/favicon.ico" rel="SHORTCUT ICON">

    <!-- JQUERY -->
    <!--script src="/js/jquery-1.7.2.min.js"></script -->
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <!-- BOOTSTRAP -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <script src="/js/bootstrap.js"></script>
    <style type="text/css">
        .mobileUl {
            margin: 0;
            background: #32333f;
        }

        .mobileUl li {
            list-style: none;
        }

        .mobileUl a {
            display: block;
            padding: 20px;
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid #252633;
            border-top: 1px solid #48495c;
            font-size: 18px;
            font-weight: bold;
        }

        .openMenu {
            float: right;
            width: 47px;
            margin: 0px;
            cursor: pointer;
            margin-top: 5px;
        }

        .openMenu span {
            display: block;
            width: 20px;
            height: 3px;
            background: #fff;
            border-radius: 10px;
            margin: 5px auto;

        }

        .relation_partner {
            float: right;
            position: relative;
            top: 6px;

        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="/">Доставка 05</a>
            <div class="openMenu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <?
            $array = '';
            $temp = Relationpartner::model()->findAll(array('condition' => 'owner_id=' . Yii::app()->user->id));

            if ($temp) {
                $owner = User::model()->findByPk(Yii::app()->user->id);
                $array = $temp;
            } else {
                $temp = Relationpartner::model()->find(array('condition' => 'user_id=' . Yii::app()->user->id));
                if ($temp) {
                    $array = Relationpartner::model()->findAll(array('condition' => 'owner_id=' . $temp->owner_id));
                    $owner = User::model()->findByPk($temp->owner_id);
                }
            }
            ?>
            <? if ($array) { ?>
                <select name="relation_partner" class="relation_partner">
                    <option value="">Выберите профиль</option>
                    <option
                        value="<? echo $owner->id ?>" <?= Yii::app()->user->getState("partner") == $owner->partner->id ? "selected" : ""; ?>><? echo $owner->partner->name ?></option>
                    <? foreach ($array as $a) { ?>
                        <option
                            value="<? echo $a->user->id ?>" <?= Yii::app()->user->getState("partner") == $a->user->partner->id ? "selected" : ""; ?>><? echo $a->user->partner->name ?></option>
                    <? } ?>
                </select>
            <? } ?>


        </div>
    </div>
    <!-- /navbar-inner -->
</div>
<!-- /navbar -->
<?php echo $content; ?>
</body>
</html>
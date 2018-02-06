<link href="/font/stylesheet.css" rel="stylesheet" type="text/css">
<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => $breadcrumbs,
));
?>


<script src="/js/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>

<style>
    .nav_partner .nav-pills > li > a {
        border-radius: 0;
        border: 1px solid #cecad3;
        border-right: 0;
        margin: 0;
        padding-left: 15px;
        padding-right: 15px;
        font-size: 12px;
        text-transform: uppercase;
        font-family: 'PT Sans';
    }

    .nav_partner .nav-pills > li:last-child > a {
        border: 0;
        border-left: 0;
        border-top: 1px solid #f5f5f5;
        border-bottom: 1px solid #f5f5f5;
        padding: 5px;

    }

    .nav_partner .nav-pills > li.active > a {
        border: 1px solid #0875b1;
        background-image: #0081c8;
        background-image: -webkit-linear-gradient(top, #0081c8, #0c8dd4);
        background-image: -o-linear-gradient(top, #0081c8, #0c8dd4);
        background-image: -ms-linear-gradient(top, #0081c8, #0c8dd4);
        background-image: linear-gradient(top, #0081c8, #0c8dd4);
        background-image: -moz-linear-gradient(top, #0081c8, #0c8dd4);
        -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#0081c8, endColorstr=#0c8dd4)";

    }

    .debt {
        width: 235px;
        min-height: 23px;
        background: url(/images/debitTop.png) no-repeat top center;
        float: right;
        padding-top: 9px;
        text-align: center;
        overflow: hidden;

    }

    .debtBox {
        background: url(/images/debitMiddle.png) top center no-repeat, url(/images/debitBottom.png) bottom center no-repeat;
        display: none;
        width: 214px;
        margin: auto;
        border-left: 1px solid #DCDCDC;
        border-right: 1px solid #DCDCDC;
        padding-top: 25px;
    }

    .debt a {
        color: #545454;
        font-size: 20px;
        text-transform: uppercase;
        font-weight: normal;
        line-height: normal;
        font-family: 'pf_dindisplay_proregular';

    }

    .debt a span {
        font-size: 36px;
        color: #0875b1;

    }

    .nav_partner .nav-pills .lastlinks a {
        border: 0;
        border-left: 1px solid #cecad3;
        border-top: 1px solid #f5f5f5;
        border-bottom: 1px solid #f5f5f5;
        padding: 5px !important;

    }

</style>

<?
$_debt = OrderPartnerDebt::getDebt($model->id);

$sql = "SELECT id FROM tbl_relation_partner WHERE (owner_id=" . $model->user->id . " OR user_id=" . $model->user->id . ")";
$command = Yii::app()->db->createCommand($sql);
$data = $command->query();

if ($data->read()) {
    $items_array = array(
        array('label' => 'Информация', 'url' => array('/admin/partner/id/' . $model->id . '/info'), 'active' => $this->action->id == 'info' ? true : false),
        array('label' => 'Меню', 'url' => array('/admin/partner/id/' . $model->id . '/menu'), 'active' => $this->action->id == 'menu' || $this->action->id == 'addmenuView' || $this->action->id == 'menuUpdate' || $this->action->id == 'menuView' || $this->action->id == 'addgoodsView' || $this->action->id == 'productView' || $this->action->id == 'productUpdate' ? true : false),
        array('label' => 'Заказы', 'url' => array('/admin/partner/id/' . $model->id . '/orders'), 'active' => $this->action->id == 'orders' || $this->action->id == 'ordersView' ? true : false),
        array('label' => 'Заказы группы', 'url' => array('/admin/partner/id/' . $model->id . '/relation_orders'), 'active' => $this->action->id == 'relation_orders' || $this->action->id == 'ordersView' ? true : false),
        array('label' => 'Профиль', 'url' => array('/admin/partner/id/' . $model->id . '/profile'), 'active' => $this->action->id == 'profile' ? true : false),
        array('label' => '<img src="/images/icon2.png" />', 'url' => array('/admin/partner/id/' . $model->id . '/message'), 'itemOptions' => array('class' => 'lastlinks'), 'active' => $this->action->id == 'message' ? true : false),
        array('label' => '<img src="/images/link.png" />', 'url' => array('/admin/partner/id/' . $model->id . '/swappartner'), 'itemOptions' => array('class' => 'lastlinks'), 'active' => $this->action->id == 'swappartner' ? true : false),
    );
} else {
    $items_array = array(
        array('label' => 'Информация', 'url' => array('/admin/partner/id/' . $model->id . '/info'), 'active' => $this->action->id == 'info' ? true : false),
        array('label' => 'Меню', 'url' => array('/admin/partner/id/' . $model->id . '/menu'), 'active' => $this->action->id == 'menu' || $this->action->id == 'addmenuView' || $this->action->id == 'menuUpdate' || $this->action->id == 'menuView' || $this->action->id == 'addgoodsView' || $this->action->id == 'productView' || $this->action->id == 'productUpdate' ? true : false),
        array('label' => 'Заказы', 'url' => array('/admin/partner/id/' . $model->id . '/orders'), 'active' => $this->action->id == 'orders' || $this->action->id == 'ordersView' ? true : false),
        array('label' => 'Профиль', 'url' => array('/admin/partner/id/' . $model->id . '/profile'), 'active' => $this->action->id == 'profile' ? true : false),
        array('label' => '<img src="/images/icon2.png" />', 'url' => array('/admin/partner/id/' . $model->id . '/message'), 'itemOptions' => array('class' => 'lastlinks'), 'active' => $this->action->id == 'message' ? true : false),
        array('label' => '<img src="/images/link.png" />', 'url' => array('/admin/partner/id/' . $model->id . '/swappartner'), 'itemOptions' => array('class' => 'lastlinks'), 'active' => $this->action->id == 'swappartner' ? true : false),
    );
} ?>



<div class="h1-box">
    <div class="well">
        <div style="min-width:800px;">
            <div style="float:left" class='nav_partner'>
                <h1><?php echo $h1 ?></h1><br>
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'encodeLabel' => false,
                    'items' => $items_array,
                    'htmlOptions' => array('class' => "nav nav-pills"),
                ));
                ?>
            </div>
            <script src="http://yandex.st/jquery/easing/1.3/jquery.easing.min.js" type="text/javascript"></script>
            <? if (Yii::app()->controller->action->id == "info") { ?>
                <div class='debt'>
                    <div class='debtBox'>
                        <a href="/admin/partner/id/<? echo $model->id; ?>/orderDebt">Задолженность<br><span><?= $_debt ?></span>
                            руб</a><br>
                    </div>
                </div>
            <? } ?>




            <script>
                //$(".debt").animate({height:'79px'},"easeInQuart")


                $(".debtBox").stop().slideDown("slow", 'easeInExpo');

                // $(".debt").animate({height:'10px'},250,function(){
                // $(".debt").animate({height:'79px'},300);
                // })


            </script>
        </div>
    </div>
</div>
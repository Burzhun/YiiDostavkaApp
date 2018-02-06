<?php /** @var Partner $model */ ?>
<link href="/font/stylesheet.css" rel="stylesheet" type="text/css">
<?php $partnerModel = Partner::model()->find(array('condition' => 'user_id=' . Yii::app()->user->id));
$message = Message::model()->findAll(array('condition' => 'partner_id=' . $partnerModel->id . ' AND `read`=0'));
if ($partnerModel->message) {
    foreach ($message as $m) { ?>
        <div class="alert alert-error">
            <button class="close" data-dismiss="alert" id="<?php echo $m->id ?>">×</button>
            <strong>Сообщение!</strong><br><br><?php echo $m->text; ?>
        </div>
    <?php } ?>
<?php } ?>

<script type="text/javascript">
    $(".close").click(function () {
        $.ajax({
            url: '<?php echo CController::createUrl('/message/ajaxDelete');?>',
            type: "post",
            cache: false,
            data: {"partner_id":<?php echo $partnerModel->id;?>, "message_id": $(this).attr('id')},
            success: function (data) {

            }
        });
    });
</script>
<script src="/js/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>

<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => $breadcrumbs,
));
?>

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
        border-left: 1px solid #cecad3;
        border-top: 1px solid #fff;
        border-bottom: 1px solid #fff;
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
        padding-top: 20px;
        padding-bottom: 11px;
    }

    .debt .debt-balans {
        color: rgba(25, 25, 25, 1);
        font-size: 14px;
        text-transform: uppercase;
        font-weight: normal;
        line-height: normal;
        font-family: 'pf_dindisplay_proregular';
    }

    .debt .debt-add {
        font-size: 14px;
        text-transform: uppercase;
        font-weight: normal;
        line-height: normal;
        font-family: 'pf_dindisplay_proregular';
        display: inline-block;
        background-color: #20C520;
        color: white;
        padding: 14px;
        border-radius: 10px;
    }

    .debt .debt-balans {
        margin-bottom: 9px;
        display: inline-block;
    }

    .debt .debt-balans span {
        font-size: 25px;
        color: #0875b1;
    }

    .popupPay {
        /*display: none;*/
        height: 0;
        transition: height 0.20s ease-out;
    }

    .popupPay.active {
        height: 84px;
    }

    #payForm input[type="text"] {
        width: 50px;
    }

    <?
    $sql = "SELECT id FROM tbl_relation_partner WHERE (owner_id=".$model->user->id." OR user_id=".$model->user->id.")";
    $command = Yii::app()->db->createCommand($sql);
    $data = $command->query();


    $items_array=array(
        array('label'=>'Информация', 'url'=>array('/partner/info'), 'active'=>$this->id=='info'?true:false),
        array('label'=>'Меню', 'url'=>array('/partner/menu'), 'active'=>$this->id=='menu'?true:false),
        array('label'=>'Отзывы', 'url'=>array('/partner/review'), 'active' => $this->id == 'review'? true : false),
    );
    /** Меню для кафе с группой и без */
    if($data->read()){
        $items_array=array_merge($items_array,array(
            array('label'=>'Заказы', 'url'=>array('/partner/orders'), 'active'=>($this->id=='orders' && $this->action->id=='index') ? true : false),
            array('label'=>'Заказы группы', 'url'=>array('/partner/orders/group'), 'active'=>$this->action->id=='group'?true:false)
        ));
    }
    else{
        $items_array=array_merge($items_array,array(array('label'=>'Заказы', 'url'=>array('/partner/orders'), 'active'=> $this->id == 'orders' ? true : false)));
    }
    $items_array=array_merge($items_array,array(
            array('label'=>'Профиль', 'url'=>array('/partner/profile'), 'active'=>$this->id=='profile'?true:false),
            array('label'=>'Платежи', 'url'=>array('/partner/payment'), 'active'=>$this->id=='payment'?true:false),

        ));
    if($partner->city_id==3){
        $items_array=array_merge($items_array,array(array('label'=>'Районы', 'url'=>array('/partner/rayon'), 'active'=>$this->id=='rayon'?true:false)));
    }
    $items_array=array_merge($items_array,array(array('label'=>'<img src="/images/icon2.png" />', 'url'=>array('/partner/message'), 'active'=>$this->id=='message'?true:false)));

    $data->readAll();
    //print_r();
    ?>

</style>
<div class="h1-box">
    <div class="well">
        <div style="min-width:800px;">
            <div style="float:left" class='nav_partner'>


                <? if (Yii::app()->controller->id == "orders" && Yii::app()->controller->action->id == "index") { ?>
                    <h1 style="display:inline-block;padding:0 18px 18px 0;"><?php echo $h1 ?></h1>
                    <a href="/partner/orders/history">История заказов</a>
                <? } else { ?>
                    <h1><?php echo $h1 ?></h1>
                <? } ?>

                <br>
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'encodeLabel' => false,
                    'items' => $items_array,
                    'htmlOptions' => array('class' => "nav nav-pills"),
                ));
                ?>
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
                    <option value="<? echo $owner->id ?>"
                            1 <?= Yii::app()->user->getState("partner") == $owner->partner->id ? "selected" : ""; ?>><? echo $owner->partner->name ?></option>
                    <? foreach ($array as $a) { ?>
                        <option
                            value="<? echo $a->user->id ?>" <?= Yii::app()->user->getState("partner") == $owner->partner->id ? "selected" : ""; ?>><? echo $a->user->partner->name ?></option>
                    <? } ?>
                </select>
            <? } ?>
            <? if (Yii::app()->controller->id == "info") { ?>
                <div class='debt'>
                    <div class='debtBox'>
                        <a href="/partner/statistic/orders" class="debt-balans">Ваш
                            баланс<br><span><?= $model->balance; ?></span> руб</a>
                        <br>
                        <a href="#" class="debt-add" id="debt-add">Пополнить счет?</a>

                        <div class="popupPay">
                            <br>
                            <? //if(isset($_GET['1'])){?>
                            <form id="payForm" action="https://merchant.pay2pay.com/?page=init" method="post">
                                <label>Сумма: <input type="text" id="sum" value=""> <?php echo City::getMoneyKod(); ?>
                                </label>
                                <input type="hidden" id="xml" name="xml" value=""/>
                                <input type="hidden" id="sign" name="sign" value=""/>
                                <input type="submit" onClick="beforeSubmit(); return false"/>
                            </form>
                            <? //}?>
                        </div>
                    </div>
                </div>
            <? } ?>

            <script type="text/javascript">
                $(document).ready(function () {
                    $('.debt-add').click(function () {
                        $('.popupPay').toggleClass('active')
                    })
                })

                function beforeSubmit() {
                    $.ajax({
                        type: 'POST',
                        url: '/partner/pay/json',
                        dataType: 'json',
                        data: 'id=<?=$model->id?>&sum=' + $("#sum").val(),
                        success: function (data) {
                            // alert('dddd')
                            if (!data.error) {
                                $('#xml').val(data.xml)
                                $('#sign').val(data.sign)
                                $('#payForm').submit();
                            } else {
                                //alert('gggg')
                            }
                            console.log(data)
                        }
                    })
                    return true;
                }
            </script>

            <? /* if(isset($_GET['1'])){ ?>

				<br>
				<div class='debt'>
					<div class='debtBox'>
						<!-- <a href="/partner/statistic/orders" class="debt-balans">Ваш баланс<br><span><?=$model->balance;?></span> руб</a> -->
						<!-- <br> -->
						<!-- <a href="#" class="debt-add">Пополнить счет?</a> -->

						<form id="payForm" action="https://merchant.pay2pay.com/?page=init" method="post">
							<label>Сумма: <input type="text" id="sum" value=""> <?php echo City::getMoneyKod();?></label>
						 	<input type="hidden" id="xml" name="xml" value=""/>
						 	<input type="hidden" id="sign" name="sign" value=""/>
						 	<input type="submit" onClick="beforeSubmit(); return false"/>
						 </form>
					</div>
				</div>

			<? } */ ?>
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

<script>
    $('.relation_partner').change(function () {
        window.location = '/partner/swappartner/' + this.value;
    });
</script>
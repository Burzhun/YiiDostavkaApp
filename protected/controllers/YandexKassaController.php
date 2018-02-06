<?php

class YandexKassaController extends Controller
{
    public function actionIndex()
    {
        echo 'Kassa'; 
    }

    public function actionCheckUrl()
    {
    	$configs = array();
		$configs['shopId'] 			= 102170;
		$configs['scId'] 			= 42642;
		$configs['ShopPassword'] 	= 'i79697867O';
        $t_order=Temp_Order::model()->findByPk($_POST['orderNumber']);       
        $condition = "session_id='" . $t_order->session_id . "'";
        

        $cart = CartItem::model()->findAll(array("condition" => $condition));
        //Yii::log($t,'error');
        $sum = 0;
        foreach ($cart as $c) {
            $sum += $c->price * $c->quality;
            if ($c->quality == 0) {
                $c->delete();
            }
        }
        $sum=(int)$sum;
        $sum_kassa=(int)$_POST['orderSumAmount'];
		$hash = md5($_POST['action'].';'.$_POST['orderSumAmount'].';'.$_POST['orderSumCurrencyPaycash'].';'.$_POST['orderSumBankPaycash'].';'.$configs['shopId'].';'.$_POST['invoiceId'].';'.$_POST['customerNumber'].';'.$configs['ShopPassword']);		
		if (strtolower($hash) != strtolower($_POST['md5'])){            
			$code = 1;
		}elseif($sum!=$sum_kassa){
            $code = 100;
            print '<?xml version="1.0" encoding="UTF-8"?>';
            print '<checkOrderResponse performedDatetime="'. $_POST['requestDatetime'] .'" code="'.$code.'"'. ' invoiceId="'. $_POST['invoiceId'] .'" shopId="'. $configs['shopId'] .'" 
            message="Злостный хацкер" techMessage="Злостный хацкер"/>';
            exit;
        }
		else {
			$code = 0;
		}
		print '<?xml version="1.0" encoding="UTF-8"?>';
		print '<checkOrderResponse performedDatetime="'. $_POST['requestDatetime'] .'" code="'.$code.'"'. ' invoiceId="'. $_POST['invoiceId'] .'" shopId="'. $configs['shopId'] .'"/>';
    }

    public function actionAvisoUrl()
    {
        $configs = array();
        $configs['shopId']          = 102170;
        $configs['scId']            = 42642;
        $configs['ShopPassword']    = 'i79697867O';

        $hash = md5($_POST['action'].';'.$_POST['orderSumAmount'].';'.$_POST['orderSumCurrencyPaycash'].';'.$_POST['orderSumBankPaycash'].';'.$configs['shopId'].';'.$_POST['invoiceId'].';'.$_POST['customerNumber'].';'.$configs['ShopPassword']);        
        if (strtolower($hash) != strtolower($_POST['md5'])){ 
            $code = 1;            
        }
        else {
            $code = 0;
            $order=new Order;
            $t=Temp_Order::model()->findByPk($_POST['orderNumber']);
            $order->setAttributes($t->attributes,false);
            $order->date=date('Y-m-d H:i:s');
            $order->info=$order->info." Оплачено карточкой.";
            $order->id=null;
            if($order->partner->token_ios!=''){
                Partner::PushIos($order->partner->token_ios);
            }
            $order->save();
            $t->order_id=$order->id;
            if($order->domain_id!=1){
                $phone=Order::FormatPhone($order->phone);
                if($phone!=null){
                    $order->phone=$phone;
                    $order->save();
                }
            }
            $t->kassa_status='payed';
            $cart=CartItem::model()->findAll(array("condition" => "session_id='" . $t->session_id. "'"));
            foreach ($cart as $c) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->goods_id = $c->goods_id;
                $orderItem->quantity = $c->quality;
                $orderItem->total_price = $c->quality * $c->price;
                $orderItem->price_for_one = $c->price;
                $orderItem->save();
            }
            CartItem::model()->deleteAll(array("condition" => "session_id='" . $t->session_id. "'"));
            $t->save();
        }
        print '<?xml version="1.0" encoding="UTF-8"?>';
        print '<paymentAvisoResponse performedDatetime="'. $_POST['requestDatetime'] .'" code="'.$code.'" invoiceId="'. $_POST['invoiceId'] .'" shopId="'. $configs['shopId'] .'"/>';
    }

  

    

    public function actionSuccessUrl()
    {
        //print_r($_GET);  
        if(!Yii::app()->mobileDetect->isMobile()){
            echo '<script>window.close();</script>';
        }else{
            header("Location:/order/thanks");
        }
        //$order->save();
    }

    public function actionFailUrl()
    {
        $t=Temp_Order::model()->findByPk($_GET['orderNumber']);
        $t->kassa_status='cancelled';
        $t->save();
        if(!Yii::app()->mobileDetect->isMobile()){
            echo '<script>window.close();</script>';
        }else{
            $url="";
            $city=City::model()->find("name='".$t->city."'");
            $domain=Domain::model()->findByPk($city->domain_id);
            $url="http://".$domain->alias."/cart";
            header("Location:".$url);
        }

        //header("Location:https://dostavka05.ru/test.php?order_id=".$_GET['orderNumber']."");
    }

    

    public function actioncheck_kassa(){
        $order=Temp_Order::model()->find(array("condition"=>"session_id='".Yii::app()->session->sessionID."'","order"=>"id desc"));
        if($order){
            echo $order->kassa_status;
        }else{
            echo 'cancelled';
        }
        exit;

    }
}
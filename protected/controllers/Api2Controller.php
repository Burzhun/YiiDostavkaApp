<?php

class Api2Controller extends Controller
{
    public function actionSpecialization()
    {
        $direction_id = Yii::app()->request->getParam('direction_id');
        $city_id = Yii::app()->request->getParam('city_id');

        if(!$city_id)
        {
            $city_id = City::getUserChooseCity();
        }

        if ($direction_id == 1) {
            $specs=Specialization::model()->findAll(array("order"=>"pos",'condition'=>"direction_id=1 and city_id=".$city_id,"limit" =>5));
            $arr=array();
            $arr[]=array('id'=>0,'name'=>'Все рестораны','image'=>'');
            foreach($specs as $key=>$spec) {
                $arr[]=array('id' => $spec->id, 'name' =>$spec->name, 'image' => $spec->getAppImage());
            }
        }

        if ($direction_id == 2) {
            $arr = array();
            $arr[] = array('id'=>0,'name'=>'Все магазины','image'=>'');
            $arr[] = array('id' => 13, 'name' => 'Фермерские продукты', 'image' => '/upload/specialization/ferm.png');
            $arr[] = array('id' => 14, 'name' => 'Молочные продукты', 'image' => '/upload/specialization/milk.png');
            $arr[] = array('id' => 15, 'name' => 'Деликатесы', 'image' => '/upload/specialization/delikates.png');
            $arr[] = array('id' => 16, 'name' => 'Выпечка', 'image' => '/upload/specialization/vypechka.png');
            $arr[] = array('id' => 12, 'name' => 'Гастрономия', 'image' => '/upload/specialization/gastronom.png');
        }

        echo json_encode($arr,JSON_UNESCAPED_UNICODE);

    }


    public function actionCities()
    {
        $region_id = Yii::app()->request->getParam('region_id');
        $region_id = preg_replace("/[^\s\w]+/u","",$region_id);
        $where = $region_id ? " WHERE region LIKE '{$region_id}'" : '';
        $sql = "SELECT id, name FROM tbl_city ".$where;

        $connection = Yii::app()->db;
        $command = $connection->cache(30000)->createCommand($sql);
        $cities = $command->queryAll();

        echo json_encode(array('error' => 0, 'msg' => '', 'data' => $cities));
    }

    public function actionPartner(){
        $partner_id=Yii::app()->request->getParam('partner_id');
        $partner=Partner::model()->findByPk($partner_id);
        echo json_encode($partner->attributes);
        exit;
    }

    public function actionPartners()
    {
        $city_id = Yii::app()->request->getParam('city_id');
        $spec_id = Yii::app()->request->getParam('spec_id');
        $direction_id = Yii::app()->request->getParam('direction_id');
        $open_rest = Yii::app()->request->getParam('open_rest');

        $where = ' WHERE status = 1 AND self_status = 1 AND soon_opening = 0'; // @TODO Попытка определить открыто ли заведение
        if ($city_id) {
            $where .= " AND city_id = $city_id ";
        }
        if ($spec_id) {
            $where .= " AND id IN (SELECT partner_id FROM tbl_spec_partner WHERE spec_id = $spec_id) ";
        } elseif ($direction_id) {
            $where .= " AND id IN (SELECT partner_id FROM tbl_spec_partner WHERE spec_id IN (SELECT id FROM tbl_specialization WHERE direction_id = " . $direction_id . "))";
        }

        $now = date('H, i, s');
        if ($open_rest == 1) {

            $where .= " AND (work_begin_time < work_end_time AND work_begin_time < MAKETIME($now) AND MAKETIME($now) < work_end_time) OR
							(work_begin_time > work_end_time AND 
								((work_begin_time < MAKETIME($now) AND MAKETIME($now) < MAKETIME(23, 59, 59)) OR work_end_time > MAKETIME($now))
							) OR (work_begin_time = work_end_time) ";
        }

        $sql = "SELECT id, name, `text`, min_sum, delivery_cost, delivery_duration, img, logo, soon_opening,
		IF((work_begin_time < work_end_time && work_begin_time < MAKETIME($now) && MAKETIME($now) < work_end_time) || 
		(work_begin_time > work_end_time && ((work_begin_time < MAKETIME($now) && MAKETIME($now) < MAKETIME(23, 59, 59)) || work_end_time > MAKETIME($now))) || 
        (work_begin_time = work_end_time), 1, 0) AS opened 
		FROM tbl_partners $where ORDER BY opened DESC, position";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);//@TODO нужно закрыть от sql инъекций
        $partners = $command->queryAll();

        echo json_encode(array('error' => 0, 'msg' => '', 'data' => $partners));
    }


    public function actionMenu()
    {
        $partner_id = Yii::app()->request->getParam('partner_id');
        $subcat_id = Yii::app()->request->getParam('subcat_id');

        if (!$partner_id) {
            echo json_encode(array('error' => 1, 'msg' => 'Нет обязательного параметра', 'data' => array()));
            exit;
        }

        $sql = "SELECT id, name, have_subcatalog, parent_id
		FROM tbl_menu WHERE publication = 1 AND partner_id = $partner_id ORDER BY pos";
        $connection = Yii::app()->db;
        $command = $connection->cache(10000)->createCommand($sql);//@TODO нужно закрыть от sql инъекций
        $menu = $command->queryAll();

        if (!empty($subcat_id) || $subcat_id === '0') {
            //выводим меню только парент_ид = сабкаталог
            $new_menu = array();
            foreach ($menu as $key => $cat) {
                if ($cat['parent_id'] == $subcat_id) {
                    $new_menu[] = $cat;
                }
            }
            $menu = $new_menu;
        }

        echo json_encode(array('error' => 0, 'msg' => '', 'data' => $menu));
    }


    public function actionMenuAll()
    {
        $partner_id = Yii::app()->request->getParam('partner_id');

        if (!$partner_id) {
            echo json_encode(array('error' => 1, 'msg' => 'Нет обязательного параметра', 'data' => array()));
            exit;
        }

        $sql = "SELECT id, name, have_subcatalog, parent_id
		FROM tbl_menu WHERE publication = 1 AND partner_id = $partner_id ORDER BY pos";
        $connection = Yii::app()->db;
        $command = $connection->cache(10000)->createCommand($sql);//@TODO нужно закрыть от sql инъекций
        $menu = $command->queryAll();
        $ready_menu = array();

        foreach ($menu as $key => $cat) {
            if ($cat['parent_id'] == 0) {
                $sub_menu = array();
                foreach ($menu as $key2 => $cat2) {
                    if ($cat2['parent_id'] == $cat['id']) {
                        $sub_menu[] = $cat2;
                    }
                }
                $cat['sub_menu'] = $sub_menu;
                $ready_menu[] = $cat;
                //$ready_menu[$cat['id']]['subcats'] = $sub_menu;
                //$ready_menu[] = $sub_menu;
            }
        }
        echo json_encode(array('error' => 0, 'msg' => '', 'data' => $ready_menu));
    }


    public function actionGoods()
    {
        $menu_id = Yii::app()->request->getParam('menu_id');

        if (!$menu_id) {
            echo json_encode(array('error' => 1, 'msg' => 'Нет обязательного параметра', 'data' => array()));
            exit;
        }

        $sql = "SELECT id, name, img, price, shorttext, `text`
		FROM tbl_goods WHERE publication = 1 AND parent_id = $menu_id ORDER BY pos";
        $connection = Yii::app()->db;
        $command = $connection->cache(30000)->createCommand($sql);//@TODO нужно закрыть от sql инъекций
        $goods = $command->queryAll();

        echo json_encode(array('error' => 0, 'msg' => '', 'data' => $goods));
    }



    public function actionCart()
    {
        $items = explode(',', $_GET['items']);

        if (!$items) {
            echo json_encode(array('error' => 1, 'msg' => 'Нет обязательного параметра', 'data' => array()));
            exit;
        }

        $items_str = '(1=2 ';
        foreach ($items as $key => $value) {
            $items_str .= ' OR id = ' . (int)$value;
        }
        $items_str .= ')';

        $sql = "SELECT id, name, img, price, shorttext, `text`
		FROM tbl_goods WHERE $items_str";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);//@TODO нужно закрыть от sql инъекций
        $goods = $command->queryAll();

        echo json_encode(array('error' => 0, 'msg' => '', 'data' => $goods));
    }


    public function actionSearch()
    {
        $text = Yii::app()->request->getParam('text');
        $city_id = Yii::app()->request->getParam('city_id');//@TODO нужно ли сделать его обязательно?
        $open_rest = Yii::app()->request->getParam('open_rest');
        if (!$text) {
            echo json_encode(array('error' => 1, 'msg' => 'Нет обязательного параметра text', 'data' => array()));
            exit;
        }

        if (!$city_id) {
            echo json_encode(array('error' => 2, 'msg' => 'Нет обязательного параметра city_id', 'data' => array()));
            exit;
        }

        $now = date('H, i, s');
        $where = ' WHERE status = 1 AND self_status = 1 ';
        if ($open_rest == 1) {
            $where .= " AND (work_begin_time < work_end_time AND work_begin_time < MAKETIME($now) AND MAKETIME($now) < work_end_time) OR
							(work_begin_time > work_end_time AND 
								((work_begin_time < MAKETIME($now) AND MAKETIME($now) < MAKETIME(23, 0, 0)) OR work_end_time > MAKETIME($now))
							) OR (work_begin_time = work_end_time) ";
        }
        $where .= " AND city_id = $city_id ";
        $where .= " AND name LIKE '%$text%' ";

        $sql = "SELECT *,
        IF((work_begin_time < work_end_time && work_begin_time < MAKETIME($now) && MAKETIME($now) < work_end_time) || 
        (work_begin_time > work_end_time && ((work_begin_time < MAKETIME($now) && MAKETIME($now) < MAKETIME(23, 59, 59)) || work_end_time > MAKETIME($now))) || 
        (work_begin_time = work_end_time), 1, 0) AS opened 
		FROM tbl_partners $where ORDER BY opened DESC, position";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);//@TODO нужно закрыть от sql инъекций
        $partners = $command->queryAll();


        echo json_encode(array('error' => 0, 'msg' => '', 'data' => $partners));
    }

    //Заказ из cherry05.ru
    public function actionOrder2()
    {
        if (isset($_POST['order'])) {
            $orderArr = json_decode($_POST['order']);
            //var_dump($_POST['order']);
            //echo $orderArr->date;
            $order = new Order;
            $order->user_id =  0;
            $order->partners_id = $orderArr->partners_id;
            $order->date = date('Y-m-d H:i:s');
            $order->phone = $orderArr->phone;
            $order->customer_name =$orderArr->customer_name;
            $order->info = $orderArr->info;
            $order->status = Order::$APPROVED_SITE;
            $order->order_source = 1;// == 3 

            $order->city = $orderArr->city;
            $order->street = $orderArr->street;
            $order->house = $orderArr->house;
            $order->storey = $orderArr->storey;
            $order->number = $orderArr->number;
            //var_dump($order);
            if ($order->save()) {
                $cart = json_decode($_POST['cart']);
                foreach ($cart as $c) {
                    $goods = Goods::model()->findByPk($c->goods_id);
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->goods_id = $c->goods_id;
                    $orderItem->quantity = $c->quantity;
                    $orderItem->total_price = $c->price ? $c->quality * $c->price : $c->quality * $goods->getOrigPrice($order->domain_id);
                    $orderItem->price_for_one = $c->price ? $c->price : $goods->getOrigPrice($order->domain_id);
                    //var_dump($orderItem->goods_id);
                    if($orderItem->save()){

                    }else{
                        print_r($orderItem->getErrors());
                    }
                }

                if ($order->partners_id <= 0) {
                    $order->partners_id = $orderItem->goods->partner_id;
                }

                if($order->partner->token_ios!=''){
                    Partner::PushIos($order->partner->token_ios);
                }
                
                //Начисление баллов за заказ
              //  UserBonus::getBonusForOrder($order);
                $order->sendSMS();


                echo 1;
                Yii::app()->end();
            } else {
                print_r($order->getErrors());
                echo -1;
                Yii::app()->end();
            }
        }

        echo -1;
        exit();
    }

    
    public function actionLogout($token){
        $user_token=UserToken::model()->find("token='".$token."'");
        $user_token->logged=0;
        $user_token->save();
        echo 1;
    }
    
    public function actionPush(){
        $deviceToken = Yii::app()->request->getParam('deviceToken');
        $partner_id = Yii::app()->request->getParam('partner_id');
        $user_token=UserToken::model()->find("token='".$partner_id."' and logged=1");
        $user=User::model()->findByPk($user_token->user_id);
        $partner=$user->partner;
        $partner->token_ios=$deviceToken;
        if($partner->save()){
            echo 1;
            Yii::app()->end();
        }
    }
    
    public function actionPush2(){
        Partner::PushIos("ee3e7e80c9695b983122fa6c654957c49510fae89bd62496364dcd7c59322fd8");
    }
}
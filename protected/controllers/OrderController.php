<?php

class OrderController extends Controller
{
    public function actions()
    {

    }

    public function actionIndex()
    {
        /*********
         *
         *
         *
         *
         *
         *
         * НЕ РАБОЧИЙ!!!!!!!!!!!!!!!!!!!!!!
         *
         *
         *
         *
         *
         *
         *********/
        if (Yii::app()->user->role == User::USER) {
            CartItem::model()->deleteAll(array('condition' => "session_id='" . Yii::app()->session->sessionId . "' AND user_id!='" . Yii::app()->user->id . "'"));
            $condition = "user_id='" . Yii::app()->user->id . "'";
        } else {
            $condition = "session_id='" . Yii::app()->session->sessionId . "'";
        }
        /** @var CartItem[] $cart */
        $cart = CartItem::model()->findAll(array("condition" => $condition));

        //Проверяем все ли товары от одного паставщика и заодно считаем сумму и заодно проверяем есть ли cartItem.кол==0 => удаляем
        $partnerCount = 0;
        $sum = 0;
        $current_partner = "";
        $have_deleted_item = false;
        foreach ($cart as $c) {
            if ($c->partner_id != $current_partner) {
                $current_partner = $c->partner_id;
                $partnerCount++;
            }
            $sum += $c->price * $c->quality;
            if ($c->quality == 0) {
                $c->delete();
                $have_deleted_item = true;
            }
        }

        //если больше одного поставщика, значит что то не так
        if ($partnerCount != 1) exit();//нада сделать обработчик на данную ошибку, типа хацкер сосни этих сдобных мягких булочек и выпей яду))

        //если при проходе по карзине был найден пустой (равен 0 кол) и удален пункт, снова запрашиваем список товаров в корзине
        if ($have_deleted_item) {
            $cart = CartItem::model()->findAll(array("condition" => $condition));
        }
        //выбираем партнера у которого произовдиться заказ
        /** @var Partner $partner */
        $partner = Partner::model()->findByPk($current_partner);
        //print_r($current_partner);
        //print_r($partner->id);exit();
        //выбираем пользователя создающий заказ. Если это гость делает заказ, то переменную $user оставляем пустой
        $user = "";
        if (Yii::app()->user->role == User::USER) {
            $user = User::model()->findByPk(Yii::app()->user->id);
        }

        if (isset($_POST['Order'])) {
            $order = new Order;
            $order->user_id = !empty($user) ? $user->id : 0;
            $order->partners_id = $partner->id;
            $order->date = date('Y-m-d H:i:s');
            $order->phone = $_POST['Order']['phone'];
            $order->customer_name = !empty($user) ? $user->name : $_POST['Order']['name'];
            $order->info = $_POST['Order']['text'];
            $order->status = Order::$APPROVED_SITE;
            $order->order_source = Yii::app()->theme == 'mobile' ? Order::$SOURCE_ORDER_MOBILE : Order::$SOURCE_ORDER_DESKTOP;

            // если зареган то адрес ищем в базе и добавляем из нее
            if (Yii::app()->user->role == User::USER) {
                if (UserAddress::model()->count(array("condition" => "user_id='" . Yii::app()->user->id . "'")) == 0)//если у пользователя не было адреса
                {
                    $address = new UserAddress();
                    $address->attributes = $_POST['Address'];
                    $address->city_id = '1';
                    $address->user_id = Yii::app()->user->id;
                    if ($address->validate()) {
                        $address->save();
                        $order->city = $address->city->name;
                        $order->street = $address->street;
                        $order->house = $address->house;
                        $order->storey = $address->storey;
                        $order->number = $address->number;
                    } else {
                        exit();
                    }
                } else//если пользователь выбрал один из существующих адресов
                {
                    /** @var UserAddress $address */
                    $address = UserAddress::model()->findByPk($_POST['Order']['address']);
                    $order->city = $address->city->name;
                    $order->street = $address->street;
                    $order->house = $address->house;
                    $order->storey = $address->storey;
                    $order->number = $address->number;
                }
            } else // иначе тащим все из формы
            {
                $order->city = $partner->city->name;//City::model()->findByPk($_POST['Address']['city'])->name;
                $order->street = $_POST['Address']['street'];
                $order->house = $_POST['Address']['house'];
                $order->storey = (int)$_POST['Address']['storey'];
                $order->number = (int)$_POST['Address']['number'];
            }

            if ($order->save()) {

                foreach ($cart as $c) {
                    /** @var OrderItem $orderItem */
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->goods_id = $c->goods_id;
                    $orderItem->quantity = $c->quality;
                    $orderItem->total_price = $c->quality * $c->price;
                    $orderItem->price_for_one = $c->price;
                    $orderItem->save();
                }
                CartItem::model()->deleteAll(array("condition" => $condition));
                $this->render('/order/thanks', array("partner_tname" => $partner->tname));
            }
        }
        $this->render('index', array("sum" => $sum, "partner" => $partner, "user" => $user));
    }


    public function actionAddAdress()
    {
        if (Yii::app()->request->isAjaxRequest && !Yii::app()->user->isGuest) {
            $address = new UserAddress();
            $address->attributes = $_POST['Address'];
            $address->city_id = '1';
            $address->user_id = Yii::app()->user->id;
            if ($address->validate()) {
                $address->save();
                $address_arr = UserAddress::model()->findAll(array('condition' => 'user_id=' . Yii::app()->user->id, 'order' => 'id DESC'));
                echo $this->renderPartial('_address_select', array('address' => $address_arr));
                Yii::app()->end();
            }
        }
    }

    public function actionCheckDeliveredOrder()
    {
        /** @var Order $ords */
        $ords = Order::model()->findAll(array('condition' => "status='" . Order::$APPROVED_PARTNER . "'"));

        foreach ($ords as $o) {
            if (strtotime(date('Y-m-d', strtotime($o->date)) . " " . $o->approved_partner) < time() - 3600) {
                $o->status = Order::$DELIVERED;
                $o->save();
            }
        }
    }

    public function actionCron()
    {
        /** @var Order $order */
        $order = Order::model()->findAll('status = "' . Order::$APPROVED_PARTNER . '"');
        foreach ($order as $key => $value) {
            $createdTime = date('H:i:s', strtotime($value->date));
            if ($createdTime > $value->approved_partner) { //Условие говорит о том что период времени от принятия заказа до смены статуса на "Принят партнером" равен как минимум один день или больше
                $approvedTime = strtotime(date('Y-m-d ' . $value->approved_partner));
            } else {
                $approvedTime = strtotime(date('Y-m-d ' . $value->approved_partner, strtotime($value->date)));
            }

            if ((((time() - $approvedTime) / 60) >= 60)) {
                $value->status = Order::$DELIVERED;
                $value->save();
            }
            //echo $key." ".$value->id."->".date('Y-m-d '.$value->approved_partner, strtotime($value->date))."->".$approvedTime."<br>";
        }
        //$file = 'cronlog.txt';
        // Открываем файл для получения существующего содержимого
        //$current = file_get_contents($file);
        // Добавляем нового человека в файл
        //$current .= "крон - сработал ".date("d-m-Y в H:i:s")."\n";
        // Пишем содержимое обратно в файл
        //file_put_contents($file, $current);
    }

    public function bitweenDate()
    {
        $start_day = "";
        $finish_day = "";

        $lastMonth = (int)date("m") - 1;
        $last3Month = (int)date("m") - 3;
        $last6Month = (int)date("m") - 6;
        $lastYear = (int)date("Y") - 1;

        if ($lastMonth < 1) {
            $lastMonth = strtotime(date("01-" . $lastMonth . "-" . $lastYear));
        } else {
            $lastMonth = strtotime(date("01-" . $lastMonth . "-Y"));
        }

        if ($last3Month < 1) {
            $last3Month = strtotime(date("01-" . (12 - $last3Month) . "-" . $lastYear));
        } else {
            $last3Month = strtotime(date("01-" . $last3Month . "-Y"));
        }

        if ($last6Month < 1) {
            $last6Month = strtotime(date("01-" . (12 - $last6Month) . "-" . $lastYear));
        } else {
            $last6Month = strtotime(date("01-" . $last6Month . "-Y"));
        }


        $start_day = strtotime(date("01-m-Y"));//за текущий месяц
        $finish_day = time();
        return "(date > '" . date('Y-m-d H:i:s', $start_day) . "' AND date < '" . date('Y-m-d H:i:s', $finish_day) . "')";
    }

    public function actionCron_sort_partners()
    {

        $partners = Partner::model()->findAll("position>100");
        $partners_array = array();
        foreach ($partners as $partner) {
            $sql = "
                SELECT id,partners_id, date, approved_site, approved_partner, delivered, cancelled, status
                FROM tbl_orders
                WHERE date_sub(curdate(), interval 80 day) <=date and " . " ((status = '" . Order::$DELIVERED . "'  AND approved_partner <> '00:00:00')
                OR status = '" . Order::$CANCELLED . "')
                AND partners_id = " . $partner->id . " ";
            $reactions = Yii::app()->db->createCommand($sql)->queryAll();
            $reactions_array = array();
            $sum_reaction_time = 0;
            $time = 0;
            foreach ($reactions as $reaction) {
                //сохраняем дату принятия заказа в формате UTC
                $date_time = strtotime($reaction['date']);
                //Сохраняем дату принятия заказа
                $date = date('Y-m-d', $date_time);
                $def = 0;
                if ($reaction['status'] == Order::$DELIVERED) {
                    //Выясняем дату смены статуса
                    $approvide_partner_time = strtotime($date . ' ' . $reaction['approved_partner']);
                    if ($date_time > $approvide_partner_time) {
                        $approvide_partner_time = $approvide_partner_time + 60 * 60 * 24;
                    }
                    $def = $approvide_partner_time - $date_time;
                } elseif ($reaction['status'] == Order::$CANCELLED) {
                    //Выясняем дату смены статуса
                    $cancelled_time = strtotime($date . ' ' . $reaction['cancelled']);
                    if ($date_time > $cancelled_time) {
                        $cancelled_time = $cancelled_time + 60 * 60 * 24;
                    }
                    $def = $cancelled_time - $date_time;
                }
                $reactions_array[] = $def;
                $sum_reaction_time += $def;
            }
            $time = floor((($sum_reaction_time / 60) / (count($reactions_array) ? count($reactions_array) : 1)));


            $time_category = 0;
            if ($time <= 2) {
                $time_category = 1;
            } elseif ($time <= 5) {
                $time_category = 2;
            } elseif ($time <= 10) {
                $time_category = 3;
            } else {
                $time_category = 4;
            }

            $partner->position = $time_category+500;
            $partner->save();

        }
        $k=1;
        $t=3600*24*30;
        for($time=501;$time<505;$time++){
            $sql="SELECT t.partner_id,sum(t.sum) as sum,t2.position FROM `tbl_payment_history` t left join tbl_partners t2 on t.partner_id=t2.id
                  WHERE t.balance_after<t.balance_before and t2.position={$time} and UNIX_TIMESTAMP()-t.date<{$t} group by t.partner_id order by sum desc";
            $res=Yii::app()->db->createCommand($sql)->queryAll();
            $position=1;
            foreach($res as $array){
                $partner=Partner::model()->findByPk($array['partner_id']);
                print_r($array);
                $partner->position=400+100*$k+$position;
                $partner->save();
                $position++;
            }
            $k++;
        }
        $sql="update tbl_partners set position=position+500 where position>500 and position<600";
        $res=Yii::app()->db->createCommand($sql)->query();
        $sql3 = "SELECT tbl_partners.*,tbl_users.reg_date, if(tbl_users.reg_date>=DATE_SUB(NOW(), INTERVAL 7 day), 1,0) as case1,
          (select sum(( SELECT SUM( total_price ) FROM tbl_order_items WHERE order_id = tbl_orders.id )) AS amount
          from tbl_orders WHERE date_sub(curdate(), interval 30 day) <=date and ((status = 'Доставлен'
           AND approved_partner <> '00:00:00') OR status = 'Отменен') AND partners_id = tbl_partners.id group by partners_id ) as total_amount
        FROM tbl_partners,tbl_users WHERE tbl_partners.id=tbl_users.partner_id and tbl_partners.position>100 having case1>0
        ORDER BY tbl_users.reg_date desc,position asc ,total_amount desc";
        $partners = Yii::app()->db->createCommand($sql3)->queryAll();
        $position = 101;
        foreach ($partners as $partner) {
            $sql = "update tbl_partners set position={$position} where id={$partner['id']}";
            Yii::app()->db->createCommand($sql)->query();
            $position++;
        }

        /*Тут можно забанить кафешку, и опустить её в списке вниз. ID пишем в массив*/
        $banned = array();
        $t = 2001;
        foreach ($banned as $id) {
            $sql = "update tbl_partners set position={$t} where id={$id}";
            Yii::app()->db->createCommand($sql)->query();
            $t++;
        }
        /* $sql = "update tbl_partners set position=1 where id=119";
        Yii::app()->db->createCommand($sql)->query();
        $sql = "update tbl_partners set position=1 where id=85";
        Yii::app()->db->createCommand($sql)->query();
        $sql = "update tbl_partners set position=2 where id=123";
        Yii::app()->db->createCommand($sql)->query();*/
    }

    /*public function actionTest(){
        $file = 'cronlog.txt';
        // Открываем файл для получения существующего содержимого
        $current = file_get_contents($file);
        // Добавляем нового человека в файл
        $current .= "John Smith\n";
        // Пишем содержимое обратно в файл
        file_put_contents($file, $current);
    }*/

    //Обновление статистики по заказам за последний день
    public function actionUpdatestat(){
        $n=isset($_GET['days']) ? $_GET['days'] : 1;
        $date=date('Y-m-d',time()-86400*$n);
        $domains=Domain::model()->findAll();
        foreach($domains as $domain){
            $res=OrderStatistics::GetLastDayOrders($domain->id,$n);
            if($res){
                $order_stat=OrderStatistics::model()->find("date='".$date."' and domain_id=".$domain->id);
                if(!$order_stat){
                    $order_stat=new OrderStatistics();
                    $order_stat->date=$date;
                    $order_stat->domain_id=$domain->id;
                }
                $order_stat->orders_count=$res['count'];
                $order_stat->orders_sum=round($res["sum"]*10)/10;
                $order_stat->new_users=Order::GetNewUsers($date,$order_stat->domain_id, false);
                $order_stat->procent=$res['sum2'];
                $order_stat->procent2=Payment_history::Sum2($date,$order_stat->domain_id, false);
                $order_stat->accept_phone=Order::getAdminOrdersCount($date,$order_stat->domain_id, false);
                $order_stat->pc=$res['pc_count'];
                $order_stat->mobile=$res['mobile_count'];
                $order_stat->app=$res['app_count'];
                $order_stat->average=Order::averageCheck($res["count"], round($res["sum"]*10)/10);
                $order_stat->save();
            }
        }
    }

    public function actionThanks()
    {
        $this->render('thanks');
    }

    public function actioncheckphone(){
        if(isset($_POST['phone'])){
            if(Order::FormatPhone($_POST['phone'])==null){
                echo 'error';
                exit;
            }
            $phone=Order::FormatPhone($_POST['phone']);
            if(isset($_GET['check_users'])&&!Yii::app()->user->isGuest){
                $user=User::model()->find("id<>".Yii::app()->user->id." and phone='".$phone."'");
                if($user){
                    echo 'error2';
                    exit;
                }else{
                    $user=User::model()->findByPk(Yii::app()->user->id);
                    $user->phone_confirmed=1;
                    $user->phone=$phone;
                    $user->save();
                    echo 'ok';
                }
            }
        }
    }

    public function actionupdatepassword(){
        if(isset($_POST['new_password'])&&isset($_POST['date'])&&isset($_POST['pol'])&&!Yii::app()->user->isGuest){
            $pass=$_POST['new_password'];
            $user=User::model()->findByPk(Yii::app()->user->id);
            $user->pass=UserModule::encrypting($pass);
            $user->pol=$_POST['pol'];
            $user->birthdate=$_POST['date'];
            if(!$user->save()){
                //print_r($user->getErrors());
                $error=$user->getErrors()[0][0];
                foreach($user->getErrors() as $ar){
                    echo $ar[0]."<br>";
                }
            }else{
                $user->password_confirmed=1;
                Yii::app()->session['password_confirmed']=1;
                $user->save();
                echo 'ok';
            }
        }
    }

    public function actionConfirmPhone(){
        if(!Yii::app()->user->isGuest){
            $user=User::model()->findByPk(Yii::app()->user->id);
            if(isset($_POST['phone'])&&isset($_GET['confirm'])) {
                if ($user->phone == $_POST['phone']) {
                    $user->phone_confirmed = 1;
                    $user->save();
                    echo 'ok';
                }
            }elseif(isset($_GET['get_token'])){
                $sms_token=UserSmsToken::model()->find("user_id=".Yii::app()->user->id);
                if($sms_token->sms_token==$_POST['token']){
                    $user->phone = $sms_token->phone;
                    $user->phone_confirmed = 1;
                    Yii::app()->session['phone_confirmed']=1;
                    $user->save();
                    echo 'ok';
                }else{
                    echo 'Неправильный код';
                }
            }elseif(isset($_POST['phone'])&&isset($_GET['send_token'])){
                $last_token=UserSmsToken::model()->find('user_id='.Yii::app()->user->id);
                $time_last=$last_token ? $last_token->date : 0;
                if(!$last_token||time()-$time_last>60){
                    if($last_token) $last_token->delete();
                    $token='';
                    for($i=0;$i<=3;$i++){
                        $token.=rand(0,9);
                    }
                    $sms_text="Код для подтверждения номера: ".$token;
                    $sms_token=new UserSmsToken();
                    $sms_token->user_id=Yii::app()->user->id;
                    $sms_token->sms_token=$token;
                    $sms_token->date=time();
                    $sms_token->phone=Order::FormatPhone($_POST['phone']);
                    if($sms_token->save()){
                        User::SendUnisenderSms($sms_token->phone,"Dostavka05",$sms_text);
                        echo 'ok';
                    }
                }                

            }

        }
    }

    public function actionAddUsers(){
        exit;
        $sql="select distinct(tbl_orders.phone) as phone,count(tbl_orders.id) as count1,tbl_orders.customer_name as name
                from tbl_orders where DATEDIFF(now(), tbl_orders.date)<91 and length(tbl_orders.phone)>10 and length(tbl_orders.customer_name)>1
                and left(tbl_orders.phone,2)='+7' and tbl_orders.user_id=0 and tbl_orders.domain_id=3 and not exists(select id from tbl_users where  tbl_users.phone=tbl_orders.phone limit 1)
                 group by tbl_orders.phone  order by count1 desc,date asc";
        $array=Yii::app()->db->createCommand($sql)->queryAll();
        foreach($array as $a){
            $order=Order::model()->find('user_id<>0 and phone="'.$a['phone'].'"');
            if($order){
                continue;
            }
            $name=$a['name'];
            $pass=substr(md5(rand(1000,10000).time().rand(1000,10000)),rand(0,25),6);
            $phone=$a['phone'];
            // @TODO Написать отдельную функцию для процесса автоматического создания пользователя
            $user=new RegistrationForm();
            $user->name = $name;
            $user->email = '';
            $user->verifyPassword =$pass;
            $user->pass = $pass;
            $user->phone = $phone;
            if ($user->validate()) {
                $soucePassword = $user->pass;
                $user->phone = Order::FormatPhone($user->phone);
                $user->activkey = UserModule::encrypting(microtime() . $user->pass);
                $user->pass = UserModule::encrypting($user->pass);
                $user->verifyPassword = UserModule::encrypting($user->verifyPassword);
                $user->reg_date = date('Y-m-d H:s:i');
                $user->last_visit = 0;
                $user->status = 0;
                $user->phone_confirmed = 1;
                $user->password_confirmed=0;
                if($user->save()){
                    $orders=Order::model()->findAll("phone='".$user->phone."'");
                    foreach($orders as $order1){
                        $order1->user_id=$user->id;
                        $order1->save();
                        UserBonus::getBonusForOldOrder($order1);                    }
                }
            }
        }
        //print_r($array);
    }

    /**
     * Отправка SMS новым пользователям. Запускается по cron
     *
     * Функция используется для отправки SMS пользователям, для которых был автоматически создан личный кабинет
     * на основе сделанных заказов. Причем пользователи еще ниразу не авторизовывались в своем личном кабинете
     */
    public function actionSendSmsToNewUsers(){
        exit; // Внимание. Присмотрись к коду. Со статусом 0 могут быть и те кто зарегался, но не подтвердил почту.
              // Кажется так. А Буржун сгенерил всех кто сделал заказ, но не зарегался и задал статус 0
        $users=User::model()->findAll(array("condition"=>"password_confirmed=0 and status=0","limit"=>10));
        foreach($users as $user){
            $pass=substr(md5(rand(1000,10000).time().rand(1000,10000)),rand(0,25),6);
            $user->pass = UserModule::encrypting($pass);
            //Может быть нужно добавить $user->verifyPassword = UserModule::encrypting($pass); и $user->activkey = UserModule::encrypting(microtime() . $pass); ?
            $user->save();
            $bonus=User::getBonus($user->id);
            $text="Вам начислено ".$bonus." баллов. Ваш логин - вам номер телефона. Ваш пароль - ".$pass;
            if(User::SendUnisenderSms($user->phone,"Dostavka05",$text)){
                $user->status=1; // @TODO Нужно пересмотреть процесс разблокировки пользователя при отправки SMS. Есть вероятность, что пользователь был заблокирован вручную админом, а после успешного (доставленного) заказа (с тем же телефоном в режиме "гость") он разблокируется
                $user->save();
            }
        }
    }
}
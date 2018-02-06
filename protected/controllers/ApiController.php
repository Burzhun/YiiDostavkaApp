<?php

class ApiController extends Controller
{
    /** @var User user */
    public $user = false;

    public function init()
    {
        if (trim($_GET['token'] != "")) {
            $token = $_GET['token'];
            $model = UserToken::model()->find("token = '$token'");
            /** @var UserToken $model */
            if ($model) {
                if ($user = User::model()->findByPk($model->user_id))
                    $this->user = $user;
            }
        }

        // Yii::app()->attachEventHandler('onError', array($this, 'handleError'));
        //  Yii::app()->attachEventHandler('onException', array($this, 'handleError'));
    }

    public function beforeAction($action)
    {
        // Yii::log('2321', CLogger::LEVEL_ERROR, Yii::app()->controller->action->id);
        //Yii::log(Yii::app()->controller->action->id, CLogger::LEVEL_ERROR,  $_SERVER['HTTP_USER_AGENT']);
        return true;
    }

    protected function handleError($event)
    {
        if ($event instanceof CExceptionEvent) {
            $body = array(
                'code' => $event->exception->getCode(),
                'message' => $event->exception->getMessage(),
                'file' => YII_DEBUG ? $event->exception->getFile() : '*',
                'line' => YII_DEBUG ? $event->exception->getLine() : '*'
            );
        } else {
            $body = array(
                'code' => $event->code,
                'message' => $event->message,
                'file' => YII_DEBUG ? $event->file : '*',
                'line' => YII_DEBUG ? $event->line : '*'
            );
        }
        ob_start();
        print_r($body);
        $exception = ob_get_contents();
        ob_clean();
        file_put_contents('exceptions.txt', $exception);

        $event->handled = true;
    }

    public function actionSpecialization()
    {
        $output = array();
        if (!is_numeric($_GET['date']))
            return false;
        else
            $date = $_GET['date'];

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        if ($date > 0) {
            $criteria->addCondition("date_change > $date");
        }

        $spec = Specialization::model()->cache(300)->findAll($criteria);

        if ($spec) {
            foreach ($spec as $k => $s) {
                $output[] = $s->attributes;
            }
        }

        echo json_encode(array('data' => $output));
        Yii::app()->end();
    }

    //--Партнеры
    public function actionPartner()
    {
        $output = array();
        if (!is_numeric($_GET['date']))
            return false;
        else
            $date = $_GET['date'];

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;

        if (!isset($_GET['ggg'])) {
            $criteria->addCondition("status=1 AND self_status=1");
        }

        if ($date > 0) {
            $criteria->addCondition("date_change > $date");
        }

        $criteria->order = 't.soon_opening, position asc';
        if ($this->domain->id == 1) {
            $rayon_id = 0;
            if (isset(Yii::app()->request->cookies['rayon'])) {
                $rayon_id = Yii::app()->request->cookies['rayon']->value;
            }
            //Условие для бакинских ресторанов
            //$criteria->addCondition("exists(select id from tbl_partner_rayon tr1 where t.id=tr1.partner_id and tr1.rayon_id=" . $rayon_id);
        }
        $spec = Partner::model()->findAll($criteria);

        if ($spec) {
            foreach ($spec as $k => $s) {
                //echo $s->name."<br>";
                $temp = (array)$s->attributes;

                unset($temp['email_order']);
                unset($temp['phone_sms']);
                unset($temp['phone_sms2']);
                unset($temp['user_id']);
                unset($temp['balance']);
                unset($temp['vip']);
                unset($temp['vip_rest']);
                unset($temp['soon_opening']);
                unset($temp['procent_deductions']);

                $output[] = $temp;
            }
        }

        echo json_encode(array('data' => $output));
        Yii::app()->end();
    }


    public function actionSpecPartner()
    {
        if (!is_numeric($_GET['date']))
            return false;
        else
            $date = $_GET['date'];

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $where = "p.status = 1 AND p.self_status=1";
        $where .= " AND t.spec_id IN (1, 4, 5, 2, 21, 3, 13, 14, 15, 16, 12)";

        if ($date > 0)
            $where .= " AND t.date_change > $date";

        $sql = "SELECT t.*, s.direction_id FROM {{spec_partner}} t INNER JOIN tbl_partners p ON t.partner_id = p.id
																				  INNER JOIN tbl_specialization s ON t.spec_id = s.id
														  WHERE  $where";


        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $items = $command->queryAll();

        echo json_encode(array('data' => $items));
        Yii::app()->end();

    }
    //----конец блока Партнеры


    //----блок Товары(goods)
    public function actionGood()
    {
        $output = array();
        if (!is_numeric($_GET['date']))
            return false;
        else
            $date = $_GET['date'];

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        if ($date > 0) {
            $criteria->addCondition("date_change > $date");
        }


        if (is_numeric($_GET['parent_id']))
            $criteria->addCondition("parent_id = " . $_GET['parent_id']);

        $criteria->addCondition("publication=1");
        $criteria->order = 'pos, id DESC';
        $goods = Goods::model()->findAll($criteria);

        if ($goods) {
            foreach ($goods as $k => $g) {
                $output[] = $g->attributes;
            }
        }

        echo json_encode(array('data' => $output));
        Yii::app()->end();

    }
    //----конец блока Товары(goods)


    //----начало блока Города(city)
    public function actionCity()
    {
        $output = array();
        if (!is_numeric($_GET['date']))
            return false;
        else
            $date = $_GET['date'];

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        if ($date > 0) {
            $criteria->addCondition("date_change > $date");
        }

        $criteria->addCondition("domain = 3");

        $cities = City::model()->findAll($criteria);

        if ($cities) {
            foreach ($cities as $k => $c) {
                $output[] = $c->attributes;
            }
        }

        echo json_encode(array('data' => $output));
        Yii::app()->end();
    }
    //----конец блока Города(city)


    //----начало блока Меню(menu)
    public function actionMenu()
    {
        $output = array();
        if (!is_numeric($_GET['date']))
            return false;
        else
            $date = $_GET['date'];

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        $criteria->join = "INNER JOIN tbl_partners p ON t.partner_id = p.id";
        $criteria->addCondition('t.publication = 1');
        if ($date > 0) {
            $criteria->addCondition("t.date_change > $date");
        }
        $criteria->order = 't.pos, t.id DESC';

        $menu = Menu::model()->findAll($criteria);

        if ($menu) {
            foreach ($menu as $k => $c) {

                $temp = (array)$c->attributes;

                unset($temp['have_subcatalog']);
                unset($temp['publication']);

                $output[] = $temp;
            }
        }

        echo json_encode(array('data' => $output));
        Yii::app()->end();

    }
    //----конец блока Меню(menu)


    //----начало блока процедуры оформления заказа
    public function actionOrder()
    {
        if (isset($_POST['order'])) {
            $orderArr = json_decode($_POST['order']);
            $adressArr = json_decode($_POST['adress']);

            $order = new Order;
            $order->user_id = !$this->user ? $this->user->id : 0;
            $order->partners_id = $orderArr->partners_id;
            $order->date = date('Y-m-d H:i:s');
            $order->phone = $orderArr->phone;
            $order->customer_name = $this->user ? $this->user->name : $orderArr->customer_name;
            $order->info = $adressArr->descr;
            $order->status = Order::$APPROVED_SITE;
            $order->order_source = $orderArr->os;// == 3 ? Order::$SOURCE_ORDER_ANDROID_APP : ($orderArr->os == 4 ? Order::$SOURCE_ORDER_IOS_APP : 10);
            $order->log = $orderArr->logs;

            $order->city = str_replace('г.','',$adressArr->city);
            $order->street = $adressArr->street;
            $order->house = $adressArr->house;
            $order->storey = $adressArr->storey;
            $order->number = $adressArr->number;
            $city=City::model()->find("name='".$order->city."'");
            $order->domain_id=$city->domain_id;
            $domain=Domain::model()->findByPk($order->domain_id);
            if ($order->save()) {
                if($order->domain_id!=1){
                    $phone=Order::FormatPhone($order->phone);
                    if($phone!=null){
                        $order->phone=$phone;
                        $order->save();
                    }
                }
                $cart = (array)json_decode($_POST['cart']);

                foreach ($cart as $c) {
                    $goods = Goods::model()->findByPk($c->goods_id);
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->goods_id = $c->goods_id;
                    $orderItem->quantity = $c->quality;
                   // $orderItem->total_price = $c->price ? $c->quality * $c->price : $c->quality * $goods->getOrigPrice($this->domain);
                    $orderItem->total_price =  $c->quality * $goods->getOrigPrice($order->domain_id);
                    $orderItem->price_for_one = $goods->getOrigPrice($order->domain_id);
                    $orderItem->save();
                }

                if ($order->partners_id <= 0) {
                    $order->partners_id = $orderItem->goods->partner_id;
                }
                $order->sum=Order::totalPrice($order->id);
                $order->save();

                if (isset($_POST['forbonus']) && !empty($this->user)) {
                    $totalPrice = $order->sum + $order->partner->delivery_cost;
                    if (User::isEnoughBonus($this->user->id, $totalPrice)) {
                        $order->forbonus = $_POST['forbonus'];
                        $order->save();
                        //Снять баллы за заказ
                        UserBonus::TakeBonus($this->user->id, $totalPrice);
                        $order->partner->GetMoneyForFreeOrder($totalPrice-$order->partner->delivery_cost, $order->id);
                    }
                }

                //Начисление баллов за заказ
                //UserBonus::getBonusForOrder($order);
                $order->sendSMS();

                if($order->partner->token_ios!=''){ 
                    //Partner::PushIos($order->partner->token_ios);
                }
                echo 1;
                Yii::app()->end();
            } else {
                echo -1;
                Yii::app()->end();
            }
        }

        echo -1;
        exit();
    }
    public function actionCron(){
        Partner::CheckPushIOS();
       // Order::checkNewOrdersMobile();

        
    }
    //----конец блока процедуры оформления заказа

    public static function PushIos($deviceToken){
        // Put your private key's passphrase here:
        $passphrase = 'dostavka05';

        // Put your alert message here:
        $message = "Push test.";

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'aps_production_cert.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        echo 'Connected to APNS' . PHP_EOL;

        $body['aps'] = array(
            'alert' => array(
                'body' => $message,
            ),
            'badge' => 1,
            'sound' => 'oven.caf',
        );

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;
        print($payload);
        // Close the connection to the server
        fclose($fp);
    }
    //----начало блока функции получения всех заказов пользователя
    /********************
     * $_GET['token'] - Токен
     * $_GET['date'] - Дата после которой следует получить список заказов(по умолчанию - 0)
     ********************/
    public function actionGetOrders()
    {
        $output = array();
        if ($this->user == false) {
            echo json_encode(array('error' => 1, 'msg' => 'Access denied! Invalid token!'));
            Yii::app()->end();
        }

        if (!is_numeric($_GET['date']))
            return false;

        $date = $_GET['date'];
        $user_id = $this->user->id;

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        //$criteria->join = "INNER JOIN tbl_users u ON t.user_id = u.id";

        $criteria->addCondition("user_id = $user_id");

        if ($date > 0) {
            $criteria->addCondition("t.date_change > $date");
        }

        $adreses = Order::model()->findAll($criteria);
        //echo count($adreses);

        if ($adreses) {
            foreach ($adreses as $k => $a) {
                $output[] = $a->attributes;
            }
        }

        echo json_encode(array('error' => 0, 'data' => $output));
        Yii::app()->end();


    }
    //----конец блока функции получения всех заказов пользователя


    //----начало блока функции получения всех записей из таблицы связи OrderItem
    /********************
     * $_GET['token'] - Токен
     * $_GET['date'] - Дата после которой следует получить список заказов(по умолчанию - 0)
     ********************/
    public function actionGetOrderItems()
    {
        $output = array();
        if ($this->user == false) {
            echo json_encode(array('error' => 1, 'msg' => 'Access denied! Invalid token!'));
            Yii::app()->end();
        }

        if (!is_numeric($_GET['date']))
            return false;

        $date = $_GET['date'];

        $user_id = $this->user->id;


        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        $criteria->join = "INNER JOIN tbl_orders o ON t.order_id = o.id";
        $criteria->addCondition("o.user_id = $user_id");

        if ($date > 0) {
            $criteria->addCondition("t.date_change > $date");
        }

        $adreses = OrderItem::model()->findAll($criteria);
        //echo count($adreses);

        if ($adreses) {
            foreach ($adreses as $k => $a) {
                $output[] = $a->attributes;
            }
        }

        echo json_encode(array('error' => 0, 'data' => $output));
        Yii::app()->end();
    }
    //----конец блока функции получения всех записей из таблицы связи OrderItem


    //----начало блока авторизации
    public function actionAuthor()
    {
        $email = $_GET['email'];
        $password = $_GET['password'];
        if (trim($email) != "" && trim($password) != "") {
            $model = new UserLogin;
            $model->username = $email;
            $model->password = $password;
            if ($model->validate()) {
                $user = User::model()->find('email="' . $email . '"');
                UserToken::model()->deleteAll('user_id=' . $user->id);
                $userToken = new UserToken;
                $userToken->user_id = $user->id;
                $userToken->token = md5(time() . rand());
                $userToken->logged=1;
                $userToken->save(false);
                echo json_encode(array(/*'user'=>$user->attributes,*/
                    'token' => $userToken->token, 'error' => 0,/*'msg'=>'Welcome!'*/));
            } else {
                $model->errors['error'] = 1;
                echo json_encode(array('error' => 1, 'msg' => 'Unknown email or bad password'));
            }
        } else {
            echo json_encode(array('error' => 1, 'msg' => 'Empty data'));
        }
    }
    //----конец блока авторизации


    //----начало блока регистрации
    public function actionReg()
    {
        $msg = '';
        if (!Yii::app()->user->id && isset($_GET['RegistrationForm'])) {
            $model = new RegistrationForm();
            $model->name = $_GET['RegistrationForm']['name'];
            $model->email = $_GET['RegistrationForm']['email'];
            $model->verifyPassword = $_GET['RegistrationForm']['password'];
            $model->pass = $_GET['RegistrationForm']['password'];
            $model->phone = $_GET['RegistrationForm']['phone'];


            if ($model->validate()) {
                $soucePassword = $model->pass;
                $model->activkey = UserModule::encrypting(microtime() . $model->pass);
                $model->pass = UserModule::encrypting($model->pass);
                $model->verifyPassword = UserModule::encrypting($model->verifyPassword);
                $model->reg_date = date('Y-m-d H:s:i');
                $model->last_visit = ((Yii::app()->getModule('user')->loginNotActiv || (Yii::app()->getModule('user')->activeAfterRegister && Yii::app()->getModule('user')->sendActivationMail == false)) && Yii::app()->getModule('user')->autoLogin) ? time() : 0;

                $model->status = ((Yii::app()->getModule('user')->activeAfterRegister) ? User::STATUS_ACTIVE : User::STATUS_NOACTIVE);

                if ($model->save()) {

                    User::Add_to_unisender($model->email, $model->phone, $model->name);

                    if (Yii::app()->getModule('user')->sendActivationMail) {
                        $activation_url = $this->createAbsoluteUrl('/user/activation/activation', array("activkey" => $model->activkey, "email" => $model->email));
                        UserModule::sendMail($model->email, UserModule::t("Ваша регистрация на сайте {site_name}", array('{site_name}' => Yii::app()->name)), UserModule::t("Для активации акаунта, перейдите по <a href='{activation_url}'>ссылке</a>", array('{activation_url}' => $activation_url)));
                    }

                    if ((Yii::app()->getModule('user')->loginNotActiv || (Yii::app()->getModule('user')->activeAfterRegister && Yii::app()->getModule('user')->sendActivationMail == false)) && Yii::app()->getModule('user')->autoLogin) {
                        $identity = new UserIdentity($model->username, $soucePassword);
                        $identity->authenticate();
                        Yii::app()->user->login($identity, 0);
                        //$this->redirect(Yii::app()->getModule('user')->returnUrl);
                    } else {
                        if (!Yii::app()->getModule('user')->activeAfterRegister && !Yii::app()->getModule('user')->sendActivationMail) {
                            $msg = 'Поздравляем, вы зарегистрированны!';
                        } elseif (Yii::app()->getModule('user')->activeAfterRegister && Yii::app()->getModule('user')->sendActivationMail == false) {
                            $msg = "Поздравляем, вы зарегистрированны. Пожалуйста " . CHtml::link(UserModule::t('Login'), Yii::app()->getModule('user')->loginUrl) . ".";
                        } elseif (Yii::app()->getModule('user')->loginNotActiv) {
                            $msg = 'Спасибо за регистрацию. Теперь вы можете войти под своим email или login.';
                        } else {
                            $msg = 'Спасибо. Для окончания регистрации перейдите по ссылке в письме отправленное вам на почту.';
                        }
                    }
                    $userToken = new UserToken;
                    $userToken->user_id = $model->id;
                    $userToken->token = md5(time() . rand());
                    $userToken->save(false);

                    UserModule::sendMail($model->email, UserModule::t("Ваша регистрация на сайте {site_name}", array('{site_name}' => Yii::app()->name)), UserModule::t("Вы успешно зарегистрировались на сайте {site_name}", array('{site_name}' => Yii::app()->name)));

                    echo json_encode(array('error' => 0, 'token' => $userToken->token, 'msg' => $msg));
                    //echo print_r(array('error' => 0, 'token' => $userToken->token, 'msg' => $msg));
                }
            } else {
                echo json_encode(array('error' => 1, 'msg' => 'Ошибка валидации', 'errors' => $model->errors));
                //echo print_r(array('error' => 1, 'msg' => 'Ошибка валидации', 'errors' => $model->errors));
            }
        } else {
            echo json_encode(array('error' => 1, 'msg' => "Ошибка. Не правильно переданы данные", 'errors' => ''));
            //echo print_r(array('error' => 1, 'msg' => "Ошибка. Не правильно переданы данные", 'errors' => ''));
        }
    }
    //----конец блока регистрации


    //----начало блока АдресА пользователя---
    /*****************
     * $_GET['date'] - Дата после которой следует выбрать все записи(обязательный параметр, по умолчанию 0)
     * $_GET['token'] - Токен(определяет факт авторизации пользователя)
     ******************/
    public function actionUserAdress()
    {
        $output = array();
        if ($this->user == false) {
            echo json_encode(array('error' => 1, 'msg' => 'Access denied! Invalid token!'));
            Yii::app()->end();
        }

        if (!is_numeric($_GET['date']))
            return false;

        $date = $_GET['date'];

        $user_id = $this->user->id;


        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        //$criteria->join = "INNER JOIN tbl_users u ON t.user_id = u.id";

        $criteria->addCondition("user_id = $user_id");

        if ($date > 0) {
            $criteria->addCondition("t.date_change > $date");
        }

        $adreses = UserAddress::model()->findAll($criteria);
        //echo count($adreses);

        if ($adreses) {
            foreach ($adreses as $k => $a) {
                $output[] = $a->attributes;
            }
        }

        echo json_encode(array('error' => 0, 'data' => $output));
        Yii::app()->end();

    }
    //----конец блока Адреса пользователя---


    //----начало блока Добавление Адрес пользователя---
    /*************
     * $_GET['token'] - токен
     * Поля (из GET запроса) которые сохраняются в таблицу адресов:
     * $_GET['city_id'] - Город(1 - Махачкала, 2 - Каспийск, 3 - Баку, 4 - Дербент)
     * $_GET['street'] - Улица
     * $_GET['house'] - Дом
     * $_GET['storey'] - Этаж
     * $_GET['number'] - Номер квартиры/офиса
     *************/
    public function actionAddAdress()
    {
        if ($this->user == false) {
            echo json_encode(array('error' => 1, 'msg' => 'Access denied! Invalid token!'));
            Yii::app()->end();
        }

        $errors = array();

        if (trim($_GET['city_id']) == "" || !is_numeric($_GET['city_id']))
            $errors['city_id'] = 'City is empty!';
        if (trim($_GET['house']) == "")
            $errors['house'] = 'House is empty!';
        if (trim($_GET['street']) == "")
            $errors['street'] = 'Street is empty!';


        $model = new UserAddress;
        $model->user_id = $this->user->id;
        $model->city_id = $_GET['city_id'];
        $model->street = $_GET['street'];
        $model->house = $_GET['house'];
        $model->storey = $_GET['storey'];
        $model->number = $_GET['number'];

        if ($model->validate() && !count($errors)) {
            $model->save();
            echo json_encode(array('error' => 0, 'msg' => 'Saved!'));
            Yii::app()->end();
        } else {
            echo json_encode(array('error' => 1, 'msg' => 'Error!', 'errors' => $errors));
            Yii::app()->end();
        }
    }
    //----конец блока Добавление Адрес пользователя---


    //----начало блока Удаленные записи(Deleted)
    public function actionDeleted()
    {
        $output = array();
        if (!is_numeric($_GET['date']))
            return false;
        else
            $date = $_GET['date'];

        if ($date == 0)
            ini_set('memory_limit', '500M');

        $criteria = new CDbCriteria;
        if ($date > 0) {
            $criteria->addCondition("date_change > $date");
        }

        $deleted = Deleted::model()->findAll($criteria);

        if ($deleted) {
            foreach ($deleted as $k => $c) {
                $output[] = $c->attributes;
            }
        }

        echo json_encode(array('data' => $output));
        Yii::app()->end();

    }
    //----конец блока Города(city)


    //---------------------- БЛОК ПАРТНЕРА---------------------------


    //----------------- Список заказов партнера ---------------------
    public function actionPartnerOrders()
    {
        $output = array();
        if (!($date = $this->getDateParam())) {
            echo json_encode(array('error' => 1, 'msg' => 'Вы не указали дату'));
            //return false;
        } elseif (!$partner_id = $this->user->partner->id) {
            echo json_encode(array('error' => 1, 'msg' => 'Данный пользователь не является партнером'));
            //return false;
        } else {
            $offset = Yii::app()->request->getParam('offset');

            $criteria = new CDbCriteria;
            $criteria->addCondition("partners_id = $partner_id");
            if ($date > 0) {
                $criteria->addCondition("date_change > $date");
            }
            $criteria->limit = 20;
            $criteria->order = 'date DESC';
            if ($offset) {
                $criteria->offset = $offset;
            }
            $orders = Order::model()->findAll($criteria);

            if ($orders) {
                foreach ($orders as $k => $o) {
                    $output[] = $o->attributes;
                }
            }

            echo json_encode(array('error' => 0, 'data' => $output));
            //Yii::app()->end();
        }
        //return true;
    }
    //-------------- Конец Список заказов партнера ------------------


    //------------------ Список блюд в заказе -----------------------
    public function actionOrderDetail()
    {
        $output = array();

        $order_id = Yii::app()->request->getParam('order_id');
        if (empty($this->user->partner)) {
            if (!$user_id = $this->user->id) {
                echo json_encode(array('error' => 1, 'msg' => 'Пользователь не авторизован'));
                Yii::app()->end();
                return false;
            }

            /** @var Order $order */
            $order = Order::model()->findByPk($order_id);

            echo 123;
            if ($order->user->id != $user_id) {
                echo json_encode(array('error' => 1, 'msg' => 'Данный заказ не является заказом данного пользователя'));
                Yii::app()->end();
                return false;
            }
        } else {
            //проверяем, это партнер делает запрос либо какой хуй с горы!!!
            if (!$partner_id = $this->user->partner->id) {
                echo json_encode(array('error' => 1, 'msg' => 'Данный пользователь не является партнером'));
                Yii::app()->end();
                return false;
            }

            /** @var Order $order */
            $order = Order::model()->findByPk($order_id);

            if ($order->partner->id != $partner_id) {
                echo json_encode(array('error' => 1, 'msg' => 'Данный заказ не является заказом данного партнера'));
                Yii::app()->end();
                return false;
            }
        }

        if ($order) {
            foreach ($order->orderItems as $k => $o) {
                /** @var OrderItem $o */
                $output[$k] = $o->attributes;
                $output[$k]['goods_name'] = $o->goods->name;
                $output[$k]['goods_image'] = $o->goods->getImage();
            }
        }
        echo json_encode(array('error' => 0, 'data' => $output));
        Yii::app()->end();

        return true;
    }
    //--------------- Конец Список блюд в заказе --------------------


    //------------------- Смена статуса заказа ----------------------
    public function actionChangeStatus()
    {
        //находим id партнера
        if (!$partner_id = $this->user->partner->id)
            return false;
        $status = Yii::app()->request->getParam('status');
        $order_id = Yii::app()->request->getParam('order_id');

        //Переводим присланный статус в формат хранящийся в базе или если прислали чушь, отрубаем выполнение метода
        switch ($status) {
            case 1:
                $status = Order::$APPROVED_SITE;
                break;
            case 2:
                $status = Order::$APPROVED_PARTNER;
                break;
            //case 3: $status = Order::$SENT;break;
            case 4:
                $status = Order::$DELIVERED;
                break;
            case 5:
                $status = Order::$CANCELLED;
                break;
            default:
                echo json_encode(array('error' => 1, 'msg' => 'Данный статус не поддерживается'));;
                Yii::app()->end();
        }


        //проверяем не равен ли статус принят сайтом так как в этот статус заказ 2й раз никогда не переключается
        if ($status != Order::$APPROVED_SITE) {
            /** @var Order $order */
            //находим в базе наш заказ
            $order = Order::model()->findByPk($order_id);
            if ($status == Order::$CANCELLED && !isset($_POST['reason'])) {
               // echo json_encode(array('error' => 1, 'msg' => 'Нельзя переключить в данный статус'));
                //exit;
            }
            if (Yii::app()->request->getParam('reason')) {
                $order->cancel_reason = Yii::app()->request->getParam('reason');
            }
            if(Yii::app()->request->getParam('reason_text')){
                $order->cancel_reason = count(Order::getReasons());
                $order->cancel_reason_text = Yii::app()->request->getParam('reason_text');
            }
            //Статус измененных и доставленных заказов нельзя изменять. Так же проверяем, принадлежит ли заказ тому партнеру который изменяет статус
            if ($order->status != Order::$DELIVERED && $order->status != Order::$CANCELLED && $order->partners_id == $partner_id) {
                $order->status = $status;
                $order->save();
                echo 1;
                Yii::app()->end();
            } else {
                echo json_encode(array('error' => 1, 'msg' => 'Заказ не должен быть доставлен или отменен, и заказ должен менять только тот партнер, кого является данный заказ'));
            }
        } else {
            echo json_encode(array('error' => 1, 'msg' => 'Нельзя переключить в данный статус'));
        }

    }
    //--------------- Конец Смена статуса заказа --------------------

    public function actionGetReasons(){
        echo json_encode(Order::getReasons(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    //------------------- Регистрация партнера ----------------------
    public function actionPartnerRegistration()
    {
        $partnerreg = new PartnerRegistration();

        if (isset($_GET['PartnerRegistration'])) {
            $partnerreg->attributes = $_GET['PartnerRegistration'];

            if ($partnerreg->validate()) {
                $partner = new Partner();
                $user = new User();

                $partner->name = $partnerreg->partnername;
                $partner->tname = mb_strtolower($this->translitIt($partnerreg->partnername));
                $partner->city_id = $partnerreg->city_id;
                $partner->address = $partnerreg->address;
                $partner->email_order = $partnerreg->email;
                $partner->phone_sms = $partnerreg->smsphone;
                $partner->min_sum = $partnerreg->min_sum;

                $partner->delivery_cost = $partnerreg->delivery_cost;
                $partner->work_begin_time = $partnerreg->work_begin_time;
                $partner->work_end_time = $partnerreg->work_end_time;

                $partner->delivery_duration = $partnerreg->delivery_duration;
                $partner->text = $partnerreg->text;
                $partner->img = $partnerreg->img;

                $partner->day1 = $partnerreg->day1;
                $partner->day2 = $partnerreg->day2;
                $partner->day3 = $partnerreg->day3;
                $partner->day4 = $partnerreg->day4;
                $partner->day5 = $partnerreg->day5;
                $partner->day6 = $partnerreg->day6;
                $partner->day7 = $partnerreg->day7;
                $partner->status = 1;
                $partner->self_status = 1;

                $user->name = $partnerreg->username;
                $user->email = $partnerreg->email;
                $user->phone = $partnerreg->contactphone;
                $user->role = User::PARTNER;
                $user->status = 1;
                $user->pass = md5($partnerreg->pass);
                $user->reg_date = date('Y-m-d');

                if ($user->validate() && $partner->validate()) {
                    $user->save();
                    $partner->save();

                    $img_property = CUploadedFile::getInstance($partnerreg, 'img');
                    if (!empty($_FILES['PartnerRegistration']['name']['img']))
                        ZHtml::imgSave($partner, $img_property, 'partner', 500, 500, 250, 250);
                    if (!empty($_GET['Spec'])) {
                        foreach ($_GET['Spec'] as $key => $s) {
                            if ($s == 1) {
                                $specPartnerModel = new SpecPartner();
                                $specPartnerModel->spec_id = (int)$key;
                                $specPartnerModel->partner_id = (int)$partner->id;
                                $specPartnerModel->save();
                            }
                        }
                    }
                    $partner->user_id = $user->id;
                    $user->partner_id = $partner->id;
                    $partner->save();
                    $user->save();
                    $userLogin = new UserLogin;
                    $userLogin->username = $partnerreg->email;
                    $userLogin->password = $partnerreg->pass;
                    echo 1;
                } else {
                    echo -1;
                }
            } else {
                echo -1;
            }
        } else {
            echo -1;
        }
    }
    //--------------- Конец Регистрация партнера --------------------


    //------------------ КОНЕЦ БЛОК ПАРТНЕРА------------------------

    public function getDateParam()
    {
        if (!is_numeric($_GET['date'])) {
            return false;
        } else {
            if ($_GET['date'] == 0)
                ini_set('memory_limit', '500M');

            return $_GET['date'];
        }
    }



    //----------------- Список заказов юзера ---------------------
    public function actionUserOrders()
    {
        $output = array();
        if (!($date = $this->getDateParam())) {
            echo json_encode(array('error' => 1, 'msg' => 'Вы не указали дату'));
            return false;
        }

        $offset = Yii::app()->request->getParam('offset');

        //print_r($this);

        if (!$user_id = $this->user->id) {
            echo json_encode(array('error' => 1, 'msg' => 'Ошибка при определении пользователя'));
            return false;
        }
        $criteria = new CDbCriteria;

        $criteria->addCondition("user_id = $user_id");

        if ($date > 0) {
            //$criteria->addCondition("date_change > $date");
        }

        $criteria->limit = 20;
        $criteria->order = 'date DESC';
        if ($offset) {
            $criteria->offset = $offset;
        }

        $orders = Order::model()->findAll($criteria);

        if ($orders) {
            foreach ($orders as $k => $o) {
                $output[] = $o->attributes;
            }
        }

        echo json_encode(array('error' => 0, 'data' => $output));
        Yii::app()->end();

        return true;
    }
    //-------------- Конец Список заказов юзера ------------------
}
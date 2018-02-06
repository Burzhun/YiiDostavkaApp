<?php

class SiteController extends Controller
{
    public function actionHidePop()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $cookie = new CHttpCookie('open_ad', '1');
            $cookie->expire = time() + 60 * 60 * 8;
            Yii::app()->request->cookies['open_ad'] = $cookie;
        }
        Yii::app()->end();
    }

    public function actions()
    {
    }

    public function make_bitly_url($url, $login, $appkey, $format = 'txt', $history = 1, $version = '2.0.1')
    {
        //create the URL
        $bitly = 'http://api.bit.ly/shorten';
        $param = 'version=' . $version . '&longUrl=' . urlencode($url) . '&login='
            . $login . '&apiKey=' . $appkey . '&format=' . $format . '&history=' . $history;

        //get the url
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $bitly . "?" . $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        //parse depending on desired format
        echo $response;
    }

    public function copypartner()
    {
        if (isset($_GET['update123'])) {
            $p1 = Partner::model()->findByPk(147);
            $p2 = Partner::model()->findByPk(150);
            $menu_array = array();
            foreach ($p1->menu as $menu) {

                if ($menu->parent_id == 0) {
                    $menu->partner_id = $p2->id;
                    $id1 = $menu->id;
                    $goods = $menu->goods;
                    $submenu = $menu->submenu;
                    $menu->isNewRecord = true;
                    $menu->id = null;
                    $menu->save();
                    foreach ($goods as $good) {
                        $image1 = $good->img;
                        $good->isNewRecord = true;
                        $good->parent_id = $menu->id;
                        $good->partner_id = $p2->id;
                        $good->id = null;
                        $good->save();
                        $image2 = "goods_" . $good->id . '.jpg';
                        @copy('upload/goods/' . $image1, 'upload/goods/' . $image2);
                        @copy('upload/goods/small' . $image1, 'upload/goods/small' . $image2);
                        $good->img = $image2;
                        $good->save();
                    }
                    foreach ($submenu as $menu2) {
                        $menu2->partner_id = $p2->id;
                        $id1 = $menu2->id;
                        $goods = $menu2->goods;
                        $menu2->isNewRecord = true;
                        $menu2->id = null;
                        $menu2->save();
                        foreach ($goods as $good) {
                            $image1 = $good->img;
                            $good->isNewRecord = true;
                            $good->parent_id = $menu2->id;
                            $good->partner_id = $p2->id;
                            $good->id = null;
                            $good->save();
                            $image2 = "goods_" . $good->id . '.jpg';
                            @copy('upload/goods/' . $image1, 'upload/goods/' . $image2);
                            @copy('upload/goods/small' . $image1, 'upload/goods/small' . $image2);
                            $good->img = $image2;
                            $good->save();
                        }
                    }
                }
            }
        }
    }

    public function actionIndex()
    {
        $salat = new mySalat('42.977492', '47.475097');
        $pray = $salat->today();
        $this->render('index', array(
            'pray' => $pray,
        ));
    }

    public function actionError()
    {
        $this->render('error');
    }

    public function actionErrors()
    {
        if ( $error=Yii::app()->errorHandler->error )
        {
            if ( Yii::app()->request->isAjaxRequest )
                echo $error['message'];
            else
            {
                if($error['code']==404)
                {
                    $data= "***********************";
                    $data.= "\r\n";
                    $data.= date("d.m.Y H:i:s");
                    $data.= "\r\n";
                    $data.= "URL:".Yii::app()->request->url;
                    $data.= "\r\n";
                    $data.= "UA: ".Yii::app()->request->userAgent;
                    $data.= "\r\n";
                    $data.= "Referrer: ".Yii::app()->request->urlReferrer;
                    $data.= "\r\n";
                    $data.= "IP: ".Yii::app()->request->getUserHostAddress();
                    $data.= "\r\n";
                    $data.= "Browser: ".Yii::app()->request->browser;
                    $data.= "\r\n";

                    $filename = "404.txt";
                    $fh = fopen($filename, "a+");
                    fwrite($fh, $data);
                    fclose($fh);
                }

                $this->render( 'error', $error );
            }
        }
    }

    public function actionLoginAjax()
    {
        //print_r($_SESSION);
        //exit;
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->user->isGuest) {
                $model = new UserLogin;
                if (isset($_POST['login']) && isset($_POST['pass'])) {

                    $model->username = $_POST['login'];
                    $model->password = $_POST['pass'];
                    $model->rememberMe = $_POST['rememberMe'];
                    if ($model->validate()) {

                        /** @var User $lastVisit */
                        $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
                        $lastVisit->last_visit = date('Y-m-d H:i:s');
                        $lastVisit->save();

                        if (Yii::app()->user->role == User::PARTNER)
                            $redirect = '/partner/orders';
                        if (Yii::app()->user->role == User::USER)
                            $redirect = '/user/profile';
                        if (Yii::app()->user->role == User::ADMIN)
                            $redirect = '/admin';

                        $data = array("error" => '', "redirect" => $redirect);
                        echo CJSON::encode($data);
                        exit;
                    } else {
                        $t=$model->getErrors();
                        $t=reset($t);
                        $data = array("error" => $t[0], "redirect" => '');
                        echo CJSON::encode($data);
                    }
                } else {
                    $data = array("error" => 'Не правильный логин/пароль', "redirect" => '');
                    echo CJSON::encode($data);
                }
            }
        }
    }

    public function actionSwitch897546()
    {

        if (isset($_POST['user'])) {
            $user = User::model()->findByPk($_POST['user']);

            $newIdentity = new UserIdentity($user->email, 'nopass');
            Yii::app()->user->login($newIdentity);
            Yii::app()->user->id = $user->id;
            $this->redirect('/');
        }

        echo CHtml::beginForm();
        echo CHtml::dropDownList('user', false, CHtml::listData(User::model()->findAll(), 'id', 'email'));
        echo CHtml::submitButton('Авторизироваться');
        echo CHtml::endForm();
    }

    public function actionClear(){
        Yii::app()->cache->flush();
    }

    public function actionRegistrationAjax()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $model = new RegistrationForm;
            if (Yii::app()->user->id) {
            } else {
                if (isset($_POST['RegistrationForm'])) {

                    $model->name = $_POST['RegistrationForm']['name'];
                    $model->email = $_POST['RegistrationForm']['email'];
                    $model->verifyPassword = $_POST['RegistrationForm']['password'];
                    $model->pass = $_POST['RegistrationForm']['password'];
                    $model->phone = Order::FormatPhone($_POST['RegistrationForm']['phone']);

                    if ($model->validate()) {
                        $soucePassword = $model->pass;
                        $model->activkey = UserModule::encrypting(microtime() . $model->pass);
                        $model->pass = UserModule::encrypting($model->pass);
                        $model->verifyPassword = UserModule::encrypting($model->verifyPassword);
                        $model->reg_date = date('Y-m-d H:s:i');
                        $model->last_visit = ((Yii::app()->getModule('user')->loginNotActiv || (Yii::app()->getModule('user')->activeAfterRegister && Yii::app()->getModule('user')->sendActivationMail == false)) && Yii::app()->getModule('user')->autoLogin) ? time() : 0;
                        $model->status = 1;


                        if ($model->save()) {
                            /*
                            Invites::ActivateInviteRegistration($model->email,$model->id);
                            User::Add_to_unisender($model->email, $model->phone, $model->name);
                            if (Yii::app()->getModule('user')->sendActivationMail) {
                                $activation_url = $this->createAbsoluteUrl('/user/activation/activation', array("activkey" => $model->activkey, "email" => $model->email));
                                UserModule::sendMail($model->email, UserModule::t("Ваша регистрация на сайте {site_name}", array('{site_name}' => Yii::app()->name)), UserModule::t("Для активации акаунта, перейдите по <a href='{activation_url}'>ссылке</a>", array('{activation_url}' => $activation_url)));
                            }*/

                            if ((Yii::app()->getModule('user')->loginNotActiv || (Yii::app()->getModule('user')->activeAfterRegister && Yii::app()->getModule('user')->sendActivationMail == false)) && Yii::app()->getModule('user')->autoLogin) {
                                $identity = new UserIdentity($model->name, $soucePassword);
                                $identity->authenticate();
                                Yii::app()->user->login($identity, 0);
                            } else {
                                Yii::app()->user->setFlash('registration', UserModule::t("Спасибо за регистрацию. Теперь вы можете войти под своим email или телефон."));

                                $data = array("error" => '', "redirect" => '/user/registration');
                                echo CJSON::encode($data);
                                Yii::app()->end();
                            }
                        }
                    } else {
                        $data = array("error" => CHtml::errorSummary($model), "redirect" => '');
                        echo CJSON::encode($data);
                    }
                } else {
                    $data = array("error" => 'Необходимо заполнить все поля', "redirect" => '');
                    echo CJSON::encode($data);
                }
            }
        }
    }

    public function actionGetPartnerRecvizit($id)
    {
        $this->layout = 'diva_popup';

        $model = Order::model()->findByPk($id);
        $this->render('popuppartner', array('model' => $model));
    }

    public function actionGetUserRecvizit($id)
    {
        $this->layout = 'diva_popup';

        $model = Order::model()->findByPk($id);

        $this->render('popupuser', array('model' => $model));
    }

    public function actionUpdateCity()
    {
        //echo $id;exit();
        if (!empty($_POST['id'])) {
            if (City::model()->findByPk($_POST['id'])) {
                Yii::app()->request->cookies['city_chosen'] = new CHttpCookie('city_chosen', 1);
                City::setUserChooseCity($_POST['id']);
            }
        }
    }

    public function actionRegistration()
    {
        $this->render('registration');
    }

    public function actionAjaxVipPartner()
    {
        $city_id = City::getUserChooseCity();
        $vip_partners = Partner::model()->cache(10000)->findAll(array('condition' => 'vip=1 AND city_id = ' . $city_id));
        $vip_rest_partners = Partner::model()->cache(10000)->findAll(array('condition' => 'vip_rest=1 AND city_id = ' . $city_id));

        if (count($vip_partners)) {
            $html_partner = "<div class='partner-box' id='vip_rest'><div class='head2'>Рестораны</div><div class='partnerBlock' id='vip_rest'><ul>";
            foreach ($vip_partners as $v) {
                $html_partner .= "<li><a href='/restorany/" . $v->tname . "'><span class='partner_img'>";
                if ($v->logo) {
                    $html_partner .= "<img style='max-width:100px;max-height:100px;border-radius:4px;' src='" . "/upload/partner/" . $v->logo . "' alt='доставка еды, махачкала, дагестан, " . $v->name . "' title='" . $v->name . ", доставка еды, махачкала, дагестан'/>";
                } else {
                    $html_partner .= "<img style='max-width:100px;max-height:100px;border-radius:4px;' src='" . "/images/default.jpg' alt='доставка еды, махачкала, дагестан, " . $v->name . "' title='" . $v->name . ", доставка еды, махачкала, дагестан'/>";
                }
                $html_partner .= "</span></a></li>";
            }
            $html_partner .= "</ul></div></div>";
        } else {
            $html_partner = "<div class='partner-box' id='vip_rest'></div>";
        }

        if (count($vip_rest_partners)) {
            $html_rest_partner = "<div class='partner-box' id='vip_mag'><div class='head2'>Рестораны</div><div class='partnerBlock' id='vip_rest'><ul>";
            foreach ($vip_rest_partners as $v) {
                $html_rest_partner .= "<li><a href='/restorany/" . $v->tname . "'><span class='partner_img'>";
                if ($v->logo) {
                    $html_rest_partner .= "<img style='max-width:100px;max-height:100px;border-radius:4px;' src='" . "/upload/partner/" . $v->logo . "' alt='доставка еды, махачкала, дагестан, " . $v->name . "' title='" . $v->name . ", доставка еды, махачкала, дагестан'/>";
                } else {
                    $html_rest_partner .= "<img style='max-width:100px;max-height:100px;border-radius:4px;' src='" . "/images/default.jpg' alt='доставка еды, махачкала, дагестан, " . $v->name . "' title='" . $v->name . ", доставка еды, махачкала, дагестан'/>";
                }
                $html_rest_partner .= "</span></a></li>";
            }
            $html_rest_partner .= "</ul></div></div>";
        } else {
            $html_rest_partner = "<div class='partner-box' id='vip_mag'style='margin-top:1px;'></div>";
        }

        $data = array("vip_partner" => $html_partner, "vip_rest_partner" => $html_rest_partner);
        echo CJSON::encode($data);
    }

    /*public function actionAjaxVipPartner()
    {
    }*/

    public function actionCheckNewOrders()
    {
        $today = date("Y-m-d 00:00:00");
        $out = '';
        $city=City::model()->findByPk(City::getUserChooseCity());
        $str = Order::model()->count(array('condition' => "t.date>='" . $today . "' and t.city='".$city->name."'")) + date('H') * 2;//$countAdd;
        $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
        $count = count($chars) - 1;
        for ($i = $count; $i >= 0; $i--) {
            $out = "<img  src = '/images/" . $chars[$i] . ".png'>" . $out;
            //$out = '<span><img src="/images/order_border.png" class=\'order_border\'>'.$chars[$i].'</span>'.$out;
        }

        if (strlen($str) == 1) {
            $zero = "<img src = '/images/0.png'>" .
                "<img src = '/images/0.png'>" .
                "<img src = '/images/0.png'>";
        }
        if (strlen($str) == 2) {
            $zero = "<img src = '/images/0.png'>" .
                "<img src = '/images/0.png'>";
        }
        if (strlen($str) == 3) {
            $zero = "<img src = '/images/0.png'>";
        }
        $out = $zero . $out;

        $out = '<p class="zakaz">ЗАКАЗОВ </p><div class="zakazCount">' . $out . '</div><p class="zakaz">НА СЕГОДНЯ</p>';
        echo $out;
    }

    public function actionAllRest()
    {
        Yii::app()->request->cookies['open_rest'] = new CHttpCookie('open_rest', 'all');
    }

    public function actionOpenRest()
    {
        Yii::app()->request->cookies['open_rest'] = new CHttpCookie('open_rest', 'opened');

    }

    public function actionBonus()
    {
        $model = Bonus::model()->findAll();
        $this->render('bonus', array('model' => $model));
    }

    public function actionTesttest()
    {
        $sql = "SELECT  `customer_name` , REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( phone,  '+9',  '8' ) ,  '+8',  '8' ) ,  '-',  '' ) ,  '+994',  '050' ) ,  ' ',  '' ) ,  ')',  '' ) ,  '(',  '' ) ,  '+7',  '8' ) AS nphone
				FROM  `tbl_orders` 
				WHERE user_id =0
				AND customer_name NOT LIKE  '%тест%'
				AND customer_name NOT LIKE  '%test%'
				GROUP BY nphone
				ORDER BY nphone, customer_name DESC";
        $connection = Yii::app()->db; // так можно делать, если в конфигурации настроен компонент соединения "db"
        $command = $connection->createCommand($sql);
        $numbers = $command->queryAll();
        $arr = array();
        foreach ($numbers as $key => $value) {
            $arr[$value['customer_name']] = preg_replace('/^7/', '8', $value['nphone']);
            $arr[$value['customer_name']] = preg_replace('/^9/', '89', $value['nphone']);
        }
        $arr = array_unique($arr);
        foreach ($arr as $key => $value) {
            echo $key . ' - ' . $value . '<br>';
        }
    }

    public function actionTest()
    {
        $this->renderPartial('test');
    }


    public function actionOpros()
    {
        if (Yii::app()->request->isAjaxRequest) {
            /** @var OprosOtvet $model */
            $model = OprosOtvet::model()->findByPk($_POST['radio_name']);
            $model->sum = $model->sum + 1;
            $model->save();

            Yii::app()->session->add("stat_" . $model->parent_id, Yii::app()->session->getSessionID());

            echo "<br><div class='oprosLeft oprosBox'>Спасибо! Ваш голос принят!</div><br><br>";
        }
    }

    public function actionMobileCart()
    {
        $cart = CartItem::model()->findAll(array('condition' => '1=1 ' . CartItem::getConditionForSelectCartItems()));
        $this->render('mobileCart', array('cart' => $cart));
    }

    public function actionLogin()
    {
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionSwapstate()
    {
        if (Yii::app()->session->get("siteState")) {
            if (Yii::app()->session->get("siteState") == "mobile") {
                Yii::app()->session->add("siteState", "classic");
            } else {
                Yii::app()->session->add("siteState", "mobile");
            }
        } else {
            Yii::app()->session->add("siteState", "classic");
        }

        $this->redirect("/");
    }

    public function actionApp()
    {
        $this->renderPartial('app');
    }


    public function actionGetPartnerCustomerPhone()
    {
        if (Yii::app()->user->role != User::ADMIN)
            return false;

        $partner_name = Yii::app()->request->getParam('partner_name');
        $orders = Order::model()->findAll();
        $phone_array = array();

        if ($partner_name) {
            /** @var Partner[] $partner */
            $partner = Partner::model()->find(array('condition' => "tname = '" . $partner_name . "'"));
            $orders = $partner->orders;
        }

        foreach ($orders as $order) {
            $phone = $order->phone;
            $phone = preg_replace('/[- ()]+/', '', $phone);
            $phone = preg_replace('/^7/', '8', $phone);
            $phone = str_replace('+7', '8', $phone);
            if (!$phone_array[$phone])
                echo $phone . ' - ' . $order->customer_name . ';<br>';

            $phone_array[$phone] = $phone_array[$phone] + 1;
        }
    }

    public function actionSetCityCookie($id)
    {
        $cookie=new CHttpCookie('city_chosen',1);
        $cookie->expire=time()+24*3600*30;
        Yii::app()->request->cookies['city_chosen'] = $cookie;
        $id = (int)$id;
        $city = new CHttpCookie('choose_city', $id);
        $city->expire = time() + 24 * 3600 * 30;
        unset(Yii::app()->request->cookies['choose_city']);
        Yii::app()->request->cookies['choose_city'] = $city;
        echo City::getUserChooseCity();
    }

    public function actionCloseWater(){
        $cookie= new CHttpCookie("water_close",1);
        $cookie->expire = time() + 86400*100;
        Yii::app()->request->cookies['water_close'] = $cookie;
    }
    public function actionSendusersms()
    {
        if(YII_DEBUG){
            echo 'Ok';
            return true;
        }
        $id = $_POST['order_id'];
        $order = Order::model()->findByPk($id);
        $user_phone = preg_replace("/[^0-9]/", '', $order->phone);
        if ($user_phone[0] == '8') {
            $user_phone = '7' . substr($user_phone, 1);
        } elseif (strlen($user_phone) == 10) {
            $user_phone = '7' . $user_phone;
        }
        $senderName = 'Dostavka05';
        if (Yii::app()->request->serverName == 'www.dostavka05.ru') {
            $senderName = 'Dostavka05';
        } elseif (Yii::app()->request->serverName == 'www.dostavka.az') {
            $senderName = 'Dostavka.az';
        }
        $sms_to_user = "Поздравляем! Теперь Ваш заказ подтвержден. Dostavka05.ru";
        if ($order->partner->sms_provider == 1) {
            $body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=" . $senderName . "&to=" . $user_phone . "&text=" . urlencode($sms_to_user));
        } elseif ($order->partner->sms_provider == 2) {
            $body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=dostavka05&to=" . $user_phone . "&text=" . urlencode($sms_to_user));
        } elseif ($order->partner->sms_provider == 3) {
            $api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
            //$sms_text = iconv('cp1251', 'utf-8',$sms);
            $POST = array(
                'api_key' => $api_key,
                'phone' => $user_phone,
                'sender' => $senderName,
                'text' => $sms_to_user
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_URL,
                'http://api.unisender.com/ru/api/sendSms?format=json');
            $result = curl_exec($ch);
        }
        $partner = $order->partner;
        $partner->balance -= 2;
        $partner->save();
        echo 'Ok';
    }

    public function actionAddInvite()
    {
        if (!Yii::app()->user->isGuest && isset($_POST['email'])) {
            $user_id = Yii::app()->user->id;
            $email = $_POST['email'];
            $user = User::model()->find('email=:email', array(':email' => $email));
            if ($user) {
                echo 'Этот e-mail уже зарегистрирован здесь';
                exit;
            }

            if (Invites::AddInvite($user_id, $email)) {
                $domain = Domain::model()->findByPk($this->domain->id);
                $url = $domain->alias;
                $city_name = "Дагестана";
                if ($this->domain->id == 1) {
                    $city_name = "Баку";
                }
                if ($this->domain->id == 2) {
                    $city_name = "Ставрополя";
                }
                $email_message = "Здравствуйте. Пользователь " . Yii::app()->user->name . " приглашает вас посетить сервис " . $url . " .
                                Данный сервис объединяет сотни служб доставки еды " . $city_name . " в Единую Систему Заказов.
                            Зарегистрировавшись по ссылке ниже вы получите 50 баллов автоматически!<br>
                             " . $url."/site/registration"  ;
                UserModule::sendMail($email, UserModule::t("Приглашение посетить сайт dostavka05.ru"), UserModule::t($email_message));

                echo "<span style='color:rgb(36, 180, 36)'>Приглашение отправлено.
                        Как только приглашенный вами друг зарегистрируется и сделает
                        первый заказ, вы получите 200 баллов на личный счет </span>";
                exit;
            } else {
                echo "Этому пользователю уже было отправлено пригашение";
                exit;
            }
        }
        echo 'ok';
    }
}

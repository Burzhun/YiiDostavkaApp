<?php

class SupplierController extends Controller
{

    public function actionGetImages()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $name = Yii::app()->request->getParam('name');
            $id = Yii::app()->request->getParam('id');

            // $name = explode(' ', $name);
            $rows = Yii::app()->db
                // ->createCommand("SELECT `name` AS value, MATCH (`name`) AGAINST (:field IN BOOLEAN MODE) as REL FROM tbl_goods WHERE MATCH (`name`) AGAINST (:field  IN BOOLEAN MODE) ORDER BY REL DESC")
                ->createCommand("SELECT name,img,id FROM tbl_goods WHERE name LIKE :field AND img <> '' ORDER BY name DESC")
                ->bindValues([':field' => '%' . $name . '%'])
                ->queryAll();

            echo $this->renderPartial('_ajaxUpdateImage', array('models' => $rows, 'id' => $id, 'name' => $name));

            Yii::app()->end();
        }
    }

    public function actionUpdateGoodImage()
    {
        if (Yii::app()->request->isAjaxRequest && (Yii::app()->user->role == 'admin')) {

            $origId = Yii::app()->request->getParam('origId');
            $id = Yii::app()->request->getParam('id');

            if ($origId && $id) {
                $good = Goods::model()->findByPk($origId);

                $info = pathinfo('upload/goods/' . $id);

                $file = 'upload/goods/' . $id;
                $file_small='upload/goods/small' . $id;
                $newfile = 'upload/goods/goods_' . $origId . '.' . $info['extension'];
                $newfile_small='upload/goods/smallgoods_' . $origId . '.' . $info['extension'];
                if ($file != $newfile) {

                    if ($good->img) {
                        @unlink('upload/goods/' . $good->img);
                    }
                    $good->img = 'goods_' . $origId . '.' . $info['extension'];
                    $good->save(false);

                    if (!copy($file, $newfile)) {
                        echo "не удалось скопировать $file...\n";
                    } else {
                        copy($file_small,$newfile_small);
                        echo '/upload/goods/' . $good->img;
                    }
                } else {
                    echo '/upload/goods/' . $good->img;
                }
            }
            Yii::app()->end();
        }
    }

    public function actions()
    {

    }

    public function actionIndex()
    {
        /*$direction = Direction::model()->findAll();
        $this->render('index', array(
                                'direction'=>$direction,
        ));*/
    }

    public function actionAdditionalInformation($supplerName = "")
    {
        if (Yii::app()->mobileDetect->isMobile()) {
            /** @var Partner $partner */
            $partner = Partner::model()->cache(40000)->find(array('condition' => "tname='" . $supplerName . "'"));
            $menu = Menu::model()->cache(10000)->findAll(array('condition' => "partner_id='" . $partner->id . "' AND parent_id='0'", 'order' => 'pos'));
            $this->title = str_replace('{name}', $partner->name, Config::getSupplierTitle($this->domain->id));
            $this->description = str_replace('{name}', $partner->name, Config::getSupplierDescription($this->domain->id));

            $this->render('additionalInformation', array(
                'partner' => $partner,
                'menu' => $menu,
            ));
        } else {
            return false;
        }
    }

    public function actionView($supplerName = "")
    {
        if($supplerName!=""&&strpos($_SERVER['REQUEST_URI'],'supplier')>0&&!CHERRY05){ // @TODO может лучше оформить в отдельный метод? Внизу есть похожие строчки
            $t=str_replace('supplier', 'restorany', $_SERVER['REQUEST_URI']);
            header("Location:http://".$_SERVER['HTTP_HOST'].$t);
        }

        if ($supplerName != "") {
            //нада бы потом разобраться что да как там.
        }
        if(CHERRY05){
            $supplerName='cherri';
        }
        /** @var Partner $partner */
        $partner = Partner::model()->cache(5000)->find(array('condition' => "tname='" . $supplerName . "'"));
        if(!$partner){
            $this->redirect("/",true,301);
        }


        $domain = Domain::getDomain(Yii::app()->request->serverName);
        if ($partner->city->domain_id != $domain->id && $domain->id != 4) {
            $domain = Domain::model()->findByPk($partner->city->domain_id);
            $alias = $domain->alias;
            $url = "http://" . $alias . $_SERVER['REQUEST_URI'];
            $this->redirect($url, true, 301);
        }

        /** @var Menu[] $menu */
        $menu = Menu::model()->cache(500)->findAll(array('condition' => "partner_id='" . $partner->id . "' AND parent_id='0' AND publication = 1", 'order' => 'pos'));

        $products = Goods::model()->cache(5000)->findAll(array('condition' => 'favorite=0 and partner_id=' . $partner->id, 'order' => 'pos'));

        $this->title =$partner->seo_title ? $partner->seo_title : str_replace('{name}', $partner->name, Config::getSupplierTitle($this->domain->id));
        $this->description =$partner->seo_description ? $partner->seo_description : str_replace('{name}', $partner->name, Config::getSupplierDescription($this->domain->id));
        $this->keywords=$partner->seo_keywords;

        $warning = $partner->isClosed();
        $timeOut = $partner->howLongWill();

        $this->render('view', array(
            'partner' => $partner,
            'menu' => $menu,
            'products' => $products,
            'warning' => $warning,
            'timeOut' => $timeOut,
        ));
        
    }

    public function actionAjaxGoods()
    {
        $id = $_GET['id'];

        $model = Goods::model()->findAll(array('condition' => "parent_id='" . $id . "' AND publication='1'", 'order' => 'pos'));
        $partner = Partner::model()->findByPk($model[0]->partner_id);
        $warning = $partner->isClosed();
        $this->renderPartial('ajaxGoods', array(
            'model' => $model,
            'partner' => $partner,
            'warning' => $warning
        ));
    }

    public function actionReview2($supplerName = false)
    {
        $this->redirect('/restorany/' . $supplerName . '/review/');
    }

    public function actionReview($supplerName = false)
    {
        //die($supplerName);
        if($supplerName&&strpos($_SERVER['REQUEST_URI'],'supplier')>0&&!CHERRY05){ // @TODO может лучше оформить в отдельный метод? Наверху есть похожие строчки
            $t=str_replace('supplier', 'restorany', $_SERVER['REQUEST_URI']);
            header("Location:http://".$_SERVER['HTTP_HOST'].$t);
        }
        $isOrder = false; // Флаг, если у пользователя есть хоть одни заказ у текущего магазина
        $isSetReview = false; //Флаг, если пользователь уже голосовал
        $supplerName = $supplerName ? $supplerName : $_REQUEST['supplerName']; //die($_REQUEST['supplerName']);
        //echo $supplerName;
        //$supplerName = preg_replace('|\%20|i', '-', $supplerName); echo $supplerName;
        $supplerName = str_replace(' ', '-', $supplerName);
        /** @var Partner $partner */
        $partner = Partner::model()->cache(40000)->find(array('condition' => "tname=:supplerName",
            'params' => array(':supplerName' => $supplerName)));

        $domain = Domain::getDomain(Yii::app()->request->serverName);
        if ($partner->city->domain_id != $domain->id && $domain->id != 4) {
            $old_alias = $domain->alias;
            $domain = Domain::model()->findByPk($partner->city->domain_id);
            $alias = $domain->alias;
            $url = "http://" . $alias . $_SERVER['REQUEST_URI'];
            $this->redirect($url, true, 301);
        }

        if (!Yii::app()->user->isGuest) {
            $user_id = Yii::app()->user->id;
            $userModel = User::model()->find("id = " . $user_id);
            //echo $userModel->status_review;
            //print_r(Order::model()->find("partners_id = $partner->id AND user_id = $user_id"));
            /** @var Order $order */
            $order = Order::model()->find(array(
                'condition' => "partners_id = $partner->id AND user_id = $user_id AND status='Доставлен'",
                'order' => 'date DESC',
            ));
            /** @var Review $review */
            $review = Review::model()->find(array(
                'condition' => "user_id = $user_id and visible=1 ",
                'order' => 'created DESC',
            ));
            //---	Если пользователь не гость(и не гвоздь, и не кость, и не гроздь, и не трость) и у него есть хоть один заказ из этого магазина то устанавливаем флаг $isOrder в true
            if ($order)
                $isOrder = true;
            //---	Если пользователь уже голосовал
            if ($isOrder && $review && ($review->created > strtotime($order->date)))
                $isSetReview = true;
        }

        //echo (int)$userModel->status_review;
        //print_r($userModel->attributes);
        if (Yii::app()->request->isAjaxRequest) {//die($supplerName);
            if (!Yii::app()->user->isGuest) {
                //---Обработка запроса отзыва(валидация, сохранение)--

                if (isset($_POST['Review'])) {
                    $review = new Review('user'); //echo json_encode(array('status'=>'er','code'=>'1'));
                    //if ($isOrder && !$isSetReview) { //Если у пользователя есть заказ из этого магазина и он еще не проголосовал
                    $review->attributes = $_POST['Review'];
                    if ($review->validate()) { //die('fff');
                        $review->partner_id = $partner->id;
                        $review->user_id = $user_id;
                        $review->created = time();
                        $review->visible = 0;
                        $review->save();
                        //$userModel->status_review = 1;
                        $userModel->save(false);
                        //Yii::app()->user->setFlash('success',$this->flashCreateMessage);
                        //$this->redirect(array('review'));
                        echo json_encode(array('status' => 'ok'));
                    } else {
                        //$a=json_encode($review->getErrors());
                        echo json_encode(array('status' => 'er', 'msg' => 'Не валидно!', 'errors' => $review->getErrors()));
                    }
                    // } else {
                    //      echo json_encode(array('status' => 'er', 'msg' => 'Вы не можете добавить отзыв!'));
                    //  }


                }
            } else {
                $review = new Review('user'); //echo json_encode(array('status'=>'er','code'=>'1'));
                $review->attributes = $_POST['Review'];
                if ($review->validate()) { //die('fff');
                    $review->partner_id = $partner->id;
                    $review->user_id = 0;
                    $review->created = time();
                    $review->visible = 0;
                    $review->save();
                    //$userModel->status_review = 1;
                    //Yii::app()->user->setFlash('success',$this->flashCreateMessage);
                    //$this->redirect(array('review'));
                    echo json_encode(array('status' => 'ok'));
                } else {
                    //print_r($review->getErrors());
                    //$a=$review->getErrors();
                    echo json_encode(array('status' => 'er', 'msg' => 'Не валидно!', 'errors' => $review->getErrors()));
                }
            }

            Yii::app()->end();
        }


        // if(!Yii::app()->user->isGuest){
        // 	$user_id = Yii::app()->user->id;
        // }
        //$menu = Menu::model()->findAll(array('condition'=>"partner_id='".$partner->id."' AND parent_id='0'",'order'=>'pos'));

        //$products = Goods::model()->findAll(array('condition'=>'partner_id='.$partner->id, 'order'=>'pos'));


        $dataProvider = new CActiveDataProvider('Review', array(
            'criteria' => array(
                'order' => 'id DESC',
                'condition' => 'visible = 1 AND partner_id=' . $partner->id,
            ),
            'pagination' => array(
                'pageSize' => 15,
            ),
        ));

        $this->title = str_replace('{name}', $partner->name, Config::getReviewTitle($this->domain->id));
        $this->description = str_replace('{name}', $partner->name, Config::getReviewTitle($this->domain->id));
        /*$this->title = "Отзывы ".$partner->name.". Заказ и доставка еды из ".$partner->name." на Доставка 05";
        $this->description = "Отзывы ".$partner->name.". Заказ и доставка еды из ".$partner->name." на Доставка 05";*/


        $this->render('review', array(
            'partner' => $partner,
            //	'menu'=>$menu,
            //	'products'=>$products,
            'dataProvider' => $dataProvider,
            'isOrder' => $isOrder,
            'isSetReview' => $isSetReview,
        ));
    }

    public function actionFilterReview()
    {
        if (Yii::app()->request->isAjaxRequest && is_numeric($_GET['partner_id'])) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('visible = 1');
            $criteria->addCondition('partner_id = ' . $_GET['partner_id']);
            $criteria->order = 'id DESC';

            if (isset($_GET['positive'])) {
                $criteria->addCondition('review = 1');
            } elseif (isset($_GET['negative'])) {
                $criteria->addCondition('review = 2');
            } /*elseif(isset($_GET['all'])){

			}*/

            $dataProvider = new CActiveDataProvider('Review', array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize' => 50,
                ),
            ));
            $this->renderPartial('_reviewPartial', array('dataProvider' => $dataProvider));
        }
    }


    public function actionCart()
    {
        if (Yii::app()->request->isAjaxRequest) {
            //$partner_id = (int) $_POST['partner'];
            /** @var CartItem[] $cart */
            $cart = CartItem::model()->findAll(array("condition" => "1=1" . CartItem::getConditionForSelectCartItems()));

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
            if ($partnerCount > 1) exit();//нада сделать обработчик на данную ошибку, типа хацкер сосни этих сдобных мягких булочек и выпей яду))

            if ($have_deleted_item) {
                $cart = CartItem::model()->findAll(array("condition" => "1=1" . CartItem::getConditionForSelectCartItems()));
            }

            //если один поставщик
            $partner = $partnerCount == 1 ? Partner::model()->findByPk($c->partner_id) : "";

            //Удаляем временные заказы
            Yii::app()->db->createCommand("update tbl_temp_orders set kassa_status='cancelled' where session_id='".Yii::app()->session->sessionID."'");

            echo $this->renderPartial('cart', array("cart" => $cart, "sum" => $sum, "partner" => $partner));
        }
    }

    public function actionWarning()
    {

        if (Yii::app()->request->isAjaxRequest) {

            $partner_id = (int)$_POST['partner'];
            $partner = Partner::model()->findByPk($partner_id);
            echo $this->renderPartial('warning', array(
                'partner' => $partner
            ));
        }

    }


    public function actionTest()
    {
        print_r($_POST);
        Yii::app()->end();
    }

    public function actionOrder()
    {

        if (Yii::app()->request->isAjaxRequest) {
            // try
            // {
            /** @var CartItem[] $cart */
            if (Yii::app()->user->getRole() == User::USER) {
                CartItem::model()->deleteAll(array('condition' => "session_id='" . Yii::app()->session->sessionID . "' AND user_id!='" . Yii::app()->user->id . "'"));
                $condition = "user_id='" . Yii::app()->user->id . "'";
            } else {
                $condition = "session_id='" . Yii::app()->session->sessionID . "'";
            }

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
            /** @var User $user */
            $user = "";
            if (Yii::app()->user->getRole() != User::GUEST) {
                $user = User::model()->findByPk(Yii::app()->user->id);
            }

            $sum = 0;
            foreach ($cart as $c2) {
                $sum += $c2->price * $c2->quality;
            }

            //проверяем, заказанный товар >= минимальной сумме заказа у данного партнера.
            $min_sum = $partner->min_sum;
            if ($this->domain->id == 1&&false) {
                $partner_rayon = PartnerRayon::model()->find("rayon_id=" . Yii::app()->request->cookies['rayon']->value . " and partner_id=" . $partner->id);
                if ($partner_rayon) {
                    $min_sum = $partner_rayon->min_sum;
                    if ($sum < $min_sum) {
                        $data = array("error" => 'Минимальная сумма заказа - <b>' . $min_sum .City::getMoneyKod(). ' </b>. У вас не достаточно товара в корзине.');
                        echo CJSON::encode($data);
                        Yii::app()->end();
                    }

                }
            } elseif ($sum < $min_sum) {
                $data = array("error" => 'Минимальная сумма заказа - <b>' . $min_sum .City::getMoneyKod(). ' </b>. У вас не достаточно товара в корзине.');
                echo CJSON::encode($data);
                Yii::app()->end();
            }

            if (isset($_POST['Order'])) {


                $order = new Order;
                $order->partners_id = $partner->id;
                $order->date = date('Y-m-d H:i:s');
                $order->phone = $_POST['Order']['phone'];
                $phone=Order::FormatPhone($order->phone);
                if($phone!=null){
                    $order->phone=$phone;
                }
                if (Yii::app()->user->getRole() == User::ADMIN || empty($user)) {
                    $order->customer_name = $_POST['Order']['name'];
                } else {
                    $order->customer_name = $user->name;
                }
                if(empty($user)){
                    $user=User::model()->find('phone="'.$phone.'"');
                }
                $order->user_id = !empty($user) ? $user->id : 0;
                $order->info = $_POST['Order']['text'];
                $order->status = Order::$APPROVED_SITE;
                $order->order_source = Yii::app()->theme->name == 'mobile' ? Order::$SOURCE_ORDER_MOBILE : Order::$SOURCE_ORDER_DESKTOP;
                $order->forbonus = 0;
                $order->domain_id=$this->domain->id;
                $order->cookie_user_id=Yii::app()->request->cookies['cookie_user_id']->value;
                // если зареган то адрес ищем в базе и добавляем из нее
                if (!Yii::app()->user->isGuest && Yii::app()->user->getRole() == 'user') {
                    /** @var UserAddress $address */
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
                            $order->podezd = $address->podezd;
                            $order->storey = $address->storey;
                            $order->number = $address->number;
                        } else {
                            //echo CHtml::errorSummary($address);
                            $data = array("error" => CHtml::errorSummary($address));
                            echo CJSON::encode($data);
                            //echo CHtml::errorSummary($order);
                            Yii::app()->end();
                            exit();

                        }
                    } else//если пользователь выбрал один из существующих адресов
                    {
                        $address = UserAddress::model()->findByPk($_POST['Order']['address']);
                        $order->city = $address->city->name;
                        $order->street = $address->street;
                        $order->house = $address->house;
                        $order->storey = $address->storey;
                        $order->number = $address->number;
                    }
                } else // иначе тащим все из формы
                {
                    $order->city = $_POST['Address']['city'];
                    $order->street = $_POST['Address']['street'];
                    $order->house = $_POST['Address']['house'];
                    $order->storey = $_POST['Address']['storey'];
                    $order->podezd = $_POST['Address']['podezd'];
                    $order->number = $_POST['Address']['number'];
                    if (!$order->validate()) {
                        $data = array("error" => CHtml::errorSummary($order));
                        echo CJSON::encode($data);
                        //echo CHtml::errorSummary($order);
                        Yii::app()->end();
                        exit();
                    }
                    if (isset($_POST['Order']['email'])) {


                    }
                }

                if (Yii::app()->user->getRole() == 'admin') {
                    if (!$_POST['Address']['fast']) {
                        $order->order_time = strtotime($_POST['Address']['date'] . "T" . $_POST['Address']['time'] . ":" . $_POST['Address']['timeMin'] . "Z");
                    }
                }
                $user_registrated = false;
                $errors = "";
                $created_user_id = null;
                //print_r($_POST);
                if ($_POST['register_email'] != '' || $_POST['register_name'] != '' && isset($_POST['register'])) {
                    if (!isset($_POST['register_email']) || $_POST['register_email'] == '') {
                        $errors = "<li>Вы не ввели E-mail</li>";
                        $data = array("error" => "<ul class='reg_errors'>" . $errors . "</ul>");
                        echo CJSON::encode($data);
                        Yii::app()->end();
                        exit();
                    }

                    $email = $_POST['register_email'];
                    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                        $errors .= "<li>Не правильный адрес email</li>";
                    }
                    if (!isset($_POST['register_name']) || $_POST['register_name'] == '') {
                        $errors .= "<li>Вы не ввели имя</li>";
                    }

                    if (!isset($_POST['register_password']) || $_POST['register_password'] == '') {
                        $errors .= "<li>Вы не ввели пароль</li>";
                    }
                    if ($errors != "") {
                        $data = array("error" => "<ul class='reg_errors'>" . $errors . "</ul>");
                        echo CJSON::encode($data);
                        Yii::app()->end();
                        exit();
                    }
                    $user = User::model()->find("email=:email", array(':email' => $email));
                    if ($user) {

                        $data = array("error" => "<ul class='reg_errors'><li>Пользователь с такой почтой уже сществует</li></ul>");
                        echo CJSON::encode($data);
                        Yii::app()->end();
                        exit();
                    } else {
                        $password = $_POST['register_password'];
                        $model = new RegistrationForm;
                        $model->name = $_POST['register_name'];
                        $model->pass = $password;
                        $model->verifyPassword = $password;
                        $model->email = $email;
                        $model->phone = $order->phone;
                        if ($model->validate()) {
                            $soucePassword = $model->pass;
                            $model->activkey = UserModule::encrypting(microtime() . $model->pass);
                            $model->pass = UserModule::encrypting($model->pass);

                            $model->verifyPassword = UserModule::encrypting($model->verifyPassword);
                            $model->reg_date = date('Y-m-d H:s:i');
                            $model->last_visit = ((Yii::app()->getModule('user')->loginNotActiv || (Yii::app()->getModule('user')->activeAfterRegister && Yii::app()->getModule('user')->sendActivationMail == false)) && Yii::app()->getModule('user')->autoLogin) ? time() : 0;
                            //$model->superuser=0;
                            $model->status = ((Yii::app()->getModule('user')->activeAfterRegister) ? User::STATUS_ACTIVE : User::STATUS_NOACTIVE);
                            if ($model->save()) {
                                if (Yii::app()->getModule('user')->sendActivationMail) {
                                    $activation_url = $this->createAbsoluteUrl('/user/activation/activation', array("activkey" => $model->activkey, "email" => $model->email));
                                    UserModule::sendMail($model->email, UserModule::t("Ваша регистрация на сайте {site_name}", array('{site_name}' => Yii::app()->name)), UserModule::t("Для активации акаунта, перейдите по <a href='{activation_url}'>ссылке</a>", array('{activation_url}' => $activation_url)));
                                }
                                $identity = new UserIdentity($model->name, $soucePassword);
                                $identity->authenticate();
                                $created_user_id = $model->id;
                                $user_registrated = true;
                            } else {
                                $data = array("error" => "<ul class='reg_errors'><li>Пользователь с такой почтой уже сществует</li></ul>");
                                echo CJSON::encode($data);
                                $errors = $model->getErrors();
                                print_r($errors);
                                Yii::app()->end();
                                exit();
                            }
                        }
                    }
                }
                if(User::isBanned()){
                    CartItem::model()->deleteAll(array("condition" => $condition));
                    $data = array("error" => '', "page" => $this->renderPartial('thenks', array("partner_tname" => $partner->tname, 'phone' => $order->phone, 'user_registrated' => $user_registrated), true));
                    echo CJSON::encode($data);
                    //echo $this->renderPartial('thenks', array("partner_tname"=>$partner->tname));
                    Yii::app()->end();
                }
                
                if ($order->save()) {

                    /*if(Yii::app()->user->role == User::USER)
                    {
                        $user->status_review = 0;
                        $user->save(false);
                    }*/

                    $orders_info=array();
                    foreach ($cart as $c) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->goods_id = $c->goods_id;
                        $orderItem->quantity = $c->quality;
                        $orderItem->total_price = $c->quality * $c->price;
                        $orderItem->price_for_one = $c->price;
                        $orderItem->save();
                        //for google_analytics
                        $order_info=array();
                        $order_info['id']=$c->goods_id;
                        $order_info['price']=$c->price;
                        $order_info['quantity']=$c->quality;
                        $order_info['name']=$c->goods->name;
                        $order_info['category']=$c->goods->tag->name;
                        $orders_info[]=$order_info;
                    }
                    $order->sum=Order::totalPrice($order->id);
                    $order->save();
                    if ($order->user_id) {
                        $user = User::model()->findByPk($order->user_id);
                        Invites::ActivateInvite($user->email);
                    }
                    if (isset($_POST['forbonus']) && !empty($user) && $order->partner->allow_bonus) {
                        $totalPrice = $order->sum + $order->partner->delivery_cost;
                        if (User::isEnoughBonus($user->id, $totalPrice)) {
                            $order->forbonus = $_POST['forbonus'];
                            $order->save();
                            //Снять баллы за заказ
                            UserBonus::TakeBonus($user->id, $totalPrice);
                            $order->partner->GetMoneyForFreeOrder($totalPrice-$order->partner->delivery_cost, $order->id);
                        }
                    }
                    if ($created_user_id) {
                        $order->user_id = $created_user_id;
                        $order->save();
                    }


                    CartItem::model()->deleteAll(array("condition" => $condition));
                    if(!Yii::app()->user->isGuest&&!$order->forbonus){
                       // UserBonus::getBonusForOrder($order);
                    }
                    $order->sendSMS();


                    $data = array("error" => '', "page" => $this->renderPartial('thenks',
                            array(
                                "partner_tname" => $partner->tname,
                                'phone' => $order->phone,
                                'order_id'=>$order->id,
                                'order_price'=>$order->sum,
                                'order_delivery'=>$order->partner->delivery_cost,
                                'user_registrated' => $user_registrated,
                                'orders_info' =>$orders_info
                            ),
                            true
                        )
                    );
                    echo CJSON::encode($data);
                    //echo $this->renderPartial('thenks', array("partner_tname"=>$partner->tname));
                    if($order->partner->token_ios!=''){
                        Partner::PushIos($order->partner->token_ios);
                    }
                    Yii::app()->end();
                }
            }
            Yii::app()->db->createCommand("update tbl_temp_orders set status='cancelled' where session_id='".Yii::app()->session->sessionID."' and status='working'")->query();
            $orders_info=array();
            foreach($cart as $c){
                $order_info=array();
                $order_info['id']=$c->goods_id;
                $order_info['price']=$c->price;
                $order_info['quality']=$c->quality;
                $order_info['name']=$c->goods->name;
                $order_info['category']=$c->goods->tag->name;
                $orders_info[]=$order_info;
            }
            $data = array("error" => '','cart_items'=>json_encode($orders_info,JSON_UNESCAPED_UNICODE), "page" => $this->renderPartial('order', array("sum" => $sum, "partner" => $partner, "user" => $user), true));
            echo CJSON::encode($data);
            //echo $this->renderPartial('order', array("sum"=>$sum, "partner"=>$partner, "user"=>$user));
            //$data = array("page"=>$page);
            // 	Yii::app()->end();
            // }catch (Exception $e)
            // {
            // 	$data = array("errors"=>"<div style='color:red'>Произошла непредвиденная ошибка. Попробуйте еще раз</div>");
            // 	echo CJSON::encode($data);
            // 	Yii::app()->end();
            // }
        } else {
            $data = array('error' => 'Something');
            echo CJSON::encode($data);
        }
    }
    public function actionOrderT(){
        $partner=Partner::model()->findByPk(46);
        $order=Order::model()->findByPk(33437);
        $orders_info=array();
        foreach ($order->orderItems as $c) {
            //for google_analytics
            $order_info=array();
            $order_info['id']=$c->goods_id;
            $order_info['price']=$c->price_for_one;
            $order_info['quantity']=$c->quantity;
            $order_info['name']=$c->goods->name;
            $order_info['category']=$c->goods->tag->name;
            $orders_info[]=$order_info;
        }
        $this->renderPartial('thenks', array(
            "partner_tname" => $partner->tname,
            'phone' => $order->phone,
            'orders_info'=>$orders_info,
            'order_id'=>$order->id,
            'order_price'=>$order->sum,
            'order_delivery'=>$order->partner->delivery_cost,
            'user_registrated' => false
        ));
    }
    public function actionOrderKassa(){
        if (Yii::app()->request->isAjaxRequest) {
            // try
            // {
            /** @var CartItem[] $cart */
            if (Yii::app()->user->getRole() == User::USER) {
                CartItem::model()->deleteAll(array('condition' => "session_id='" . Yii::app()->session->sessionID . "' AND user_id!='" . Yii::app()->user->id . "'"));
                $condition = "user_id='" . Yii::app()->user->id . "'";
            } else {
                $condition = "session_id='" . Yii::app()->session->sessionID . "'";
            }

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
            /** @var User $user */
            $user = "";
            if (Yii::app()->user->getRole() != User::GUEST) {
                $user = User::model()->findByPk(Yii::app()->user->id);
            }

            $sum = 0;
            foreach ($cart as $c2) {
                $sum += $c2->price * $c2->quality;
            }

            //проверяем, заказанный товар >= минимальной сумме заказа у данного партнера.
            $min_sum = $partner->min_sum;
            if ($this->domain->id == 1&&false) {
                $partner_rayon = PartnerRayon::model()->find("rayon_id=" . Yii::app()->request->cookies['rayon']->value . " and partner_id=" . $partner->id);
                if ($partner_rayon) {
                    $min_sum = $partner_rayon->min_sum;
                    if ($sum < $min_sum) {
                        $data = array("error" => 'Минимальная сумма заказа - <b>' . $min_sum .City::getMoneyKod(). ' </b>. У вас не достаточно товара в корзине.');
                        echo CJSON::encode($data);
                        Yii::app()->end();
                    }

                }
            } elseif ($sum < $min_sum) {
                $data = array("error" => 'Минимальная сумма заказа - <b>' . $min_sum .City::getMoneyKod(). ' </b>. У вас не достаточно товара в корзине.');
                echo CJSON::encode($data);
                Yii::app()->end();
            }

            if (isset($_POST['Order'])) {
                $order=Temp_Order::model()->find("session_id='".Yii::app()->session->sessionID."' and status='working'");
                if($order){
                    $data = array("error" => '', "page" =>'ok','sum'=>$sum,'order_id'=>$order->id);
                    echo CJSON::encode($data);
                    Yii::app()->end();
                    exit;
                }

                $order = new Temp_Order;
                $order->user_id = !empty($user) ? $user->id : 0;
                $order->partners_id = $partner->id;
                $order->kassa_status='working';
                $order->session_id=Yii::app()->session->sessionID;
                $order->date = date('Y-m-d H:i:s');
                $order->phone = $_POST['Order']['phone'];
                if (Yii::app()->user->getRole() == User::ADMIN || empty($user)) {
                    $order->customer_name = $_POST['Order']['name'];
                } else {
                    $order->customer_name = $user->name;
                }
                $order->info = $_POST['Order']['text'];
                $order->status = Order::$APPROVED_SITE;
                $order->order_source = Yii::app()->theme->name == 'mobile' ? Order::$SOURCE_ORDER_MOBILE : Order::$SOURCE_ORDER_DESKTOP;
                $order->forbonus = 0;
                $order->domain_id=$this->domain->id;
                $order->cookie_user_id=Yii::app()->request->cookies['cookie_user_id']->value;
                // если зареган то адрес ищем в базе и добавляем из нее
                if (!Yii::app()->user->isGuest && Yii::app()->user->getRole() != 'admin') {
                    /** @var UserAddress $address */
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
                            $order->podezd = $address->podezd;
                            $order->storey = $address->storey;
                            $order->number = $address->number;
                        } else {
                            //echo CHtml::errorSummary($address);
                            $data = array("error" => CHtml::errorSummary($address));
                            echo CJSON::encode($data);
                            //echo CHtml::errorSummary($order);
                            Yii::app()->end();
                            exit();

                        }
                    } else//если пользователь выбрал один из существующих адресов
                    {
                        $address = UserAddress::model()->findByPk($_POST['Order']['address']);
                        $order->city = $address->city->name;
                        $order->street = $address->street;
                        $order->house = $address->house;
                        $order->storey = $address->storey;
                        $order->number = $address->number;
                    }
                } else // иначе тащим все из формы
                {
                    $order->city = $_POST['Address']['city'];
                    $order->street = $_POST['Address']['street'];
                    $order->house = $_POST['Address']['house'];
                    $order->storey = $_POST['Address']['storey'];
                    $order->podezd = $_POST['Address']['podezd'];
                    $order->number = $_POST['Address']['number'];
                    if (!$order->validate()) {
                        $data = array("error" => CHtml::errorSummary($order));
                        echo CJSON::encode($data);
                        //echo CHtml::errorSummary($order);
                        Yii::app()->end();
                        exit();
                    }
                    if (isset($_POST['Order']['email'])) {


                    }
                }

                if (Yii::app()->user->getRole() == 'admin') {
                    if (!$_POST['Address']['fast']) {
                        $order->order_time = strtotime($_POST['Address']['date'] . "T" . $_POST['Address']['time'] . ":" . $_POST['Address']['timeMin'] . "Z");
                    }
                }
                $user_registrated = false;
                $errors = "";
                $created_user_id = null;
                //print_r($_POST);
               
                if(User::isBanned()){
                    CartItem::model()->deleteAll(array("condition" => $condition));
                    $data = array("error" => '', "page" => $this->renderPartial('thenks', array("partner_tname" => $partner->tname, 'phone' => $order->phone, 'user_registrated' => $user_registrated), true));
                    echo CJSON::encode($data);
                    //echo $this->renderPartial('thenks', array("partner_tname"=>$partner->tname));
                    Yii::app()->end();
                }
                
                if ($order->save()) {
                    $data = array("error" => '', "page" =>'ok','sum'=>$sum,'order_id'=>$order->id);
                    Yii::app()->session['yandex_kassa_status']='working';
                    echo CJSON::encode($data);
                    Yii::app()->end();
                }else{
                    $data = array("error" => 'Минимальная сумма заказа - <b>' . $min_sum .City::getMoneyKod(). ' </b>. У вас не достаточно товара в корзине.');
                    echo CJSON::encode($data);
                    Yii::app()->end();
                }
            }
            $data = array("error" => '', "page" => $this->renderPartial('order', array("sum" => $sum, "partner" => $partner, "user" => $user), true));
            echo CJSON::encode($data);
            //echo $this->renderPartial('order', array("sum"=>$sum, "partner"=>$partner, "user"=>$user));
            //$data = array("page"=>$page);
            //  Yii::app()->end();
            // }catch (Exception $e)
            // {
            //  $data = array("errors"=>"<div style='color:red'>Произошла непредвиденная ошибка. Попробуйте еще раз</div>");
            //  echo CJSON::encode($data);
            //  Yii::app()->end();
            // }
        } else {
            $data = array('error' => 'Something');
            echo CJSON::encode($data);
        }
        exit;
    }

    public function actionGetThanksPage(){
        $temp_order=Temp_Order::model()->find(array("condition"=>"session_id='".Yii::app()->session->sessionID."' and status='payed'","order"=>"id desc"));
        $order=Order::model()->findByPk($temp_order->order_id);
        $orders_info=array();
        foreach ($order->orderItems as $c) {
            //for google_analytics
            $order_info=array();
            $order_info['id']=$c->goods_id;
            $order_info['price']=$c->price_for_one;
            $order_info['quantity']=$c->quantity;
            $order_info['name']=$c->goods->name;
            $order_info['category']=$c->goods->tag->name;
            $orders_info[]=$order_info;
        }
        echo $this->renderPartial('thenks', array(
            "partner_tname" => $order->partner->tname,
            'phone' => $order->phone,
            'orders_info'=>$orders_info,
            'order_id'=>$order->id,
            'order_price'=>$order->sum,
            'order_delivery'=>$order->partner->delivery_cost,
            'user_registrated' => false
        ),true);
    }

    public function actionUsePromoKod()
    {
        if (isset($_POST['kod'])&&isset($_POST['partner_id'])) {
            $kod = (int)$_POST['kod'];
            $partner_id=(int)$_POST['partner_id'];
            if (!Yii::app()->user->isGuest) {
                $user_id = Yii::app()->user->id;
                $partner=Partner::model()->findByPk($partner_id);
                $s="(";
                foreach($partner->promos as $promo){
                    $s.=$promo->id.",";
                }
                $promos=Promo::model()->with('partners')->findAll(array("having"=>"t1_c0 is null"));
                foreach($promos as $promo){
                    $s.=$promo->id.",";
                }
                $s.="-1)";
                $user_promo = UserPromo::model()->with('promo')->find("user_id=" . $user_id . " and used=0 and activated=1 and promo.id in ".$s);
                if (!$user_promo) {
                    $promo = Promo::model()->with('partners')->find(array("condition"=>"`from`<unix_timestamp() and until>unix_timestamp() and kod={$kod}","having"=>"t1_c0 is null or partners.id=".$partner->id));
                    if ($promo) {
                        $user_promo = new UserPromo();
                        $user_promo->user_id = $user_id;
                        $user_promo->promo_id = $promo->id;
                        $user_promo->used = 0;
                        $user_promo->activated = 1;
                        if ($user_promo->save()) {
                            $result = array('success' => 1);
                            echo json_encode($result);
                            exit;
                        }

                    } else {
                        $result = array('error' => 'Неправильный промокод');
                        echo json_encode($result);
                        exit;
                    }
                } else {
                    $promo = Promo::model()->findByPk($user_promo->id);
                    if (!$promo) {
                        $result = array('error' => 'Несуществующий промокод');
                        echo json_encode($result);
                        exit;
                    }
                    if ($promo->from < time() && $promo->until > time()) {
                        $user_promo->delete();
                        $user_promo = new UserPromo();
                        $user_promo->user_id;
                        $user_promo = $promo->id;
                        $user_promo->used = 0;
                        $user_promo->activated = 1;
                        $result = array('success' => 1);
                        echo json_encode($result);
                        exit;
                    } else {
                        $result = array('error' => 'Вы уже активировали промокод');
                        echo json_encode($result);
                        exit;
                    }

                }
            } else {
                $result = array('error' => 'Неправильный промокод');
                echo json_encode($result);
                exit;
            }

        } else {
            $result = array('error' => 'Неправильный промокод');
            echo json_encode($result);
        }
    }

    public function actionCronPromokod()
    {
        //echo '24234';exit;

        $time = time() - 180 * 24 * 3600;
        $sql = "delete from tbl_user_bonus where date<{$time}";
        Yii::app()->db->createCommand($sql)->query();
    }

    public function actionAddAdress()
    {
        if (Yii::app()->request->isAjaxRequest && !Yii::app()->user->isGuest) {

            $address = new UserAddress();
            $address->attributes = $_POST['Address'];
            $address->user_id = Yii::app()->user->id;
            if ($address->validate()) {
                $address->save();
                $address_arr = UserAddress::model()->findAll(array('condition' => 'user_id=' . Yii::app()->user->id, 'order' => 'id DESC'));
                $data = array("error" => '', "page" => $this->renderPartial('_address_select', array('address' => $address_arr), true));
                echo CJSON::encode($data);
                Yii::app()->end();
            } else {
                $data = array("error" => CHtml::errorSummary($address), "page" => '');
                echo CJSON::encode($data);
                Yii::app()->end();
            }
        }
    }


    private function getConditionForSelectCartItems()  // @TODO нигед не используется. Нужен ли он?
    {
        if (Yii::app()->user->getRole() == User::USER) {
            $result = " AND user_id='" . Yii::app()->user->id . "'";
        } else {
            $result = " AND session_id='" . Yii::app()->session->sessionID . "'";
        }
        return $result;
    }
}
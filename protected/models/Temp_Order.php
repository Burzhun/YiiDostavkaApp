<?php

/**
 * Class Order
 * @property int $id
 * @property int $user_id
 * @property int $partners_id
 * @property int $date
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string $podezd
 * @property int $storey
 * @property string $number
 * @property string $phone
 * @property string $info
 * @property string $status
 * @property string $status_info
 * @property int $persons_count
 * @property string $customer_name
 * @property string $approved_site
 * @property string $approved_partner
 * @property string $sent
 * @property string $delivered
 * @property string $cancelled
 * @property string $pay
 * @property int $order_time
 * @property int $order_source
 * @property int $date_change
 * @property string $log
 * @property string $sms_id
 * @property int $sms_status
 * @property string $sms_id2
 * @property int $sms_status2
 * @property int $forbonus
 * @property int $cancel_reason
 * @property string $other_cancel_reason_text
 *
 * @property User user
 * @property Partner partner
 * @property OrderItem[] orderItems
 */
class Temp_Order extends CActiveRecord
{
    public static $APPROVED_SITE = 'Новый заказ';
    public static $APPROVED_PARTNER = 'Принят партнером';
    public static $SENT = 'Отправлен';
    public static $DELIVERED = 'Доставлен';
    public static $CANCELLED = 'Отменен';
    public static $PAID = 'Оплачен через payQR';

    public static $SOURCE_ORDER_DESKTOP = 1;
    public static $SOURCE_ORDER_MOBILE = 2;
    public static $SOURCE_ORDER_ANDROID_APP = 3;
    public static $SOURCE_ORDER_IOS_APP = 4;
    public static $SMS_SENT = "Доставлено";
    public static $SMS_SENDING = "Отправлено";
    public static $SMS_NOT_SENT = "Не доставлено";
    public static $SMS_UNKNOWN = "Не известно";

    public  $tableName1='orders';
    
  
   
    public static function model($className = __CLASS__)
    {
        return parent::model($className); 
    }

    public function tableName()
    {
        //$tableName=$this->getAttribute("tableName1");
        return '{{temp_orders}}';
    }

    public function rules()
    {
        return array(
            array('phone, street, house', 'required'),

            //array('house', 'match', 'pattern' => '/^[0-9]+\s?[A-zА-я]?$/u', 'message' => 'Поле "Дом" может содержать цифры и одну букву'),

            //array('user_id, storey, order_time', 'numerical', 'integerOnly'=>true),
            array('user_id,order_id, storey, forbonus', 'numerical', 'integerOnly' => true),
            array('partners_id,cookie_user_id,kassa_status,session_id, phone, info', 'length', 'max' => 255),
            array('date', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('status', 'in', 'range' => Order::getStatusList()),

            array('id, city,podezd, number, status_info, persons_count, customer_name, approved_site, approved_partner, delivered, cancelled, order_source, log, cancel_reason, cancel_reason_text', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'partner' => array(self::BELONGS_TO, 'Partner', 'partners_id'),
            'orderItems' => array(self::HAS_MANY, 'OrderItem', 'order_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'partners_id' => 'Партнер',
            'date' => 'Дата',
            'city' => 'Город',
            'street' => 'Улица',
            'house' => 'Дом',
            'podezd' => 'Подъезд',
            'storey' => 'Этаж',
            'number' => 'Номер квартиры/офиса',
            'phone' => 'Телефон',
            'info' => 'Инфо',
            'status' => 'Статус заказа',
            'status_info' => 'Примечание о статусе',
            'persons_count' => 'Колличество персон',
            'approved_site' => 'Новый заказ',
            'approved_partner' => 'Принят партнером',
            'delivered' => 'Доставлено',
            'cancelled' => 'Отменено',
            'order_time' => 'Время доставки',
            'customer_name' => 'Заказчик',
            'date_change' => 'Дата изменения',
            'order_source' => 'Откуда пришел заказ',
            'log' => 'Лог действий перед заказом через приложение',
            'sms_status' => "Статус смс",
        );
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
        return $response;
    }

    public function sendSMS()
    {
        /***********отправка СМС о поступлении нового заказа*************/

        $sms = "Заказ!";

        $little_money = 150;
        if (Domain::getDomain(Yii::app()->request->serverName)->id == 1) {
            $little_money = 150;
        } elseif (Domain::getDomain(Yii::app()->request->serverName)->id == 3) {
            $little_money = 15;
        }

        $balance = $this->partner->balance <= $little_money ? ' Внимание, низкий баланс-' . $this->partner->balance . ' ' . City::getMoneyKod() . '. Пополните счет во избежание отключения.' : '';

        $o_i = "";
        if ($this->forbonus == 1) {
            $sms = "Бесплатный заказ!";
        }
        foreach ($this->orderItems as $item) {
            $o_i .= " " . $item->goods->name . " -" . $item->quantity . "шт.(" . $item->price_for_one . City::getMoneyKod() . ");";
        }
        $c = $this->city ? " г." . $this->city . ", " : "";
        $s = $this->street ? "ул " . $this->street . ", " : "";
        $h = $this->house ? "дом " . $this->house . ", " : "";
        $y = $this->storey ? "этаж " . $this->storey . ", " : "";
        $n = $this->number ? "кв/каб " . $this->number . ", " : "";
        $sms .= $balance . $o_i . $c . $s . $h . $y . $n . ". Тел: " . $this->phone;
        //SMS
        //$to = $this->partner->phone_sms;
        //$to2 = $this->partner->phone_sms2;

        /*$sms = "Новый заказ на Доставка05. ";
        $c = $this->city ? "".$this->city.", " : "";
        $s = $this->street ? "".$this->street." " : "";
        $h = $this->house ? "".$this->house."" : "";
        $sms .= $c.$s.$h.". Тел: ".$this->phone;*/
        //SMS

        $to = $this->partner->phone_sms;
        $to2 = $this->partner->phone_sms2;

        $user_phone = preg_replace("/[^0-9]/", '', $this->phone);
        if ($user_phone[0] == '8') {
            $user_phone = '7' . substr($user_phone, 1);
        } elseif (strlen($user_phone) == 10) {
            $user_phone = '7' . $user_phone;
        }
        //$to = '79894576923';
        //$to2 = '79886390669';

        $url = "http://www.dostavka05.ru/partner/orders/" . $this->id;
        $short = $this->make_bitly_url($url, 'burzhun', 'R_652f56404b4d4441a5bbde47076efdd0');

        $sms .= "  Ссылка на заказ: " . $short;
        $begin = date('Hi', strtotime($this->partner->work_begin_time));
        $end = date('Hi', strtotime($this->partner->work_end_time));
        $now = date('Hi');
        $sms_to_user = "";
        /** @var Partner $gazetta
         *  @var Partner $gambit
         */
        $gazetta = Partner::model()->findByPk(46);
        $gambit = Partner::model()->findByPk(85);

        if(!$this->partner->isClosed())
        {
            $sms_to_user = $this->customer_name . "! В данный момент {$this->partner->name} закрыто.
				Попробуйте сделать заказ в других заведениях, например El Gusto";
            if ($gambit->isClosed()) {
                $sms_to_user .= ", Гамбит";
            }
            if ($gazetta->isClosed()) {
                $sms_to_user .= ", Газетта";
            }
        }

        $senderName = 'Dostavka05';
        if (Yii::app()->request->serverName == 'www.dostavka05.ru') {
            $senderName = 'Dostavka05';
        } elseif (Yii::app()->request->serverName == 'www.dostavka.az') {
            $senderName = 'Dostavka.az';
        }
        $sms_to_user2 = "Благодарим вас за заказ. Дождитесь звонка оператора.";
        if (Domain::getDomain(Yii::app()->request->serverName)->id <> 1) {
            if ($this->partner->sms_provider == 1) {
                $body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=" . $senderName . "&to=" . $user_phone . "&text=" . urlencode($sms_to_user2));
            } elseif ($this->partner->sms_provider == 2) {
                $body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=dostavka05&to=" . $user_phone . "&text=" . urlencode($sms_to_user2));
            } elseif ($this->partner->sms_provider == 3) {
                $api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
                //$sms_text = iconv('cp1251', 'utf-8',$sms);
                $POST = array(
                    'api_key' => $api_key,
                    'phone' => $user_phone,
                    'sender' => $senderName,
                    'text' => $sms_to_user2
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
        }


        /////////////  отправка SMS
        if (!YII_DEBUG && $this->partner->sms_enabled) {

            if ($this->partner->sms_provider == 1) {
                $body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=" . $senderName . "&to=" . $to . "&text=" . urlencode($sms));
                $body = strval($body);
                $body = utf8_encode($body);
                $sms_id = explode("\n", $body);
                $this->sms_id = $sms_id[1];
                $this->save();
                if ($sms_to_user != "") {
                    $body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=" . $senderName . "&to=" . $user_phone . "&text=" . urlencode($sms_to_user));
                }
                if ($to2) {
                    $body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=" . $senderName . "&to=" . $to2 . "&text=" . urlencode($sms));
                    $body = strval($body);
                    $body = utf8_encode($body);
                    $sms_id = explode("\n", $body);
                    $this->sms_id2 = $sms_id[1];
                    $this->save();
                }
                $this->get_sms_status();
            } elseif ($this->partner->sms_provider == 2) {
                $body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=dostavka05&to=" . $to . "&text=" . urlencode($sms));

                if ($sms_to_user != "") {
                    $body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=dostavka05&to=" . $user_phone . "&text=" . urlencode($sms_to_user));
                }
                if ($to2) {
                    $body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=dostavka05&to=" . $to2 . "&text=" . urlencode($sms));
                }
                $this->get_sms_status();
            } elseif ($this->partner->sms_provider == 3) {
                $api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
                //$sms_text = iconv('cp1251', 'utf-8',$sms);
                $POST = array(
                    'api_key' => $api_key,
                    'phone' => $to,
                    'sender' => $senderName,
                    'text' => $sms
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_URL,
                    'http://api.unisender.com/ru/api/sendSms?format=json');
                $result = curl_exec($ch);
                if ($result) {
                    $jsonObj = json_decode($result);
                    if (null != $jsonObj && empty($jsonObj->error)) {
                        $this->sms_id = $jsonObj->result->sms_id;
                        $this->save();
                    }
                }
                if ($to2) {
                    $POST = array(
                        'api_key' => $api_key,
                        'phone' => $to2,
                        'sender' => $senderName,
                        'text' => $sms
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_URL,
                        'http://api.unisender.com/ru/api/sendSms?format=json');
                    $result = curl_exec($ch);
                    if ($result) {
                        $jsonObj = json_decode($result);
                        if (!null === $jsonObj && empty($jsonObj->error)) {
                            $this->sms_id2 = $jsonObj->result->sms_id;
                            $this->save();
                        }
                    }

                }

            }
        }
        ////////// конец Отправка SMS


        ///////// отправка Email
        if ($this->partner->email_order&&$this->partner->send_email) {
            $message = '';
            $message .= 'Новый заказ на сайте ' . Yii::app()->request->serverName . '<br><br>';
            $message .= 'ФИО: ' . $this->customer_name . '<br>';
            $message .= 'Номер телефона: ' . $this->phone . '<br>';
            $message .= 'Стоимость заказа: ' . Order::totalPriceAdmin($this->id) . '<br>';
            $message .= 'Оплата: Наличными<br>';
            $message .= 'Доставка: Курьером<br>';

            $c = $this->city ? " г." . $this->city . ", " : "";
            $s = $this->street ? "ул " . $this->street . ", " : "";
            $h = $this->house ? "дом " . $this->house . ", " : "";
            $y = $this->storey ? "этаж " . $this->storey . ", " : "";
            $n = $this->number ? "кв/каб " . $this->number . ", " : "";
            $message .= 'Адрес: ' . $c . $s . $h . $y . $n . '<br>';

            $message .= 'Время доставки: ' . ($this->order_time ? date('d-m-Y H:i', $this->order_time) : 'Сейчас (в течении часа)') . '<br>';

            $o_i = '<table border="1" cellspacing="1" cellpadding="1"><tr><td>Наименование</td><td>Кол.</td><td>Цена</td></tr>';
            foreach ($this->orderItems as $item) {
                $o_i .= '<tr><td>' . $item->goods->name . '</td><td>' . $item->quantity . '</td><td>' . $item->total_price . City::getMoneyKod() . '</td></tr>';
            }
            $o_i .= '</table>';
            $message .= '<br>Состав заказа<br><br>';
            $message .= $o_i;

            $adminEmail = 'info@dostavka05.ru';//Yii::app()->params['adminEmail'];
            $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
            $message = wordwrap($message, 70);
            $message = str_replace("\n.", "\n..", $message);
            mail($this->partner->email_order, '=?UTF-8?B?' . base64_encode('Новый заказ на сайте доставки') . '?=', $message, $headers);
            if($this->partner->email_order2){
                mail($this->partner->email_order2, '=?UTF-8?B?' . base64_encode('Новый заказ на сайте доставки') . '?=', $message, $headers);
            }
        }
    }

    protected function afterSave()
    {
        parent::afterSave();
        if (!Yii::app()->user->isGuest) {
            $user_id = Yii::app()->user->id;
            $user_promo = UserPromo::model()->find("user_id=" . $user_id . " and used=0 and activated=1");
            if ($user_promo) {
                $user_bonus = new UserBonus();
                $user_bonus->user_id = $user_id;
                $user_bonus->date = time();
                $user_bonus->sum_in_start = $user_promo->promo->count;
                $user_bonus->sum = $user_promo->promo->count;
                $user_bonus->info = "Активировал промокод на " . $user_bonus->sum . " баллов.";
                if ($user_bonus->save()) {
                } else {
                    $t = $user_bonus->getErrors();
                    $r = $t;
                }
                $user_promo->used = 1;
                $user_promo->save();
            }


        }


        //Если заказ доставлен, то вычитаем у партнера с баланса денюшку
        if ($this->status == Order::$DELIVERED) {
            $totalPrice = Order::totalPrice($this->id);

            $this->partner->orderDelivered($totalPrice, $this->id);

            if ($this->user) {
                $sum = round($totalPrice * User::BONUS_PROCENT_FROM_ORDER, 0);
                $userBonus = new UserBonus();
                $userBonus->user_id = $this->user->id;
                $userBonus->sum = $userBonus->sum_in_start = $sum;
                $userBonus->order_id = $this->id;
                $userBonus->date = time();
                $userBonus->info = 'Сделал заказ в ' . $this->partner->name . ' на сумму ' . $totalPrice . ' и получил ' . $sum . ' баллов';
                if (!UserBonus::model()->find("order_id=:order_id", array(':order_id' => $this->id))) {
                    $userBonus->save();
                }


                $this->user->save();
            }
        }
        //Отправляем смс об отмене заказа
        if ($this->status == Order::$CANCELLED && Domain::getDomain(Yii::app()->request->serverName)->id <> 1) {
            $user_phone = preg_replace("/[^0-9]/", '', $this->phone);
            if ($user_phone[0] == '8') {
                $user_phone = '7' . substr($user_phone, 1);
            } elseif (strlen($user_phone) == 10) {
                $user_phone = '7' . $user_phone;
            }
            if (Yii::app()->request->serverName == 'www.dostavka05.ru') {
                $senderName = 'Dostavka05';
            } elseif (Yii::app()->request->serverName == 'www.dostavka.az') {
                $senderName = 'Dostavka.az';
            }
            $sms_to_user2 = "Ваш заказ на Dostavka05.ru был отменен. Если заказ отменили не Вы, просьба сообщить об этом по тел. 8(928)2184030";
            if ($this->partner->sms_provider == 1) {
                $body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=" . $senderName . "&to=" . $user_phone . "&text=" . urlencode($sms_to_user2));
            } elseif ($this->partner->sms_provider == 2) {
                $body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=dostavka05&to=" . $user_phone . "&text=" . urlencode($sms_to_user2));
            } elseif ($this->partner->sms_provider == 3) {
                $api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
                //$sms_text = iconv('cp1251', 'utf-8',$sms);
                $POST = array(
                    'api_key' => $api_key,
                    'phone' => $user_phone,
                    'sender' => $senderName,
                    'text' => $sms_to_user2
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
        }
        /*    действие   */
        if ($this->isNewRecord) {
            $action = "добавил заказ #" . $this->id;
            if ($this->user) {// die('user');
                $userr = User::model()->findByPk($this->user->id);
                /** @var User $userr */
                $userr->total_order++;
                $userr->save();
            }
        } else {
            if ($this->scenario == 'UpdateInfo') {
                $action = "изменил информацию заказа #" . $this->id;
            } else {
                $action = "изменил статус заказа #" . $this->id . " - " . $this->status;
            }
        }
        /**********************/

        /*    актер      */
        if (Yii::app()->user->isGuest) {
            Action::addNewAction(Action::ORDER, 0, $this->partner ? $this->partner->id : 0, 'Гость (Session ' . Yii::app()->getSession()->sessionID . ') ' . $action);
        } else {

            switch (Yii::app()->user->role) {
                case User::USER:
                    Action::addNewAction(Action::ORDER, $this->user ? $this->user->id : 0, $this->partner ? $this->partner->id : 0, 'Пользователь (ID ' . Yii::app()->user->id . ') ' . $action);
                    break;
                case User::PARTNER:
                    Action::addNewAction(Action::ORDER, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Партнер (ID ' . $this->partner->id . ') ' . $action);
                    break;
                case User::ADMIN:
                    Action::addNewAction(Action::ORDER, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Администратор ' . $action);
                    break;
            }
        }

    }


    protected function beforeDelete()
    {
        Action::addNewAction(Action::ORDER, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Заказ #' . $this->id . ' удален');
    }


    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            //return false;
        }
        $this->date_change = time();

        return true;
    }


    //для админа, вывод всех заказов
    public function search($serchCriteria = array())
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('street', $this->street, true);
        $criteria->compare('house', $this->house, true);
        $criteria->compare('storey', $this->storey, true);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('info', $this->info, true);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('status_info', $this->status_info, true);
        $criteria->compare('persons_count', $this->persons_count, true);
        $criteria->compare('customer_name', $this->user_id, true);
        $criteria->compare('approved_site', $this->approved_site, true);
        $criteria->compare('approved_partner', $this->approved_partner, true);
        $criteria->compare('delivered', $this->delivered, true);
        $criteria->compare('cancelled', $this->cancelled, true);
        $criteria->compare('log', $this->log, true);

        $criteria->with = array('user', 'partner');
        $criteria->compare('user.name', $this->partners_id, true);
        $criteria->compare('partner.name', $this->partners_id, true);


        /*if($_SERVER['REMOTE_ADDR'] == '81.163.63.159')
        {*/
        $partner = Partner::model()->findByPk($serchCriteria['one_partners_id']);
        $deliveryDuration = Partner::$deliveryDuration[$partner->delivery_duration];
        $time = time() + 10800 + $deliveryDuration; // прибавил +3 (10800), т.к. неправильно показывается время

        //Не показывать заказы ДекартМедиа
        /*$criteria->addCondition('order_time=0 OR order_time<' . $time);
        if(Yii::app()->user->id!=989) {
            $criteria->addCondition('t.partners_id<>115');
        }*/
        //}

        $criteria->addInCondition('city_id', City::getCityArray(Domain::getDomain(Yii::app()->request->serverName)->domain_id));//выводим заказы только из данного домена

        if (isset($_GET['Order'])) {
            if (isset($_GET['Order']['id'])) $criteria->addSearchCondition("t.id", $_GET['Order']['id']);
            if (isset($_GET['Order']['date'])) $criteria->addSearchCondition("date", $_GET['Order']['date']);
            if (isset($_GET['Order']['approved_site'])) $criteria->addSearchCondition("approved_site", $_GET['Order']['approved_site']);
            if (isset($_GET['Order']['approved_partner'])) $criteria->addSearchCondition("approved_partner", $_GET['Order']['approved_partner']);
            if (isset($_GET['Order']['delivered'])) $criteria->addSearchCondition("delivered", $_GET['Order']['delivered']);
            if (isset($_GET['Order']['cancelled'])) $criteria->addSearchCondition("cancelled", $_GET['Order']['cancelled']);
            if (isset($_GET['Order']['status'])) $criteria->addSearchCondition("t.status", $_GET['Order']['status']);
            if (isset($_GET['User'])) $criteria->addSearchCondition("customer_name", $_GET['User']['user_id']);
            if (isset($_GET['Partner'])) $criteria->addSearchCondition("partner.name", $_GET['Partner']['partners_id']);
        }

        if (isset($serchCriteria['user_id'])) {
            $criteria->addSearchCondition('t.user_id', $serchCriteria['user_id'], true, 'AND');
        }

        if (isset($serchCriteria['partners_id'])) {
            //$sql = "SELECT id from tbl_partners where (user_id IN (
            //		SELECT user_id from tbl_relation_partner WHERE owner_id IN (
            //		SELECT user_id FROM `tbl_partners` WHERE id = ".$serchCriteria['partners_id'].")) OR id = ".$serchCriteria['partners_id'].")";
            $sql = "SELECT id FROM tbl_partners WHERE (user_id IN (
						SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
							SELECT user_id FROM tbl_partners WHERE id = " . $serchCriteria['partners_id'] . "
						)
					) OR user_id IN (
						SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
							SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
								SELECT user_id FROM tbl_partners WHERE id = " . $serchCriteria['partners_id'] . "
							)
						)
					)) OR id=" . $serchCriteria['partners_id'] . "
					OR user_id IN (
						SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
							SELECT user_id FROM tbl_partners WHERE id = " . $serchCriteria['partners_id'] . "
						)
					)";
            $command = Yii::app()->db->createCommand($sql);
            $data = $command->queryColumn();
            $criteria->addInCondition('t.partners_id', $data);
            //	$criteria->addSearchCondition('t.partners_id', $serchCriteria['partners_id']);
        }

        if (isset($serchCriteria['one_partners_id'])) {
            //$criteria->addInCondition('t.partners_id', $serchCriteria['one_partners_id']);

            $criteria->addSearchCondition('t.partners_id', $serchCriteria['one_partners_id']);
        }
        if(isset($serchCriteria['blocked'])){
            $criteria->addCondition("t.status='Новый заказ' or t.status='Принят партнером'");
        }
        if (Yii::app()->theme->name == 'mobile'
            && !isset($serchCriteria['one_partners_id'])
            && !isset($serchCriteria['partners_id'])
            && Yii::app()->user->role != User::ADMIN
        ) {
            return self::model()->findAll($criteria);
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.id DESC',
                'attributes' => array(
                    '*',
                )
            ),
            'pagination' => array(
                'pageSize' => 40,
            ),
        ));
    }

    public function getCancelReasonText(){
        return $this->cancel_reason_text=='' ? ($this->cancel_reason==0 ? '' : Order::getReasons()[$this->cancel_reason]['name']): $this->cancel_reason_text;
    }

    public function behaviors()
    {
        return array(
            'status' => array(
                'class' => 'StatusBehavior'),
        );
    }


    public function toOrder($user_id)
    {
        //переводим товар из карзины(CartItems) в заказ(OrderItems)
    }


    public function goods()
    {
        $goods = "";
        foreach ($this->orderItems as $i) {
            $goods .= $i->goods ? Chtml::link($i->goods['name'], array('/partner/menu/product/' . $i->goods['id'])) . "<br>" : " Товар удален ";
        }

        return $goods;
    }


    public static function totalPrice($id)
    {
        $model = Order::model()->with('orderItems')->findByPk($id);
        $price = 0;
        foreach ($model->orderItems as $i) {
            $price += $i->price_for_one * $i->quantity;
        }

        return $price;
    }

    public static function totalPriceAdmin($id, $rub = false)
    {
        $model = Order::model()->with('orderItems')->findByPk($id);

        $price = 0;
        foreach ($model->orderItems as $i) {
            $price += $i->price_for_one * $i->quantity;
        }
        if ($rub) {
            $price .= " " . City::getMoneyKod();
        }
        if ($model->forbonus == 1) {
            $price .= " Бесплатный заказ";
        }
        return $price;
    }

    public static function getRowColor($status, $forbonus)
    {
        $statuscolor = 'yellow';
        if ($forbonus) {
            return 'red2';
        }
        switch ($status) {
            case self::$APPROVED_SITE:
                $statuscolor = 'red';
                break;
            case self::$APPROVED_PARTNER:
                $statuscolor = 'yellow';
                break;
            case self::$SENT:
                $statuscolor = 'blue';
                break;
            case self::$DELIVERED:
                $statuscolor = 'green';
                break;
            case self::$CANCELLED:
                $statuscolor = 'white';
                break;
        }
        return $statuscolor;
    }

    public static function getStatusList()
    {
        $values = array();
        preg_match('/\((.*)\)/', Order::model()->tableSchema->columns['status']->dbType, $matches);
        foreach (explode(',', $matches[1]) as $value) {
            $value = str_replace("'", null, $value);
            $values[$value] = Yii::t('enumItem', $value);
        }

        return $values;
    }

    public static function DeleteAllOrders($goodId)
    {
        /** @var self $orders */
        $orders = self::model()->findAll('partners_id=:id', array(':id' => $goodId));
        foreach ($orders as $data) {
            $data->delete();
        }
    }

    protected function afterDelete()
    {
        OrderItem::model()->deleteAll('order_id=' . $this->id);

        return parent::afterDelete();
    }

    public static function getOrderDate($period)
    {
        $dateArray = array();
        $startDate = time();
        for ($i = 0; $i < $period; $i++) {
            $dataName = Yii::app()->dateFormatter->format('d MMMM, EEE', strtotime('+' . $i . ' day', $startDate));
            $dateValue = Yii::app()->dateFormatter->format('yyyy-MM-dd', strtotime('+' . $i . ' day', $startDate));
            $dateArray[$dateValue] = $dataName;
        }
        return $dateArray;
    }

    public static function getTimeOfPeriod($begin, $end)
    {
        $times = array();
        if ($begin >= $end) {
            for ($i = $begin; $i < 25; $i++) {
                if ($i == 24) {
                    $times[0] = '00';
                } else {
                    $times[$i] = $i;
                }
            }
            for ($i = 1; $i < $end; $i++) {
                $times[$i] = $i;
            }
        } else {
            for ($i = $begin; $i < $end; $i++) {
                if ($i == 24) {
                    $times[0] = '00';
                } else {
                    $times[$i] = $i;
                }
            }
        }

        return $times;
    }

    // период минут в час
    public static function getTimeMinutesPeriod($step = 10, $begin = 0, $end = 60)
    {
        $minutes = array();
        for ($i = $begin; $i < $end; $i += $step) {
            $minutes[$i] = str_pad($i, 2, "0", STR_PAD_LEFT);
        }

        return $minutes;
    }

    public static function getSourceName($num)
    {
        $name = '0';
        switch ($num) {
            case 1:
                $name = 'Компьютер';
                break;
            case 2:
                $name = 'Мобильник';
                break;
            case 3:
                $name = 'Android';
                break;
            case 4:
                $name = 'iOS';
                break;
        }
        return $name;
    }

    static function GetStatistics($partner_id, $condition)
    {
        $stat = array();
        $p_condition = "";
        if ($partner_id) {
            $p_condition = " and partners_id={$partner_id}";
        }
        for ($i = 1; $i < 5; $i++) {
            $sql = "select count(id) as count from tbl_orders where order_source={$i} " . $condition . $p_condition;
            $count = Yii::app()->db->createCommand($sql)->queryAll();
            $count = $count[0];
            $name = self::getSourceName($i);
            $stat[$name] = $count['count'];
        }
        return $stat;
    }

    /*
	static function GetStatisticsDay($partner_id){
		$stat=array();
		$p_condition="";
		if($partner_id){
			$p_condition=" and partners_id={$partner_id}";
		}
		for($i=1;$i<5;$i++){
			$sql="select count(id) as count from tbl_orders where order_source={$i} and date >= now() - INTERVAL 1 DAY".$p_condition;
			$count=Yii::app()->db->createCommand($sql)->queryAll();
			$count=$count[0];
			$name=self::getSourceName($i);
			$stat[$name]=$count['count'];
		}
		return $stat;
	}

	static function GetStatisticsWeek($partner_id){
		$stat=array();
		$p_condition="";
		if($partner_id){
			$p_condition=" and partners_id={$partner_id}";
		}
		for($i=1;$i<5;$i++){
			$sql="select count(id) as count from tbl_orders where order_source={$i} and date >= now() - INTERVAL 1 week".$p_condition;
			$count=Yii::app()->db->createCommand($sql)->queryAll();
			$count=$count[0];
			$name=self::getSourceName($i);
			$stat[$name]=$count['count'];
		}
		return $stat;
	}

	static function GetStatisticsMonth($partner_id){
		$stat=array();
		$p_condition="";
		if($partner_id){
			$p_condition=" and partners_id={$partner_id}";
		}
		for($i=1;$i<5;$i++){
			$sql="select count(id) as count from tbl_orders where order_source={$i} and date >= now() - INTERVAL 1 month".$p_condition;
			$count=Yii::app()->db->createCommand($sql)->queryAll();
			$count=$count[0];
			$name=self::getSourceName($i);
			$stat[$name]=$count['count'];
		}
		return $stat;
	}

	static function GetStatistics1($partner_id){
		$stat=array();
		$p_condition="";
		if($partner_id){
			$p_condition=" and partners_id={$partner_id}";
		}
		for($i=1;$i<5;$i++){
			$sql="select count(id) as count from tbl_orders where order_source={$i}".$p_condition;
			$count=Yii::app()->db->createCommand($sql)->queryAll()[0];
			$name=self::getSourceName($i);
			$stat[$name]=$count['count'];
		}
		return $stat;
	}
*/
    public function get_sms_status1($repeat = false)
    {
        if (!$this->sms_id) {
            return self::$SMS_UNKNOWN;
        } else {
            if ($this->sms_status) {
                if ($this->sms_status == 103) {
                    return self::$SMS_SENT;
                } elseif ($this->sms_status > 103 && $this->sms_status < 200) {
                    return self::$SMS_SENDING;
                } elseif ($this->sms_status >= 200) {
                    return self::$SMS_UNKNOWN;
                } elseif ($this->sms_status > 99 && $this->sms_status < 103 && !$repeat) { // TODO распиши в комментах что тут происходит?
                    $body = file_get_contents("http://sms.ru/sms/status?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&id=" . $this->sms_id);

                    if ($body < 100 || $body > 102) {
                        $this->sms_status = $body;
                        $this->save();
                        return $this->get_sms_status(true);
                    } else {
                        return self::$SMS_SENDING;
                    }

                } elseif ($this->sms_status > 99 && $this->sms_status < 103 && $repeat) {
                    return self::$SMS_SENDING;
                } else {
                    return self::$SMS_UNKNOWN;
                }
            } else {
                $body = file_get_contents("http://sms.ru/sms/status?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&id=" . $this->sms_id);
                if ($body != '') {
                    $this->sms_status = $body;
                    $this->save();
                } else {
                    return self::$SMS_UNKNOWN;
                }
            }
        }
    }

    public function get_sms_status($repeat = false)
    {
        if ($this->partner->sms_provider == 1) {
            $st1 = $this->get_sms_status1($repeat);
            if ($st1 == self::$SMS_SENT) {
                return self::$SMS_SENT;
            }
            if (!$this->sms_id2) {
                return $st1;
            } else {
                if ($this->sms_status2) {
                    if ($this->sms_status2 == 103) {
                        return self::$SMS_SENT;
                    } elseif ($this->sms_status2 > 103 && $this->sms_status2 < 200) {
                        if ($st1 == self::$SMS_SENDING) {
                            return self::$SMS_SENDING;
                        } else {
                            return self::$SMS_NOT_SENT;
                        }
                    } elseif ($this->sms_status2 >= 200) {
                        if ($st1 == self::$SMS_SENDING) {
                            return self::$SMS_SENDING;
                        } else {
                            return self::$SMS_UNKNOWN;
                        }
                    } elseif ($this->sms_status2 > 99 && $this->sms_status2 < 103 && !$repeat) {
                        $body = file_get_contents("http://sms.ru/sms/status?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&id=" . $this->sms_id2);
                        if ($body < 100 || $body > 102) {
                            $this->sms_status2 = $body;
                            $this->save();
                            return $this->get_sms_status(true);
                        } else {
                            return self::$SMS_SENDING;
                        }

                    } elseif ($this->sms_status2 > 99 && $this->sms_status2 < 103 && $repeat) {
                        return self::$SMS_SENDING;
                    } else {
                        if ($st1 = self::$SMS_SENDING) {
                            return self::$SMS_SENDING;
                        } else {
                            return self::$SMS_UNKNOWN;
                        }
                    }
                } else {
                    if (!$repeat) {
                        $body = file_get_contents("http://sms.ru/sms/status2?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&id=" . $this->sms_id2);
                        if ($body != '') {
                            $this->sms_status2 = $body;
                            $this->save();
                            $this->get_sms_status(true);
                        }
                    } else {
                        return $st1;
                    }
                }
            }
        } elseif ($this->partner->sms_provider == 3) {
            if ($this->sms_status) {
                //0-sent
                //1-delivered
                //-1 not sent
                if ($this->sms_status == 0) {
                    if ($this->sms_id) {
                        $api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
                        $POST = array(
                            'api_key' => $api_key,
                            'sms_id' => $this->sms_id
                        );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_URL, 'http://api.unisender.com/ru/api/checkSms?format=json');
                        $result = curl_exec($ch);
                        if ($result) {
                            $jsonObj = json_decode($result);
                            if (null != $jsonObj && empty($jsonObj->error)) {
                                $status = $jsonObj->result->status;
                                if ($status == 'ok_delivered') {
                                    return self::$SMS_SENT;
                                    $this->sms_status = 1; //@TODO Код не отрабатывается тут
                                    $this->save();
                                } elseif ($status == 'ok_sent') {
                                    return self::$SMS_SENDING;
                                } else {
                                    $this->sms_status = -1;
                                    $this->save();
                                    return self::$SMS_NOT_SENT;
                                }
                            }
                        } else {
                            // Ошибка соединения с API-сервером
                            return self::$SMS_UNKNOWN;
                            echo "API access error"; //@TODO Код не отрабатывается тут
                        }
                    }
                } else {
                    if ($this->sms_status == 1) {
                        return self::$SMS_SENT;
                    } elseif ($this->sms_status == -1) {
                        return self::$SMS_NOT_SENT;
                    } else {
                        return self::$SMS_UNKNOWN;
                    }
                }
            } else {
                if ($this->sms_id) {
                    //0-sent
                    //1-delivered
                    //-1 not sent
                    $api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
                    $POST = array(
                        'api_key' => $api_key,
                        'sms_id' => $this->sms_id
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_URL, 'http://api.unisender.com/ru/api/checkSms?format=json');
                    $result = curl_exec($ch);
                    if ($result) {
                        $jsonObj = json_decode($result);
                        if (null != $jsonObj && empty($jsonObj->error)) {
                            $status = $jsonObj->result->status;
                            if ($status == 'ok_delivered') {
                                $this->sms_status = 1;
                                $this->save();
                                return self::$SMS_SENT;
                            } elseif ($status == 'ok_sent') {
                                return self::$SMS_SENDING;
                            } else {
                                $this->sms_status = -1;
                                $this->save();
                                return self::$SMS_NOT_SENT;
                            }
                        }
                    } else {
                        // Ошибка соединения с API-сервером
                        return self::$SMS_UNKNOWN;
                    }
                }
            }

        }
    }

    /**
     * @param $cityIds
     * @return string
     */
    public static function getAllOrdersForDomain($cityIds)
    {

        $today = date("Y-m-d 00:00:00");

        $criteria = new CDbCriteria;
        $criteria->condition = 'date>=:today';
        $criteria->addInCondition('city', $cityIds);
        $criteria->params = array(':today' => $today);

        return self::model()->count($criteria) + date('H') * 2;
    }

    public static function getAdminOrdersCount($date)
    {
        switch (strlen($date)) {
            case 10:
                $date_format = '%Y-%m-%d';
                break;
            case 7:
                $date_format = '%Y-%m';
                break;
            case 4:
                $date_format = '%Y';
                break;
        }


        $sql = "select count(id) as count from tbl_orders as t where  FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '{$date_format}')='{$date}' and
			exists(select id from tbl_users as users where users.id=t.user_id and users.role='admin'  limit 1) and status='" . Order::$DELIVERED . "'";
        $count = Yii::app()->db->cache(3600)->createCommand($sql)->queryAll()[0]['count'];
        return $count;
    }

    public static function getAdminCancelledOrdersCount($date)
    {
        switch (strlen($date)) {
            case 10:
                $date_format = '%Y-%m-%d';
                break;
            case 7:
                $date_format = '%Y-%m';
                break;
            case 4:
                $date_format = '%Y';
                break;
        }

        $sql = "select count(id) as count from tbl_orders as t where  FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '{$date_format}')='{$date}' and
			exists(select id from tbl_users as users where users.id=t.user_id and users.role='admin'  limit 1) and status='" . Order::$CANCELLED . "'";
        $count = Yii::app()->db->cache(3600)->createCommand($sql)->queryAll()[0]['count'];
        return $count;
    }

    public static function getReasons()
    {
        $sql = "SELECT * FROM tbl_order_reason_canceled";
        $reasons = Yii::app()->db->createCommand($sql)->queryAll();
        return $reasons;
    }

    public static function getReasonStatusText($_reason_id = 0)
    {
        $reason_name = '';
        $sql = "SELECT * FROM tbl_order_reason_canceled WHERE id = ".$_reason_id;
        $reason = Yii::app()->db->createCommand($sql)->query();
        if($reason)
        {
            $reason_name = $reason['name'];
        }
        return $reason_name;
    }
}

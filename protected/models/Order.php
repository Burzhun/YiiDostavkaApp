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
class Order extends CActiveRecord
{
	public $approved_operator;
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

	/* @var $oldRecord Order|null */
	protected $oldRecord = null;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{

		//$tableName=$this->getAttribute("tableName1");
		return '{{orders}}';
	}

	public function rules()
	{
		return array(
			array('phone, street, house', 'required'),

			//array('house', 'match', 'pattern' => '/^[0-9]+\s?[A-zА-я]?$/u', 'message' => 'Поле "Дом" может содержать цифры и одну букву'),

			//array('user_id, storey, order_time', 'numerical', 'integerOnly'=>true),
			array('user_id, domain_id, storey, forbonus', 'numerical', 'integerOnly' => true),
			array('partners_id,cookie_user_id, phone, info,sum', 'length', 'max' => 255),
			array('date', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
			array('status', 'in', 'range' => Order::getStatusList()),
			//array('phone','correctPhone','correct'=>true),

			array('id, city,podezd,comment, number, status_info, persons_count, customer_name, approved_site, approved_partner, delivered, cancelled, order_source, log, cancel_reason, cancel_reason_text, approved_operator', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'partner' => array(self::BELONGS_TO, 'Partner', 'partners_id'),
			'operator' => array(self::BELONGS_TO, 'User', 'user_id', 'condition' => 'operator.role = "admin"'),
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
			'domain_id' => 'Домен',
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
			'comment' => "Комментарий оператора"
		);
	}

	public function correctPhone($attribute,$params){
		if($params['correct']==true){
			if(self::FormatPhone($this->$attribute)==null){
				$this->addError($attribute,"Вы ввели неверный номер");
			}
		}
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

		$senderName = 'Dostavka05';
		// @TODO YII_DEBUG переписать на YII_ENV
		if (Yii::app()->request->serverName == 'www.dostavka05.ru' || YII_DEBUG) {
			$senderName = 'Dostavka05';
			$url = "http://www.dostavka05.ru/partner/orders/" . $this->id;
			$sms_to_user2 = "Спасибо за заказ. Если вам не перезвонили, сообщите нам 555-880.";
		} elseif (Yii::app()->request->serverName == 'www.dostavka.az') {
			$senderName = 'Dostavka.az';
			$url = "http://www.dostavka.az/partner/orders/" . $this->id;
			$sms_to_user2 = "Спасибо за заказ. Если вам не перезвонили, сообщите нам 0503778822.";
		}

		$short = $this->make_bitly_url($url, 'burzhun', 'R_652f56404b4d4441a5bbde47076efdd0');

		$sms .= "  Ссылка на заказ: " . $short;
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
			$message .= 'Стоимость заказа: ' . $this->sum . '<br>';
			$message .= 'Оплата: Наличными<br>';
			$message .= 'Доставка: Курьером<br>';
			$message .= 'Ссылка:'.str_replace('http://','http://www.',$short)." <br>";

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
		$old_order = $this->oldRecord;

		//Если заказ доставлен, то вычитаем у партнера с баланса денюшку
		if ($this->status == Order::$DELIVERED&&$old_order->status!=Order::$DELIVERED) {
			$user_id = $this->user_id;
			if ($user_id) {
				$user_promo = UserPromo::model()->with('promo')->find("user_id=" . $user_id . " and used=0 and activated=1");
				if($user_promo){
					$sql="select id from tbl_promo_partner where (promo_id=".$user_promo->promo->id." and partner_id=".$this->partner->id.")";
				 	$sql2="select count(id) as count from tbl_promo_partner where promo_id=".$user_promo->promo->id;
					$res=Yii::app()->db->createCommand($sql)->queryAll();
					$res2=Yii::app()->db->createCommand($sql2)->queryAll();
					if (isset($res[0])||$res2[0]['count']==0) {
						$user_bonus = new UserBonus();
						$user_bonus->user_id = $user_id;
						$user_bonus->date = time();
						$user_bonus->sum_in_start = $user_promo->promo->count;
						$user_bonus->sum = $user_promo->promo->count;
						$user_bonus->info = "Активировал промокод на " . $user_bonus->sum . " баллов.";
						$user_bonus->save();
						$user_promo->used = 1;
						$user_promo->save();
					}
				}
			}
			$totalPrice = Order::totalPrice($this->id);
			$this->partner->orderDelivered($totalPrice, $this->id);
			if ($this->user&&!$this->forbonus) {
				$this->user->save();
			}
		}

		$domain_id = Domain::getDomain(Yii::app()->request->serverName)->id;

		//Отправляем смс об отмене заказа
		if ($this->status == Order::$CANCELLED && $domain_id <> 4) {
			if(time()-strtotime($this->date)<80000){
				$user_phone = preg_replace("/[^0-9]/", '', $this->phone);
				if ($user_phone[0] == '8') {
					$user_phone = '7' . substr($user_phone, 1);
				} elseif (strlen($user_phone) == 10) {
					$user_phone = '7' . $user_phone;
				}
				$senderName = 'Dostavka05';
				if ($domain_id == 1) {
					$senderName = 'Dostavka.az';
				} elseif ($domain_id == 2) {
					$senderName = 'Edostav';
				} elseif ($domain_id == 3) {
					$senderName = 'Dostavka05';
				}
				$phone_for_feedback = Config::getValue('phone_for_feedback_on_canceled_phone', $domain_id);
				$sms_to_user2 = "Ваш заказ отменен. Если это сделали не Вы, позвоните нам ".$phone_for_feedback;
				if ($this->partner->sms_provider == 1) {
					$body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=" . $senderName . "&to=" . $user_phone . "&text=" . urlencode($sms_to_user2));
				} elseif ($this->partner->sms_provider == 2) {
					$body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=".$senderName."&to=" . $user_phone . "&text=" . urlencode($sms_to_user2));
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
		}
		$action="";
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
				if($this->status!=$old_order->status){
					$action = "изменил статус заказа #" . $this->id . " - " . $this->status;
				}
				if($this->comment&&!$old_order->comment){
					$action = "добавил кооментарий к заказу #".$this->id;
				}
			}
		}
		/**********************/

		/*    актер      */
		if($action!=""){
			$partnerId = $this->partner ? $this->partner->id : 0;
			if (Yii::app()->user->isGuest) {
				Action::addNewAction(Action::ORDER, 0, $partnerId, 'Гость (Session ' . Yii::app()->getSession()->sessionID . ') ' . $action, $this->id);
			} else {

				switch (Yii::app()->user->role) {
					case User::USER:
						Action::addNewAction(Action::ORDER, $this->user ? $this->user->id : 0, $partnerId, 'Пользователь (ID ' . Yii::app()->user->id . ') ' . $action, $this->id);
						break;
					case User::PARTNER:
						Action::addNewAction(Action::ORDER, Yii::app()->user->id, $partnerId, 'Партнер (ID ' . $this->partner->id . ') ' . $action, $this->id);
						break;
					case User::ADMIN:
						Action::addNewAction(Action::ORDER, Yii::app()->user->id, $partnerId, 'Администратор ' . $action, $this->id);
						break;
				}
			}
		}

		return parent::afterSave();
	}

	public function getOperator(){
		return User::model()->cache(100)->find('id='.$this->user_id.' and role="admin"');
	}

	protected function beforeDelete()
	{
		Action::addNewAction(Action::ORDER, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Заказ #' . $this->id . ' удален');
	}


	protected function beforeSave()
	{
		if (!parent::beforeSave()) {
			return false;
		}

		$this->oldRecord = Order::model()->findByPk($this->id);
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
		$criteria->compare('domain_id', $this->domain_id, true);
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

		if($this->approved_operator){
			$criteria->with[] = 'operator';
			$this->approved_operator = $this->approved_operator == 'all' ? '' : $this->approved_operator;
			$criteria->compare('operator.id', $this->approved_operator);
		}
		if(isset($_GET['orders_status'])&&$_GET['orders_status']=='active'){
			$criteria->addCondition("t.status!='".Order::$DELIVERED."'");
			$criteria->addCondition(time()."-UNIX_TIMESTAMP(t.date)<20000");
		}

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

		$criteria->addInCondition('city_id', City::getCityArray(Domain::getDomain(Yii::app()->request->serverName)->id));//выводим заказы только из данного домена

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
			$criteria->addSearchCondition('t.user_id', $serchCriteria['user_id'], false, 'AND');
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

			$criteria->addSearchCondition('t.partners_id', $serchCriteria['one_partners_id'],false);
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

	public static function totalPriceAdmin($sum,$forbonus, $rub = false)
	{

		if ($rub) {
			$sum .= " " . City::getMoneyKod();
		}
		if ($forbonus == 1) {
			$sum .= " Бесплатный заказ";
		}
		return $sum;
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

	static function GetStatistics($partner_id, $condition,$domain_id)
	{
		$stat = array();
		$p_condition = "";
		if ($partner_id) {
			$p_condition = " and partners_id={$partner_id}";
		}

		$domain_condition=" and domain_id=".$domain_id;

		for ($i = 1; $i < 6; $i++) {
			$sql = "select count(id) as count from tbl_orders where status='".Order::$DELIVERED."' and order_source={$i} " . $condition . $p_condition.$domain_condition;
			if($i==1){
				$sql.=" and not exists (select id from tbl_users where tbl_users.id=tbl_orders.user_id and role='admin')";
			}
			if($i==5){
				$sql="select count(id) as count from tbl_orders where order_source=1 and status='".Order::$DELIVERED."' and (select role from tbl_users where tbl_users.id=tbl_orders.user_id)='admin'".$condition.$p_condition.$domain_condition;
			}
			$count = Yii::app()->db->createCommand($sql)->queryAll();
			$count = $count[0];
			if($i<5) $name = self::getSourceName($i);
			else $name='Операторы';
			$stat[$name] = $count['count'];
		}
		return $stat;
	}

	static function FormatPhone($phone){

		$phone=preg_replace("/[^0-9+]+/","",$phone);
		if(strlen($phone)==13){
			if(preg_match("/^\+994[50|51|55|70][0-9]{7}/",$phone)){
				return $phone;
			}else{
				return null;
			}
		}
		if(strlen($phone)==12){
			if(preg_match("/^\+79[0-9]{9}/",$phone)) {
				return $phone;
			}elseif(preg_match("/^994[50|51|55|70][0-9]{7}/",$phone)){
				return '+'.$phone;
			}else{
				return null;
			}
		}elseif(strlen($phone)==11) {
			if (preg_match("/^79[0-9]{9}/", $phone)) {
				return '+' . $phone;
			} elseif (preg_match("/^89[0-9]{9}/", $phone)) {
				$phone[0] = '7';
				return '+' . $phone;
			} else {
				return null;
			}
		}elseif(strlen($phone)==10){
			if(preg_match("/^0[50|51|55|70][0-9]{7}/",$phone)) {
				return '+994'.substr($phone,1);
			}
			if(preg_match("/^9[0-9]{9}/",$phone)){
				return '+7'.$phone;
			}
		}else{
			return null;

		}
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
									$this->sms_status = 1;
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
							echo "API access error";
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

	public static function getAdminOrdersCount($date,$domain_id, $operators = false)
	{
		$domain_id=$domain_id ? $domain_id : Yii::app()->session['domain_id']->id;
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

		// Оператор
        if ($operators) {
            if($operators == 'all'){
                /*$admins = User::getAdmins();
                foreach ($admins as $data) {
                    $adminIds[] = $data->id;
                }*/
				$admin_sql=" and exists(select id from tbl_users t2 where t2.id=t.user_id and t2.role='admin' limit 1)";
                //$admin_sql = " AND t.user_id IN (" . implode(',', $adminIds) .")";
            }else{
                $admin_sql = " AND t.user_id = " . $operators;
            }
        }else{
			$admin_sql=" and exists(select id from tbl_users t2 where t2.id=t.user_id and t2.role='admin' limit 1)";
		}
		$domain_condition=" and t.domain_id=".$domain_id;
		$sql =
			"SELECT count(id) as count
			FROM tbl_orders as t
			WHERE  FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '{$date_format}')='{$date}'
					 and order_source=1
					AND status='" . Order::$DELIVERED . "'".$admin_sql.$domain_condition;

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

	static function GetNewUsers($date,$domain_id, $operators = false){
		$date_format = '%Y-%m-%d';
		$domain_id=$domain_id ? $domain_id : Yii::app()->session['domain_id']->id;
		if (isset($_GET['period'])) {
			if ($_GET['period'] == "day") {
				$date_format = '%Y-%m-%d';
			} elseif ($_GET['period'] == 'month') {
				$date_format = '%Y-%m';
			} elseif ($_GET['period'] == 'year') {
				$date_format = '%Y';
			}
		}

		if (!empty($_GET['partner_id'])) { // @TODO ничего не используется, можно удалить?
			$partner_id = $_GET['partner_id'];
			$partner_sql = " AND partners_id = " . $_GET['partner_id'];
		} else {
			$partner_id = 0;
			$partner_sql = "";
		}
		$domain_condition=" and t1.domain_id=".$domain_id;

		$admin_sql='';
		// Оператор
         if ($operators) {
            if($operators == 'all'){
                $admins = User::getAdmins();
                foreach ($admins as $data) {
                    $adminIds[] = $data->id;
                }
                $admin_sql = " AND t1.user_id IN (" . implode(',', $adminIds) .")";
            }else{
                $admin_sql = " AND t1.user_id = " . $operators;
            }
        }

		$sql="SELECT count( * ) as count
				FROM (
					SELECT count( t2.id ) AS count
					FROM tbl_orders t1
					LEFT OUTER JOIN (
						SELECT id, phone
						FROM tbl_orders
					) AS t2 ON ( t2.phone = t1.phone
					AND t2.id < t1.id )
					WHERE FROM_UNIXTIME( UNIX_TIMESTAMP( t1.date ) , '{$date_format}' ) = '{$date}' {$domain_condition} {$admin_sql}
					GROUP BY t1.phone
					HAVING count =0
				)sd";
		$result=Yii::app()->db->cache(20000)->createCommand($sql)->queryAll();
		return $result[0]['count'];
	}

	public static function averageCheck($count = 1, $orderPiceSum)
	{
		return $count==0 ? 0: floor($orderPiceSum / $count);
	}

	public function getApprovedHtml()
	{
		$html = '<div class="appr-div">';
		$html .= '<span class="appr-site">'.$this->approved_site.'</span>'.
				'<span class="appr-partner">'.$this->approved_partner.'</span>';
		if($this->delivered != '00:00:00'){
			$html .= '<span class="appr-delivered">'.$this->delivered.'</span>';
		}
		if($this->cancelled != '00:00:00'){
			$html .= '<span class="appr-cancelled">'.$this->cancelled.'</span>';
		}

		return $html.'</div>';
	}

	public function FormatDate(){
		$date=date_create($this->date);
		return date_format($date,"d.m.y H:i:s");
	}
	static function FormatDateDay($date){
		if (strlen($date)==4)
			return $date;
		$date=date_create($date);
		if(isset($_GET['period'])){
			if($_GET['period']=='month'){
				return date_format($date,"m.y");
			}
			if($_GET['period']=='year'){

				return date_format($date,"Y");
			}
		}

		return date_format($date,"d.m.y");
	}

	//Предлагать приложения для заказавших с мобильного
	static function checkNewOrdersMobile(){
		$sql="select id,phone,domain_id,partners_id from tbl_orders where  UNIX_TIMESTAMP()-UNIX_TIMESTAMP(date)>600 and
                UNIX_TIMESTAMP()-UNIX_TIMESTAMP(date)<661 and order_source=2 and status<>'".Order::$CANCELLED."'";
		$res=Yii::app()->db->createCommand($sql)->queryAll();
		foreach($res as $data){
			$sql2="select id from tbl_orders where id<>".$data['id']." and id>36586 and phone='".$data['phone']."' limit 1";
			$r=Yii::app()->db->createCommand($sql2)->queryAll();
			if(!isset($r[0]['id'])){
				$sql3="select sms_provider from tbl_partners where id=".$data['partners_id'];
				$p=Yii::app()->db->createCommand($sql3)->queryAll();
				$sms_provider=$p[0]['sms_provider'];
				$senderName='';
				if($data['domain_id']==3){
					$senderName = 'Dostavka05';
					$url="Доставка05 http://www.dostavka05.ru/app";
				}
				if($data['domain_id']==1){
					$senderName = 'Dostavka.az';
					$url="Dostavka.az http://www.dostavka.az/app";
				}
				$text="Скачайте удобное приложение ".$url;
				if ($sms_provider == 1) {
					$body = file_get_contents("http://sms.ru/sms/send?api_id=3e4fd4c2-05c0-8f34-499e-1926d8383684&from=".$senderName."&to=".$data['phone'] . "&text=" . urlencode($text));
				} elseif ($sms_provider == 2) {
					$body = file_get_contents("http://gate.smsaero.ru/send/?user=ilyas_urg@mail.ru&password=25f9e794323b453885f5181f1b624d0b&from=dostavka05&to=" . $data['phone'] ."&text=" . urlencode($text));
				} elseif ($sms_provider == 3) {
					$api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
					//$sms_text = iconv('cp1251', 'utf-8',$sms);
					$POST = array(
						'api_key' => $api_key,
						'phone' => $data['phone'],
						'sender' => $senderName,
						'text' => $text
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

		}
	}
}


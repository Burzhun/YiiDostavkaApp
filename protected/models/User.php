<?php

/**
 * The followings are the available columns in table 'users':
 * @property int id
 * @property string name
 * @property string email
 * @property string reg_date
 * @property string last_visit
 * @property string phone
 * @property int address_id
 * @property int total_order
 * @property string img
 * @property int partner_id
 * @property string pass
 * @property string activkey
 * @property int status
 * @property string role
 * @property string bonus
 * @property string identity
 * @property string network
 * @property int state
 *
 * @property Order[] orders
 * @property CartItem[] cartItem
 * @property Partner partner
 * @property Review[] reviews
 * @property UserAddress[] address
 */
class User extends CActiveRecord
{
	const STATUS_NOACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_BANED = -1;

	// роли на сайте
	const GUEST = "guest";
	const USER = "user";
	const PARTNER = "partner";
	const ADMIN = "admin";
    const OPERATOR = "operator";
	const BONUS_PROCENT_FROM_ORDER = 0.1;

    //public $bonus2;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Генерация пароля
	 *
	 * @return string
	 */
	public static function generatePassword()
	{
		return substr(md5(rand(1000, 10000) . time() . rand(1000, 10000)), rand(0, 25), 6);
	}

	protected function afterFind()

    {

        //$this->bonus2=$this->getTotalBonus();

    }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->getModule('user')->tableUsers;
		//return '{{users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.

		return (
		array(
			array('name, phone', 'required', 'on' => 'simplereg'),
			array('name', 'length', 'max' => 20, 'min' => 3, 'message' => UserModule::t("Некорректное имя (длина должна составлять от 3 до 20 символов).")),
			array('email', 'email'),
			array('pass', 'length', 'max' => 128, 'min' => 4, 'message' => UserModule::t("Некорректный пароль (минимум должно быть 4 символа).")),
			array('total_order, status, partner_id', 'numerical', 'integerOnly' => true),
			//array('name', 'unique', 'message' => UserModule::t("Пользователь с таким логином уже существует.")),
			//array('name', 'match', 'pattern' => '/^[А-Яа-яA-Za-z0-9_]+$/u','message' => "Присутствуют некорректные символы (А-Яа-яA-z0-9)."),
			//array('email', 'unique', 'on'=>'simplereg', 'message' => UserModule::t("Пользователь с таким email`ом уже существует.")),
			array('reg_date', 'date', 'format' => 'yyyy-MM-dd'),
			//array('last_visit', 'date', 'format'=>'yyyy-MM-dd'),
			array('img', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true, 'maxSize' => 2 * 1024 * 1024),
			array('bonus,pol,birthdate,phone_confirmed,password_confirmed, id,identity,network, name, email, reg_date, last_visit, phone, address_id, total_order, img, partner_id, pass, status, activkey', 'safe'),

		));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		$relations = array(
			/*'profile'=>array(self::HAS_ONE, 'Profile', 'user_id'),
		    'advertisements'=>array(self::HAS_MANY, 'Advertisement', 'author_id'),*/
			'orders' => array(self::HAS_MANY, 'Order', 'user_id'),
			'cartItem' => array(self::HAS_MANY, 'CartItem', 'user_id'),
			'partner' => array(self::HAS_ONE, 'Partner', 'user_id'),
			'reviews' => array(self::HAS_MANY, 'Review', 'user_id'),
			'address' => array(self::HAS_MANY, 'UserAddress', 'user_id'),
			'bonuses' => array(self::HAS_MANY, 'UserBonus', 'user_id'),
			'user_token' => array(self::HAS_ONE, 'UserToken','user_id'),
			'user_sms_token' => array(self::HAS_ONE, 'UserSmsToken','user_id'),
		);

		//if (isset(Yii::app()->getModule('user')->relations)) $relations = array_merge($relations,Yii::app()->getModule('user')->relations);
		return $relations;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name'           => UserModule::t("Имя"),
			'pass'           => UserModule::t("Пароль"),
			'verifyPassword' => UserModule::t("Повторите пароль"),
			'email'          => UserModule::t("E-mail"),
			'verifyCode'     => UserModule::t("Проверочный код"),
			'id'             => UserModule::t("Id"),
			'phone'          => UserModule::t("Телефон"),
			'partner_id'     => UserModule::t("Партнер"),
			'bonus'          => 'Бонусы',
			'activkey'       => UserModule::t("Активационный ключ"),
			'reg_date'       => UserModule::t("Дата регистрации"),
			'total_order'    => UserModule::t("Количество заказов"),
			'last_visit'     => UserModule::t("Последний визит"),
			'status'         => UserModule::t("Статус"),
			'role'           => UserModule::t('Роль'),
			'birthdate'      => UserModule::t('Дата рождения'),
			'pol'            => UserModule::t('Пол')
		);
	}

	public function scopes()
	{
		return array(
			'active' => array(
				'condition' => 'status=' . self::STATUS_ACTIVE,
			),
			'notactvie' => array(
				'condition' => 'status=' . self::STATUS_NOACTIVE,
			),
			'banned' => array(
				'condition' => 'status=' . self::STATUS_BANED,
			),
			/*'superuser'=>array(
				'condition'=>'superuser=1',
			),*/

			/*'chosenuser'=>array(
				'condition'=>'chosen=1',
			),*/

			'notsafe' => array(
				'select' => 'id, name, pass, email, activkey, reg_date, last_visit, status, total_order',
			),
		);
	}

	public function defaultScope()
	{
        $s='bonus, network,role, identity,id,pol,birthdate, name, email, reg_date, last_visit, status, partner_id, phone, total_order, pass';
        if($_SERVER['REQUEST_URI']=='/admin/statistics/users'){
            $s.=",role";
        }
		return array(
			'select' => $s
		);
	}

	public static function itemAlias($type, $code = null)
	{
		$_items = array(
			'UserStatus' => array(
				self::STATUS_NOACTIVE => UserModule::t('Не активирован'),
				self::STATUS_ACTIVE => UserModule::t('Активирован'),
				self::STATUS_BANED => UserModule::t('Забанен'),
			),
			'AdminStatus' => array(
				'0' => UserModule::t('No'),
				'1' => UserModule::t('Yes'),
			),

			'ChosenStatus' => array(
				'0' => UserModule::t('Нет'),
				'1' => UserModule::t('Да'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}

	public function search($params = array())
	{
		$criteria = new CDbCriteria;
		$criteria->compare('t.id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('reg_date', $this->reg_date, true);
		$criteria->compare('birthdate', $this->birthdate, true);
		$criteria->compare('pol', $this->pol, true);
		$criteria->compare('last_visit', $this->last_visit, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('address_id', $this->address_id, true);
		$criteria->compare('total_order', $this->total_order);
		$criteria->compare('img', $this->img, true);
		$criteria->compare('partner_id', $this->partner_id);
		$criteria->compare('pass', $this->pass);
		$criteria->compare('activkey', $this->activkey);
		$criteria->compare('status', $this->status);
        if(isset($_GET['User']['role'])){
            $criteria->addCondition("role like '%{$_GET['User']['role']}%'");
        }

		//$criteria->compare('role', $this->getRole());
		//$criteria->compare('bonus2', $this->getTotalBonus());
        if(isset($_GET['User']['bonus'])){
            //$criteria->addCondition($_GET['User']['bonus']=$this->getTotalBonus());
            //$criteria->join="left join tbl_user_bonus on tbl_user_bonus.user_id=t.id";
            /*$criteria->with=array('bonuses'=>array(
                'select'=>'sum',
                'joinType'=>'LEFT JOIN',
                'having'=>"sum(bonuses.sum)=".$_GET['User']['bonus']
                //'condition'=>'sum2='.$_GET['User']['bonus']
            ));*/
            //$criteria->addCondition("exists(select id from tbl_user_bonus where tbl_user_bonus.user_id=t.id and sum(tbl_user_bonus.sum)=0 limit 1)");
        }
		//$criteria->compare('gave_total_money', $this->getGaveTotalMoney());
		$criteria->compare('identity', $this->identity);
		$criteria->compare('network', $this->network);
		$criteria->compare('state', $this->state);

		/*$criteria->condition = '1=1 ';
		$criteria->condition .= ' AND role != "admin"';*/
		//$criteria->addSearchCondition("role", User::ADMIN, true, 'AND', 'NOT LIKE');

		if (isset($params['nopartner'])) {
			//$criteria->addSearchCondition("role", User::PARTNER, true, 'AND', 'NOT LIKE');
			//$criteria->condition .= "AND role !='".User::PARTNER."'";
		}
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 40,
			),
			'sort' => array(
	    		'defaultOrder' => 'id DESC',
			)
		));
	}

	public static function phoneConfirmed(){
		if(Yii::app()->user->isGuest||Yii::app()->session['domain_id']->id!=3){
			return true;
		}
		if(isset(Yii::app()->session['phone_confirmed'])){
			return Yii::app()->session['phone_confirmed'];
		}else{
			$sql="select phone_confirmed from tbl_users where id=".Yii::app()->user->id;
			$result=Yii::app()->db->createCommand($sql)->queryScalar();
			Yii::app()->session['phone_confirmed']=$result;
			return $result;
		}

	}
	public static function passwordConfirmed(){
		if(Yii::app()->user->isGuest||Yii::app()->session['domain_id']->id!=3){
			return true;
		}
		if(isset(Yii::app()->session['password_confirmed'])){
			return Yii::app()->session['password_confirmed'];
		}else{
			$sql="select password_confirmed from tbl_users where id=".Yii::app()->user->id;
			$result=Yii::app()->db->createCommand($sql)->queryScalar();
			Yii::app()->session['password_confirmed']=$result;
			return $result;
		}
	}

	public function getRole()
	{
		return $this->partner_id ? self::PARTNER : self::USER;
	}

	static function isBanned(){
		if(!isset(Yii::app()->request->cookies['cookie_user_id'])){
			return false;
		}
		$sql="select id from tbl_banned where cookie_user_id='".Yii::app()->request->cookies['cookie_user_id']->value."'";
		$res=Yii::app()->db->createCommand($sql)->queryAll();
		if(isset($res[0])){
			return true;
		}
		return false;
	}


	public function suggestName($keyword, $limit = 20)
	{
		$criteria = array(
			'condition' => 'name LIKE :keyword',
			'order' => 'name',
			'limit' => $limit,
			'params' => array(
				':keyword' => "$keyword%"
			)
		);
		$models = $this->findAll($criteria);
		$suggest = array();
		foreach ($models as $model) {
			$suggest[] = array(
				'value' => $model->name,
				'label' => $model->name,
			);
		}

		return $suggest;
	}

	public static function getPartnerListWith2phone($_id)
	{
		if ($_id == 18 || $_id == 57 || $_id == 85) {
			return true;
		} else {
			return false;
		}
	}

	public function getTotalBonus()
	{
		$bonus1=0;
        foreach ($this->bonuses as $bonus) {
            $bonus1+=$bonus->sum;
        }
        return $bonus1;
    }

	public function getGaveTotalMoney()
	{
		$sum = 0;
		foreach ($this->orders as $order) {
            if($order->status==Order::$DELIVERED)
			$sum += $order->sum;
		}
		return $sum;
	}
    public function getTotalProcent()
    {
        $sum = 0;
        foreach ($this->orders as $order) {
            if($order->status==Order::$DELIVERED)
                $sum += $order->sum*$order->partner->procent_deductions/100;
        }
        return $sum;
    }
	public function bonusCount()
	{
		return $this->bonus;
	}
	public function getDeliveredOrdersCount(){
		$sql="select count(id) as count from tbl_orders where user_id=".$this->id." and status='Доставлен'";
		$count=Yii::app()->db->createCommand($sql)->queryAll();
		return $count[0]['count'];
	}
	public function getCancelledOrdersCount(){
		$sql="select count(id) as count from tbl_orders where user_id=".$this->id." and status='Отменен'";
		$count=Yii::app()->db->createCommand($sql)->queryAll();
		return $count[0]['count'];
	}

    static function getBonus($user_id){
        $sql=" select sum(`sum`) as sum from tbl_user_bonus where user_id=".$user_id;
        $sum=Yii::app()->db->createCommand($sql)->queryAll($sql)[0]['sum'];
        return $sum;

    }

    static function isEnoughBonus($user_id,$totalPrice){
        $bonus=User::getBonus($user_id);
        if($totalPrice*4<=$bonus){
            return true;
        }
        else{
            return false;
        }
    }

    public function getTotalMoneySpent(){
        $sql="select sum(t2.total_price)as sum from tbl_orders t1 left join tbl_order_items t2 on t1.id=t2.order_id where t1.user_id=".$this->id." and t1.status='{Order::$DELIVERED}'";
        $sum=Yii::app()->db->createCommand($sql)->queryAll($sql)[0]['sum'];
        return $sum;
    }

    protected function afterSave()
    {
        $email=$this->email;
        $invite=Invites::model()->find('email=:email and wasted=0',array(':email'=>$email));
        if($invite&&false){
            $invite->wasted=1;
            $invite->save();
            $user_bonus=new UserBonus();
            $user_bonus->user_id=$invite->user_id;
            $user_bonus->date=time();
            $user_bonus->sum_in_start=100;
            $user_bonus->sum=100;
            $user_bonus->info="Получил 100 баллов за регистрацию приглашенного друга";
            $user_bonus->save();
        }
        //$this->date_change = time();
        parent::afterSave();

        return;
    }

    public static function Add_to_unisender($email,$phone,$name){
        $api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";

        // Новые подписчики


        // Список, куда их добавить
        $list = "5405738";

        // Создаём POST-запрос
        $POST = array (
            'api_key' => $api_key,
            'field_names[0]' => 'email',
            'field_names[1]' => 'Name',
            'field_names[2]' => 'phone',
            'field_names[3]' => 'email_list_ids'
        );
        $POST['data[0][0]'] = $email;
        $POST['data[0][1]'] = iconv('cp1251', 'utf-8', $name);
        $POST['data[0][2]'] = $phone;
        $POST['data[0][3]'] = $list;
        $_POST['force_import']=1;

        // Устанавливаем соединение
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_URL,
            'http://api.unisender.com/ru/api/importContacts?format=json');
        $result = curl_exec($ch);

        if ($result) {
            // Раскодируем ответ API-сервера
            $jsonObj = json_decode($result);
            if(null===$jsonObj) {
                // Ошибка в полученном ответе
                //echo "Invalid JSON";

            }
            elseif(!empty($jsonObj->error)) {
                // Ошибка импорта
               // echo "An error occured: " . $jsonObj->error . "(code: " . $jsonObj->code . ")";

            } else {
                // Новые подписчики успешно добавлены
                //echo "Success! Added " . $jsonObj->result->new_emails . " new e-mail addresses";

            }
        } else {
            // Ошибка соединения с API-сервером
            //echo "API access error";
        }
    }

    public static function getAdmins()
    {
    	return self::model()->findAll('role = "admin"');
    }

	static function hasStatAccess(){
		if(!Yii::app()->user->isGuest){
//			$ids=array(989,1348,129,1344,1082);
//			if(in_array(Yii::app()->user->id,$ids)){
//				return true;
//			}
			return Yii::app()->user->role == 'admin';
		}
		return false;
	}

	static function SendUnisenderSms($phone,$sendername,$text){
		$phone=Order::FormatPhone($phone);
		$api_key = "5iaema369yopimxbjrbjnx1a3yoncqx7z3o46oha";
		$senderName="Dostavka05";
		//$sms_text = iconv('cp1251', 'utf-8',$sms);
		$POST = array(
			'api_key' => $api_key,
			'phone' => str_replace('+','',$phone),
			'sender' => $sendername,
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
		if ($result) {
			// Раскодируем ответ API-сервера
			$jsonObj = json_decode($result);

			if(null===$jsonObj) {
				// Ошибка в полученном ответе
				//echo "Invalid JSON";

			}
			elseif(!empty($jsonObj->error)) {
				// Ошибка отправки сообщения
				//echo "An error occured: " . $jsonObj->error . "(code: " . $jsonObj->code . ")";

			} else {
				// Сообщение успешно отправлено
				return true;

			}
		} else {
			// Ошибка соединения с API-сервером
			//echo "API access error";
		}
		return false;
	}

	/**
	 * Создание пользователя на основе сделанного заказа
	 *
	 * Если пользователь не был зарегистрирован, для него автоматически создается личный кабинет на основе данных,
	 * указанных при оформлении заказа. При этом заказ обязательно должен быть в статусе "Доставлен"
	 *
	 * @param Order $order
	 */
	static function addUserFromOrder($order){
		$user=User::model()->find("phone='".$order->phone."'");
		if($user){
			if($user->status==1&&$user->email==''){
				// Случай, когда заказ был сделан неавторизованным пользователем, но по номеру телефона
				// было определено, что пользователь был зарегистрирован ранее. В этом случае у заказа меняется только владелец.
				// При этом SMS с количеством баллов может отправляться, например, в методе orderDelivered модели Partner
				$order->user_id=$user->id;
				$order->save();
			}
			if($user->status==0&&$user->email==''){
				// Случай, когда заказ был сделан неавторизованным пользователем, но для него ранее был создан личный кабинет,
				// например, создан автоматически.
				$pass= self::generatePassword();
				$user->pass = UserModule::encrypting($user->pass);
				$user->save();
				$bonus=User::getBonus($user->id);
				$text="Вам начислено ".$bonus." баллов. Ваш логин - ".$user->phone.". Ваш пароль - ".$pass;
				if(User::SendUnisenderSms($user->phone,"Dostavka05",$text)){
					$user->status=1;
					$user->save();
				}
			}
		}else{
			$order2=Order::model()->find('user_id<>0 and phone="'.$order->phone.'"');
			// @TODO Изменить условие создания личного кабинета так, чтобы он создавался для номера телефона, который когда-то уже принадлежал другому пользователю (оформлявшему заказы), а теперь у этого номера иной владелец.
			if(!$order2){
				$name=$order->customer_name;
				$pass = self::generatePassword();
				$phone=Order::FormatPhone($order->phone);
				// @TODO Написать отдельную функцию для процесса автоматического создания пользователя
				$user=new RegistrationForm();
				$user->name = $name;
				$user->email = '';
				$user->verifyPassword =$pass;
				$user->pass = $pass;
				$user->phone = $phone;
				if ($user->validate()) {
					$soucePassword = $user->pass;
					$user->activkey = UserModule::encrypting(microtime() . $user->pass);
					$user->pass = UserModule::encrypting($user->pass);
					$user->verifyPassword = UserModule::encrypting($user->verifyPassword);
					$user->reg_date = date('Y-m-d H:s:i');
					$user->last_visit = 0;
					$user->status = 0;
					$user->phone_confirmed = 1;
					$user->password_confirmed = 0;
					if($user->save()){
						$orders=Order::model()->findAll("phone='".$user->phone."' and status='".Order::$DELIVERED."'");
						foreach($orders as $order1){
							$order1->user_id=$user->id;
							$order1->save();
							UserBonus::getBonusForOldOrder($order1);
						}
						$bonus= (int) User::getBonus($user->id);
						$sms_to_user="Вам начислено {$bonus} баллов. Ваш логин ".$user->phone.", пароль ".$pass;
						if(self::SendUnisenderSms($user->phone,"Dostavka05",$sms_to_user)){
							$user->status=1;
							$user->save();
						}
					}

				}
			}
		}

	}
}
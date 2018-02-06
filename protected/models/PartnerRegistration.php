<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PartnerRegistration extends CFormModel
{
	public $id;
	public $partnername;
	public $username;
	public $pass;
	public $verifyPassword;
	public $email;
	public $orderemail;
	public $city_id;
	public $address;
	public $min_sum;
	public $smsphone;
	public $contactphone;
	public $regmail;
	public $delivery_cost;
	public $delivery_duration;
	public $work_begin_time;
	public $work_end_time;
	public $day1;
	public $day2;
	public $day3;
	public $day4;
	public $day5;
	public $day6;
	public $day7;
	public $img;
	public $text;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			
			array('partnername, email, username, city_id', 'required', 'message'=>'Необходимо обязательно заполнить'),
			
			array('partnername, username', 'match', 'pattern' => '/^[-0-9A-Za-zА-Яа-я\s]+$/u', 'message' => UserModule::t("Поле заполнено некорректно. Допускаются буквы, цифры, знак подчеркивания и тере.")),
			
			
			array('smsphone, contactphone', 'match', 'pattern' => '/^7\d{10}$/','message' => UserModule::t("Некорректно введен номер. Формат должен быть вида '79881112233'")),
			array('work_begin_time, work_end_time', 'match', 'pattern' => '/^\d{1,2}:\d{1,2}$/','message' => UserModule::t("Время должно соответствовать формату ЧЧ:ММ" )),
			array('work_begin_time, work_end_time', 'length', 'max'=>5, 'min' => 3,'message' => UserModule::t("Время должно соответствовать формату ЧЧ:ММ")),
			array('day1, day2, day3, day4, day5, day6, day7', 'numerical', 'integerOnly'=>true),
			array('day1, day2, day3, day4, day5, day6, day7', 'match', 'pattern' => '/^0|1$/','message' => UserModule::t("Ошибка")),
			
			array('img', 'file', 'types'=>'jpg, jpeg, gif, png', 'allowEmpty'=>true, 'maxSize'=>2*1024*1024),
			
			array('min_sum, delivery_cost', 'match', 'pattern' => '/[0-9]+/','message' => UserModule::t("В поле должны содержаться только цифры.")),
			array('delivery_duration', 'in', 'range'=>Partner::getDeliveryDurationList()),
			
			array('orderemail, email', 'email'),
			//array('rememberMe', 'boolean'),
			
			//array('pass, verifyPassword', 'password'),
			
			array('address', 'length', 'max'=>255, 'message' => UserModule::t("Слишком длинный адрес")),
			
			array('pass', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Некорректный пароль (минимум должно быть 4 символа).")),
			array('verifyPassword', 'compare', 'compareAttribute'=>'pass', 'message' => UserModule::t("Пароли не совпадают.")),
			
			array('email', 'uniqueEmail'),
			
			array('id, partnername, username, pass, email, orderemail, city_id, address, min_sum, smsphone, contactphone, regmail, delivery_cost, delivery_duration, work_begin_time, work_end_time, day1, day2, day3, day4, day5, day6, day7, img, text', 'safe'),
		);
	}
	
	/*protected function beforeValidate()
	{
		parent::beforeValidate();
		$result = Partner::model()->find(array('condition'=>"name='".$this->partnername."'"));
		if(!empty($result))
		{
			$this->addError("partnername", "Партнер с таким названием уже зарегистрирован");
		}
	}*/
	
	public function uniqueEmail($attribute,$params)
	{
		$unique = User::model()->count(array('condition'=>"email='".$this->$attribute."'"));
		if($unique != 0){$this->addError($attribute, 'Пользователь с таким email`ом уже существует.');}
	}
	
	public function attributeLabels()
	{
		return array(
			'id'=>"ID",
			'partnername'=>"Название",
			'username'=>"Контактное лицо",
			'email'=>"Email",
			'orderemail'=>"Email для получения уведомления о заказах",
			'city_id'=>'Город',
			'address'=>'Адрес',
			'min_sum'=>'Мин. сумма заказа',
			'contactphone'=>'Телефон',
			'smsphone'=>'Телефон для получения смс уведомлений',
			'pass'=>'Пароль',
			'verifyPassword'=>'Повторите пароль',
			'delivery_duration'=>'Время доставки',
			'delivery_cost'=>'Стоимость доставки',
			'work_begin_time' => 'Начало рабочего дня',
			'work_end_time' => 'Конец рабочего дня',
			'day1' => 'Пн',
			'day2' => 'Вт',
			'day3' => 'Ср',
			'day4' => 'Чт',
			'day5' => 'Пт',
			'day6' => 'Сб',
			'day7' => 'Вс',
			'img' => 'Картинка',
			'text' => 'Дополнительная информация',
		);
	}
}
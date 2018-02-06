<?php

class UloginWidget extends CWidget
{
	//параметры по-умолчанию
	private $params = array(
		'mobilebutton' =>  '0',
		'display'       =>  'panel',
		'fields'        =>  'first_name,last_name,email',
		'providers'     =>  'vkontakte,odnoklassniki,mailru,facebook,twitter,google',
		'hidden'        =>  'yandex,livejournal,openid,liveid,steam',
		'redirect'      =>  '',
		'logout_url'    =>  '/ulogin/logout',
		//'lang'          => 'en',
	);

	public function run()
	{
		//подключаем JS скрипт
		Yii::app()->clientScript->registerScriptFile('http://ulogin.ru/js/ulogin.js', CClientScript::POS_HEAD);
		$this->render('uloginWidget', $this->params);
	}

	public function setParams($params)
	{
		$this->params = array_merge($this->params, $params);
	}
}

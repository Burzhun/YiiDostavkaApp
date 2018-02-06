<?php

/*--Кодировка--*/
define('CHARSET', 'utf-8');

/*--Валидация полей--*/
define('VALIDATE_HTML5', 0);													//Включить валидацию для html5
define('KOLSIM', 3);															//Минимальное кол-во вводимых символов
define('ERROR_NAME', 'Поле "имя" заполнено некорректно!', TRUE);				//Текст ошибок
define('ERROR_EMAIL', 'Поле "e-mail" заполнено некорректно!', TRUE);
define('ERROR_TELEFON', 'Поле "Телефон" заполнено некорректно!', TRUE);
define('ERROR_EMPTY', 'Поля не заполнены или слишком короткие!', TRUE);

/*--Настройка письма--*/
$to_email = array('info@dostavka05.ru');				                                	//Адрес Email кому доставлять письма								
$cc_email = array();															//Адрес Email кому доставлять копияю письма
define('FROM_EMAIL', 'info@site.ru');											//Адрес Email от кого письма(Email сайта)
define('FROM_NAME', 'Info');													//Имя отправителя
define('SUBJECT', 'Форма обратной связи');										//Тема письма
define('HEADTABLE', 'Сообщение отправлено с сайта www.dostavka05.ru');					//Текст заголовка таблицы в письме
define('WIDTHTABLE', 0);														//Ширина таблицы в письме				
define('WIDTHNAME',80);													     	//Ширина столбца с названиями полей формы в письме
define('WIDTHMESSAGE',0);														//Ширина столбца с данными полей формы в письме
define('ALIGNNAME', 'left');													//Выравнивание текста в столбце с названиями полей формы в письме
define('ALIGNMESSAGE', 'right');												//Выравнивание текста в столбце с данными полей формы в письме

/*--Отчет об отправки--*/
//сообщение при удачной отправке:
$good_mail = <<<html
<div class = "error-report">
<div class = "head-report">Спасибо!</div>
<div class = "text-report">
<p>Ваше сообщение отправлено.</p>
<p>Отправить новое <a href="#" class="repeatform">сообщение?</a><p>
</div>
</div>
html;
//сообщение при не удачной отправке:
$bad_mail = <<<html
<div class="error-report">
<div style="margin-top: 20px; font-size: 18px;">
Ваше сообщение не отправлено. Попрбуйте отправить письмо позже.</div>
</div>
html;

/*--Обратное письмо--*/
define('BACK_MAIL', 0);															//Отправка письма, написавшему о поддтверждении получения заказа/письма. Уполя "почта" должно быть аттрибут name = "email"
//сообщение:
$repeat_mail = <<<html
<p>Спасибо! Ваш отзыв получен. После модерации он будет размещен на нашем сайте.</p>
html;

/*--Настройка полей фрмы--*/
$zForma = array(
/*--Заголовок--*/
array(
	'type' => 'freearea',
	'value' => '<div class="form-head"></div>',
	),
/*--Ваше имя--*/
array(
	'type' => 'input',
	'label' => 'Ваше имя (*)',
	'formail' => 1,
	'name_mail'=>'Имя',
	'attributs' => array(
					'id'=>'youname',
					'name'=>'name',
					'type'=>'text',
					'placeholder'=>'Ваше имя',
					'value'=>'',
					'required'=>'',
					'autofocus'=>'',
				   ),
	),
/*--Ваш e-mail--*/
array(
	'type' => 'input',
	'label' => 'Ваш e-mail (*)',
	'formail' => 1,
	'name_mail'=>'E-mail',
	'attributs' => array(
					'id'=>'youemail',
					'name'=>'email',
					'type'=>'text',
					'placeholder'=>'Ваш e-mail',
					'required'=>'',
					'pattern'=>'^([a-z,._,.\-,0-9])+@([a-z,._,.\-,0-9])+(\.([a-z])+)+$',
				   ),
	),
/*--Ваше сообщение--*/
array(
	'type' => 'textarea',
	'label' => 'Текст сообщения:',
	'formail' => 1,
	'name_mail'=>'Отзыв',
	'attributs' => array(
					'name'=>'message',
					'type'=>'text',
					'rows'=>'8',
					'cols'=>'46',
					'placeholder'=>'',
					'value'=>'',
				   ),
	),
/*--Кнопка--*/
array(
	'type' => 'input',
	'class' => 'buttonform',
	'attributs' => array(
					'type'=>'submit',
					'value'=>'Отправить',
				   ),
	),
/*--Блок ошибок--*/
array(
	'type' => 'freearea',
	'value' => '<div class="error_form"></div>',
	),
);
?>
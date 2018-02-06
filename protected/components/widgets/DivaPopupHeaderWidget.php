<?php

class DivaPopupHeaderWidget extends CWidget
{
		
	public $icon = '';
	public $title = '';
	public $params = array();

	public function init()
	{

		
	}

	public function run()
	{

		$icons = array(
				'icons'=>array(
					'event16'=>array('url'=>'/img/event16.png'),
					'center16'=>array('url'=>'/images/touch-icon-ipad.png'),
					'location16'=>array('url'=>'/img/location16.png'),
					'lang16' =>array('url'=> '/img/lang16.png'),
					'delivery16' =>array('url'=> '/img/delivery16.png'),
					'gift16' =>array('url'=> '/img/gift16.png'),
				),
		);
		$icons = $icons['icons'];
		
		$this->render('diva_popup_header', array(
				'icons'=>$icons
		));

	}
}

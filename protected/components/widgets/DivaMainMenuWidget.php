<?php

class DivaMainMenuWidget extends CWidget
{
		
	public $config;

	public function init()
	{

		// load config
		$this->config = new CConfiguration(Yii::getPathOfAlias('admin.config').'\main_menu.php');

	}

	public function run()
	{

		$this->render('diva_main_menu', array());

	}
}

<?php

class DivaPopupFooterWidget extends CWidget
{
		
	public $leftBar = array();
	public $rightBar = array();

	public function init()
	{

	}

	public function run()
	{

		$this->render('diva_popup_footer', array());

	}
}

<?php

class DivaPositionSelectWidget extends CWidget
{
		
	public $object;
	public $positionField = 'pos';
	public $behavior = 'positioning';  // 1
	
	public function init()
	{

		

	}

	public function run()
	{

		$options = $this->object->asa($this->behavior)->getDataForPositionSelectOptions();  // 2
						
		$this->render('diva_position_select', array(
				'options'=>$options
		));

	}
}

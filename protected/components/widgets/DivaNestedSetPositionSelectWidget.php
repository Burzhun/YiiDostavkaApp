<?php

class DivaNestedSetPositionSelectWidget extends CWidget
{
		
	public $object;
	public $behavior = 'tree';  
	
	public function init()
	{

		

	}

	public function run()
	{

		$options = $this->object->asa($this->behavior)->getDataForPositionSelectOptions();  
		//p($options); exit(0);
		
		$this->render('diva_nested_set_position_select', array(
				'options'=>$options
		));

	}
}

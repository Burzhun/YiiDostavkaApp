<?php

class DivaItemCommentWidget extends CWidget
{
		
	public $inputName = '';
	public $object = '';
	public $behavior = 'comment';

	public function init()
	{
			
		if (empty ($this->inputName)){
			$class = get_class($this->object);
			$commentField = $this->object->asa($this->behavior)->commentField;
			$this->inputName = $class.'['.$commentField.']';
		}

	}

	public function run()
	{
		
		$commentField = $this->object->asa($this->behavior)->commentField;

		$this->render('diva_item_comment', array(
				'commentField'=>$commentField
		));

	}
}

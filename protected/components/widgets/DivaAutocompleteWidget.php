<?php

class DivaAutocompleteWidget extends CWidget
{
		
	public $model;
	public $behavior = 'autocomplete';  
	public $inputName;
	public $url;
	public $additionalOptions = array();
	
	
	public function init()
	{

		

	}

	public function run()
	{

		$defaultOptions = array(
			'autoFill'=>true,
			'minChars'=>3,
		);
		
		
		$value = $this->model->asa($this->behavior)->valueTemplate;
		foreach($this->model->asa($this->behavior)->valueFields as $field){
			$value = str_replace('{{'.$field.'}}', $this->model->$field, $value);
		}
		
		
		$options = array(
			'name'=>('__autocomplete__trash__'.$this->inputName),
			'value'=>$value,
			'url'=>$this->url,
			'htmlOptions' => array(
				'onkeyup'=>'window.diva.autocomplete_prepare_object_id(this, "'.addslashes($this->inputName).'");',	
			)
		);
		
		
		$finalOptions = array_merge($defaultOptions, $this->additionalOptions, $options);

		
		$this->render('diva_autocomplete', array(
				'options'=>$finalOptions
		));

	}
}

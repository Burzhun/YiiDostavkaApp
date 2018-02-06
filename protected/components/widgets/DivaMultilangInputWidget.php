<?php

class DivaMultilangInputWidget extends CWidget
{
		
	public $attribute;
	public $model;	
	public $form;	

	public function init()
	{
			
		
		
	}

	public function run()
	{
		
		// получаем имя модели
		$modelname = get_class($this->model);
		
		//перебираем список доступных языков,
		//метод возвращает массив ('ru' => 'Русский') и т.д
		//если язык - тот который по умолчанию то название поля ввода не title_ru, (_ru - суффикс) а просто title
		$str = '';
		
		foreach (Language::getLangsForMLBehavior() as $langCode => $langName) :
		
			if($langCode === Yii::app()->params['defaultLanguage'])
					$suffix = '';
			else
					$suffix = '_'.$langCode;
			
			$attribute = $this->attribute.$suffix;

			$langARInputName = $modelname."[$attribute]"; // @TODO не используется, можно удалить?
			
			
// итак что у нас есть
			//  $suffix =   _ru _en ..
			//  $langARInputName =   ModelName['attribute']
			//  $lang =  "Русский" ..

			$value = $this->model->{$this->attribute.$suffix}; // @TODO не используется, можно удалить?
			//pa($model);
			
			// собираем
			$str .= CHtml::activeTextField($this->model, $attribute, array (
				'type'=>"text",
				'class'=>'lang-input',
				'placeholder'=>$langName
			));
			$str .= $this->form->error($this->model, $attribute);
			//p($this->form->error($this->model, $attribute));
			
			//заменяем шалоны
			// {{langSuffix}} - суффикс ( _ru ... )
			// {{langName}} - название языка        
			// {{langARInputName}} - название поля ввода для AR    
			/*$output = str_replace('{{langARInputName}}',$langARInputName, $text);
			$output = str_replace('{{langSuffix}}',$suffix, $output);
			$output = str_replace('{{langName}}',$langName, $output);
			$output = str_replace('{{langValue}}',$value, $output);*/
			//выводим сформированную строку
			
			
		endforeach;
		
		echo $str;
		
	}
}

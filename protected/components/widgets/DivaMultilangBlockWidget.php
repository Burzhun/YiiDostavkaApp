<?php

class DivaMultilangInputWidget extends CWidget
{
		
	public $attribute;
	public $model;	

	public function init()
	{
			
		ob_start();
		ob_implicit_flush(false);
		
	}

	public function run()
	{
		
		// получаем имя модели
		$modelname = get_class($this->model);
		
		 //получаем переданную в виджет строку
		$text = ob_get_clean();  
		
		//перебираем список доступных языков,
		//метод возвращает массив ('ru' => 'Русский') и т.д
		//если язык - тот который по умолчанию то название поля ввода не title_ru, (_ru - суффикс) а просто title
		foreach (Language::getLangsForMLBehavior() as $langCode => $langName) :
		
			if($langCode === Yii::app()->params['defaultLanguage'])
					$suffix = '';
			else
					$suffix = '_'.$langCode;

			$langARInputName = $modelname."[$this->attribute$suffix]";
			// итак что у нас есть
			//  $suffix =   _ru _en ..
			//  $langARInputName =   ModelName['attribute']
			//  $lang =  "Русский" ..

			$value = $this->model->{$this->attribute.$suffix};
			//pa($model);
			
			//заменяем шалоны
			// {{langSuffix}} - суффикс ( _ru ... )
			// {{langName}} - название языка        
			// {{langARInputName}} - название поля ввода для AR    
			$output = str_replace('{{langARInputName}}',$langARInputName, $text);
			$output = str_replace('{{langSuffix}}',$suffix, $output);
			$output = str_replace('{{langName}}',$langName, $output);
			$output = str_replace('{{langValue}}',$value, $output);
			//выводим сформированную строку
			echo $output;
			
		endforeach;

	}
}

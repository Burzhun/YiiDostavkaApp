<?php

class Diva
{
	
	public static function linkMainCsss(){
		
		Yii::app()->clientScript->registerCssFile(
			Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('application.components.assets.diva').DIRECTORY_SEPARATOR.'diva_main.css'
			)
		);
		/*Yii::app()->clientScript->registerScriptFile(
			Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('application.assets').'/publications-front-main.js'
			)
		);*/
		
	}
	public static function linkMainJss(){
		
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->assetManager->publish(
					Yii::getPathOfAlias('application.components.assets.diva').DIRECTORY_SEPARATOR.'diva_main.js'
			)
		);
		
	}
	
	public static function popupJs($url, $name = '_blank', $ww = '60%', $wh = '80%'){
		
		return 'window.diva.nice_popup("'.addslashes($url).'", "'.addslashes($name).'", "'.addslashes($ww).'", "'.addslashes($wh).'");';
		
	}
	public static function popupLink($url, $html, $name = '_blank', $ww = '60%', $wh = '80%'){
		
		$js = self::popupJs($url, $name, $ww, $wh);
		return '<a class="link" href="javascript:void(0)" onclick="'.htmlspecialchars($js).'">'.$html.'</a>';
		
	}
	
	
	
}
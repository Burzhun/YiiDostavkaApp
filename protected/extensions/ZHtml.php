<?php
class ZHtml extends CHtml
{
	public static function enumItem($model,$attribute)
	{
		$attr=$attribute;
		self::resolveName($model,$attr);
		preg_match('/\((.*)\)/',$model->tableSchema->columns[$attr]->dbType,$matches);
		foreach(explode(',', $matches[1]) as $value)
		{
			$value=str_replace("'",null,$value);
			$values[$value]=Yii::t('enumItem',$value);
		}
		
		return $values;
	}
	
	public static function enumDropDownList($model, $attribute, $htmlOptions)
	{
		return CHtml::activeDropDownList( $model, $attribute,ZHtml::enumItem($model, $attribute), $htmlOptions);
	}
	
	public static function imgSave($model,$img_property,$folder, $bw, $bh, $sw=0, $sh=0, $secondImgPartner=false)
	{
		//print_r($img_property);exit();
		$prefiximg = $folder.'_';
		$directoryPathImg = 'upload/'.$folder;
		$extensionName = strtolower($img_property->extensionName);
		if($extensionName == 'jpeg'){
			$extensionName = 'jpg';
		}

		if($secondImgPartner)
		{
			$imageName = 'logo_'.$prefiximg.$model->id.".".$extensionName;
			$smallimageName = 'logo_small'.$imageName;
		}
		else
		{
			$imageName = $prefiximg.$model->id.".".$extensionName;			
			$smallimageName = 'small'.$imageName;
		}

		//$smallimageName = 'small'.$imageName;
		$fullname = Yii::getPathOfAlias('webroot').DS.$directoryPathImg.DS.$imageName;
		$smallname = Yii::getPathOfAlias('webroot').DS.$directoryPathImg.DS.$smallimageName;
		$img_property->saveAs($fullname);
		if(file_exists($fullname)){
			$image = Yii::app()->image->load($fullname);
			if($sw!=0 && $sh!=0)
			{
				$image = ZHtml::resize($image, $sw, $sh);
				$image->save($smallname);
			}
			$image = ZHtml::resize($image, $bw, $bh);
			$image->save($fullname);
		}
		

		if($secondImgPartner)
			$model->logo = $imageName;
		else
			$model->img = $imageName;

		$model->save();
	}
	
	private static function resize($img, $w, $h)
	{
		if($img->width >= $img->height)
		{
			if($img->width > $w)
			{
				$img->resize($w, $h,Image::WIDTH)->quality(100);
			}
		}
		if($img->height > $img->width)
		{
			if($img->height > $h)
			{
				$img->resize($w, $h,Image::HEIGHT)->quality(100);
			}
		}
		return $img;
	}

	public static function rusMonth($time)
	{
		$str = date('m', $time);
		switch($str)
		{
			case '01': $result = 'Января';
					break;
			case '02': $result = 'Февраля';
					break;
			case '03': $result = 'Марта';
					break;
			case '04': $result = 'Апреля';
					break;
			case '05': $result = 'Мая';
					break;
			case '06': $result = 'Июня';
					break;
			case '07': $result = 'Июля';
					break;
			case '08': $result = 'Августа';
					break;
			case '09': $result = 'Сентября';
					break;
			case '10': $result = 'Октября';
					break;
			case '11': $result = 'Ноября';
					break;
			case '12': $result = 'Декабря';
					break;
		}
		return $result;
	}
	
}
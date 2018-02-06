<?php

class Config extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{config}}';
	}

	public function rules()
	{
		return array(
			array('name,domain_id', 'required'),
			array('name', 'length', 'max'=>255),
			array('value', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'name' => 'Название',
			'description' => 'Название',
			'value' => 'Значение',
		);
	}

	public static function getTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:title and domain_id=:domain_id', array(':title'=>'title','domain_id'=>$domain_id))->value;
	}

	public static function getKeywords($domain_id)
	{
		return self::model()->cache(5000)->find('name=:keywords and domain_id=:domain_id', array(':keywords'=>'keywords','domain_id'=>$domain_id))->value;
	}

	public static function getDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:description and domain_id=:domain_id', array(':description'=>'description','domain_id'=>$domain_id))->value;
	}



	public static function getFoodTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:title and domain_id=:domain_id', array(':title'=>'food_title','domain_id'=>$domain_id))->value;
	}

	public static function getFoodKeywords($domain_id)
	{
		return self::model()->cache(5000)->find('name=:keywords and domain_id=:domain_id', array(':keywords'=>'food_keywords','domain_id'=>$domain_id))->value;
	}

	public static function getFoodDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:description and domain_id=:domain_id', array(':description'=>'food_description','domain_id'=>$domain_id))->value;
	}



	public static function getFoodsTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:title and domain_id=:domain_id', array(':title'=>'foods_title','domain_id'=>$domain_id))->value;
	}

	public static function getFoodsKeywords($domain_id)
	{
		return self::model()->cache(5000)->find('name=:keywords and domain_id=:domain_id', array(':keywords'=>'foods_keywords','domain_id'=>$domain_id))->value;
	}

	public static function getFoodsDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:description and domain_id=:domain_id', array(':description'=>'foods_description','domain_id'=>$domain_id))->value;
	}


	/***********ресторанов**************/
	public static function getGoodTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:title and domain_id=:domain_id', array(':title'=>'good_title','domain_id'=>$domain_id))->value;
	}

	public static function getGoodDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:description and domain_id=:domain_id', array(':description'=>'good_description','domain_id'=>$domain_id))->value;
	}



	public static function getGoodsTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:title and domain_id=:domain_id', array(':title'=>'goods_title','domain_id'=>$domain_id))->value;
	}

	public static function getGoodsKeywords($domain_id)
	{
		return self::model()->cache(5000)->find('name=:keywords and domain_id=:domain_id', array(':keywords'=>'goods_keywords','domain_id'=>$domain_id))->value;
	}

	public static function getGoodsDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:description and domain_id=:domain_id', array(':description'=>'goods_description','domain_id'=>$domain_id))->value;
	}


	/*****для партнеров******/
	public static function getSupplierTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:title and domain_id=:domain_id', array(':title'=>'supplier_title','domain_id'=>$domain_id))->value;
	}

	public static function getSupplierDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:description and domain_id=:domain_id', array(':description'=>'supplier_description','domain_id'=>$domain_id))->value;
	}

	/*****для отзывов******/
	public static function getReviewTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:title and domain_id=:domain_id', array(':title'=>'review_title','domain_id'=>$domain_id))->value;
	}

	public static function getReviewDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:description and domain_id=:domain_id', array(':description'=>'review_description','domain_id'=>$domain_id))->value;
	}

	/*****текст для каталога******/
	public static function getProductText($domain_id)
	{
		return self::model()->cache(5000)->find('name=:text and domain_id=:domain_id', array(':text'=>'text_products','domain_id'=>$domain_id))->value;
	}

	/*****статичная******/
	public static function getStaticTitle($domain_id)
	{
		return self::model()->cache(5000)->find('name=:text and domain_id=:domain_id', array(':text'=>'static_title','domain_id'=>$domain_id))->value;
	}
	public static function getStaticDescription($domain_id)
	{
		return self::model()->cache(5000)->find('name=:text and domain_id=:domain_id', array(':text'=>'static_description','domain_id'=>$domain_id))->value;
	}

	public static function getSoc($field, $domain_id){
		return self::model()->cache(5000)->find('name=:text and domain_id=:domain_id', array(':text'=>$field,'domain_id'=>$domain_id))->value;
	}


	public static function getValue($field, $domain_id)
	{
		return self::model()->cache(5000)->find('name=:text and domain_id=:domain_id', array(':text'=>$field,'domain_id'=>$domain_id))->value;
	}


	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('domain_id',$this->domain_id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
		        'pageSize'=>20,
		    ),
		));
	}
}
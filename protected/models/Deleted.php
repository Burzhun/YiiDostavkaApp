<?php

/**
 * Class Deleted
 * @property int id
 * @property string name
 */
/*
Поле type: 1 - Товары(Goods) , 2-партнеры(Partner), 3-меню(Menu)
*/
class Deleted extends CActiveRecord
{

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return '{{deleted}}';
	}


	public function rules()
	{

		return array(
			/*array('name', 'required'),
			array('name', 'length', 'max' => 255),
			array('id, name', 'safe'),*/
		);
	}


	public function relations()
	{
		return array(
			/*'userAddress' => array(self::HAS_MANY, 'UserAddress', 'city_id'),
			'partner' => array(self::HAS_MANY, 'Partner', 'city_id'),*/
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
		);
	}

	/*public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}*/



	protected function beforeSave()
	{
		$this->date_change = time();

		return parent::beforeSave();
	}


}
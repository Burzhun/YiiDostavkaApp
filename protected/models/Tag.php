<?php

/**
 * Class Tag
 * @property int id
 * @property string name
 */
class Tag extends CActiveRecord
{

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return '{{tag}}';
	}


	public function rules()
	{

		return array(
			array('name', 'required'),
			array('name', 'length', 'max' => 255),
			array('id, name', 'safe'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function getTagList()
	{
		return CHtml::ListData(self::model()->findAll(), 'id', 'name');
	}

	/**
	 * Напиток - тег
	 * @return Tag | false
	 */
	public static function getDrink()
	{
		return Tag::model()->find('name = "Напиток"');
	}

}
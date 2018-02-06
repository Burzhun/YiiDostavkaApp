<?php

/**
 * Class Direction
 *
 * @property string $id
 * @property string $name
 *
 * @property Specialization[] $specialization
 */
class Direction extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return '{{direction}}';
	}


	public function rules()
	{
		return array(
			array('name', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('id, name', 'safe'),
		);
	}


	public function relations()
	{
		return array(
			'specialization'=>array(self::HAS_MANY, 'Specialization', 'direction_id'),
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getList()
	{
		return CHtml::ListData(self::model()->findAll(), 'id', 'name');
	}
}
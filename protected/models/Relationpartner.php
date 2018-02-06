<?php

/**
 * Class Relationpartner
 * @property int id
 * @property int owner_id
 * @property int user_id
 *
 * @property User owner
 * @property User user
 */
class Relationpartner extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{relation_partner}}';
	}
	
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, owner_id, user_id', 'safe'),
		);
	}
	
	public function relations()
	{
		return array(
			'owner'=>array(self::BELONGS_TO, 'User', 'owner_id'),
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_id' => 'Главный партнер',
			'user_id' => 'Партнер',
		);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
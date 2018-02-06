<?php

class Runtext extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{runtext}}';
	}
	
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text', 'required'),
			array('id, text', 'safe'),
		);
	}
	
	public function relations()
	{		
		return array(
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Текст',
		);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('text',$this->text);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
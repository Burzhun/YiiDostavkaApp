<?php

/**
 * Class Message
 * @property int id
 * @property string text
 * @property int partner_id
 * @property int read
 * @property string date
 *
 * @property Partner partner
 */
class Message extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return '{{message}}';
	}
	
	
	public function rules()
	{
		return array(
			array('text, partner_id', 'required'),
			array('id, partner_id', 'numerical', 'integerOnly'=>true),
			array('id, partner_id, text, read, date', 'safe'),
		);
	}
	
	
	public function relations()
	{
		return array(
			'partner'=>array(self::BELONGS_TO, 'Partner', 'partner_id'),
		);
	}
	
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Сообщение',
			'partner_id' => 'Партнер',
			'read' => 'Прочитан',
			'date' => 'Дата',
		);
	}
	
	
	public function search($serchCriteria = array())
	{
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id, true);
		$criteria->compare('text',$this->text, true);
		$criteria->compare('partner_id',$this->partner_id, true);
		$criteria->compare('read',$this->read, true);
		$criteria->compare('date',$this->date, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
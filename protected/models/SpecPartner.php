<?php

/**
 * Class SpecPartner
 * @property int id
 * @property int spec_id
 * @property int partner_id
 * @property int date_change
 */
class SpecPartner extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return '{{spec_partner}}';
	}
	
	
	public function rules()
	{
		return array(
			array('spec_id, partner_id', 'required'),
			array('id, spec_id, partner_id', 'numerical', 'integerOnly'=>true),
			array('id, spec_id, partner_id', 'safe'),
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
			'spec_id' => 'Специализация',
			'partner_id' => 'Партнер',
			'date_change' => 'Дата изменения',
		);
	}
	
	
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('spec_id',$this->spec_id,true);
		$criteria->compare('partner_id',$this->partner_id,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeSave(){
		$this->date_change = time();
		return parent::beforeSave();
	}
}
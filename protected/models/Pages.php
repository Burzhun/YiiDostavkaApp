<?php

/**
 * This is the model class for table "{{pages}}".
 *
 * The followings are the available columns in table '{{pages}}':
 * @property integer $id
 * @property string $label
 * @property string $link
 * @property integer $type
 * @property integer $pos
 * @property integer $parent_id
 * @property string $title
 * @property string $keywords
 * @property string $description
 */
class Pages extends CActiveRecord
{
	public static $domains = array('ru'=>'ru', 'az'=>'az');
	/**
	 * Returns the static model of the specified AR class.
	 * @return Pages the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{pages}}';
	}


	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, domain', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('id, name, shorttext, text, uri', 'safe'),
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
			'name' => 'Название',
			'shorttext' => 'Краткий текст',
			'text' => 'Текст',
			'uri' => 'Url адрес',
			'domain' => 'Домен',

		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name);
		$criteria->compare('shorttext',$this->shorttext);
		$criteria->compare('text',$this->text);
		$criteria->compare('uri',$this->uri);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


}
<?php

/**
 * Class CommentInPost
 * @property int id
 * @property string name
 * @property string email
 * @property string text
 * @property string date
 * @property int session_id
 * @property int post_id
 * @property int publ
 *
 * @property Post post
 */
class CommentInPost extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{comment_in_post}}';
	}

	public function rules()
	{
		
		return array(
			array('name, text', 'required'),
			array('name', 'length', 'max'=>30),
			array('id, name, email, text, date, session_id, publ, post_id', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'post'=>array(self::BELONGS_TO, 'Post', 'post_id'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Имя',
			'email' => 'Email',
			'text' => 'Текст',
			'date' => 'Дата',
			'session_id' => 'Id сессии',
			'post_id' => 'Пост',
			'publ' => 'Статус',
		);
	}
	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('publ',$this->publ,true);

		$criteria->order = 'date DESC';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
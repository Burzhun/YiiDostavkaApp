<?php


class PostTag extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return '{{post_tag}}';
	}
	
	
	public function rules()
	{
		
		return array(
			array('post_id, tag_id', 'required'),
			array('id, post_id, tag_id', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'Пост',
			'tag_id' => 'Тег',
		);
	}
}
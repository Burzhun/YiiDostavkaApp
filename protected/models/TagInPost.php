<?php


class TagInPost extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return '{{tag_in_post}}';
	}
	
	
	public function rules()
	{
		
		return array(
			array('name', 'required'),
			array('id, name, tname, pos', 'safe'),
		);
	}
	
	
	public function relations()
	{
		return array(
			'posts'=>array(self::MANY_MANY, 'Post',
                'tbl_post_tag(tag_id, post_id)'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'pos' => 'Позиция',
		);
	}

	public static function listData() {
		$array = array();

		foreach (self::model()->findAll() as $value) {
			$array[] = $value->name;
		}
		return $array;
	}

	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pos',$this->pos,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * Class Bonus
 * @property int id
 * @property string name
 * @property string img
 * @property string text
 * @property string shorttext
 * @property int price
 * @property int pos
 * @property int parent_id
 */
class Bonus extends CActiveRecord
{

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return '{{bonus}}';
	}


	public function rules()
	{

		return array(
			array('name, shorttext', 'required'),
			array('price, parent_id, pos', 'numerical', 'integerOnly' => true),
			array('name, shorttext', 'length', 'max' => 255),
			array('img', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true, 'maxSize' => 2 * 1024 * 1024),
			array('id, name, img, price, shorttext, text, parent_id, pos', 'safe'),
		);
	}


	public function relations()
	{
		return array();
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'img' => 'Картинка',
			'text' => 'Текст',
			'shorttext' => 'Короткий текст',
			'price' => 'Цена',
			'pos' => 'Позиция',
			'parent_id' => 'Родительский каталог',
		);
	}

	public function search($serchCriteria = "")
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('img', $this->img, true);
		$criteria->compare('price', $this->price);
		$criteria->compare('shorttext', $this->shorttext, true);
		$criteria->compare('text', $this->text, true);
		$criteria->compare('parent_id', $this->parent_id);
		$criteria->compare('pos', $this->pos);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getImage()
	{
		if ($this->img) {
			$str = '/upload/bonus/small' . $this->img;
		} else {
			if (!empty($this->partner->img)) {
				$str = '/upload/partner/small' . $this->partner->img;
			} else {
				$str = '/images/default.jpg';
			}
		}

		return $str;
	}
}
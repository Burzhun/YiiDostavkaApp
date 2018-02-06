<?php

/**
 * This is the model class for table "{{user_address}}".
 *
 * The followings are the available columns in table '{{user_address}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $city_id
 * @property string $street
 * @property string $house
 * @property integer $storey
 * @property integer $number
 * @property integer $date_change
 *
 * @property User user
 * @property City city
 */
class UserAddress extends CActiveRecord
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{user_address}}';
	}

	public function rules()
	{
		return array(
			array('user_id, city_id, street, house', 'required'),
			array('user_id, city_id, storey, number', 'numerical', 'integerOnly' => true),
			//array('street, house', 'length', 'max'=>255),
			array('city_id', 'in', 'range' => City::getCityArray()),

			array('id,podezd', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'city_id' => 'Город',
			'street' => 'Улица',
            'podezd' => 'Подъезд',
			'house' => 'Дом',
			'storey' => 'Этаж',
			'number' => 'Номер квартиры/офиса',
			'date_change' => 'Дата изменения',
		);
	}

	public function search($serchCriteria)
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('city_id', $this->city_id);
		$criteria->compare('street', $this->street, true);
		$criteria->compare('house', $this->house, true);
		$criteria->compare('storey', $this->storey);
		$criteria->compare('number', $this->number);
		$criteria->compare('date_change', $this->number);

		if (isset($serchCriteria['user_id'])) {
			$criteria->condition = 'user_id=' . $serchCriteria['user_id'];
		}

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function getUserAddress($userId)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = 'user_id=:id';
		$criteria->params = array(':id' => $userId);

		return self::model()->findAll($criteria);
	}

	protected function beforeSave()
	{
		$this->date_change = time();

		return parent::beforeSave();
	}
}
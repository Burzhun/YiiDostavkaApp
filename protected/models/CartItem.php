<?php

/**
 * This is the model class for table "{{cart}}".
 *
 * The followings are the available columns in table '{{cart}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $partner_id
 * @property integer $goods_id
 * @property double $quality
 * @property integer $price
 * @property string $date
 * @property string $session_id
 *
 * @property User user
 * @property Goods goods
 */
class CartItem extends CActiveRecord
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{cart}}';
	}

	public function rules()
	{
		return array(
			array('user_id, partner_id, goods_id', 'numerical', 'integerOnly' => true),
			//array('quality', 'numerical'),
			array('session_id, price', 'length', 'max' => 255),

			//array('date', 'date', 'format'=>'yyyy-MM-dd'),
			array('id, user_id, partner_id, goods_id, quality, price, date, session_id', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
			'partner' => array(self::BELONGS_TO, 'Partner', 'partner_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'partner_id' => 'Партнер',
			'goods_id' => 'Товар',
			'quality' => 'Количество',
			'price' => 'Цена',
			'date' => 'Дата',
			'session_id' => 'Сессия',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('partner_id', $this->partner_id);
		$criteria->compare('goods_id', $this->goods_id);
		$criteria->compare('quality', $this->quality);
		$criteria->compare('price', $this->price);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('session_id', $this->session_id, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function sum_cart_item()
	{
		$cart = CartItem::model()->findAll(array("condition" => "user_id=" . Yii::app()->user->id));

		$sum = 0;
		foreach ($cart as $c) {
			$sum += $c->quality * $c->price;
		}

		return $sum;
	}

	public static function countCartItem()
	{
		$cart = CartItem::model()->findAll(array("condition" => "1=1 " . CartItem::getConditionForSelectCartItems()));

		$count = 0;
		foreach ($cart as $c) {
			$count += $c->quality;
		}

		return $count;
	}

	public static function getConditionForSelectCartItems()
	{
		if (Yii::app()->user->role == User::USER) {
			$result = " AND user_id='" . Yii::app()->user->id . "'";
		} else {
			$result = " AND session_id='" . Yii::app()->session->sessionId . "'";
		}

		return $result;
	}

	public static function hasDrinks()
	{
		$tagId = Tag::getDrink() ? Tag::getDrink()->id : '';

		$cart = CartItem::model()->findAll(array("condition" => "1=1" . CartItem::getConditionForSelectCartItems()));
		foreach ($cart as $data) {
			if($data->goods->tag_id == $tagId){
				return false;
			}
		}

		return true;
	}
}
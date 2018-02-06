<?php

/**
 * Class OrderItem
 * @property int id
 * @property int order_id
 * @property int goods_id
 * @property int quantity
 * @property int total_price
 * @property int price_for_one
 * @property int date_change
 *
 * @property Order order
 * @property Goods goods
 */
class OrderItem extends CActiveRecord
{
	public $orderItemCount;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{order_items}}';
	}


	public function rules()
	{
		return array(
			array('order_id, goods_id, quantity, total_price, price_for_one', 'required'),
			array('order_id, goods_id', 'numerical', 'integerOnly' => true),
			array('quantity', 'numerical'),
			array('id, order_id, goods_id, quantity, total_price, price_for_one', 'safe'),
		);
	}


	public function relations()
	{
		return array(
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Заказ',
			'goods_id' => 'Товар',
			'quantity' => 'Количество',
			'total_price' => 'Общая цена',
			'price_for_one' => 'Цена за единицу',
			'date_change' => 'Дата изменения',
		);
	}


	public function search($serchCriteria)
	{

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('order_id', $this->order_id);
		$criteria->compare('goods_id', $this->goods_id);
		$criteria->compare('quantity', $this->quantity);
		$criteria->compare('total_price', $this->total_price);
		$criteria->compare('price_for_one', $this->price_for_one);

		if (isset($serchCriteria['order_id'])) {
			$criteria->condition = 'order_id=' . $serchCriteria['order_id'];
		}

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>2000,
            ),
		));
	}

	protected function beforeSave()
	{
		$this->date_change = time();

		return parent::beforeSave();
	}
}
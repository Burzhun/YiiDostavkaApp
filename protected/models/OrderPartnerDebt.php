<?php

/**
 * Class OrderPartnerDebt
 * @property int id
 * @property int partner_id
 * @property int user_id
 * @property string user_name
 * @property string date
 * @property string address
 * @property int sum
 * @property string phone
 * @property string info
 * @property int paid
 *
 * @property Partner partner
 * @property User user
 */
class OrderPartnerDebt extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return '{{order_partner_debt}}';
	}
	
	
	public function rules()
	{
		return array(
			array('phone', 'required'),
			//array('date', 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'),
			
			array('id, partner_id, user_name, user_id, date, sum, address, info, paid', 'safe'),
		);
	}
	
	
	public function relations()
	{
		return array(
			'partner'=>array(self::BELONGS_TO, 'Partner', 'partner_id'),
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}
	
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'partner_id' => 'Партнер',
			'user_id' => 'ID пользователя',
			'user_name' => 'Имя пользователя',
			'date' => 'Дата',
			'address' => 'Адрес',
			'sum' => 'Сумма заказа',
			'phone' => 'Телефон',
			'info' => 'Инфо заказа',
			'paid' => 'Оплачено',
		);
	}
	
	
	//для админа, вывод всех заказов
	public function search($serchCriteria = array())
	{
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('user_name',$this->user_name);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('partner_id',$this->partner_id, true);
		$criteria->compare('date',$this->date, true);
		$criteria->compare('address',$this->address, true);
		$criteria->compare('sum',$this->sum, true);
		$criteria->compare('phone',$this->phone, true);
		$criteria->compare('info',$this->info, true);
		$criteria->compare('paid',$this->paid, true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrderPartnerDebt'=>'date DESC',
				'attributes'=>array(
					'*',
				)
			),
			'pagination' => array(  
	            'pageSize' => 400,  
	        ),
		));
	}

	/**
	* В функции выбираем и возвращаем заказы которые не были еще оплачены(paid=0).
	* Значением каждого дня будет сумма всех заказов в этот день.
	*/
	public static function getDataForGraphic($partner_id)
	{
		$debt = OrderPartnerDebt::model()->findAll(array('condition'=>'partner_id='.$partner_id.' AND paid=0', 'order'=>'date'));
		$arr = array();



		$date_now = '';
		foreach ($debt as $d) {
			$date = $d->date;
			$arr_keys = array_keys($arr);
			if(!in_array($date, $arr_keys)){
				$arr[$date] = 0;
				$arr[$date] += $d->sum;
			}else
			{
				$arr[$date] += $d->sum;
			}
		}
		return $arr;
	}

	public static function getDebt($partner_id)
	{
		$debt = OrderPartnerDebt::model()->findAll(array('condition'=>'partner_id='.$partner_id.' AND paid=0', 'order'=>'date'));

		$sum = 0;

		foreach ($debt as $d) {
			$sum += $d->sum;
		}
		return floor($sum*(Partner::model()->findByPk($partner_id)->procent_deductions/100));
	}
}
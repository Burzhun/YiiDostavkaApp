<?php

/**
 * Class UserBonus
 * @property int $id
 * @property int $user_id
 * @property int $sum
 * @property int $sum_in_start
 * @property int $date
 * @property int $info
 */
class UserBonus extends CActiveRecord
{

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return '{{user_bonus}}';
	}


	public function rules()
	{

		return array(
			array('user_id, sum, sum_in_start, date', 'required'),
			array('user_id,order_id, date', 'numerical', 'integerOnly' => true),
			array('info', 'safe'),
		);
	}


	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'sum_in_start' => 'Сумма в начале',
			'sum' => 'Сумма',
			'date' => 'Дата',
			'info' => 'Информация',
		);
	}


	public function search($serchCriteria = "")
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('sum_in_start', $this->sum_in_start);
		$criteria->compare('sum', $this->sum);
		$criteria->compare('date', $this->date);
		$criteria->compare('info', $this->info);
        if(isset($_GET['date_from'])){
            $date=$_GET['date_from'];
            //$date=preg_replace("/[^0-9.]+/","",$date);
            $criteria->addCondition("date>unix_timestamp('{$date} 00:00:00')",'and');
        }
        if(isset($_GET['date_to'])){
            $date=$_GET['date_to'];
            //$date=preg_replace("/[^0-9.]+/","",$date);
            $criteria->addCondition("date<unix_timestamp('{$date} 23:59:59')",'and');
        }
        $criteria->order="date desc";
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 30,
            ),
		));
	}

    static function getBonusForOrder($order){
        $user_bonus=new UserBonus();
        $user_bonus->user_id=$order->user_id;
		$user_bonus->date=time();
        $totalPrice =$order->sum;
		$user_bonus->order_id = $order->id;
        $user_bonus->sum=floor($totalPrice*$order->partner->bonus_procent/100);
        $user_bonus->sum_in_start=$user_bonus->sum;
        $user_bonus->info="Сделал заказ в ".$order->partner->name." на сумму ".$totalPrice." и получил ".$user_bonus->sum. " баллов";
		if (!UserBonus::model()->find("order_id=:order_id", array(':order_id' => $order->id))) {
			$user_bonus->save();
		}
    }

    static function getBonusForOldOrder($order){
        $user_bonus=new UserBonus();
        $user_bonus->user_id=$order->user_id;
		$user_bonus->date=strtotime($order->date);
        $totalPrice =$order->sum;
		$user_bonus->order_id = $order->id;
        $user_bonus->sum=floor($totalPrice*$order->partner->bonus_procent/100);
        $user_bonus->sum_in_start=$user_bonus->sum;
        $user_bonus->info="Сделал заказ в ".$order->partner->name." на сумму ".$totalPrice." и получил ".$user_bonus->sum. " баллов";
		if (!UserBonus::model()->find("order_id=:order_id", array(':order_id' => $order->id))) {
			$user_bonus->save();
		}
    }


	static function takeBonusByAdmin($user_id,$number){
		$number= User::getBonus($user_id)<$number ? User::getBonus($user_id) : $number;
		$user_bonus=new UserBonus();
		$user_bonus->user_id=$user_id;
		$user_bonus->date=time();
		$user_bonus->sum=-$number;
		$user_bonus->sum_in_start=$user_bonus->sum;
		$user_bonus->info="Администратор списал {$number} баллов";
		$user_bonus->save();
	}

    static function TakeBonus($user_id,$totalPrice){
        $user_bonuses=UserBonus::model()->findAll("user_id={$user_id} order by date");
        $rest=0;
        foreach($user_bonuses as $user_bonus){
            if(($user_bonus->sum+$rest)>=$totalPrice*4){
                $user_bonus->sum-=$totalPrice*4-$rest;
                $user_bonus->save();
                break;
            }
            else{
                $rest+=$user_bonus->sum;
                $user_bonus->sum=0;
                $user_bonus->save();
            }
        }

    }
}
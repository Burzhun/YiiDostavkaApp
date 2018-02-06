<?php

/**
 * Class Action
 * @property int id
 * @property string date
 * @property string action
 * @property int user_id
 * @property int partner_id
 * @property string info
 * @property string $order_id
 *
 * @property User user
 * @property Partner partner
 */
class Action extends CActiveRecord
{

	const GOODS = 'Товар';
	const MENU = 'Меню';
	const ORDER = 'Заказ';
	const REGISTRATION = 'Регистрация';
	const REVIEW = 'Отзыв';
	const OTHER = 'Разное';

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{actions}}';
	}

	public function rules()
	{
		return array(
			array('date, action, user_id, partner_id, info', 'required'),
			array('date', 'date', 'format' => 'yyyy-MM-dd hh:mm:ss'),
			array('user_id, partner_id', 'numerical', 'integerOnly' => true),
			array('action', 'length', 'max' => 22),
			array('id, date, action, user_id, partner_id, info, order_id', 'safe'),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'partner' => array(self::BELONGS_TO, 'Partner', 'partner_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Дата',
			'action' => 'Действие',
			'user_id' => 'Пользователь',
			'partner_id' => 'Партнер',
			'info' => 'Инфо',
			'order_id' => 'Номер заказа',
		);
	}

	public function search($serchCriteria = array('order' => 'date DESC'), $domain = false)
	{
		$criteria = new CDbCriteria;

		if($domain){
			$partnerIds = Partner::getDomainPartnersIds($domain->id);
			$criteria->addInCondition('partner_id', $partnerIds);
		}

		$criteria->compare('id', $this->id);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('action', $this->action, true);
		$criteria->compare('user_id', $this->user_id, true);
		$criteria->compare('partner_id', $this->partner_id, true);
		$criteria->compare('info', $this->info, true);
		$criteria->compare('order_id', $this->order_id, true);

		$criteria->order = 'date DESC';

		//$criteria->limit = 40;

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 40,
			)
		));
	}

	public function user_link(){
		$user=User::model()->findByPk($this->user_id);
		if($user){			
			return CHtml::link($user->name, array("/admin/user/id/".$user->id."/profile/"));
		}
		return "";
	}

	public static function addNewAction($p_action, $p_user, $p_partner, $p_info, $p_order = 0)
	{
		$model_action = new Action();
		$model_action->date = date("Y-m-d H:i:s");
		$model_action->user_id = $p_user;
		$model_action->partner_id = $p_partner;
		$model_action->info = $p_info;
		$model_action->action = $p_action;
		$model_action->order_id = $p_order;
		$model_action->save();
	}
	
	public function sum(){
		if($this->balance_before<$this->balance_after){
			return '+'.$this->sum;
		}
		else{
			return '-'.$this->sum;
		}
	}

	/**
	 * @return int
	 */
	public static function getLastId()
	{
		$sql = "SELECT id FROM tbl_actions ORDER BY id DESC LIMIT 1";
		$command = Yii::app()->db->createCommand($sql);
		return $command->queryScalar();
	}
}
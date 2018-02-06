<?php

/**
 * This is the model class for table "{{review}}".
 *
 * The followings are the available columns in table '{{review}}':
 * @property integer $id
 * @property integer $review
 * @property integer $partner_id
 * @property integer $visible
 * @property string $content
 * @property integer $user_id
 * @property integer $created
 * @property integer $status_review
 */
class Review extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Review the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{review}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, review, partner_id, visible, content, user_id, created', 'required', 'on'=>'admin'),
			//array('id, review, partner_id, visible, user_id, created', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, review, partner_id, visible, content, user_id, created', 'safe', 'on'=>'search'),
			array('id, review, partner_id, visible, content, user_id, created', 'safe', 'on'=>'partner'),
			array('content', 'required', 'message'=>'Напишите текст отзыва', 'on'=>'user'),
			array('review', 'required', 'message'=>'Поставьте оценку', 'on'=>'user'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'partner'=>array(self::BELONGS_TO, 'Partner', 'partner_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'Пользователь',
			'partner_id' => 'Магазин',
			'review' => 'Оценка',
			'content' => 'Отзыв',
			'visible' => 'Опубликован',
			'created' => 'Дата',
            'approved' => 'Одобрен',
		);
	}

	public function dateFormat(){
		/*$date = $this->created;
		$today = 60*60*24;// 24 часа
		$yesterday = 60*60*24*2; //48 часов(двое суток)
		//echo (time());
		if((time()-$date)<=$today)
			return "Сегодня в ".date('H:i',$date);
		elseif((time()-$date)>$today && (time()-$date)<=$yesterday)
			return "Вчера в ".date('H:i',$date);
		else
			return Yii::app()->dateFormatter->format('d MMMM yyyy', $date);*/
		$date = date('dmY',$this->created);
		if($date == date('dmY'))
			return "Сегодня в ".date('H:i',$this->created);
		elseif($date == date('dmY',strtotime('-1 day')))
			return "Вчера в ".date('H:i',$this->created);
		else
			return Yii::app()->dateFormatter->format('d MMMM yyyy', $this->created);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($_partner_id = 0)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('review',$this->review);
		$criteria->compare('partner_id',$this->partner_id);
		$criteria->compare('visible',$this->visible);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('created',$this->created);

		if($this->scenario="partner" && $_partner_id){
			$criteria->compare('partner_id',$_partner_id);
		}

		$criteria->order = "created desc,visible desc";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
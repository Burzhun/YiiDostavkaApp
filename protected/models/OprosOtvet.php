<?php

/**
 * This is the model class for table "{{opros_otvet}}".
 *
 * The followings are the available columns in table '{{opros_otvet}}':
 * @property integer $id
 * @property integer $parent_id
 * @property string $answer
 * @property integer $pos
 * @property integer $sum
 */
class OprosOtvet extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OprosOtvet the static model class
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
		return '{{opros_otvet}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, answer', 'required'),
			array('parent_id, pos, sum', 'numerical', 'integerOnly'=>true),
			array('answer', 'length', 'max'=>255),
			
			array('id, parent_id, answer, pos, sum', 'safe', 'on'=>'search'),
			array('parent_id, answer, pos, sum', 'safe', 'on'=>'create, update'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'opros'=>array(self::BELONGS_TO, 'Opros', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Опрос',
			'answer' => 'Ответ',
			'pos' => 'Позиция',
			'sum' => 'Голоса',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('sum',$this->sum);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
<?php

/**
 * Class UserBonus
 * @property int $id
 * @property int $user_id
 * @property int $promo_id
 * @property int $used
 * @property int activated
*/
class UserPromo extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{user_promo}}';
    }


    public function rules()
    {

        return array(
            array('user_id,promo_id,used,activated', 'required'),
            array('user_id,promo_id,used,activated', 'numerical', 'integerOnly' => true),
        );
    }


    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'promo' =>array(self::BELONGS_TO, 'Promo','promo_id')
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'Пользователь',
        );
    }


    public function search($serchCriteria = "")
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('promo_id', $this->promo_id);
        $criteria->compare('used', $this->used);
        $criteria->compare('activated', $this->activated);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
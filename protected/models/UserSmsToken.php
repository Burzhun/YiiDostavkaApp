<?php

/**
 * Class UserToken
 * @property int id
 * @property int user_id
 * @property int date
 * @property string sms_token
 */
class UserSmsToken extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{user_sms_token}}';
    }


    public function rules()
    {

        return array();
    }


    public function relations()
    {
        return array(
          //  'user'=>array(self::BELONGS_TO,'User','user_id')
        );
    }


    public function attributeLabels()
    {
        return array();
    }

    public function search()
    {
        /*$criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));*/
    }

}
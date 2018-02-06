<?php

/**
 * Class UserToken
 * @property int id
 * @property int user_id
 * @property string token
 */
class UserToken extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{user_token}}';
    }


    public function rules()
    {

        return array();
    }


    public function relations()
    {
        return array();
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
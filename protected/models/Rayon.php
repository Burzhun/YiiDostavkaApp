<?php

/**
 * This is the model class for table "{{rayon}}".
 *
 * The followings are the available columns in table '{{rayon}}':
 * @property integer $id
 * @property string $name
 */
class Rayon extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{rayon}}';
    }

    public function rules(){
        return array(
            array('name,city_id','required'),
            array('name','length','max'=>30),
        );
    }
    public function relations(){
        return array(
            'city' => array(self::BELONGS_TO, 'City', 'city_id'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'name' => 'Имя',
        );
    }
    public function getCityName(){
        return $this->city->name;
    }
}
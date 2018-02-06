<?php
class Redirect extends CActiveRecord{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{redirects}}';
    }

    public function rules(){
        return array(
            array('old_url,new_url','required'),
            array('old_url,new_url','length','max'=>255),
        );
    }

    public function attributeLabels()
    {
        return array(
            'old_url' => 'Старый url',
            'new_url' => 'Новый url'
        );
    }
}
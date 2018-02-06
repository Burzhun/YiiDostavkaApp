<?php
class PartnerInfo extends CActiveRecord{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{partners_info}}';
    }

    public function rules(){
        return array(
            array('partner_id,name,phone','required'),
            array('name,occupation,phone','length','max'=>40),
        );
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Имя',
            'occupation' => 'Должность'
        );
    }
}
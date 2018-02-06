<?php


class PartnerRayon extends CActiveRecord
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
        return '{{partner_rayon}}';
    }

    public function rules(){
        return array(
            array('partner_id,rayon_id,min_sum','required'),
            array('partner_id,rayon_id,min_sum', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations(){
        return array(
            'partner' => array(self::BELONGS_TO,'Partner','partner_id'),
            'rayon' => array(self::BELONGS_TO,'Rayon','rayon_id'),

        );
    }

    public function attributeLabels(){
        return array(
            'min_sum' => 'Минимальная сумма доставки'
        );
    }
}
<?php

/**
 * Class UserBonus
 * @property int $id
 * @property int $name
 * @property int $kod
 * @property int $count
 * @property int $from
 * @property int $untill
 */
class Promo extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{promo}}';
    }


    public function rules()
    {

        return array(
            array('name,kod,count,from,until', 'required'),
            array('count', 'numerical', 'integerOnly' => true),
            array('name,kod,from,until', 'safe'),
        );
    }


    public function relations()
    {
        return array(
            'user_promo' =>array(self::HAS_ONE, 'UserPromo','promo_id'),
            'partners' => array(self::MANY_MANY, 'Partner',
                'tbl_promo_partner(promo_id,partner_id)'),
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Название',
            'kod' => 'Код',
            'count' => 'Количество баллов',
            'from' => 'От',
            'until' => 'До',
            'partners' => 'Партнеры'
        );
    }


    public function search($serchCriteria = "")
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('kod', $this->kod);
        $criteria->compare('count', $this->count);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    public function afterFind(){
        $this->from=date('Y-m-d',$this->from);
        $this->until=date('Y-m-d', $this->until);
        return parent::afterFind();
    }
    public function beforeValidate(){
        $this->from=strtotime($this->from);
        $this->until=strtotime($this->until);
        return parent::beforeValidate();
    }
    public function GetPartnerName(){
        if(!$this->partners){
            return "Все партнеры";
        }else{
            $s="";
            foreach($this->partners as $partner){
                $s.=$partner->name.', ';
            }
            return $s;
        }

    }
    public function usedCount(){
        $sql="select count(id) as count from tbl_user_promo where used=1 and activated=1 and promo_id=".$this->id;
        $res=Yii::app()->db->createCommand($sql)->queryAll();
        return $res[0]['count'];
    }
}
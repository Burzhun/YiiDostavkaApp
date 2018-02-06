<?php

/**
 * Class Bonus
 * @property int id
 * @property string name
 * @property string img
 * @property string text
 * @property string shorttext
 * @property int price
 * @property int pos
 * @property int parent_id
 */
class CancelReason extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{order_reason_canceled}}';
    }

    public function rules()
    {
        return array(
            array('name', 'required'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'name'=>'Название'
        );
    }

    public function search($serchCriteria = "")
    {
        $criteria = new CDbCriteria;

        $criteria->compare('name', $this->name);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function DeleteReason($id){
        $cp=CancelReason::model()->findByPk($id);
        $text=$cp->name;
        Yii::app()->db->createCommand("update tbl_order_reason_canceled set cancel_reason=100,cancel_reason_text='{$text}' where id={$id}")->query();
    }
}
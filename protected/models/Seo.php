<?php


class Seo extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{seo}}';
    }


    public function rules()
    {

        return array(
            array('name,url,city_id', 'required'),
            array('city_id', 'numerical', 'integerOnly' => true),
            array('url, name', 'length', 'max' => 255),
            array('value', 'safe'),
        );
    }


    public function relations()
    {
        return array();
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Название',
            'value' => 'Значение'
        );
    }

    public function search($serchCriteria = "")
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('city_id', $this->city_id, true);
        $criteria->order='url';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
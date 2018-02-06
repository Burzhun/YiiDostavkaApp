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
class Banner extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{banners}}';
    }


    public function rules()
    {

        return array(
            array('url,date_begin,date_stop,partner_id', 'required'),
            array('text', 'length', 'max' => 105),
            array('image', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true, 'maxSize' => 2 * 1024 * 1024),

            array('id, text,date_begin,date_stop, city_id', 'safe'),
        );
    }


    public function relations()
    {
        return array();
    }


    public function attributeLabels()
    {
        return array(
            'id'         => 'ID',
            'image'      => 'Картинка',
            'text'       => 'Текст',
            'url'        => 'Ссылка',
            'date_begin' => 'Начало работы',
            'date_stop'  => 'Конец работы',
            'city_id'    => 'Город',
        );
    }

    public function search($serchCriteria = "")
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('date_begin', $this->date_begin, true);
        $criteria->compare('date_stop', $this->date_stop, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /*public function getImage()
    {
        if ($this->img) {
            $str = '/upload/bonus/small' . $this->img;
        } else {
            if (!empty($this->partner->img)) {
                $str = '/upload/partner/small' . $this->partner->img;
            } else {
                $str = '/images/default.jpg';
            }
        }

        return $str;
    }*/
}
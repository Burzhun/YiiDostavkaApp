<?php

/**
 * Class Specialization
 * @property int id
 * @property string name
 * @property string tname
 * @property int direction_id
 * @property int pos
 * @property string title
 * @property string keywords
 * @property string description
 * @property int date_change
 *
 * @property Direction direction
 * @property Partner[] partners
 */
class Specialization extends CActiveRecord
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{specialization}}';
	}

	public function rules()
	{
		return array(
			array('name, direction_id', 'required'),
			array('id, direction_id,pos,city_id', 'numerical', 'integerOnly' => true),
			array('name, tname, title, keywords', 'length', 'max' => 255),
			array('description', 'length', 'max' => 500),
            array('image,image_min', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true, 'maxSize' => 2 * 1024 * 1024),
			array('id, name, direction_id, tname,text,h1, title, keywords, description', 'safe'),
		);
	}


	public function relations()
	{
		return array(
			'direction' => array(self::BELONGS_TO, 'Direction', 'direction_id'),
			'partners' => array(self::MANY_MANY, 'Partner',
				'tbl_spec_partner(spec_id, partner_id)'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'           => 'ID',
			'name'         => 'Название',
			'tname'        => 'Транскрипция',
			'city_id'      => 'Город',
			'direction_id' => 'Направление',
			'pos'          => 'Позиция',
			'title'        => 'Title',
			'keywords'     => 'keywords',
			'description'  => 'Description',
			'date_change'  => 'Дата изменения',
			'image'        => 'Изображение для мобильной версии',
			'image_min'    => 'Изображение для приложения',
			'text'         => 'Текст на странице'
		);
	}

	public function beforeSave()
	{
		$this->date_change = time();

		return parent::beforeSave();
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('tname', $this->tname, true);
		$criteria->compare('direction_id', $this->direction_id, true);
		$criteria->compare('pos', $this->pos);
		$criteria->compare('title', $this->title);
		$criteria->compare('city_id', $this->city_id);
		$criteria->compare('keywords', $this->keywords);
		$criteria->compare('description', $this->description);
        $criteria->order="pos";
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
            'pagination'=>array(
                'pagesize'=>30
            ),
		));
	}

    public function getMobileImage(){
        $s="/upload/specialization/".$this->city_id.'/'.$this->image;
        if(file_exists(getcwd().$s)){
            return $s;
        }else{
            return "";
        }
    }
    public function getAppImage(){
        $s= "/upload/specialization/".$this->city_id.'/'.$this->image_min;
        if(file_exists(getcwd().$s)){
            return $s;
        }else{
            return "";
        }
    }
}
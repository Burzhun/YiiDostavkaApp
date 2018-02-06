<?php

/**
 * This is the model class for table "{{domain}}".
 *
 * The followings are the available columns in table '{{domain}}':
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $logo
 * @property string $footer_logo
 */
class Domain extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Domain the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{domain}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, alias, logo, footer_logo', 'required'),
			array('name, logo, footer_logo', 'length', 'max'=>255),
			array('alias,postfix', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, alias, logo, footer_logo', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Имя',
			'alias' => 'Url',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('footer_logo',$this->footer_logo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
		        'pageSize'=>20,
		    ),
		));
	}

	public static function ListData()
	{
		return CHtml::listData(self::model()->findAll(), 'id', 'name');
	}

	public static function getDomain($domainName)
	{
		return Yii::app()->session['domain_id'];
		if (isset($_SERVER['HTTP_X_HTTPS'])) {
	        return self::model()->findByPk(3);
	    }
		if($_SERVER['HTTP_HOST']=='derbent.dostavka05.ru'){
            return self::model()->findByPk(3);
        }
        if($_SERVER['HTTP_HOST']=='kaspiysk.dostavka05.ru'){
            return self::model()->findByPk(3);
        }
		if($_SERVER['HTTP_HOST']=='vladikavkaz.edostav.ru'){
			return self::model()->findByPk(2);
		}
        if($_SERVER['HTTP_HOST']=='dost05.tmweb.ru'){
        	return self::model()->findByPk(3);
        }
        if($_SERVER['HTTP_HOST']=='www.cherry05.ru'){
        	return self::model()->findByPk(3);
        }
		if($_SERVER['HTTP_HOST']=='dostavka05.local'){
			return self::model()->findByPk(3);
		}
		return self::model()->cache(5000)->find('alias=:alias', array(':alias'=>$domainName));
        //return self::model()->findByPk(1);
	}

	public function getText($name, $language)
	{
		return Text::model()->cache(5000)->find('name=:name AND domain_id=:id', array(':name'=>$name, ':id'=>$this->id))->{'text_'.$language};
	}
}
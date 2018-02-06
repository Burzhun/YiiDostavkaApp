<?php

/**
 * Class City
 * @property int id
 * @property string name
 */
class City extends CActiveRecord
{

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return '{{city}}';
	}


	public function rules()
	{

		return array(
			array('name', 'required'),
			array('name, region,alias', 'length', 'max' => 255),
			array('id, name, domain_id,time_zone', 'safe'),
		);
	}


	public function relations()
	{
		return array(
			'userAddress' => array(self::HAS_MANY, 'UserAddress', 'city_id'),
			'partner' => array(self::HAS_MANY, 'Partner', 'city_id'),
            'domain' => array(self::BELONGS_TO, 'Domain','domain_id'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'alias' => 'Алиас'
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('alias', $this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function getCityArray($domain_id=null)
	{
		if($domain_id==null){
			$domain_id=Yii::app()->session['domain_id']->id;
		}
        $criteria = new CDbCriteria();
        if($domain_id)
        {
            $criteria->addCondition('domain_id = "'.$domain_id.'"');
        }
		$allmodel = City::model()->cache(5000)->findAll($criteria);
		$arr = array();
		foreach ($allmodel as $a) {
			$arr[] = '' . $a->id;
		}

		return $arr;
	}



	protected function beforeSave()
	{
		$this->date_change = time();

		return parent::beforeSave();
	}

    public static function getCityList($domainId = null)
    {
        $criteria = new CDbCriteria();
        if($domainId)
        {
            $criteria->addCondition('domain_id = :id');
            $criteria->params = array(':id'=>$domainId);
        }
    	return static::model()->findAll($criteria);
        // return City::model()->findAll(array('condition' => 'site = "'.Yii::app()->request->serverName.'"'));
    }


    public static function getCityByIp(){
        $domain_id = Domain::getDomain(Yii::app()->request->serverName)->id;
        if($domain_id == 4){
            $domain_id = 3;
        }
        if(isset(Yii::app()->request->cookies['id_city_from_ip'])){
            $city = City::model()->findByPk(Yii::app()->request->cookies['id_city_from_ip']->value);
            return $city;
        }
        else{
            $city = City::model()->cache(5000)->findAll('domain_id='.$domain_id);
            //print_r($city);

            return $city[0];
        }

    }

    public static function getMoneyKod()
    {
        $domain=Domain::getDomain(Yii::app()->request->serverName);
    	if($domain->alias == 'www.dostavka.az'){
			return 'ман.';
		}else{
			return 'руб.';
		}
    }

    public static function getDomainCityIds($domainID){
		$ids = Yii::app()->db->createCommand()
				->select('id')
				->from('tbl_city')
				->where('domain_id=:did', array(':did'=>$domainID))
				->queryColumn();

		return $ids;
	}

	public static function getUserChooseCity()
	{
		return Yii::app()->request->cookies['choose_city']->value;
	}
	
	public static function setUserChooseCity($value)
	{
		Yii::app()->request->cookies['choose_city'] = new CHttpCookie('choose_city', $value);
	}
}
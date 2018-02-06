<?php

/**
 * Class Menu
 * @property self this
 * @property int id
 * @property string name
 * @property int have_subcatalog
 * @property int parent_id
 * @property int pos
 * @property int partner_id
 * @property int publication
 * @property int date_change
 *
 * @property Partner partner
 * @property Menu[] submenu
 * @property Goods[] goods
 */
class Menu extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return '{{menu}}';
	}
	
	
	public function rules()
	{
		return array(
			array('name, have_subcatalog, parent_id, pos, partner_id', 'required'),
			array('have_subcatalog, parent_id, pos, partner_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('id, name, have_subcatalog, parent_id, pos, partner_id', 'safe'),
		);
	}
	
	
	public function relations()
	{
		return array(
			'partner'=>array(self::BELONGS_TO, 'Partner', 'partner_id'),
			'submenu'=>array(self::HAS_MANY, 'Menu', 'parent_id', 'condition'=>'publication = 1', 'order'=>'pos'),
			'goods'=>array(self::HAS_MANY, 'Goods', 'parent_id', 'condition'=>'publication = 1', 'order'=>'pos'),
		);
	}
	
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'have_subcatalog' => 'Имеет подкаталог',
			'parent_id' => 'Родительский каталог',
			'pos' => 'Позиция',
			'partner_id' => 'Партнер',
			'publication' => 'Публикация',
			'date_change' => 'Дата изменения',
		);
	}
	
	
	protected function afterSave()
	{
		Yii::app()->cache->flush();
		/*    действие   */
    	if($this->isNewRecord)
        {
        	$action = "добавил пункт меню ".$this->name." - #".$this->id;
        }else {
        	$action = "отредактировал пункт меню ".$this->name." - #".$this->id;
        }
        
        /**********************/
        
        /*    актер      */
        if(Yii::app()->user->isGuest)
        {
        	$acter = "гость"; // @TODO не используется, можно удалить?
			Action::addNewAction(Action::MENU, 0, $this->partner ? $this->partner->id : 0, 'Гость (Session '.Yii::app()->getSession()->sessionID.') '.$action);
        }else{
        	switch(Yii::app()->user->role)
        	{
        		case User::PARTNER: Action::addNewAction(Action::MENU, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Партнер (ID '.$this->partner->id.') '.$action);
        							break;
        		case User::ADMIN: 	Action::addNewAction(Action::MENU, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Администратор '.$action);
        							break;
        	}
        }
	}
	
	
	protected function beforeDelete()
	{

		$this->saveDeleted();
		
		$action = "удалил пункт меню ".$this->name." - #".$this->id;
		
		if(parent::beforeSave())
    	{
    		switch(Yii::app()->user->role)
        	{
        		case User::PARTNER: Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Партнер (ID '.$this->partner->id.') '.$action);
        							break;
        		case User::ADMIN: 	$user = User::model()->findByPk(Yii::app()->user->id);
        							Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Администратор '.$action);
        							break;
        	}
    		return true;
    	}else {
    		return false;
    	}

    	
	}

	protected function saveDeleted(){
		$d = new Deleted;
		$d->item_id = $this->id;
		$d->type = 3;
		$d->date_change = time();
		$d->save(false);
	}

	
	protected function beforeSave(){
		$this->date_change = time();
		return parent::beforeSave();
	}
	
	public function search($serchCriteria = "")
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('have_subcatalog',$this->have_subcatalog);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('partner_id',$this->partner_id);
		
		$criteria->order = 'pos, id DESC';
		
		//$criteria->condition = "1=1 ";
		if(isset($serchCriteria['partner_id']))
		{
			//$criteria->condition .= ' AND partner_id='.$serchCriteria['partner_id'];
			$criteria->addCondition("partner_id='".$serchCriteria['partner_id']."'");
		}
		
		if(isset($serchCriteria['parent_id']))
		{
			//$criteria->condition .= ' AND parent_id='.$serchCriteria['parent_id'];
			$criteria->addCondition("parent_id='".$serchCriteria['parent_id']."'");
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
				'attributes'=>array(
					'*',
				)
			),
			'pagination' => array(  
	            'pageSize' => 100,
	        ),
		));
	}
	
}
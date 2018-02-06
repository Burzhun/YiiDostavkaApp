<?php

/**
 * Class Goods
 * @property self this
 * @property int id
 * @property string name
 * @property string img
 * @property int price
 * @property string unit
 * @property string shorttext
 * @property string text
 * @property int parent_id
 * @property int partner_id
 * @property int pos
 * @property string related_products
 * @property int favorite
 * @property int publication
 * @property int date_change
 *
 * @property Partner partner
 * @property Menu parent_menu
 */
class Goods extends CActiveRecord
{
	public $order_count;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return '{{goods}}';
	}


	public function rules()
	{

		return array(
			array('name, parent_id, partner_id', 'required'),
			array('parent_id,action, partner_id, pos, tag_id', 'numerical', 'integerOnly'=>true),
			array('name,price,old_price, shorttext', 'length', 'max'=>255),
			array('unit', 'length', 'max'=>4),
            //array('price', 'numerical', 'message' => 'Цена должен быть числом. Запятые замените на точку'),
			array('img', 'file', 'types'=>'jpg, jpeg, gif, png','allowEmpty'=>true, 'maxSize'=>2*1024*1024),
			array('id, name, img, price, unit, shorttext, text, parent_id, partner_id, pos, related_products, publication', 'safe'),
		);
	}


	public function relations()
	{
		return array(
			'tag'=>array(self::BELONGS_TO, 'Tag', 'tag_id'),
			'partner'=>array(self::BELONGS_TO, 'Partner', 'partner_id'),
			'parent_menu'=>array(self::BELONGS_TO, 'Menu', 'parent_id'),
		);
	}

	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			$this->date_change = time();
			if($this->isNewRecord)
			{
				$this->publication = 1;
			}
			return true;
		} else
			return false;
	}

	private $treated_id;

	protected function afterSave()
	{
		Yii::app()->cache->flush();
		if($this->treated_id == $this->id){return;}
		/*    действие   */
    	if($this->isNewRecord)
        {
        	$action = "добавил товар #".$this->id." ".$this->name;
        }else {
        	$action = "отредактировал товар #".$this->id." ".$this->name;
        }

        /*if(count(Goods::model()->findAll(array('condition'=>'parent_id='.$this->parent_id) ) ) )//если в подкаталогах есть что то
        {
        	$this->parent_menu->have_subcatalog = 0;
        	$this->parent_menu->save();
        }else
        {
        	$this->parent_menu->have_subcatalog = 1;
        	$this->parent_menu->save();
        }*/

        /**********************/

        /*    актер      */
        if(Yii::app()->user->isGuest)
        {
        	$acter = "гость"; // @TODO не используется, можно удалить?
			Action::addNewAction(Action::GOODS, 0, $this->partner ? $this->partner->id : 0, 'Гость (Session '.Yii::app()->getSession()->sessionID.') '.$action);
        }else{
        	switch(Yii::app()->user->role)
        	{
        		case User::USER:	Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Пользователь (ID '.Yii::app()->user->id.') '.$action);
        							break;
        		case User::PARTNER: Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Партнер (ID '.$this->partner->id.') '.$action);
        							break;
        		case User::ADMIN:
        							Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Администратор '.$action);
        							break;
        	}
        }

		$this->treated_id = $this->id;
	}

	protected function beforeDelete()
	{

		$this->saveDeleted();
		@unlink("upload/goods/".$this->img);
		@unlink("upload/goods/small".$this->img);
		$action = "удалил товар #".$this->id." - ".$this->name;
		if(parent::beforeSave())
    	{
    		switch(Yii::app()->user->role)
        	{
        		case User::USER:	Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Пользователь (ID '.Yii::app()->user->id.') '.$action);
        							break;
        		case User::PARTNER: Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Партнер (ID '.$this->partner->id.') '.$action);
        							break;
        		case User::ADMIN: 	Action::addNewAction(Action::GOODS, Yii::app()->user->id, $this->partner ? $this->partner->id : 0, 'Администратор '.$action);
        							break;
        	}
    		return true;
    	}else {
    		return false;
    	}

    	/*if(parent::beforeDelete()){
			$this->saveDeleted();
		}*/
	}

	protected function saveDeleted(){
		$d = new Deleted;
		$d->item_id = $this->id;
		$d->type = 1;
		$d->date_change = time();
		if($d->save(false))
			file_put_contents('deleted', "ok");
		else
			file_put_contents('deleted', "no");
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'img' => 'Картинка',
			'price' => 'Цена',
			'old_price' =>'Старая цена',
			'unit' => 'Ед. измерения',
			'shorttext' => 'Короткий текст',
			'text' => 'Описание',
			'parent_id' => 'Родительский каталог',
			'partner_id' => 'Партнер',
			'pos' => 'Позиция',
			'related_products' => 'Сопуствующие товары',
			'favorite' => 'Избранное',
			'publication' => 'Публикация',
			'date_change' => 'Дата изменения',
			'tag_id' => 'Тег',
			'tag.name' => 'Тег',
			'action'=>'Акция'
		);
	}

	public function search($serchCriteria = "")
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('tag_id',$this->tag_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('shorttext',$this->shorttext,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('partner_id',$this->partner_id);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('related_products',$this->related_products);
		$criteria->compare('favorite',$this->favorite);
		$criteria->compare('publication',$this->publication);
		$criteria->compare('date_change',$this->date_change);

		$criteria->order = 'pos, id DESC';

		if(isset($serchCriteria['parent_id']))
		{
			$criteria->addCondition('parent_id='.$serchCriteria['parent_id']);
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
	            'pageSize' => 100,
	        ),
		));
	}

	public static function getOrderCount($id)
	{
		$count = 0;

		$result = Yii::app()->db->createCommand("SELECT COUNT(DISTINCT order_id ) as count FROM `tbl_order_items` WHERE goods_id = '".$id."'")->queryAll();
		$count = $result[0]['count'];

		return $count;
	}

	public function getImage()
	{
		$str = '';
		if($this->img){
			$str = '/upload/goods/small'.$this->img;
		}else{
			$str = $this->partner->getImage();
		}
		return $str;
	}

	public static function DeleteAllGoods($goodId){
		/** @var self[] $goods */
		$goods = self::model()->findAll('partner_id=:id', array(':id'=>$goodId));
		foreach ($goods as $data) {
			$data->delete();
		}
	}

	public function getOrigPrice($domain)
	{
		if($domain==1){
			return $this->price;
		}else{
			return (int)$this->price;
		}
	}

	public function getOldPrice($domain){
		if($domain->alias == 'www.dostavka.az'){
			return $this->old_price;
		}else{
			return (int)$this->old_price;
		}
	}

	/**
	 * Все напитки партнера
	 * @param  int $partner_id
	 * @return [type]             [description]
	 */
	public static function getDrinks($partner_id)
	{
		$tagId = Tag::getDrink() ? Tag::getDrink()->id : '';

		if(!$tagId){
			return false;
		}

		$criteria = new CDbCriteria;
		$criteria->select = 't.*, COUNT(oi.goods_id) AS order_count';
		$criteria->condition = 'partner_id = :pId AND tag_id = :drinkId';
		$criteria->params = array(
				':pId' => $partner_id,
				':drinkId' => $tagId,
			);
		$criteria->limit = 4;

		$criteria->join = 'LEFT JOIN tbl_order_items AS oi ON t.id = oi.goods_id';
		$criteria->group = 't.id';
		$criteria->order = 'order_count DESC';

		return self::model()->findAll($criteria);
	}

}
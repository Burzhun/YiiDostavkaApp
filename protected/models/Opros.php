<?
class Opros extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{opros}}';
	}

	public function rules()
	{
		return array(
			array('name, pos', 'required'),
			array('pos', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			
			
			array('id, name, pos', 'safe', 'on'=>'search'),
			array('name, pos', 'safe', 'on'=>'create, update'),
			array('id', 'unsafe'),
		);
	}

	public function relations()
	{
		return array(
			'otvety'=>array(self::HAS_MANY, 'OprosOtvet', 'parent_id', 'order'=>'pos, id DESC'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Имя',
			'pos' => 'Позиция',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pos',$this->pos);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeDelete()
	{
		foreach ($this->otvety as $o) {
			$o->delete();
		}
		parent::beforeDelete();
		return true;
	}
}
<?php


class Post extends CActiveRecord
{
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	public function tableName()
	{
		return '{{post}}';
	}
	
	
	public function rules()
	{
		
		return array(
			array('title, shorttext, text', 'required'),
			//array('shorttext', 'length', 'max'=>255),
			array('img', 'file', 'types'=>'jpg, jpeg, gif, png','allowEmpty'=>true, 'maxSize'=>2*1024*1024),
			array('id, title, date, shorttext, text, img, view', 'safe'),
		);
	}
	
	
	public function relations()
	{
		return array(
			'tags'=>array(self::MANY_MANY, 'TagInPost',
                'tbl_post_tag(post_id, tag_id)'),
			'comment'=>array(self::HAS_MANY, 'Comment', 'post_id'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'img' => 'Картинка',
			'shorttext' => 'Короткий текст',
			'text' => 'Текст',
			'date' => 'Дата',
			'view' => 'Просмотров',
		);
	}


	public function selectedListData()
	{
		$str = '';

		foreach ($this->tags as $value) {
			$str .= $value->name.',';
		}

		return substr($str, 0, strlen($str)-1);
	}

	public function setTag($str)
	{
		PostTag::model()->deleteAll(array('condition'=>'post_id='.$this->id));

		$tags = explode(',', $str);

		foreach ($tags as $value) {
			$postTag = new PostTag();
			$postTag->post_id = $this->id;

			if(!TagInPost::model()->exists("name='".$value."'")){
				$teg = new TagInPost();
				$teg->name = $value;
				$teg->tname = Controller::translit($value);
				$teg->save();
				$postTag->tag_id = $teg->id;
			}else{
				$postTag->tag_id = TagInPost::model()->find("name='".$value."'")->id;
			}
			
			$postTag->save();
		}
	}


	
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('shorttext',$this->shorttext,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('view',$this->view,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
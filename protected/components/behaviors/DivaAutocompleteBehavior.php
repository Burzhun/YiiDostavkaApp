<?php

class DivaAutocompleteBehavior extends CActiveRecordBehavior {
	
	public $idField = 'id';
	public $searchFields = array('name');
	public $valueFields = array('id', 'name');
	public $valueTemplate = '#{{id}} {{name}}';
	
	public function getAutocompleteItems($q)
	{
		
		$provider = $this->searchForAutocomplete($q);
		
		$items = $provider->getData();
		
		$result = array();
		foreach($items as $item){
			
			$str = $this->valueTemplate;
			foreach($this->valueFields as $field){
				$str = str_replace('{{'.$field.'}}', $item->$field, $str);
			}
			
			$result[] = $str; 
		}
		return $result;
		
	}

	
	public function searchForAutocomplete($q)
	{
		

		$criteria=new CDbCriteria;
		foreach($this->searchFields as $field){
			$criteria->addSearchCondition($field, $q, true, 'OR');
		}
		$criteria->select = implode(', ', $this->valueFields);
		
		return new CActiveDataProvider($this->owner, array(
			'criteria'=>$criteria,
		));
	}	
	
	
}

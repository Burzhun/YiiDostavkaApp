<?php

class StatusBehavior extends CActiveRecordBehavior
{   
    public $old_status;
    public function afterFind($event)
	{
		parent::afterFind($event);
		$this->owner->old_status=$this->owner->status;
	}
	
	public function beforeSave($event)
	{
		$owner_classname = get_class($this->getOwner()); // @TODO не используется, можно удалить?
		if($this->owner->isNewRecord) {
			$this->owner->status=$this->owner->status;
			$this->owner->approved_site = date('H:i:s');
			$this->owner->approved_partner = $this->owner->delivered = $this->owner->cancelled = "00:00:00";
			//$this->owner->comment_date = time();
		}
		else
		{
			if($this->owner->status!=$this->owner->old_status)
			{
				switch ($this->owner->status)
				{
					case Order::$APPROVED_SITE: $this->owner->approved_site = date('H:i:s');$this->owner->approved_partner = $this->owner->delivered = $this->owner->cancelled = "00:00:00";break;
					case Order::$APPROVED_PARTNER: $this->owner->approved_partner = date('H:i:s');$this->owner->delivered = $this->owner->cancelled = "00:00:00";break;
					//case Order::$SENT: $this->owner->sent = date('H:i:s');$this->owner->delivered = $this->owner->cancelled = "00:00:00";break;
					case Order::$DELIVERED: $this->owner->delivered = date('H:i:s');$this->owner->cancelled = "00:00:00";break;
					case Order::$CANCELLED: $this->owner->cancelled = date('H:i:s');break;
				}
				$this->owner->status=$this->owner->status;
	            //$this->owner->comment_date = time();
			}
		}
	}
}
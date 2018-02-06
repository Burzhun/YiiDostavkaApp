<?php
class WebUser extends CWebUser {
    private $_model = null;

    function getRole() {
        if($user = $this->getModel()){
        	$_role = "";
            if($user->partner_id == 0)
            	$_role = User::USER;
            if($user->partner_id != 0)
            	$_role = User::PARTNER;
            if($user->role == 'operator')
                $_role = User::OPERATOR;
            if($user->role == 'admin')
            	$_role = User::ADMIN;
            return $_role;
        }
	}

    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id, array('select' => 'id, name,phone, partner_id, role, status'));
        }
        return $this->_model;
    }

	function getStatus() {
        if($user = $this->getModel()){
            return $user->status == 1 ? true : ($user->email=='' ? true : false);
        }
	}

    function getphone(){
        if($user = $this->getModel()){
            return $user->phone;
        }
    }
    function isAdmin(){
        if($user = $this->getModel()){
            return $user->role == User::ADMIN ? true : false;
        }
    }
    function isPartner(){
        if($user = $this->getModel()){
            return $user->role == User::PARTNER ? true : false;
        }
    }
}
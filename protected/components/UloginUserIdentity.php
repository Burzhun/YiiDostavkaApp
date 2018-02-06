<?php

class UloginUserIdentity implements IUserIdentity
{

    private $id;
    private $name;
    private $isAuthenticated = false;
    private $states = array();

    public function __construct()
    {
    }

    public function authenticate($uloginModel = null)
    {

        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('identity', $uloginModel->identity, true, 'AND');
        $criteria->addSearchCondition('network', $uloginModel->network, true, 'AND');
        /*$criteria->condition = 'identity=:identity AND network=:network';
        $criteria->params = array(
            ':identity' => $uloginModel->identity
        , ':network' => $uloginModel->network
        );*/
        $user = User::model()->find($criteria);

        $cremail = new CDbCriteria;
        $cremail->condition = 'email=:email';
        $cremail->params = array(
            ':email' => $uloginModel->email
        );
        $useremail = User::model()->find($cremail);

        if(null !== $useremail){
            if (null !== $user) {
                $this->id = $user->id;
                $this->name = $user->name;
            }
            else {
                $user = $useremail;
                $user->identity .= ','.$uloginModel->identity;
                $user->network .= ','.$uloginModel->network;
                if(!$user->name){
                    $user->name = $uloginModel->name;    
                }
                $user->save();
                
                $this->id = $user->id;
                $this->name = $user->name;
            }
        }else{
            if (null !== $user) {
                $user->email = $uloginModel->email;
                $user->save();
                $this->id = $user->id;
                $this->name = $user->name;
            }
            else {
                $user = new User();
                $user->identity = $uloginModel->identity;
                $user->network = $uloginModel->network;
                $user->name = $uloginModel->name;
                $user->email = $uloginModel->email;
                $user->status=1;
                $user->save();
                
                $this->id = $user->id;
                $this->name = $user->name;
            }

        }
        
        $this->isAuthenticated = true;
        return true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsAuthenticated()
    {
        return $this->isAuthenticated;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPersistentStates()
    {
        return $this->states;
    }
}
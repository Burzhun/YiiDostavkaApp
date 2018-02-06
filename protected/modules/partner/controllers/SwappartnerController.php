<?php

class SwappartnerController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array(User::PARTNER),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id)
    {
        $_user = Relationpartner::model()->find(array('condition' => 'user_id=' . $id));

        if (!$_user) {
            $_user = Relationpartner::model()->find(array('condition' => 'owner_id=' . $id . ' AND user_id=' . Yii::app()->user->id));

            $_user = isset($_user->owner) ? $_user->owner : "";
            if (!$_user) {
                $this->redirect('/partner/orders');
                exit();
                Yii::app()->end();
            }
        } else {
            $_user = $_user->user;
        }

        $username = $_user->email;//'mustafa_urg@mail.ru';
        $_identity = new swapUserIdentity($username);
        $_identity->authenticate();

        Yii::app()->user->login($_identity);
        //echo Yii::app()->user->id;

        $this->redirect('/partner/orders');
    }
}
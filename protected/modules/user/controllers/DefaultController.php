<?php

class DefaultController extends Controller
{
    private $_model;

    public $layout = '//layouts/main';

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
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        if (Yii::app()->user->isGuest)
            $this->redirect(array('/user/login'));
        if (Yii::app()->user->role == User::ADMIN)
            $this->redirect(array('/admin'));
        if (Yii::app()->user->role == User::PARTNER)
            $this->redirect(array('/partner/profile'));
        if (Yii::app()->user->role == User::USER)
            $this->redirect(array('/user/profile'));
    }


    public function actionThanks()
    {
        $this->render('thanks');
    }


    public function loadUser($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = User::model()->findbyPk($id !== null ? $id : $_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }
}
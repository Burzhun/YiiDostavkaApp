<?php

class MessageController extends Controller
{
    private $_model;

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

    public function actionIndex()
    {
        $model = $this->loadPartner();

        $breadcrumbs = array($model->name => array('/partner/message'), 'Сообщения');

        $h1 = $model->name;

        $message = Message::model()->findAll(array('condition' => 'partner_id=' . $model->id, 'order' => 'date DESC'));

        $this->render('index', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'message' => $message,
        ));
    }


    public function loadPartner()
    {
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = User::model()->findByPk(Yii::app()->user->id)->partner;//Yii::app()->controller->module->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }
}
<?php

class PaymentController extends Controller
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
    private $_model;

    public function actionIndex()
    {

        $model = $this->loadPartner();
        $id = $model->id;
        $breadcrumbs = array($model->name => array('/partner/payment'), 'История платежей');
        $h1 = $model->name;
        $condition2 = "";
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition2 = " and unix_timestamp('{$_GET['from']}') < date and unix_timestamp('" . $_GET['to'] . "')+86400>date";
        } else {
            if (isset($_GET['from'])) {
                $condition2 = " and unix_timestamp('{$_GET['from']}') < date";
            }
            if (isset($_GET['to'])) {
                $condition2 = " and unix_timestamp('" . $_GET['to'] . "')+86400>date";
            }
        }
        $data = new CActiveDataProvider('Payment_history', array(
            'criteria' => array(
                'condition' => 'partner_id=' . $id . $condition2,
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        $this->render('index', array(
            'data' => $data,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
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
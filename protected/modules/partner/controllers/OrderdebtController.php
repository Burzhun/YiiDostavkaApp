<?php

class OrderdebtController extends Controller
{
    private $_model;

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
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

        $breadcrumbs = array('Партнеры' => array('/partner'), 'Задолженость');

        $h1 = $model->name . ' - Задолженость';

        $this->render('orderDebt', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    /*public function actionRemoveOrderDebt()
    {
        $model = $this->loadPartner();

        OrderPartnerDebt::model()->updateAll(array('paid'=>1), 'partner_id='.$model->id.' AND paid=0');

        $this->redirect('/partner/orderDebt');
    }*/

    public function actionGetOrderDebtList()
    {
        $model = $this->loadPartner();

        $dataProvider = new CActiveDataProvider('OrderPartnerDebt', array(
            'criteria' => array(
                'condition' => 'partner_id=' . $model->id . ' AND paid=0',
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 500,
            ),
        ));

        echo $this->renderPartial('orderDebtList', array("dataProvider" => $dataProvider));
    }

    public function loadPartner()
    {
        //print_r(Yii::app()->controller->id);
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = User::model()->findByPk(Yii::app()->user->id)->partner;//Yii::app()->controller->module->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }
}
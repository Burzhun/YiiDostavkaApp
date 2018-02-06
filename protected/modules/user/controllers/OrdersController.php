<?php

class OrdersController extends Controller
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
                'roles' => array(User::USER),
            ),
            /*array('allow',
                'roles'=>array(User::PARTNER),
            ),*/
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $model = $this->loadUser();

        $breadcrumbs = array($model->name => array('/user/profile'), 'Заказы');

        $h1 = $model->name;

        $order_model = new Order('search');
        $order_model->unsetAttributes();

        if (isset($_GET['Order']))
            $order_model->attributes = $_GET['Order'];

        $this->render('index', array(
            'order_model' => $order_model,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionView($id)
    {
        $order = Order::model()->findByPk($id);

        $model = $this->loadUser();

        $breadcrumbs = array($model->name => array('/user/profile'), 'Заказы' => array('/user/orders'));

        $h1 = 'Заказ #' . $order->id;

        if ($order->user_id == Yii::app()->user->id) {
            $order_item_model = new OrderItem('search');

            if (isset($_POST['Order'])) {
                $order->status = $_POST['Order']['status'];
                $order->save();
            }

            $this->render('orderView', array(
                'order' => $order,
                'order_item_model' => $order_item_model,
                'model' => $model,
                'breadcrumbs' => $breadcrumbs,
                'h1' => $h1,
            ));
        } else {
            $this->redirect(array('index'));
        }
    }

    public function actionCancelled($id)
    {
        $order = Order::model()->findByPk($id);

        if ($order->user_id == Yii::app()->user->id) {
            if ($order->status == Order::$APPROVED_SITE) {
                $order->status = Order::$CANCELLED;
                $order->save();
                $this->redirect('/user/orders/' . $order->id);
            } else {
                $this->redirect(array('index'));
            }
        } else {
            $this->redirect(array('index'));
        }
    }

    public function loadUser()
    {
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = Yii::app()->controller->module->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }
}
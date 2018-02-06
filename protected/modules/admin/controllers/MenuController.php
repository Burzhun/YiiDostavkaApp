<?php

class GoodsController extends Controller
{

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
                'roles' => array(User::ADMIN),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        $model = new Goods('search');
        $model->unsetAttributes();
        if (isset($_GET['Goods']))
            $model->attributes = $_GET['Goods'];

        $this->render('index', array(
            'model' => $model,
        ));
    }


    public function actionOrder($id)
    {
        $model = OrderItem::model()->findAll(array('condition' => 'goods_id=' . $id));

        $this->render('order', array(
            'model' => $model,
        ));
    }


    public function loadModel($id)
    {
        $model = Goods::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
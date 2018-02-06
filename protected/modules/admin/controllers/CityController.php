<?php

class CityController extends Controller
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
                'users' => array('@'),
                'expression' => 'User::hasStatAccess()'
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        $model = new City('search');
        $model->unsetAttributes();
        if (isset($_GET['City']))
            $model->attributes = $_GET['City'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        /*$breadcrumbs = array('Домены'=>array('/admin/City'),$model->name ? $model->name : "Создание домена");
        $h1 = $model->name ? $model->name : "Создание домена";*/
        $model = new City;
        if (isset($_POST['City'])) {
            $model->attributes = $_POST['City'];

            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            /*'breadcrumbs'=>$breadcrumbs,
            'h1'=>$h1,*/
        ));
    }

    public function actionUpdate($id)
    {

        $model = $this->loadModel($id);
        $breadcrumbs = array('Города' => array('/admin/City'), $model->name ? $model->name : "Изменение города");
        $h1 = $model->name ? $model->name : "Изменение города";

        if (isset($_POST['City'])) {
            $model->attributes = $_POST['City'];

            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionDelete($id)
    {
        $model = City::model()->findByPk($id);
        $model->delete();
        $this->redirect(array('/admin/city'));
    }

    public function loadModel($id)
    {
        $model = City::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
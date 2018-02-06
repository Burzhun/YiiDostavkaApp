<?php

class DomainController extends Controller
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
        $model = new Domain('search');
        $model->unsetAttributes();
        if (isset($_GET['Domain']))
            $model->attributes = $_GET['Domain'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        /*$breadcrumbs = array('Домены'=>array('/admin/domain'),$model->name ? $model->name : "Создание домена");
        $h1 = $model->name ? $model->name : "Создание домена";*/
        $model = new Domain;
        if (isset($_POST['Domain'])) {
            $model->attributes = $_POST['Domain'];

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
        $breadcrumbs = array('Домены' => array('/admin/domain'), $model->name ? $model->name : "Изменение домена");
        $h1 = $model->name ? $model->name : "Изменение домена";


        if (isset($_POST['Domain'])) {
            $model->attributes = $_POST['Domain'];

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


    public function loadModel($id)
    {
        $model = Domain::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
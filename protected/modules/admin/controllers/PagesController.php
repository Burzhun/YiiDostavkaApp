<?php

class PagesController extends Controller
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
        $model = new Pages('search');
        $model->unsetAttributes();
        if (isset($_GET['Pages']))
            $model->attributes = $_GET['Pages'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }


    public function actionCreate()
    {
        $model = new Pages;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Pages'])) {
            $model->attributes = $_POST['Pages'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }


    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Pages'])) {
            $model->attributes = $_POST['Pages'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }

        }

        $this->render('update', array(
            'model' => $model,
        ));
    }


    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    public function loadModel($id)
    {
        $model = Pages::model()->findByPk($id);
        /*if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');*/
        return $model;
    }
}
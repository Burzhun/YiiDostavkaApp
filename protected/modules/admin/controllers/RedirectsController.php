<?php

class RedirectsController extends Controller
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
        $model = new CActiveDataProvider('Redirect', array(
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $model = $this->loadModel($id);
            if (isset($_POST['Redirect'])) {
                $model->attributes = $_POST['Redirect'];

                if ($model->save()) {
                    $this->redirect(array('index'));
                }
            }
            $this->render('update', array(
                'model' => $model,
                /*'breadcrumbs'=>$breadcrumbs,
                'h1'=>$h1,*/
            ));
        }

    }

    public function actionCreate()
    {
        $model = new Redirect;
        if (isset($_POST['Redirect'])) {
            $model->attributes = $_POST['Redirect'];

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
        $model = Redirect::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
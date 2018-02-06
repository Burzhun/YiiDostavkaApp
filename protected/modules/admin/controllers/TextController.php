<?php

class TextController extends Controller
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
                'roles' => array(User::ADMIN),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        $model = new Text('search');
        $model->unsetAttributes();
        if (isset($_GET['Text']))
            $model->attributes = $_GET['Text'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        /*$breadcrumbs = array('Домены'=>array('/admin/domain'),$model->name ? $model->name : "Создание домена");
        $h1 = $model->name ? $model->name : "Создание домена";*/
        $model = new Text;
        if (isset($_POST['Text'])) {
            $model->attributes = $_POST['Text'];

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
        /*$breadcrumbs = array('Домены'=>array('/admin/Text'),$model->name ? $model->name : "Изменение домена");
        $h1 = $model->name ? $model->name : "Изменение домена";*/

        $model = $this->loadModel($id);
        if (isset($_POST['Text'])) {
            $model->attributes = $_POST['Text'];

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


    public function loadModel($id)
    {
        $model = Text::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
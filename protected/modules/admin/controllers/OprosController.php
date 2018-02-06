<?php

class OprosController extends Controller
{
    //public $layout='//layouts/column2';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
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

    public function actionView($id)
    {

        $dataProvider = new CActiveDataProvider('OprosOtvet', array(
            'criteria' => array(
                'condition' => 'parent_id=' . $id,
                'order' => 'sum',
            ),
        ));

        $model = Opros::model()->findByPk($id);

        $breadcrumbs = array('Опросы' => array('/admin/opros'), $model->name);

        $h1 = 'Создание опроса';

        $this->render('view', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionCreate()
    {
        $model = new Opros('create');
        //$model = Partner::model()->findByPk($id);

        $breadcrumbs = array('Опросы' => array('/admin/opros'), 'Создание');

        $h1 = 'Создание опроса';


        // $this->performAjaxValidation($model);

        if (isset($_POST['Opros'])) {
            $model->attributes = $_POST['Opros'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $model->scenario = 'update';

        $breadcrumbs = array('Опросы' => array('/admin/opros'), 'Редактирование');

        $h1 = $model->name . " - Редактирование";

        // $this->performAjaxValidation($model);

        if (isset($_POST['Opros'])) {
            $model->attributes = $_POST['Opros'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


    public function actionAddAnswer()
    {
        $model = new OprosOtvet('create');
        $model->answer = $_POST['answer'];
        $model->parent_id = $_POST['parent_id'];
        $model->save();
    }


    public function actionUpdateAnswer($id)
    {
        $model = OprosOtvet::model()->findByPk($id);
        $model->scenario = 'update';

        $breadcrumbs = array('Опросы' => array('/admin/opros'), $model->opros->name => array('opros/view', 'id' => $model->opros->id), 'Редактирование');

        $h1 = 'Создание опроса';

        if (isset($_POST['OprosOtvet'])) {
            $model->attributes = $_POST['OprosOtvet'];

            if ($model->save())
                $this->redirect(array('view', 'id' => $model->parent_id));
        }

        $this->render('updateAnswer', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionDeleteAnswer($id)
    {
        $model = OprosOtvet::model()->findByPk($id);
        $model->delete();
    }

    public function actionIndex()
    {
        $model = new Opros('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Opros']))
            $model->attributes = $_GET['Opros'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function loadModel($id)
    {
        $model = Opros::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'opros-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
<?php

class RuntextController extends Controller
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
        $model = $this->loadModel(1);

        if (isset($_POST['Runtext'])) {
            $model->attributes = $_POST['Runtext'];
            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id)
    {
        $model = Runtext::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
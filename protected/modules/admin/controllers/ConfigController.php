<?php

class ConfigController extends Controller
{
    public $name = 'Настройки';

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
        $model = new Config('search');
        $model->unsetAttributes();
        if (isset($_GET['Domain']))
            $model->attributes = $_GET['Domain'];
        if(isset($_GET['domain_id'])){
            $model->domain_id=$_GET['domain_id'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }


    public function actionUpdateAjax()
    {
        $es = new EditableSaver('Config');
        $es->update();
    }

    /*public function actionIndex(){

        if(Yii::app()->request->isPostRequest){

            foreach ($_POST['Config']['value'] as $key=>$value) {
                $config = Config::model()->findbyPk($key);
                $config->value = $value;

                if($config->save()) {
                    Yii::app()->user->setFlash('success', "<b>$this->name</b>: Данные успешно обновлены!");
                }
            }
        }

        $models = Config::model()->findAll();

        $this->render('index',array(
            'models'=>$models,
        ));
    }*/
}

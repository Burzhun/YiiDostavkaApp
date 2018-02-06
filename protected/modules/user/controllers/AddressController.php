<?php

class AddressController extends Controller
{
    public $defaultAction = 'index';

    public $layout = '//layouts/main';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */

    private $_model;


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

        $breadcrumbs = array($model->name => array('/user/profile'), 'Адреса');

        $h1 = $model->name;

        $address_model = new UserAddress('search');
        $address_model->unsetAttributes();
        if (isset($_GET['UserAddress']))
            $model->attributes = $_GET['UserAddress'];

        if (Yii::app()->theme->name == 'mobile') {
            $userAddresses = UserAddress::getUserAddress($model->id);
        }

        $this->render('index', array(
            'address_model' => $address_model,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'userAddresses' => $userAddresses,
        ));
    }


    public function actionCreate()
    {
        $model = $this->loadUser();

        $breadcrumbs = array($model->name => array('/user/profile'), 'Добавление адреса');

        $h1 = 'Добавление адреса';

        $address_model = new UserAddress();

        if (isset($_POST['UserAddress'])) {
            $address_model->attributes = $_POST['UserAddress'];

            $address_model->city_id = 1 * $_POST['City']['id'];
            if ($address_model->validate()) {
                $address_model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('create', array(
            'address_model' => $address_model,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionUpdate($id)
    {
        $model = $this->loadUser();

        $breadcrumbs = array($model->name => array('/user/profile'), 'Редактирование адреса');

        $h1 = 'Редактирование адреса';

        $address_model = UserAddress::model()->findByPk($id);

        if ($address_model->user_id == Yii::app()->user->id) {
            if (isset($_POST['UserAddress'])) {
                $address_model->attributes = $_POST['UserAddress'];

                $address_model->city_id = 1 * $_POST['City']['id'];
                if ($address_model->validate()) {
                    $address_model->save();
                    $this->redirect(array('index'));
                }
            }

            $this->render('update', array(
                'address_model' => $address_model,
                'model' => $model,
                'breadcrumbs' => $breadcrumbs,
                'h1' => $h1,
            ));
        } else {
            $this->redirect(array('index'));
        }
    }

    public function actionTest()
    {
        echo "prrr!!!";
    }


    public function actionDelete($id)
    {
        $address_model = UserAddress::model()->findByPk($id);

        if ($address_model->user_id == Yii::app()->user->id) {
            $address_model->delete();
            $this->redirect(array('index'));
        } else {
            $this->redirect(array('index'));
        }
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
     */
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
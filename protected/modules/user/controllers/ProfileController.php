<?php

class ProfileController extends Controller
{
    //public $defaultAction = 'profile';
    public $layout = '//layouts/main';

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

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * Shows a particular model.
     */
    public function actionIndex()
    {
        $model = $this->loadUser();
        /*		$user = User::model()->findbyPk(Yii::app()->user->id);
                echo "<pre>";
                print_r($user);
                exit();
        */

        $breadcrumbs = array($model->name => array('/user/profile'), 'Профиль');

        $h1 = $model->name;

        $this->render('profile', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionEdit()
    {
        $model = $this->loadUser();

        $breadcrumbs = array($model->name => array('/user/profile'), 'Редактирование профиля');

        $h1 = $model->name . " - Редактирование профиля";

        // ajax validator
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'profile-form') {
            echo UActiveForm::validate(array($model, $profile));
            Yii::app()->end();
        }

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($_POST['User']['birthdate1'] != '' && $_POST['User']['birthdate2'] != '' && $_POST['User']['birthdate1'] != '') {
                $model->birthdate = $_POST['User']['birthdate3'] . "-" . $_POST['User']['birthdate2'] . "-" . $_POST['User']['birthdate1'];
            }

            //$profile->attributes=$_POST['Profile'];

            if ($model->validate()) {
                $model->save();
                //$profile->save();
                Yii::app()->user->setFlash('profileMessage', UserModule::t("Изменения сохранены."));
                $this->redirect(array('/user/profile'));
            } //else $profile->validate();
        }

        $this->render('edit', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionChangepassword()
    {
        $model_pass = new UserChangePassword;

        $model = $this->loadUser();

        $breadcrumbs = array($model->name => array('/user/profile'), 'Редактирование профиля' => array('/user/profile/edit'), 'Изменение пароля');

        $h1 = $model->name . " - Изменение пароля";

        if (Yii::app()->user->id) {

            // ajax validator
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'changepassword-form') {
                echo UActiveForm::validate($model_pass);
                Yii::app()->end();
            }

            if (isset($_POST['UserChangePassword'])) {
                $model_pass->attributes = $_POST['UserChangePassword'];
                if ($model_pass->validate()) {
                    $new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
                    $new_password->pass = UserModule::encrypting($model_pass->password);
                    $new_password->activkey = UserModule::encrypting(microtime() . $model_pass->password);
                    $new_password->save();
                    Yii::app()->user->setFlash('profileMessage', UserModule::t("Новый пароль сохранен."));
                    $this->redirect("/user/profile");
                }
            }

            $this->render('changepassword', array('model_pass' => $model_pass, 'model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1,));
        }
    }


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
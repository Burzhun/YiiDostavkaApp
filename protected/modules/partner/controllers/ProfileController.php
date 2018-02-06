<?php

class ProfileController extends Controller
{

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
                'roles' => array(User::PARTNER),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $model = $this->loadPartner();

        if (isset($_POST['User'])) {
            $user_model = User::model()->findByPk($model->user->id);
            $user_model->attributes = $_POST['User'];
            if ($user_model->save() && !isset($_POST['Partner']))
                $this->redirect(array('/partner/profile'));
        }

        if (isset($_POST['Partner'])) {
            //$oldPass = $model->pass;
            $model->attributes = $_POST['Partner'];
            //$model->pass = empty($_POST['Partner']['pass']) ? $oldPass : md5($_POST['Partner']['pass']);
            if ($model->save())
                $this->redirect(array('/partner/profile'));
        }

        $breadcrumbs = array($model->name => array('/partner/info'), 'Профиль');

        $h1 = $model->name;

        $this->render('index', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionChangepassword()
    {
        $model_pass = new UserChangePassword;
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
                    $this->redirect(array("/partner/profile"));
                }
            }
            $model = $this->loadPartner();

            $breadcrumbs = array($model->name => array('/partner/info'), 'Изменение пароля');

            $h1 = 'Изменение пароля';

            $this->render('changepassword', array('model_pass' => $model_pass, 'model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1));
        }
    }

    public function getFragmentBreadcrumbs($p_partner_id, $p_menu_model)
    {
        $ar = array();
        $temp_model = Menu::model()->findByPk($p_menu_model->parent_id);
        $ar[$p_menu_model->name] = "";
        if ($temp_model) {
            while ($temp_model) {
                $ar[$temp_model->name] = '/admin/partner/id/' . $p_partner_id . '/menu/' . $temp_model->id;
                $temp_model = $temp_model->parent_id ? Menu::model()->findByPk($temp_model->parent_id) : 0;
            }
        }
        //exit();
        return array_reverse($ar);
    }

    public function actionUpdateAjax()
    {
        $es = new EditableSaver('Goods');
        $es->update();
    }

    public function loadPartner()
    {
        //print_r(Yii::app()->controller->id);
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = User::model()->findByPk(Yii::app()->user->id)->partner;//Yii::app()->controller->module->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }
}
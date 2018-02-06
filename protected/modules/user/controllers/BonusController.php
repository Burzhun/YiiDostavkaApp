<?php

class BonusController extends Controller
{

    private $_model;

    public $layout = '//layouts/main';

    public function actionIndex()
    {
        $model = $this->loadUser();

        $breadcrumbs = array($model->name => array('/user/profile'), 'Адреса');

        $h1 = $model->name;

        $user_bonus = new UserBonus('search');
        $user_bonus->user_id = Yii::app()->user->id;

        if (isset($_POST['UserBonus'])) {
            $user_bonus->attributes = $_POST['UserBonus'];
        }

        $this->render('index', array(
            'user_bonus' => $user_bonus,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
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
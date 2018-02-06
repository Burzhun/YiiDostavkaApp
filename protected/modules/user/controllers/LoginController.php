<?php

class LoginController extends Controller
{
    public $layout = '//layouts/main';

    public $defaultAction = 'login';

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (Yii::app()->user->isGuest) {
            $model = new UserLogin;
            // collect user input data
            if (isset($_POST['UserLogin'])) {
                $model->attributes = $_POST['UserLogin'];
                // validate user input and redirect to previous page if valid
                if ($model->validate()) {
                    $this->lastViset();
                    if (strpos(Yii::app()->user->returnUrl, '/index.php') !== false) {
                        if (Yii::app()->user->role == User::PARTNER)
                            $this->redirect(array('/partner/profile'));
                        if (Yii::app()->user->role == User::USER)
                            $this->redirect(array('/user/profile'));
                        if (Yii::app()->user->role == User::ADMIN)
                            $this->redirect(array('/admin'));
                        //$this->redirect(Yii::app()->controller->module->returnUrl);
                    } else {
                        $this->redirect(Yii::app()->user->returnUrl);
                    }
                }
            }
            // display the login form
            $this->render('/user/login', array('model' => $model));
        } else {
            $this->redirect(Yii::app()->controller->module->returnUrl);
        }
    }

    private function lastViset()
    {
        $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
        $lastVisit->last_visit = date('Y-m-d H:i:s');
        $lastVisit->save();
    }
}
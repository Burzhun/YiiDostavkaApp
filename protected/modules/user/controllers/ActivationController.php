<?php

class ActivationController extends Controller
{
    public $defaultAction = 'activation';

    public $layout = '//layouts/main';

    /**
     * Activation user account
     */
    public function actionActivation()
    {

        $email = $_GET['email'];
        $activkey = $_GET['activkey'];
        if ($email && $activkey) {
            /** @var User $find */
            $find = User::model()->notsafe()->findByAttributes(array('email' => $email, 'status' => '0'));
            if (isset($find) && $find->status) {
                $this->render('/user/message', array('title' => UserModule::t("Активация пользователя"), 'content' => UserModule::t("Ваш аккаунт активирован.")));
                //echo 1;print_r("Я побывал тут");
            } elseif (isset($find->activkey) && ($find->activkey == $activkey)) {
                $find->activkey = UserModule::encrypting(microtime());
                //$find->last_visit =

                $criteria = new CDbCriteria;
                $criteria->condition = 'status=:status AND email=:email';
                $criteria->params = array(':status' => 1, ':email' => $find->email);
                $user = User::model()->find($criteria);
                if ($user) {
                    $user->name = $find->name;
                    $user->pass = $find->pass;
                    $user->activkey = $find->activkey;
                    $user->network .= ',simplereg';
                    $find->delete();
                    //$user->save();
                } else {
                    $find->status = 1;
                    $find->save();
                }


                $this->render('/user/message', array('title' => UserModule::t("Активация пользователя"), 'content' => UserModule::t("Ваш аккаунт активирован. Вы можете авторизоваться в форме в верху экрана")));//Вы можете <a href='/user/login'>авторизоваться</a>
                echo 2;
                print_r("Я и тут побывал");
                print_r($find->status);
                echo "<br>";
                echo CHtml::errorSummary($find);
                echo "<br>";
            } else {
                $this->render('/user/message', array('title' => UserModule::t("Активация пользователя"), 'content' => UserModule::t("Неверный активационный url.")));
            }
        } else {
            $this->render('/user/message', array('title' => UserModule::t("Активация пользователя"), 'content' => UserModule::t("Неверный активационный url.")));
        }
    }

}
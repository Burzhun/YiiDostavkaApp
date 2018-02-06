<?php

class RegistrationController extends Controller
{
    public $defaultAction = 'registration';

    public $layout = '//layouts/main';


    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') ? array() : array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    /**
     * Registration user
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm;
        //$profile=new Profile;
        //$profile->regMode = true;

        // ajax validator
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') {
            echo UActiveForm::validate(array($model));
            Yii::app()->end();
        }

        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->controller->module->profileUrl);
        } else {
            if (isset($_POST['RegistrationForm'])) {
                $model->attributes = $_POST['RegistrationForm'];
                $model->phone = '1';
                $model->scenario = 'simplereg';
                //$profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
                if ($model->validate()) {
                    $soucePassword = $model->pass;
                    $model->activkey = UserModule::encrypting(microtime() . $model->pass);
                    $model->pass = UserModule::encrypting($model->pass);
                    $model->verifyPassword = UserModule::encrypting($model->verifyPassword);
                    $model->reg_date = date('Y-m-d H:s:i');
                    $model->last_visit = ((Yii::app()->controller->module->loginNotActiv || (Yii::app()->controller->module->activeAfterRegister && Yii::app()->controller->module->sendActivationMail == false)) && Yii::app()->controller->module->autoLogin) ? time() : 0;
                    //$model->superuser=0;
                    $model->status = ((Yii::app()->controller->module->activeAfterRegister) ? User::STATUS_ACTIVE : User::STATUS_NOACTIVE);


                    /*echo '<pre>';
                    print_r($model);
                    exit();*/

                    if ($model->save()) {
                        //$profile->user_id=$model->id;
                        //$profile->save();
                        if (isset($_GET['user_from_id'])) {
                            Invites::AddInvite($_GET['user_from_id'], $model->email);
                        }
                        if (Yii::app()->controller->module->sendActivationMail) {
                            $activation_url = $this->createAbsoluteUrl('/user/activation/activation', array("activkey" => $model->activkey, "email" => $model->email));
                            UserModule::sendMail($model->email, UserModule::t("Ваша регистрация на сайте {site_name}", array('{site_name}' => Yii::app()->name)), UserModule::t("Для активации акаунта, перейдите по <a href='{activation_url}'>ссылке</a>", array('{activation_url}' => $activation_url)));
                        }

                        if ((Yii::app()->controller->module->loginNotActiv || (Yii::app()->controller->module->activeAfterRegister && Yii::app()->controller->module->sendActivationMail == false)) && Yii::app()->controller->module->autoLogin) {
                            $identity = new UserIdentity($model->username, $soucePassword);
                            $identity->authenticate();
                            Yii::app()->user->login($identity, 0);
                            $this->redirect(Yii::app()->controller->module->returnUrl);
                        } else {
                            if (!Yii::app()->controller->module->activeAfterRegister && !Yii::app()->controller->module->sendActivationMail) {
                                Yii::app()->user->setFlash('registration', UserModule::t("Спасибо, вы зарегистрированны. Contact Admin to activate your account."));
                            } elseif (Yii::app()->controller->module->activeAfterRegister && Yii::app()->controller->module->sendActivationMail == false) {
                                Yii::app()->user->setFlash('registration', UserModule::t("Спасибо, вы зарегистрированны. Please {{login}}.", array('{{login}}' => CHtml::link(UserModule::t('Login'), Yii::app()->controller->module->loginUrl))));
                            } elseif (Yii::app()->controller->module->loginNotActiv) {
                                Yii::app()->user->setFlash('registration', UserModule::t("Спасибо за регистрацию. Теперь вы можете войти под своим email или login."));
                            } else {
                                Yii::app()->user->setFlash('registration', UserModule::t("Спасибо за регистрацию. Проверьте ваш email."));
                            }
                            $this->refresh();
                        }
                    }
                } //else $profile->validate();
            }
            $this->render('/user/registration', array('model' => $model));
        }
    }
}
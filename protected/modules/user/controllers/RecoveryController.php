<?php

class RecoveryController extends Controller
{
    public $defaultAction = 'recovery';

    public $layout = '//layouts/main';

    /**
     * Recovery password
     */
    public function actionRecovery()
    {
        $form = new UserRecoveryForm;
        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->controller->module->returnUrl);
        } else {
            $email = ((isset($_GET['email'])) ? $_GET['email'] : '');
            $activkey = ((isset($_GET['activkey'])) ? $_GET['activkey'] : '');
            if ($email && $activkey) {
                $form2 = new UserChangePassword;
                $find = User::model()->notsafe()->findByAttributes(array('email' => $email));
                if (isset($find) && $find->activkey == $activkey) {
                    if (isset($_POST['UserChangePassword'])) {
                        $form2->attributes = $_POST['UserChangePassword'];
                        if ($form2->validate()) {
                            $find->pass = Yii::app()->controller->module->encrypting($form2->password);
                            $find->activkey = Yii::app()->controller->module->encrypting(microtime() . $form2->password);
                            if ($find->status == 0) {
                                $find->status = 1;
                            }
                            $find->save();
                            Yii::app()->user->setFlash('recoveryMessage', UserModule::t("Новый пароль сохранен."));
                            $this->redirect(Yii::app()->controller->module->recoveryUrl);
                        }
                    }

                    $model = $find;
                    $breadcrumbs = array($model->name => array('/user/profile'), 'Редактирование профиля' => array('/user/profile/edit'), 'Изменение пароля');

                    $this->render('/profile/changepassword', array('model_pass' => $form2, 'model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => 'Изменение пароля'));
                } else {
                    Yii::app()->user->setFlash('recoveryMessage', UserModule::t("Некорректная ссылка воcстановления."));
                    $this->redirect(Yii::app()->controller->module->recoveryUrl);
                }
            } else {
                if (isset($_POST['UserRecoveryForm'])) {
                    $form->attributes = $_POST['UserRecoveryForm'];
                    if ($form->validate()) {
                        $user = User::model()->notsafe()->findbyPk($form->user_id);
                        $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->controller->module->recoveryUrl), array("activkey" => $user->activkey, "email" => $user->email));

                        $subject = UserModule::t("Вы запросили восстановление пароля с сайта {site_name}",
                            array(
                                '{site_name}' => Yii::app()->name,
                            ));
                        $message = UserModule::t("Вы запросили восстановление пароля с сайта {site_name}. Для получения нового пороля, перейдите по <a href='{activation_url}'>ссылке </a>.  Если вы не запрашивали этого, просто проигнорируйте это письмо.",
                            array(
                                '{site_name}' => Yii::app()->name,
                                '{activation_url}' => $activation_url,
                            ));

                        UserModule::sendMail($user->email, $subject, $message);

                        Yii::app()->user->setFlash('recoveryMessage', UserModule::t("Пожалуйста, проверте вашу почту. Перейдите по находящейся в письме ссылке для активации профиля."));
                        $this->refresh();
                    }
                }
                $this->render('/user/recovery', array('form' => $form));
            }
        }
    }
}
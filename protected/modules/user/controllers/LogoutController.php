<?php

class LogoutController extends Controller
{
    public $defaultAction = 'logout';
    public $layout = '//layouts/main';

    public function actionLogout()
    {
    	$past = time() - 3600;
		foreach ( $_COOKIE as $key => $value )
		{
		    setcookie( $key, $value, $past, '/' );
		}
        Yii::app()->request->cookies->clear();
        Yii::app()->user->logout();
        $this->redirect("/");
    }
}
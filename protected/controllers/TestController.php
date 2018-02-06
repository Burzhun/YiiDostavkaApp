<?php

class TestController extends Controller
{
	
	public function actionIndex()
	{
		$this->layout='';
		$this->renderPartial('index');
	}
	
	public function actionChart(){
		$this->renderPartial('chart');
	}
    public function actionActions()
    {

        //$actions = Action::model()->findAll();
        $model = new Action('search');
        $model->unsetAttributes();
        if (isset($_GET['Action']))
            $model->attributes = $_GET['Action'];

        $this->render('actions', array(
            'model' => $model,
        ));
    }

    public function actionUser()
    {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('user', array(
            'model' => $model,
        ));
    }

}
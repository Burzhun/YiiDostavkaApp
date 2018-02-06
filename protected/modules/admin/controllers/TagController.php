<?php

class TagController extends Controller
{

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}


	public function accessRules()
	{
		return array(
			array('allow',
				'roles' => array(User::ADMIN),
			),
			array('deny',
				'users' => array('*'),
			),
		);
	}


	public function actionIndex()
	{
		$model = new Tag('search');
		$model->unsetAttributes();
		if (isset($_GET['Tag']))
			$model->attributes = $_GET['Tag'];

		$this->render('index', array(
			'model' => $model,
		));
	}


	public function actionCreate()
	{
		$model=new Tag;
		$breadcrumbs = array('Баннеры' => array('/admin/banners'), 'Создать ');
		if(isset($_POST['Tag'])){
			$model->attributes=$_POST['Tag'];

			if($model->save()) {
				Yii::app()->user->setFlash('success', "Пункт <b>$model->name</b> успешно добавлен");
				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'breadcrumbs' => $breadcrumbs,
			'model'=>$model,
		));
	}

	public function actionUpdate($id){

		$model=$this->loadModel($id);
		$breadcrumbs = array('Баннеры' => array('/admin/banners'), 'Создать ');
		if(isset($_POST['Tag'])){

			$model->attributes=$_POST['Tag'];

			if($model->save()) {
				Yii::app()->user->setFlash('success', "Пункт <b>$model->name</b> изменен");
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'breadcrumbs' => $breadcrumbs,
			'model'=>$model,
		));
	}


	public function actionDelete($id){
		if(Yii::app()->request->isPostRequest){

			$model = $this->loadModel($id);
			$model->delete();

			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function loadModel($id)
	{
		$model = Tag::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
}
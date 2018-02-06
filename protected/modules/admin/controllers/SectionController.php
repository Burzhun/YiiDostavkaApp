<?php

class SectionController extends Controller
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
				'users' => array('@'),
				'expression' => 'User::hasStatAccess()'
			),
			array('deny',
				'users' => array('*'),
			),
		);
	}


	public function actionIndex()
	{
		
		if(isset($_GET['city_id'])){
			Yii::app()->session['section_city_id']=$_GET['city_id'];
		}
		if(!isset(Yii::app()->session['section_city_id'])){
			Yii::app()->session['section_city_id']=3;
		}
		$model = new Specialization('search');
		$model->unsetAttributes();
		$model->city_id=Yii::app()->session['section_city_id'];
		// $model->direction_id=1;
		if (isset($_GET['Specialization']))
			$model->attributes = $_GET['Specialization'];

		$this->render('index', array(
			'model' => $model,
		));
	}

	public function actionUpdate($id)
	{

		$model = Specialization::model()->findByPk($id);

		$breadcrumbs = array('Разделы' => array('/admin/section'), 'Редактирование - ' . $model->name);
		//Заголовок страницы
		$h1 = $model->name . ' - Редактирование';
		//Если форма заполнена
		if (isset($_POST['Specialization'])) {
			$image = CUploadedFile::getInstance($model, 'image');
			$image_min=CUploadedFile::getInstance($model, 'image_min');
			//Записываем все находившиеся в форме данные, в модель
			$model->attributes = $_POST['Specialization'];			
			$name=trim($model->tname).$model->city_id;
			if(strlen($name)>20){
				$name=substr($name,0,rand(20,25));
			}
			$model->image=$name.'_big.png';
			$model->image_min=$name.'.png';
			if ($model->save()) {
				$dir='upload/specialization/'.$model->city_id;
				if (!file_exists($dir) && !is_dir($dir)) {
					mkdir($dir);
				}
				
				if($image){
					$image->saveAs($dir.'/'. $name.'_big.png');
				}
				if($image_min){
					$image_min->saveAs($dir.'/'. $name.'.png');
				}

				$specs=Specialization::model()->findAll("id<>".$model->id." and pos>=".$model->pos." and direction_id=1 and city_id=".$model->city_id);
				foreach($specs as $spec){
					$spec->pos+=1;
					$spec->save();
				}
				//Action::addNewAction(Action::MENU, 0, $model->id, 'Администратор отредактировал пункт меню - '.$model->name.' ID '.$model->id.', партнера - '.$model->name);
				$this->redirect(array('/admin/section/'));
			}
		}

		$this->render('create', array(
			'model' => $model,
			'breadcrumbs' => $breadcrumbs,
			'h1' => $h1,
		));
	}
	public function actionSort(){
		foreach($_POST as $array){
			$id=$array[1];
			$pos=$array[0]+1;
			Yii::app()->db->createCommand("update tbl_specialization set pos=".$pos ." where id=".$id)->query();
		}
	}

	public function actionCreate()
	{
		$model = new Specialization;

		$breadcrumbs = array('Разделы' => array('/admin/section'), 'Добавление раздела');
		//Заголовок страницы
		$h1 = 'Добавление раздела';
		//Если форма заполнена
		if (isset($_POST['Specialization'])) {
			//Записываем все находившиеся в форме данные, в модель
			$model->attributes = $_POST['Specialization'];
			//$model->city_id=Yii::app()->session['section_city_id'];
			$model->image = CUploadedFile::getInstance($model, 'image');
			$model->image_min = CUploadedFile::getInstance($model, 'image_min');

			if (!$model->image||!$model->image_min) {
				if(!$model->image){
					$model->addError('image', 'Картинка не загружена');
				}
				if(!$model->image_min){
					$model->addError('image_min', 'Картинка не загружена');
				}
				// $this->redirect(array('/admin/section/create'));
			}else{
				if ($model->save()) {
					$dir='upload/specialization/'.$model->city_id;
					if (!file_exists($dir) && !is_dir($dir)) {
						mkdir($dir);
					}
					$name=trim($model->tname); 
					if(strlen($name)>20){
						$name=substr($name,0,20);
						$sp2=Specialization::model()->find("image='".$name."_big.png and city_id=".$model->city_id);
						if($sp2){
							$name.=rand(100,1000);
						}
					}
					$model->image->saveAs('upload/specialization/'.$model->city_id.'/'. $name.'_big.png');
					$model->image_min->saveAs('upload/specialization/'.$model->city_id.'/'. $name.'.png');

					$model->image = $name . '_big.png';
					$model->image_min = $name . '.png';

					$model->save(false);

					$this->redirect(array('/admin/section'));

				}
			}
		}

		$this->render('create', array(
			'model'       => $model,
			'breadcrumbs' => $breadcrumbs,
			'h1'          => $h1,
			'model'       => $model,
		));
	}

	public function actionDelete($id)
	{
		$model = Specialization::model()->findByPk($id);
		$specs=Specialization::model()->findAll("pos>".$model->pos." and direction_id=1 and city_id=".$model->city_id);
		foreach($specs as $spec){
			$spec->pos-=1;
			$spec->save();
		}
		@unlink($model->getMobileImage());
		@unlink($model->getAppImage());
		$model->delete();
	}


	public function loadModel($id)
	{
		$model = Specialization::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}
}
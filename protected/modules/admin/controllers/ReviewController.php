<?php

class ReviewController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column2';

    public $indexH1 = "";
    public $createH1 = "Создать";
    public $updateH1 = "Изменить";
    public $adminH1 = "Все отзывы";
    public $viewH1 = "";
    public $addLabel = "Добавить"; // Текст для конпки "Добавить - {addLabel}"

    public $flashCreateMessage = "Запись успешно сохранена"; //Флеш текст при успешном сохранении
    public $flashUpdateMessage = "Запись успешно изменена"; //Флеш текст при успешном редактировании

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
    /**
     * @return array action filters
     */
    /*	public function filters()
        {
            return array(
                'accessControl', // perform access control for CRUD operations
                'postOnly + delete', // we only allow deletion via POST request
            );
        } */

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */




    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    /*public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $model->setScenario('admin');
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Review']))
        {
            $model->attributes=$_POST['Review'];
            if($model->validate()){
                $model->save();
                Yii::app()->user->setFlash('success',$this->flashUpdateMessage);
                $this->redirect(array('index'));
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }*/

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(array('admin'));
    }

    /**
     * Lists all models.
     */
    /*	public function actionIndex()
        {
            //$dataProvider=new CActiveDataProvider('Review');
            //$criteria=new CDbCriteria;
            $dataProvider = new CActiveDataProvider('Review', array(
                //'criteria'=>$criteria,
                'pagination'=>array(
                    'pageSize'=>20,
                ),
            ));
            $this->render('index',array(
                'dataProvider'=>$dataProvider,
            ));
        }*/

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Review('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Review']))
            $model->attributes = $_GET['Review'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionTogglePub()
    {

        /*if(!Yii::app()->user->checkAccess('administrator')){
            echo json_encode(array('error'=>true,'msg'=>'Вам запрещено данное действие'));
            return;
        }	*/
        //else{

        if (is_numeric(@$_GET['id'])) {
            $model = Review::model()->findbyPk($_GET['id']);
            if ($model) {
                if ($model->visible) {
                    $model->visible = 0;
                } else {
                    $model->visible = 1;
                }


                if ($model->save()) {
                    echo json_encode(array('error' => false, 'msg' => 'Операция успешно завершена!', 'pub' => $model->visible));
                }


            } else {
                echo json_encode(array('error' => true, 'msg' => 'Не найдена новость')); //не найдена запись в бд
            }

        }


        return;
        //}
    }

    public function getPartnerId()
    {
        // return $this->_partner->id;
        return false;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Review the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Review::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Review $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'review-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}

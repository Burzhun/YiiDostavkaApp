<?php

class PartnerRayonController extends Controller
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
        $condition = "1=1";
        if (isset($_GET['city_id'])) {
            $id = (int)$_GET['city_id'];
            $condition = "city_id=" . $id;
        }
        $model = new CActiveDataProvider('PartnerRayon', array(
            'criteria' => array(
                'condition' => $condition,
            ),

            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        $this->render('index', array('model' => $model));
    }

    public function actionCreate()
    {
        $model = new PartnerRayon();
        if (isset($_POST['PartnerRayon'])) {
            $model->attributes = $_POST['PartnerRayon'];

            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            /*'breadcrumbs'=>$breadcrumbs,
            'h1'=>$h1,*/
        ));
    }

    public function actionUpdate()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $model = $this->loadModel($id);
            if (isset($_POST['PartnerRayon'])) {
                $model->attributes = $_POST['PartnerRayon'];

                if ($model->save()) {
                    $this->redirect(array('index'));
                }
            }
            $this->render('update', array(
                'model' => $model,
                /*'breadcrumbs'=>$breadcrumbs,
                'h1'=>$h1,*/
            ));
        }

    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id)
    {
        $model = PartnerRayon::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
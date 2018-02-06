<?php

class RayonController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array(User::PARTNER),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }
    private $_model;

    public function actionIndex()
    {
        $model = $this->loadPartner();
        $breadcrumbs = array($model->name => array('/partner/rayon'), 'Районы доставки');
        $h1 = $model->name;
        $partner = new CActiveDataProvider('PartnerRayon', array(
            'criteria' => array(
                'condition' => 'partner_id=' . $model->id,
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        $this->render('index', array('model' => $model, 'partner' => $partner, 'h1' => $h1, 'breadcrumbs' => $breadcrumbs));
    }

    public function actionUpdate()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $partner_rayon = $this->loadModel($id);

            if (isset($_POST['PartnerRayon'])) {
                $partner_rayon->attributes = $_POST['PartnerRayon'];

                if ($partner_rayon->save()) {
                    $this->redirect(array('index'));
                }
            }
            $this->render('update', array(
                'partner_rayon' => $partner_rayon,
                /*'breadcrumbs'=>$breadcrumbs,
                'h1'=>$h1,*/
            ));
        }

    }

    public function actionCreate()
    {
        $model = $this->loadPartner();
        $breadcrumbs = array($model->name => array('/partner/rayon'), 'Районы доставки');
        $h1 = $model->name;
        $partner_rayon = new PartnerRayon;
        if (isset($_POST['PartnerRayon'])) {
            $partner_rayon->partner_id = $model->id;
            $partner_rayon->attributes = $_POST['PartnerRayon'];

            if ($partner_rayon->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'partner_rayon' => $partner_rayon,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
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

    public function loadPartner()
    {
        if ($this->_model === null) {
            if (Yii::app()->user->id)
                $this->_model = User::model()->findByPk(Yii::app()->user->id)->partner;//Yii::app()->controller->module->user();
            if ($this->_model === null)
                $this->redirect(Yii::app()->controller->module->loginUrl);
        }
        return $this->_model;
    }

    public function loadModel($id)
    {
        $model = PartnerRayon::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
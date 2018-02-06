<?php


class PromoController extends Controller
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
        $model = new Promo('search');
        $model->unsetAttributes();
        if (isset($_GET['Text']))
            $model->attributes = $_GET['Text'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionCreate()
    {
        /*$breadcrumbs = array('Домены'=>array('/admin/domain'),$model->name ? $model->name : "Создание домена");
        $h1 = $model->name ? $model->name : "Создание домена";*/
        $model = new Promo;
        if (isset($_POST['Promo'])) {
            $model->attributes = $_POST['Promo'];

            if ($model->save()) {
                if(isset($_POST['Promo']['partners'])){
                    if($_POST['Promo']['partners']!=''){
                        $array=explode(',',$_POST['Promo']['partners']);
                        foreach($array as $t){
                            $partner=Partner::model()->find("name='".$t."'");
                            $sql="insert into tbl_promo_partner(promo_id,partner_id) values({$model->id},{$partner->id})";
                            //echo $sql;
                            Yii::app()->db->createCommand($sql)->query();
                        }
                    }

                }
                $this->redirect(array('index'));
            }
        }
        $this->render('create', array(
            'model' => $model,
            /*'breadcrumbs'=>$breadcrumbs,
            'h1'=>$h1,*/
        ));
    }

    public function actionUpdate($id)
    {
        /*$breadcrumbs = array('Домены'=>array('/admin/Text'),$model->name ? $model->name : "Изменение домена");
        $h1 = $model->name ? $model->name : "Изменение домена";*/

        $model = $this->loadModel($id);
        if (isset($_POST['Promo'])) {
            $model->attributes = $_POST['Promo'];
            if ($model->save()) {
                Yii::app()->db->createCommand("delete from tbl_promo_partner where promo_id=".$id)->query();
                if(isset($_POST['Promo']['partners'])){
                    if($_POST['Promo']['partners']!=''){
                        $array=explode(',',$_POST['Promo']['partners']);
                        foreach($array as $t){
                            $partner=Partner::model()->find("name='".$t."'");
                            $sql="insert into tbl_promo_partner(promo_id,partner_id) values({$id},{$partner->id})";
                            //echo $sql;
                            Yii::app()->db->createCommand($sql)->query();
                        }
                    }

                }
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array(
            'model' => $model,
            /*'breadcrumbs'=>$breadcrumbs,
            'h1'=>$h1,*/
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


    public function loadModel($id)
    {
        $model = Promo::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
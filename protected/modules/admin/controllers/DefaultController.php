<?php

class DefaultController extends Controller
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
                'roles' => array(User::ADMIN,USER::OPERATOR),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $today = date("Y-m-d 00:00:00");
        $week = date("Y-m-d 00:00:00", strtotime('-1 week'));
        $month = date("Y-m-d 00:00:00", strtotime('-1 month'));

        $orders = array();
        $visits = array();
        $newUser = array();

        $orders['today'] = Order::model()->count(array('condition' => "date>='" . $today . "' AND status='Доставлен'"));
        $orders['week'] = Order::model()->count(array('condition' => "date>='" . $week . "' AND status='Доставлен'"));
        $orders['month'] = Order::model()->count(array('condition' => "date>='" . $month . "' AND status='Доставлен'"));

        $visits['today'] = User::model()->count(array('condition' => "last_visit>='" . $today . "'"));
        $visits['week'] = User::model()->count(array('condition' => "last_visit>='" . $week . "'"));
        $visits['month'] = User::model()->count(array('condition' => "last_visit>='" . $month . "'"));

        $newUser['today'] = User::model()->count(array('condition' => "reg_date>='" . $today . "'"));
        $newUser['week'] = User::model()->count(array('condition' => "reg_date>='" . $week . "'"));
        $newUser['month'] = User::model()->count(array('condition' => "reg_date>='" . $month . "'"));

        //$actions = Action::model()->findAll();
        $model = new Action('search');
        $model->unsetAttributes();
        if (isset($_GET['Action']))
            $model->attributes = $_GET['Action'];

        $this->render('index', array(
            'orders' => $orders,
            'visits' => $visits,
            'newUser' => $newUser,
            'model' => $model,
        ));
    }

    public function actionOrderComments()
    {
        $dataProvider = new CActiveDataProvider(Order, array(
            'criteria' => array(
                'condition' => 'info <> ""',
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
        $this->render('orderComments', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionOrderCancels()
    {
        $dataProvider = new CActiveDataProvider(Order, array(
            'criteria' => array(
                'condition' => 'status="Отменен"',
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
        $this->render('orderCancels', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionBanned(){
        if(isset($_POST['cookie_user_id'])){
            $sql="insert into tbl_banned(cookie_user_id) values ('".$_POST['cookie_user_id']."')";
            Yii::app()->db->createCommand($sql)->query();
        }
        $sql="select * from tbl_banned";
        $dataProvider= new CSqlDataProvider($sql,array(
            'pagination' =>array(
                'pagesize' => 30
            )
        ));
        $this->render('banned', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionErrors(){
        $this->render('errors', array(
        ));
    }

    public function actionCancel_reasons(){
        if(isset($_GET['text'])){
            $cp=new CancelReason();
            $cp->name=$_GET['text'];
            $cp->save();
        }
        $data=new CActiveDataProvider('CancelReason');
        $this->render('cancel_reason',array(
            'dataProvider'=>$data
        ));
    }
    public function actionUpdateAjaxReason()
    {
        $es = new EditableSaver('CancelReason');
        $es->update();
    }

    public function actionSeo(){
        $model = new Seo('search');
        $model->unsetAttributes();
        if (isset($_GET['Seo']))
            $model->attributes = $_GET['Seo'];
        if (isset($_GET['city_id']))
            $model->city_id = $_GET['city_id'];
        else
            $model->city_id=1;




        $this->render('seo', array(
            'model' => $model,
        ));
    }
    public function actionSeo_add(){
        $model=new Seo();
        if(isset($_POST['Seo'])){
            $model->attributes=$_POST['Seo'];
            if($model->save()){
                if($model->city_id!=1){
                    $g="?city_id=".$model->city_id;
                }
                $this->redirect(array('/admin/default/seo'.$g));
            }
        }
        $this->render('seo_add', array(
            'model' => $model,
        ));
    }

    public function actionUpdateSeoAjax()
    {
        $es = new EditableSaver('seo');
        $es->update();
    }
}
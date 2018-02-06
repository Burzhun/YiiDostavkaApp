<?php

class UserController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column2';


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
                'roles' => array(User::ADMIN,User::OPERATOR),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new User;

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
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

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionProfile($id)
    {
        $model = User::model()->findByPk($id);
        if(isset($_GET['bonus_minus'])){
            $n=(int)$_GET['bonus_minus'];
            if($n>0){
                UserBonus::takeBonusByAdmin($id,$n);
            }
        }
        if (isset($_POST['User'])) {

            $oldPass = $model->pass;
            $model->attributes = $_POST['User'];

            $model->pass = empty($_POST['User']['pass']) ? $oldPass : md5($_POST['User']['pass']);
            if ($model->save()) {
                if (Yii::app()->user->name == 'admin' || Yii::app()->user->id == 989) {
                    if (isset($_POST['User']['role'])) {
                        $role = $_POST['User']['role'];
                        $sql = "update tbl_users set role='{$role}' where id={$model->id}";
                        Yii::app()->db->createCommand($sql)->query();
                    }
                }
                //$this->redirect(array('user/id/'.$id.'/profile/'));
            } else {
                print_r($model->getErrors());
                exit;
            }

        }

        $breadcrumbs = array('Пользователи' => array('/admin/user'), $model->name => '/admin/user/id/' . $model->id . '/orders/', 'Профиль');
        $h1 = $model->name;

        $this->render('profile', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionOrders($id)
    {
        $model = User::model()->findByPk($id);
        $breadcrumbs = array('Пользователи' => array('/admin/user'), $model->name => '/admin/user/id/' . $model->id . '/orders/', 'Заказы');
        $h1 = $model->name;

        $order_model = new Order('search');
        $order_model->unsetAttributes();

        if (isset($_GET['Order']))
            $order_model->attributes = $_GET['Order'];

        $this->render('orders', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'order_model' => $order_model,
        ));
    }

    public function actionOrdersView($id, $actionId)
    {
        $model = User::model()->findByPk($id);
        $breadcrumbs = array('Пользователи' => array('/admin/user'), $model->name => '/admin/user/id/' . $model->id . '/orders/', 'Заказы' => '/admin/user/id/' . $model->id . '/orders/', 'Заказ #' . $actionId);
        $h1 = $model->name . ' - Заказ #' . $actionId;
        $order = Order::model()->findByPk($actionId);

        $order_item_model = new OrderItem('search');
        $order_item_model->unsetAttributes();

        if (isset($_GET['OrderItem']))
            $model->attributes = $_GET['OrderItem'];

        $this->render('ordersView', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'order' => $order,
            'order_item_model' => $order_item_model,
        ));
    }


    public function actionAddress($id)
    {
        $model = User::model()->findByPk($id);

        $breadcrumbs = array('Пользователи' => array('/admin/user'), $model->name => '/admin/user/id/' . $model->id . '/orders/', 'Адреса');

        $h1 = $model->name;

        $address_model = new UserAddress('search');
        $address_model->unsetAttributes();

        if (isset($_GET['UserAddress']))
            $model->attributes = $_GET['UserAddress'];

        $this->render('address/index', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'address_model' => $address_model,
        ));
    }

    public function actionAddressUpdate($id, $actionId)
    {
        $model = User::model()->findByPk($id);
        $breadcrumbs = array('Пользователи' => array('/admin/user'), $model->name => '/admin/user/id/' . $model->id . '/profile/', 'Адреса' => '/admin/user/id/' . $model->id . '/address/', 'Редактирование адреса');
        $h1 = "Редактирование адреса";
        $address_model = UserAddress::model()->findByPk($actionId);

        if (isset($_POST['UserAddress'])) {
            $address_model->attributes = $_POST['UserAddress'];

            $address_model->city_id = $_POST['City']['id'];
            if ($address_model->save())
                $this->redirect(array('user/id/' . $id . '/address/'));
        }

        $this->render('address/update', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'address_model' => $address_model,
        ));
    }

    public function actionAddressDelete($id, $actionId)
    {
        $address_model = UserAddress::model()->findByPk($actionId);
        $address_model->delete();
        $this->redirect(array('user/id/' . $id . '/address/'));
    }

    public function actionBonus($id){
        $user_bonus = new UserBonus('search');
        $user_bonus->user_id = $id;
        $this->render('bonus',array(
            'user_bonus' => $user_bonus,
            'user_id'=>$id
        ));
    }

    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}

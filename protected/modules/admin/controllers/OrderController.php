<?php

class OrderController extends Controller
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
                'roles' => array(User::ADMIN,User::OPERATOR),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
    {
        $model = new Order('search');
        $model->unsetAttributes();
        $model->domain_id=$this->domain->id;

        if (isset($_GET['Order']))
            $model->attributes = $_GET['Order'];
        $this->render('index', array(
            'order_model' => $model,
        ));
    }


    public function actionView($id)
    {
        $order = Order::model()->findByPk($id);
        $order_item_model = new OrderItem('search');

        if (isset($_POST['Order'])) {
            $order->status = $_POST['Order']['status'];
            $order->save();
        }

        $this->render('ordersView', array(
            'order' => $order,
            'order_item_model' => $order_item_model,
        ));
    }


    public function actionInfo($id)
    {
        $model = Partner::model()->findByPk($id);

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name);

        $h1 = $model->name;


        if (isset($_POST['Partner'])) {
            $model->attributes = $_POST['Partner'];
            $img_property = CUploadedFile::getInstance($model, 'img');

            if ($model->save()) {
                if (!empty($_FILES['Partner']['name']['img']))
                    ZHtml::imgSave($model, $img_property, 'partner', 500, 500, 250, 250);


                $this->redirect(array('partner/id/' . $id . '/info/'));
            }
        }

        $this->render('info', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionMenu($id)
    {
        $model = Partner::model()->findByPk($id);
        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/profile/', 'Меню');
        $h1 = $model->name;

        $this->render('orders', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionOrders($id)
    {
        $model = Partner::model()->findByPk($id);
        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/profile/', 'Заказы');
        $h1 = $model->name;

        $order_model = new Order('search');
        $order_model->unsetAttributes();

        if (isset($_GET['Order']))
            $model->attributes = $_GET['Order'];

        $this->render('orders', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
            'order_model' => $order_model,
        ));
    }

    public function actionOrdersView($id, $actionId)
    {
        $model = Partner::model()->findByPk($id);
        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $model->id . '/profile/', 'Заказы' => '/admin/partner/id/' . $model->id . '/orders/', 'Заказ #' . $actionId);
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

    public function actionProfile($id)
    {
        $model = Partner::model()->findByPk($id);

        if (isset($_POST['User'])) {
            $user_model = User::model()->findByPk($model->user->id);
            $user_model->attributes = $_POST['User'];
            if ($user_model->save() && !isset($_POST['Partner']))
                $this->redirect(array('partner/id/' . $id . '/profile/'));
        }

        if (isset($_POST['Partner'])) {
            $model->attributes = $_POST['Partner'];
            if ($model->save())
                $this->redirect(array('partner/id/' . $id . '/profile/'));
        }

        $breadcrumbs = array('Партнеры' => array('/admin/partner'), $model->name => '/admin/partner/id/' . $id . '/info/', 'Профиль');

        $h1 = $model->name;

        $this->render('profile', array(
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionAddPartner()
    {
        $partnerModel = new Partner();

        $userModel = new User();

        if (!empty($_POST)) {
            if (isset($_POST['User'])) {
                $userModel->attributes = $_POST['User'];
                if ($userModel->save() && !isset($_POST['Partner']))
                    $this->redirect(array('index'));
            }

            if (isset($_POST['Partner'])) {
                $partnerModel->attributes = $_POST['Partner'];
                $partnerModel->tname=mb_strtolower($partnerModel->tname);
                $partnerModel->user_id = $userModel->id;
                $img_property = CUploadedFile::getInstance($partnerModel, 'img');

                if ($partnerModel->save()) {
                    if (!empty($_FILES['Partner']['name']['img']))
                        ZHtml::imgSave($partnerModel, $img_property, 'partner', 500, 500, 250, 250);
                    $userModel->partner_id = $partnerModel->id;
                    $userModel->save();
                    $this->redirect(array('index'));
                }
            }
        }

        $this->render('addpartner', array(
            'partnerModel' => $partnerModel,
            'userModel' => $userModel,
        ));
    }

    public function actionChangeStatus($id = '', $status = '')
    {
        switch ($status) {
            case 1:
                $status = Order::$APPROVED_SITE;
                break;
            case 2:
                $status = Order::$APPROVED_PARTNER;
                break;
            //case 3: $status = Order::$SENT;break;
            case 4:
                $status = Order::$DELIVERED;
                break;
            case 5:
                $status = Order::$CANCELLED;
                break;
        }

        if (Yii::app()->request->isAjaxRequest && $status != Order::$APPROVED_SITE) {
            /** @var Order $order */
            $order = Order::model()->findByPk($id);
            if ($status == Order::$CANCELLED && !isset($_POST['reason'])) {
                exit;
            }
            if (isset($_POST['reason'])&&isset($_POST['reason_text'])) {
                if ($_POST['reason'] == count(Order::getReasons())+1) {
                    $order->cancel_reason = $_POST['reason'];
                    $order->cancel_reason_text = $_POST['reason_text'];
                } else {
                    $order->cancel_reason = $_POST['reason'];
                }
            }
            $order->status = $status;
            $order->save(false);
            Yii::app()->end();
        }

        Yii::app()->end();
    }

    public function actionAddOrderComment(){
        if(isset($_POST['id'])&&isset($_POST['text'])){
            $id=$_POST['id'];
            $text=$_POST['text'];
            $order=Order::model()->findByPk($id);
            if(!$order->comment){
                $order->comment=$text;
                $order->save();
            }
        }
    }

    public function actionTest()
    {
        $this->render('test');
    }


}
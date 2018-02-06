<?php

class StatisticController extends Controller
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
        $this->redirect('/partner/statistic/orders');
    }

    public function bitweenDate()
    {
        $start_day = "";
        $finish_day = "";

        $lastMonth = (int)date("m") - 1;
        $last3Month = (int)date("m") - 3;
        $last6Month = (int)date("m") - 6;
        $lastYear = (int)date("Y") - 1;


        if ($lastMonth < 1) {
            $lastMonth = strtotime(date("01-" . $lastMonth . "-" . $lastYear));
        } else {
            $lastMonth = strtotime(date("01-" . $lastMonth . "-Y"));
        }

        if ($last3Month < 1) {
            $last3Month = strtotime(date("01-" . (12 - $last3Month) . "-" . $lastYear));
        } else {
            $last3Month = strtotime(date("01-" . $last3Month . "-Y"));
        }

        if ($last6Month < 1) {
            $last6Month = strtotime(date("01-" . (12 - $last6Month) . "-" . $lastYear));
        } else {
            $last6Month = strtotime(date("01-" . $last6Month . "-Y"));
        }

        if (!empty($_GET['period'])) {
            Yii::app()->session['period'] = $_GET['period'];
        }

        if (isset(Yii::app()->session['period'])) {
            $period = Yii::app()->session['period'];
        } else {
            $period = 1;
        }

        switch ($period) {
            case 1:
                $start_day = strtotime(date("01-m-Y"));//за текущий месяц
                $finish_day = time();
                break;
            case 2:
                $start_day = $lastMonth;//за прошлый месяц
                $finish_day = strtotime(date("01-m-Y"));
                break;
            case 3:
                $start_day = $last3Month;//за 3 месяца
                $finish_day = time();
                break;
            case 4:
                $start_day = $last6Month;//за пол года
                $finish_day = time();
                break;
            case 5:
                $start_day = strtotime(date("01-m-" . $lastYear));//за год
                $finish_day = time();
                break;
            case 6:
                $start_day = 0;//за все время
                $finish_day = time();
                break;
        }

        return "date > '" . date('Y-m-d H:i:s', $start_day) . "' AND date < '" . date('Y-m-d H:i:s', $finish_day) . "'";
    }

    public function actionOrders()
    {
        $model = $this->loadPartner();
        $breadcrumbs = array($model->name => array('/partner/statistic'), 'Статистика');
        $h1 = $model->name;

        $bitweenDate = $this->bitweenDate();
        $connection = Yii::app()->db;

        $sql = "SELECT date, COUNT( * ) AS amount, DATE(date) AS day
		FROM  `tbl_orders`
		WHERE " . $bitweenDate . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "'
		GROUP BY DAY(day) ORDER BY  `day` DESC
		LIMIT 90";

        //$result = $connection->cache(40000)->createCommand($sql)->queryAll();
        $result = $connection->createCommand($sql)->queryAll();

        $sql = "SELECT date, SUM((SELECT SUM(total_price) FROM `tbl_order_items` WHERE order_id = `tbl_orders`.id)) AS amount, DATE(date) AS day
		FROM  `tbl_orders`
		WHERE " . $bitweenDate . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "'
		GROUP BY DAY(day) ORDER BY  `date` DESC
		LIMIT 90";
        //$resultSum = $connection->cache(40000)->createCommand($sql)->queryAll();
        $resultSum = $connection->createCommand($sql)->queryAll();

        $this->render('order', array(
            'result' => $result,
            'resultSum' => $resultSum,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionGoods()
    {
        $model = $this->loadPartner();
        $breadcrumbs = array($model->name => array('/partner/statistic'), 'Статистика');
        $h1 = $model->name;

        $bitweenDate = $this->bitweenDate();

        $sql = "SELECT *, SUM(goods_id) AS sum_orders, SUM(`tbl_order_items`.quantity) AS sum_goods
		FROM  `tbl_order_items`
		INNER JOIN tbl_orders ON `tbl_order_items`.order_id = `tbl_orders`.Id
		WHERE " . $bitweenDate . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "'
		GROUP BY goods_id
		ORDER BY `sum_goods` DESC ";

        $count = Yii::app()->db->createCommand($sql)->queryColumn();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => count($count),
            /*'sort'=>array(
                'attributes'=>array(
                    'id', 'username', 'email',
                ),
            ),*/
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));

        /*$connection=Yii::app()->db;
        $result=$connection->createCommand($sql)->queryAll();*/

        $this->render('goods', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
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
}
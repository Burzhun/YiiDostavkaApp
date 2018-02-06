<?php

class StatisticsController extends Controller
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
    public function actionDevice()
    {
        if (isset($_GET['partner_id'])) {
            $partner_id = $_GET['partner_id'];
            $period_url = "/admin/statistics/device?partner_id=" . $_GET['partner_id'] . "&";
        } else {
            $partner_id = '';
            $period_url = "/admin/statistics/device?";
        }

        $condition2 = "";


        $url_partner = "/admin/statistics/device?";
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition2 .= " and unix_timestamp('{$_GET['from']}') < unix_timestamp(date) and unix_timestamp('" . $_GET['to'] . "')+86400>unix_timestamp(date)";
            $url_partner .= "from={$_GET['from']}&to={$_GET['to']}&";
        } else {
            if (isset($_GET['from'])) {
                $condition2 .= " and unix_timestamp('{$_GET['from']}') < unix_timestamp(date)";
                $url_partner .= "from={$_GET['from']}&";
            }
            if (isset($_GET['to'])) {
                $condition2 .= " and unix_timestamp('" . $_GET['to'] . "')+86400>unix_timestamp(date)";
                $url_partner .= "to={$_GET['to']}&";
            }
        }
        $stat = Order::GetStatistics($partner_id, $condition2,$this->domain->id);
        $partners = Partner::model()->findAll();

        $this->render('index', array(
                'stat' => $stat,
                'partners' => $partners,
                'partner_url' => $url_partner,
                'period_url' => $period_url
            )
        );
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

        return "(date > '" . date('Y-m-d H:i:s', $start_day) . "' AND date < '" . date('Y-m-d H:i:s', $finish_day) . "')";
    }

    public function actionMap()
    {
        $breadcrumbs = array(array('/admin/statistics/map'), 'Карта заказов');
        $h1 = "Карта заказов";

        $map_city_id = $_GET['map_city'] ? $_GET['map_city'] : ((int)Yii::app()->session['map_city'] ? (int)Yii::app()->session['map_city'] : 1);
        Yii::app()->session['map_city'] = (int)$map_city_id;
        $period = $_GET['period'] ? $_GET['period'] : ((int)Yii::app()->session['period'] ? (int)Yii::app()->session['period'] : 1);
        Yii::app()->session['period'] = (int)$period;

        $between_Date = $this->bitweenDate();
        $connection = Yii::app()->db;
        $cities = City::getCityArray();
        $sql = "SELECT *
        FROM  tbl_orders
        WHERE " . $between_Date . "  AND status = '" . Order::$DELIVERED . "' and city='" . City::model()->findByPk($map_city_id)->name . "' limit 180";

        $orders = $connection->createCommand($sql)->queryAll();
        $cities_coord = array();
        $cities_coord[1] = '42.989324,47.505676';
        $cities_coord[2] = '42.891594,47.636692';
        $cities_coord[3] = '40.379256,49.836449';
        $coords = $cities_coord[$map_city_id];
        $this->render('map', array(
            'orders' => $orders,
            'city_coords' => $coords,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionPayment()
    {

        $breadcrumbs = array('История платежей');
        $condition2 = "1=1";

        $url_partner = "/admin/statistics/payment?";
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition2 .= " and unix_timestamp('{$_GET['from']}') < date and unix_timestamp('" . $_GET['to'] . "')+86400>date";
            $url_partner .= "from={$_GET['from']}&to={$_GET['to']}&";
        } else {
            if (isset($_GET['from'])) {
                $condition2 .= " and unix_timestamp('{$_GET['from']}') < date";
                $url_partner .= "from={$_GET['from']}&";
            }
            if (isset($_GET['to'])) {
                $condition2 .= " and unix_timestamp('" . $_GET['to'] . "')+86400>date";
                $url_partner .= "to={$_GET['to']}&";
            }
        }
        $url_date = "/admin/statistics/payment?";
        if (isset($_GET['partner_id'])) {
            $condition2 .= " and partner_id=" . $_GET['partner_id'];
            $url_date = "/admin/statistics/payment?partner_id=" . $_GET['partner_id'] . "&";
        }
        $data = new CActiveDataProvider('Payment_history', array(
            'criteria' => array(
                'condition' => $condition2,
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));

        $partners = Partner::model()->findAll();
        $this->render('payment', array(
            'data' => $data,
            'breadcrumbs' => $breadcrumbs,
            'partners' => $partners,
            'url_date' => $url_date,
            'url_partner' => $url_partner,
            'type' => '',
        ));
    }

    public function actionPayment2()
    {

        $breadcrumbs = array('История платежей');
        $condition2 = "1=1";

        $url_partner = "/admin/statistics/payment2?";
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition2 .= " and unix_timestamp('{$_GET['from']}') < date and unix_timestamp('" . $_GET['to'] . "')+86400>date";
            $url_partner .= "from={$_GET['from']}&to={$_GET['to']}&";
        } else {
            if (isset($_GET['from'])) {
                $condition2 .= " and unix_timestamp('{$_GET['from']}') < date";
                $url_partner .= "from={$_GET['from']}&";
            }
            if (isset($_GET['to'])) {
                $condition2 .= " and unix_timestamp('" . $_GET['to'] . "')+86400>date";
                $url_partner .= "to={$_GET['to']}&";
            }
        }
        $url_date = "/admin/statistics/payment2?";
        if (isset($_GET['partner_id'])) {
            $condition2 .= " and partner_id=" . $_GET['partner_id'];
            $url_date = "/admin/statistics/payment2?partner_id=" . $_GET['partner_id'] . "&";
        }
        $data = new CActiveDataProvider('Payment_history', array(
            'criteria' => array(
                'condition' => 'balance_before<balance_after and '.$condition2,
                'order' => 'date DESC',
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));

        $partners = Partner::model()->findAll();
        $this->render('payment', array(
            'data' => $data,
            'breadcrumbs' => $breadcrumbs,
            'partners' => $partners,
            'url_date' => $url_date,
            'url_partner' => $url_partner,
            'type'=>2
        ));
    }

    public function actionUsers()
    {
        $query = "select count(*) as count from tbl_users";
        $users_count = Yii::app()->db->createCommand($query)->queryAll()[0]['count'];
        $condition = "";
        $condition2 = "";
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition = " '" . $_GET['from'] . "' < reg_date and '" . $_GET['to'] . "'>reg_date";
            $condition2 = " '" . $_GET['from'] . " 00:00:00' < last_visit and '" . $_GET['to'] . " 23:59:59'>last_visit";
        } else {
            if (isset($_GET['from'])) {
                $condition = "'" . $_GET['from'] . "' < reg_date";
                $condition2 = " '" . $_GET['from'] . " 00:00:00' < last_visit";
            }
            if (isset($_GET['to'])) {
                $condition = "'" . $_GET['to'] . "'>reg_date";
                $condition = "'" . $_GET['to'] . " 23:59:59'>last_visit";
            }
        }
        if ($condition != '') {
            $condition = ' where ' . $condition;
            $condition2 = ' where ' . $condition2;
        }
        $query = "select count(*) as count from tbl_users " . $condition;
        $users_count2 = Yii::app()->db->createCommand($query)->queryAll()[0]['count'];

        $query = "select count(id) as count from tbl_users" . $condition2;
        $active_users = Yii::app()->db->createCommand($query)->queryAll()[0]['count'];


        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];
        $this->render('users', array(
            'users_count' => $users_count,
            'users_count2' => $users_count2,
            'model' => $model,
            'active_users' => $active_users
        ));
    }

    public function actionPartners2()
    {
        $condition = "";
        $condition2 = "1=1 ";
        $url_partner = "/admin/statistics/partners?";
        $url = '?';
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition = " '" . $_GET['from'] . "' < date and '" . $_GET['to'] . "'>reg_date";
            $condition2 = " '" . $_GET['from'] . " 00:00:00' < date and '" . $_GET['to'] . " 23:59:59'>date";
            $url_partner .= "from={$_GET['from']}&to={$_GET['to']}&";
            $url .= "from={$_GET['from']}&to={$_GET['to']}&";
        } else {
            if (isset($_GET['from'])) {
                $condition = "'" . $_GET['from'] . "' < reg_date";
                $condition2 = " '" . $_GET['from'] . " 00:00:00' < date";
                $url_partner .= "from={$_GET['from']}&";
                $url .= "from={$_GET['from']}&";
            }
            if (isset($_GET['to'])) {
                $condition = "'" . $_GET['to'] . "'>reg_date";
                $condition2 = "'" . $_GET['to'] . " 23:59:59'>date";
                $url_partner .= "to={$_GET['to']}&";
                $url .= "to={$_GET['to']}&";
            }
        }
        $between_Date = $this->bitweenDate();
        $connection = Yii::app()->db;
        $condition2 .= " and ";
        $url_date = "/admin/statistics/partners?";
        if (isset($_GET['partner_id'])) {
            $condition2 .= " partners_id=" . $_GET['partner_id'] . " and ";
            $url_date = "/admin/statistics/partners?partner_id=" . $_GET['partner_id'] . "&";
            $url .= "partners_id=" . $_GET['partner_id'];
        }

        $sql = "SELECT date, COUNT( * ) AS amount, DATE(date) AS day, order_source
        FROM  `tbl_orders`
        WHERE " . $condition2 . " status = '" . Order::$DELIVERED . "'
        GROUP BY day ORDER BY  `day` DESC ";

        //exit;
        $result = $connection->cache(40000)->createCommand($sql)->queryAll();

        $sql = "SELECT date, SUM((SELECT SUM(total_price) FROM `tbl_order_items` WHERE order_id = `tbl_orders`.id)) AS amount, DATE(date) AS day
        FROM  `tbl_orders`
        WHERE " . $condition2 . "status = '" . Order::$DELIVERED . "' GROUP BY day ORDER BY  `date` DESC";
        $resultSum = $connection->cache(40000)->createCommand($sql)->queryAll();
        $partners = Partner::model()->findAll();
        if (isset($_GET['map_city'])) {
            Yii::app()->session['map_city'] = $_GET['map_city'];
        } else {
            if (!isset(Yii::app()->session['map_city'])) {
                Yii::app()->session['map_city'] = 1;
            }
        }
        $map_city_id = (int)Yii::app()->session['map_city'];
        $cities = City::getCityArray();
        $sql = "SELECT *
        FROM  tbl_orders
        WHERE " . $condition2 . " status = '" . Order::$DELIVERED . "' and city='" . $cities[$map_city_id - 1] . "' limit 480";


        $orders = $connection->cache(40000)->createCommand($sql)->queryAll();
        $cities_coord = array();
        $cities_coord[1] = '42.989324,47.505676';
        $cities_coord[2] = '42.891594,47.636692';
        $cities_coord[3] = '40.379256,49.836449';
        $coords = $cities_coord[$map_city_id];

        $this->render('partners', array(
            'result' => $result,
            'resultSum' => $resultSum,
            'orders' => $orders,
            'city_coords' => $coords,
            'partners' => $partners,
            'url_date' => $url_date,
            'url_partner' => $url_partner,
            'url' => $url,
            'condition2' => $condition2,
        ));
    }
    public function actionPartners(){
        $partner=new Partner('search');
        $partner->unsetAttributes();
        $partner->status=1;
        $partner->self_status=1;
        $url_date = "/admin/statistics/partners?";
        if (isset($_GET['Partner']))
            $partner->attributes = $_GET['Partner'];

        $this->render('partners2', array(
            'model' => $partner,
            'url_date' => $url_date,
        ));
    }
    public function actionPartnersgoods()
    {
        $condition = ""; // @TODO не используется, можно удалить?
        $condition2 = "1=1 ";
        $url_partner = "/admin/statistics/partnersgoods?";
        $url = '?';
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $condition = " '" . $_GET['from'] . "' < date and '" . $_GET['to'] . "'>reg_date";
            $condition2 = " '" . $_GET['from'] . " 00:00:00' < date and '" . $_GET['to'] . " 23:59:59'>date";
            $url_partner .= "from={$_GET['from']}&to={$_GET['to']}&";
            $url .= "from={$_GET['from']}&to={$_GET['to']}&";
        } else {
            if (isset($_GET['from'])) {
                $condition = "'" . $_GET['from'] . "' < reg_date";
                $condition2 = " '" . $_GET['from'] . " 00:00:00' < date";
                $url_partner .= "from={$_GET['from']}&";
                $url .= "from={$_GET['from']}&";
            }
            if (isset($_GET['to'])) {
                $condition = "'" . $_GET['to'] . "'>reg_date";
                $condition2 = "'" . $_GET['to'] . " 23:59:59'>date";
                $url_partner .= "to={$_GET['to']}&";
                $url .= "to={$_GET['to']}&";
            }
        }
        $connection = Yii::app()->db;
        $condition2 .= " and ";
        $url_date = "/admin/statistics/partnersgoods?";
        if (isset($_GET['partner_id'])) {
            $condition2 .= "partners_id=" . $_GET['partner_id'] . " and ";
            $url_date = "/admin/statistics/partnersgoods?partner_id=" . $_GET['partner_id'] . "&";
            $url .= "partners_id=" . $_GET['partner_id'];
        }

        $sql = "SELECT date, COUNT( * ) AS amount, DATE(date) AS day, order_source
        FROM  `tbl_orders`
        WHERE " . $condition2 . " status = '" . Order::$DELIVERED . "'
        GROUP BY day ORDER BY  `day` DESC
        LIMIT 190";
        //echo $sql;
        //exit;
        $result = $connection->cache(40000)->createCommand($sql)->queryAll();

        // @TODO не используется, можно удалить?
        $sql = "SELECT date, SUM((SELECT SUM(total_price) FROM `tbl_order_items` WHERE order_id = `tbl_orders`.id)) AS amount, DATE(date) AS day
        FROM  `tbl_orders`
        WHERE " . $condition2 . "status = '" . Order::$DELIVERED . "'
        GROUP BY day ORDER BY  `date` DESC
        LIMIT 190";

        $sql = "SELECT *, SUM(goods_id) as sum_orders, SUM(`tbl_order_items`.quantity) as sum_goods
        FROM  `tbl_order_items`
        INNER JOIN tbl_orders ON `tbl_order_items`.order_id = `tbl_orders`.Id
        WHERE " . $condition2 . " status = '" . Order::$DELIVERED . "'
        GROUP BY goods_id
        ORDER BY `sum_goods` DESC ";
        $resultSum = $connection->cache(40000)->createCommand($sql)->queryAll();
        $partners = Partner::model()->findAll();
        $count = Yii::app()->db->createCommand($sql)->queryColumn();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => count($count),
            /*'sort'=>array(
                'attributes'=>array(
                    'id', 'username', 'email',
                ),
            ),*/
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $this->render('partnersgoods', array(
            'dataProvider' => $dataProvider,
            'result' => $result,
            'resultSum' => $resultSum,
            'partners' => $partners,
            'url_date' => $url_date,
            'url_partner' => $url_partner,
            'url' => $url,
        ));
    }

    public function actionOrders()
    {
        $date_format = '%Y-%m-%d';
        if (isset($_GET['period'])) {
            if ($_GET['period'] == "day") {
                $date_format = '%Y-%m-%d';
            } elseif ($_GET['period'] == 'month') {
                $date_format = '%Y-%m';
            } elseif ($_GET['period'] == 'year') {
                $date_format = '%Y';
            }
        }

        // Партнер
        if (!empty($_GET['partner_id'])) {
            $partner_id = $_GET['partner_id'];
            $partner_sql = " AND partners_id = " . $_GET['partner_id'];
        } else {
            $partner_id = 0;
            $partner_sql = "";
        }

        // Оператор
         if (!empty($_GET['admin_id'])) {
            if($_GET['admin_id'] == 'all'){
                $admins = User::getAdmins();
                foreach ($admins as $data) {
                    $adminIds[] = $data->id;
                }
                $admin_sql = " AND t.user_id IN (" . implode(',', $adminIds) .")";
            }else{
                $admin_sql = " AND t.user_id = " . $_GET['admin_id'];
            }
        }

        if(!isset($_GET['city_id'])){
            $domain_condition=" and t.domain_id=".$this->domain->id;
        }else{
            $city=City::model()->findByPk($_GET['city_id']);
            $domain_condition=" and t.city='".$city->name."'";
        }
        if((!isset($_GET['admin_id'])||$_GET['admin_id']=='')&&!isset($_GET['partner_id'])&&!isset($_GET['city_id'])){
            if($date_format=='%Y-%m-%d'){
                $date=date('Y-m-d');
                $res=OrderStatistics::GetThisDayOrders($this->domain->id);
                $order_stat=OrderStatistics::model()->find("date='".$date."' and domain_id=".$this->domain->id);
                if(!$order_stat){
                    $order_stat=new OrderStatistics();
                    $order_stat->date=$date;
                    $order_stat->domain_id=$this->domain->id;
                }                
                $order_stat->orders_count=$res['count'];
                $order_stat->orders_sum=round($res["sum"]*10)/10;
                $order_stat->new_users=Order::GetNewUsers($date,$order_stat->domain_id, false);
                $order_stat->procent=$res['sum2'];
                $order_stat->procent2=Payment_history::Sum2($date,$order_stat->domain_id, false);
                $order_stat->accept_phone=Order::getAdminOrdersCount($date,$order_stat->domain_id, false);
                $order_stat->pc=$res['pc_count'];
                $order_stat->mobile=$res['mobile_count'];
                $order_stat->app=$res['app_count'];
                $order_stat->average=Order::averageCheck($res["count"], round($res["sum"]*10)/10);
                $order_stat->save();
                $dataProvider = new CActiveDataProvider('OrderStatistics', array(
                    'criteria' => array(
                        'order'=>isset($_GET['OrderStatistics_sort'])? '' : 'date desc',
                        'condition' => 'domain_id = ' .$this->domain->id ,
                    ),
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));
                $this->render('orders_default', array(
                    'dataProvider' => $dataProvider,
                    'named'=>true,
                    'date_format' => $date_format,
                    'partner_id' => $partner_id
                ));
            }else{
                if($date_format=='%Y-%m'){
                    $count=30;
                }else{
                    $count=365;
                }
                $sql="SELECT `id` , sum(`orders_count`) as orders_count , sum(`orders_sum`) as orders_sum ,
                      sum(`new_users`) as new_users , sum(`procent`) as procent , sum(`procent2`) as procent2 ,
                      sum(`accept_phone`) as accept_phone , sum(`pc`) as pc , sum(`mobile`) as mobile , sum(`app`) as app ,
                       sum(`average`)/{$count} as average , FROM_UNIXTIME( UNIX_TIMESTAMP( date ) , '{$date_format}' ) AS p_date
                    FROM `tbl_orders_statistics`
                    where domain_id={$this->domain->id}
                    GROUP BY p_date  order by date desc";
                    
                //print_r($data);
                $dataProvider = new CSqlDataProvider($sql, array(
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));

                $this->render('orders_default', array(
                    'dataProvider' => $dataProvider,
                    'named'=>false,
                    'date_format' => $date_format,
                    'partner_id' => $partner_id
                ));
            }

        }else{
            $sql = "SELECT `t`.id, COUNT(DISTINCT `t`.id) AS count,
                  FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '$date_format') AS p_date, t.date,sum(t.sum) AS sum1,
                 count(DISTINCT case  when t.order_source<2 then  t.id else null end ) as pc_count,
                 count(DISTINCT case  when t.order_source=2 then  t.id else null end ) as mobile_count,
                 count(DISTINCT case  when t.order_source>2 then  t.id else null end ) as app_count
                FROM `tbl_orders` `t`
                LEFT OUTER JOIN tbl_partners partners ON (partners.id=t.partners_id)
                WHERE `t`.status = '" . Order::$DELIVERED ."'".$admin_sql.$partner_sql .$domain_condition. "
                GROUP BY p_date
                ORDER BY `t`.`date` desc";


            $count = Yii::app()->db->createCommand("SELECT COUNT(*), FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '$date_format') AS p_date
                FROM `tbl_orders` `t`
                WHERE status = '" . Order::$DELIVERED  ."'" .$domain_condition. $partner_sql."
                GROUP BY p_date")->queryAll();

            $dataProvider = new CSqlDataProvider($sql, array(
                'totalItemCount' => count($count),
                'pagination' => array(
                    'pageSize' => 30,
                ),
            ));


            $this->render('orders', array(
                'dataProvider' => $dataProvider,
                'date_format' => $date_format,
                'partner_id' => $partner_id
            ));
        }


    }
    public function actionOrders2(){
        $admins = User::getAdmins();
        foreach ($admins as $data) {
            $adminIds[] = $data->id;
        }
        $date="2016-05-23";
        if(isset($_GET['date'])){
            $date=$_GET['date'];
        }
        $admin_sql = " and (t.user_id IN (" . implode(',', $adminIds) ."))";
        $sql="SELECT t.id as id, tbl_users.name as name,  DATE_FORMAT(t.date, '%d-%m-%Y') as o_date
FROM tbl_orders t
left outer JOIN tbl_users ON t.user_id=tbl_users.id
WHERE  DATE_FORMAT(t.date, '%Y-%m-%d')>='{$date}' and DATE_FORMAT(t.date, '%H') < 9 AND DATE_FORMAT(t.date, '%H') >= 0 {$admin_sql}
ORDER BY  t.date asc";
$count = Yii::app()->db->createCommand("SELECT COUNT(t.id) 
                FROM `tbl_orders` `t`
                left outer JOIN tbl_users ON t.user_id=tbl_users.id
                WHERE DATE_FORMAT(t.date, '%Y-%m-%d')>='{$date}' and DATE_FORMAT(t.date, '%H') < 9 AND DATE_FORMAT(t.date, '%H') >= 0" .$admin_sql."
                group by t.id ORDER BY t.user_id, t.date asc")->queryAll();
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => count($count),
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));


        $this->render('orders2', array(
            'dataProvider' => $dataProvider,
        ));

    }
    public function actionCancelledOrders()
    {
        $date_format = '%Y-%m-%d';

        if (isset($_GET['period'])) {
            if ($_GET['period'] == "day") {
                $date_format = '%Y-%m-%d';
            } elseif ($_GET['period'] == 'month') {
                $date_format = '%Y-%m';
            } elseif ($_GET['period'] == 'year') {
                $date_format = '%Y';
            }
        }

        if (!empty($_GET['partner_id'])) {
            $partner_sql = " AND partners_id = " . $_GET['partner_id'];
        } else {
            $partner_sql = "";
        }

        $sql = "SELECT `t`.id, COUNT(DISTINCT `t`.id) AS count,
                  FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '$date_format') AS p_date, t.date,
                   t.sum AS sum1, floor(t.sum*partners.procent_deductions/100) as sum2,

                 count(DISTINCT case  when t.order_source<2 then  t.id else null end ) as pc_count,


                 count(DISTINCT case  when t.order_source=2 then  t.id else null end ) as mobile_count,
                 count(DISTINCT case  when t.order_source>2 then  t.id else null end ) as app_count
                FROM `tbl_orders` `t`
                left outer JOIN tbl_partners partners on (partners.id=t.partners_id)

                WHERE `t`.status = '" . Order::$CANCELLED . "'" . $partner_sql . "
                GROUP BY p_date
                ORDER BY `t`.`date` DESC";


        $count = Yii::app()->db->cache(10000)->createCommand("SELECT COUNT(*), FROM_UNIXTIME(UNIX_TIMESTAMP(`date`), '$date_format') AS p_date
                FROM `tbl_orders` `t`
                WHERE status = '" . Order::$CANCELLED . "'
                GROUP BY p_date" . $partner_sql)->queryAll();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => count($count),
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));


        $this->render('cancelled_orders', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCancels()
    {
        $date_format = '%Y-%m-%d';

        if (isset($_GET['period'])) {
            if ($_GET['period'] == "day") {
                $date_format = '%Y-%m-%d';
            } elseif ($_GET['period'] == 'month') {
                $date_format = '%Y-%m';
            } elseif ($_GET['period'] == 'year') {
                $date_format = '%Y';
            }
        }

        if (!empty($_GET['partner_id'])) {
            $partner_sql = " AND partners_id = " . $_GET['partner_id'];
        } else {
            $partner_sql = "";
        }
        $sql = "SELECT `t`.id, COUNT(DISTINCT `t`.id) AS count,
                  FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '$date_format') AS p_date, t.date,
                   t.sum AS sum1, floor(t.sum*partners.procent_deductions/100) as sum2";
        $s = "";
        foreach (Order::getReasons() as $index => $reason) {
            $sql .= " , count(distinct case when t.cancel_reason='" . $reason['id'] . "' then t.id else null end) as sum_reason" . $reason['id'];
            if ($index == 0) {
                $s .= "sum_reason0";
            } else {
                $s .= "+sum_reason" . $reason['id'];
            }
        }
        $sql .= "
            FROM `tbl_orders` `t`
                left outer JOIN tbl_partners partners on (partners.id=t.partners_id)
                WHERE `t`.status = '" . Order::$CANCELLED . "' and cancel_reason<>''" . $partner_sql . "
                GROUP BY p_date
                ORDER BY `t`.`date` DESC";
        $dataProvider = new CSqlDataProvider($sql, array(
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));


        $this->render('cancels', array(
            'dataProvider' => $dataProvider,
        ));

        //echo $sql;
    }

    public function actionOrders_time(){
        $date_from=isset($_GET['date_from']) ? $_GET['date_from']." 00" : date('Y-m-d 00');
        $date_to=isset($_GET['date_to']) ? $_GET['date_to']." 23:59:59" :date('Y-m-d 23:59:59');
        $city_condition="";
        if(isset($_GET['city_id'])){
            $city=City::model()->findByPk($_GET['city_id']);
            $city_condition=" and city='".$city->name."'";
        }
        $n=round((strtotime($date_to)-strtotime($date_from.":00:00"))/86400);
        $sql="select id,FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%H') AS p_date,count(id) as count from tbl_orders
              where status<>'".Order::$CANCELLED."' and '{$date_from}'<=FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%Y-%m-%d %H')
              and '{$date_to}'>=FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%Y-%m-%d %H:%i:%s') {$city_condition}
              group by p_date order by p_date";

        $data_av=Yii::app()->db->createCommand($sql)->queryAll();
        $data2_av=array();
        foreach($data_av as $d){
            $i1=(int)$d["p_date"];
            $data2_av[$i1]=$d["count"]/$n;
        }
        for($i=0;$i<24;$i++){
            if(!isset($data2_av[$i])){
                $data2_av[$i]=0;
            }
        }
        ksort($data2_av);
        $date=date('Y-m-d 00:00:00');
        $sql="select id,FROM_UNIXTIME(UNIX_TIMESTAMP(date), '%H') AS p_date,count(id) as count from tbl_orders
              where status<>'".Order::$CANCELLED."' and '{$date}'<date {$city_condition}
              group by p_date order by p_date";
        $data=Yii::app()->db->createCommand($sql)->queryAll();
        $data2=array();
        foreach($data as $d){
            $i1=(int)$d["p_date"];
            $data2[$i1]=$d["count"];
        }
        for($i=0;$i<=date('H');$i++){
            if(!isset($data2[$i])){
                $data2[$i]=0;
            }
        }
        ksort($data2);
        //print_r($data2);
       // exit;
        //var_dump($data);
        $this->render('orders_time', array(
            'data_av' => $data2_av,
            'data' => $data2,
            'date_from' =>substr($date_from,0,10),
            'date_to'=>substr($date_to,0,10)
        ));
    }
}
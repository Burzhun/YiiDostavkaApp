<?php

class HistoryOrderController extends Controller
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
                'roles' => array(User::ADMIN),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function actionIndex()
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
                   SUM(`orderitems`.`total_price`) AS sum, floor(SUM(orderitems.total_price*partners.procent_deductions/100)) as sum2
				FROM `tbl_orders` `t`
				LEFT OUTER JOIN `tbl_order_items` `orderitems` ON (`orderitems`.`order_id`=`t`.`id`)
				left outer JOIN tbl_partners partners on (partners.id=t.partners_id)
				WHERE `t`.status = '" . Order::$DELIVERED . "'" . $partner_sql . "
				GROUP BY p_date
				ORDER BY `t`.`date` DESC";


        $count = Yii::app()->db->cache(10000)->createCommand("SELECT COUNT(*), FROM_UNIXTIME(UNIX_TIMESTAMP(`date`), '$date_format') AS p_date
				FROM `tbl_orders` `t`
				WHERE status = '" . Order::$DELIVERED . "'
				GROUP BY p_date" . $partner_sql)->queryAll();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => count($count),
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));


        $this->render('history', array(
            'dataProvider' => $dataProvider,
        ));
    }


    public function actionView($id)
    {

    }
}
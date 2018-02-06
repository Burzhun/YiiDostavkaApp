<?php

class OrdersController extends Controller
{
    private $_model;

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

    /*изменение количества товара в заказе*/
    public function actionUpdateOrderItemCount()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->request->getParam('pk');
            if ($id) {
                $orderItem = OrderItem::model()->findByPk($id);
                $quantity = Yii::app()->request->getParam('value');
                $orderItem->quantity = $quantity;
                $orderItem->total_price = $orderItem->price_for_one * $quantity;
                $orderItem->save();

                $order = Order::model()->findByPk($orderItem->order_id);
                $action = "изменил количество товара #" . $orderItem->id . " в  заказе #" . $order->id . ' на ' . $quantity;
                Action::addNewAction(Action::ORDER, $order->user ? $order->user->id : 0, $order->partner ? $order->partner->id : 0, 'Партнер (ID ' . $order->partner->id . ') ' . $action);

                echo $orderItem->total_price;
                die();
            }
        }
        die();
    }

    public function actionUpdateOrderInfo()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = $_POST['Order']['id'];
            if ($id) {
                $order = Order::model()->findByPk($id);
                $order->attributes = $_POST['Order'];
                $order->scenario = 'UpdateInfo';
                if ($order->save()) {
                    $data['success'] = 'Данные успешно сохранены';
                } else {
                    $errors='';
                    foreach ($order->errors as $value => $key) {
                        //$errors = $errors.$value2;
                        foreach ($order->errors[$value] as $value2) {
                            $errors = $errors . $value2;//TODO что за ошибка?
                        }
                    }

                    $data['errors'] = $errors;
                }
                echo CJSON::encode($data);
                die();
            }
            echo "<pre>";//TODO WTF нужно???
            exit();
        }
    }

    public function actionIndex()
    {
        $model = $this->loadPartner();


        $breadcrumbs = array($model->name => array('/partner/info'), 'Заказы');

        $h1 = "Заказы";
        $order_model = new Order('search');
        $order_model->unsetAttributes();
        if (isset($_GET['Order']))
            $order_model->attributes = $_GET['Order'];
        $this->render('index', array(
            'model' => $model,
            'order_model' => $order_model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }

    public function actionGroup()
    {
        $model = $this->loadPartner();

        $breadcrumbs = array($model->name => array('/partner/info'), 'Заказы группы');

        $h1 = "Заказы группы";

        $order_model = new Order('search');
        $order_model->unsetAttributes();
        if (isset($_GET['Order']))
            $order_model->attributes = $_GET['Order'];

        $this->render('group', array(
            'model' => $model,
            'order_model' => $order_model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }


    public function actionView($id)
    {
        $model = $this->loadPartner();

        $breadcrumbs = array($model->name => array('/partner/info'),
            'Заказы' => '/partner/orders', 'Заказ #' . $id);

        $order = Order::model()->findByPk($id);

        if ($order->partners_id != Yii::app()->user->partner) {
            $rel_partner = Relationpartner::model()->findByAttributes(array('owner_id' => $order->partners_id));
        }

        if ($order->status == Order::$APPROVED_SITE) {
            $order->status = Order::$APPROVED_PARTNER;
            $order->save(false);
        }

        if ($order->partners_id != Yii::app()->user->partner) {
            $sql = "SELECT id FROM tbl_partners WHERE ((id={$order->partners_id}) and ((user_id IN (
						SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
							SELECT user_id FROM tbl_partners WHERE id = " . Yii::app()->user->partner . "
						)
					) OR user_id IN (
						SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
							SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
								SELECT user_id FROM tbl_partners WHERE id = " . Yii::app()->user->partner . "
							)
						)
					)) OR id=" . Yii::app()->user->partner . "
					OR user_id IN (
						SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
							SELECT user_id FROM tbl_partners WHERE id = " . Yii::app()->user->partner . "
						)
					)))";
            $a = Yii::app()->db->createCommand($sql)->queryAll();
            if (!isset($a[0])) {
                $this->redirect(array('/partner/orders'));;
            }
        }
        if ($order->status == Order::$APPROVED_SITE) {
            $order->status = Order::$APPROVED_PARTNER;
            $order->save(false);
        }

        $h1 = $order->partner->name . ' - Заказ #' . $id;

        if (isset($_POST['Order'])) {
            $order->status = $_POST['Order']['status'];
            $order->save();
        }

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


    public function loadPartner()
    {
        if ($this->_model === null) {
            if (Yii::app()->user->id) {
                $this->_model = User::model()->findByPk(Yii::app()->user->id)->partner;//Yii::app()->controller->module->user();
                $user=User::model()->findByPk(Yii::app()->user->id);
                
                Yii::app()->user->setState("partner", $this->_model->id);
            }
            if ($this->_model === null)
                $this->redirect(array("/"));
        }
        return $this->_model;
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
            if ($order->status != Order::$DELIVERED) {
                if ($status == Order::$CANCELLED && !isset($_POST['reason'])) {
                    Yii::app()->end();
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
        }
        Yii::app()->end();
    }

    // @TODO проверь, нужна ли данная функция?
    public function actionCheckNewOrderForMusik()
    {
        $model = $this->loadPartner();
        $orders = Order::model()->findAll(array('condition' => "date > '" . date("Y-m-d H:i:s", time() - 60) . "' AND partners_id = " . $model->id));

        if (count($orders)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    // @TODO проверь, нужна ли данная функция?
    public function actionOverdueOrders()
    {
        $model = $this->loadPartner();
        $orders = Order::model()->findAll(array('condition' => "date < '" . date("Y-m-d H:i:s", time() - 180) . "' AND status = '" . Order::$APPROVED_SITE . "' AND partners_id = " . $model->id));

        if (count($orders)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    // @TODO проверь, нужна ли данная функция?
    public function actionCheckNewRelationOrderForMusik()
    {
        $model = $this->loadPartner();

        $sql = "SELECT id FROM tbl_partners WHERE (user_id IN (
					SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
						SELECT user_id FROM `tbl_partners` WHERE id = " . $model->id . "
					)
				) OR user_id IN (
					SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
						SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
							SELECT user_id FROM `tbl_partners` WHERE id = " . $model->id . "
						)
					)
				)) OR id=" . $model->id;
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryColumn();

        $query = "";
        foreach ($data as $key => $value) {
            $query .= " OR partners_id=" . $value;
        }

        $orders = Order::model()->findAll(array('condition' => "date>'" . date("Y-m-d H:i:s", time() - 60) . "' AND (1=2" . $query . ")"));

        if (count($orders)) {
            echo 1;
        } else {
            echo 0;
        }
    }


    public function actionChackOrders()
    {
        $lastActionId = $_POST['lastActionId'];
        $relation = $_POST['relation'];
        $partner_id = $_POST['partner_id'];
        $json = array();

        $partner_query = "";
        if ($relation) {
            $sql = "SELECT id FROM tbl_partners WHERE (user_id IN (
					SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
						SELECT user_id FROM `tbl_partners` WHERE id = " . $partner_id . "
					)
				) OR user_id IN (
					SELECT user_id FROM tbl_relation_partner WHERE owner_id IN (
						SELECT owner_id FROM tbl_relation_partner WHERE user_id IN (
							SELECT user_id FROM `tbl_partners` WHERE id = " . $partner_id . "
						)
					)
				)) OR id=" . $partner_id;
            $command = Yii::app()->db->cache(10000)->createCommand($sql);
            $data = $command->queryColumn();

            foreach ($data as $key => $value) {
                $partner_query .= " OR partners_id=" . $value;
            }
            $partner_query =  " AND (1=2 ". $partner_query .")";
        } else {
            $partner_query .= ' AND partners_id = "'.$partner_id.'"';
        }

        $conditionNewOrders = "date > '" . date("Y-m-d H:i:s", time() - 60) . "' ".$partner_query;
        $ordersNewOrders =Yii::app()->db->createCommand("select count(id) from tbl_orders where ".$conditionNewOrders)->queryScalar();

        $conditionOverdueOrders = "date<'" . date("Y-m-d H:i:s", time() - 180) . "' AND status = '" . Order::$APPROVED_SITE . "' ". $partner_query;
        $overdueOrders =Yii::app()->db->createCommand("select count(id) from tbl_orders where ".$conditionOverdueOrders)->queryScalar();

        //Проверка наличия новых заказов
        $json['haveNewOrders'] = $ordersNewOrders ? 1 : 0;
        //Проверка просроченных заказов
        $json['haveOverdueOrders'] = $overdueOrders ? 1 : 0;
        //Проверка изменения таблицы заказов
        $action = Action::model()->findAll(array('condition' => 'action = "'. Action::ORDER .'" AND id > '.$lastActionId, 'order' => 'id DESC', 'limit' => '1'));
        $json['newActions'] = count($action);
        //Передаем последнее действие по заказам
        $json['lastActionId'] = count($action) ? $action[0]->id : $lastActionId;

        echo json_encode($json);
    }


    public function actionHistory()
    {
        $model = $this->loadPartner();

        $breadcrumbs = array($model->name => array('/partner/info'), 'Заказы' => array('/partner/orders'), 'История заказов');

        $h1 = "Заказы";

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

        $partner_sql = " AND `t`.`partners_id` = " . $model->id;

        $sql = "SELECT `t`.id, COUNT(DISTINCT `t`.id) AS count, FROM_UNIXTIME(UNIX_TIMESTAMP(`t`.`date`), '$date_format') AS p_date, date, SUM(`orderitems`.`total_price`) AS sum
				FROM `tbl_orders` `t`
				LEFT OUTER JOIN `tbl_order_items` `orderitems` ON (`orderitems`.`order_id`=`t`.`id`)
				WHERE `t`.status = '" . Order::$DELIVERED . "' " . $partner_sql . "
				GROUP BY p_date
				ORDER BY `t`.`date` DESC";

        $count_sql = "SELECT COUNT(*), FROM_UNIXTIME(UNIX_TIMESTAMP(`date`), '$date_format') AS p_date
				FROM `tbl_orders` `t`
				WHERE status = '" . Order::$DELIVERED . "'" . $partner_sql . " GROUP BY p_date ";
        $count = Yii::app()->db->createCommand($count_sql)->queryAll();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => count($count),
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));

        $this->render('ordersHistory', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
            'breadcrumbs' => $breadcrumbs,
            'h1' => $h1,
        ));
    }
}
<?php

/**
 * Class Partner
 *
 * @property int id
 * @property string name
 * @property string tname
 * @property string text
 * @property string seo_text
 * @property int city_id
 * @property string address
 * @property string email_order
 * @property string phone_sms
 * @property string phone_sms2
 * @property int min_sum
 * @property int delivery_cost
 * @property string work_begin_time
 * @property string work_end_time
 * @property string delivery_duration
 * @property int day1
 * @property int day2
 * @property int day3
 * @property int day4
 * @property int day5
 * @property int day6
 * @property int day7
 * @property string img
 * @property string logo
 * @property int user_id
 * @property int balance
 * @property int status
 * @property int self_status
 * @property int vip
 * @property int vip_rest
 * @property int soon_opening
 * @property int procent_deductions
 * @property int date_change
 * @property int use_kassa
 *
 * @property Menu[] menu
 * @property Order[] orders
 * @property Message[] message
 * @property User user
 * @property City city
 * @property Specialization[] specialization
 * @property Goods[] favorite_goods
 */
class Partner extends CActiveRecord
{
    public static $deliveryDuration = array(
        '30 мин.' => 1800,
        '45 мин.' => 2700,
        '60 мин.' => 3600,
        '90 мин.' => 5400,
        '3 часа' => 10800,
        '4 часа' => 14400,
        'в течение дня' => 86400,
        '24 часа' => 86400,
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{partners}}';
    }


    public function rules()
    {
        return array(
            array('name', 'required'),
            array('city_id, min_sum,position,use_kassa, delivery_cost,free_delivery_sum,allow_bonus,bonus_procent,action, day1, day2, day3, day4, day5, day6, day7', 'numerical', 'integerOnly' => true),
            array('name,token_ios,token_android', 'length', 'max' => 255),
            array('delivery_duration', 'length', 'max' => 24),
            array('email_order,email_order2', 'email'),
            array('balance', 'type', 'type' => 'float'),
            array('img', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true, 'maxSize' => 2 * 1024 * 1024),
            //array('city_id', 'in', 'range' => City::getCityArray()),
            array('day1, day2, day3, day4, day5, day6, day7', 'in', 'range' => array(0, 1)),
            array('delivery_duration', 'in', 'range' => Partner::getDeliveryDurationList()),

            array('id, logo, sms_enabled, sms_provider, tname, text, seo_text,seo_title,seo_keywords,seo_description, address, phone_sms, phone_sms2, work_begin_time, work_end_time, user_id, status, self_status, vip, soon_opening, procent_deductions, send_email', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'menu' => array(self::HAS_MANY, 'Menu', 'partner_id'),
            'orders' => array(self::HAS_MANY, 'Order', 'partners_id'),
            'message' => array(self::HAS_MANY, 'Message', 'partner_id'),
            'user' => array(self::HAS_ONE, 'User', 'partner_id'),
            'city' => array(self::BELONGS_TO, 'City', 'city_id'),
            'specialization' => array(self::MANY_MANY, 'Specialization',
                'tbl_spec_partner(partner_id, spec_id)'),
            'promos' => array(self::MANY_MANY, 'Promo',
                'tbl_promo_partner(partner_id,promo_id)'),
            'partner_info' => array(self::HAS_MANY, 'PartnerInfo', 'partner_id'),
            'favorite_goods' => array(self::HAS_MANY, 'Goods', 'partner_id', 'condition' => 'favorite=1'),
        );
    }


    public function behaviors()
    {
        return array('ESaveRelatedBehavior' => array(
            'class' => 'application.components.behaviors.ESaveRelatedBehavior'));
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Название',
            'tname' => 'Транслит',
            'text' => 'Информация',
            'seo_text' => 'СЕО Текст',
            'city_id' => 'Город',
            'address' => 'Адрес',
            'email_order' => 'Email для получения заказов',
            'email_order2' => 'Доп. email для получения заказов',
            'phone_sms' => 'Номер для уведомления по смс',
            'phone_sms2' => 'Номер для уведомления по смс 2',
            'min_sum' => 'Минимальная сумма заказа',
            'free_delivery_sum' => 'Сумма для бесплатной доставки',
            'delivery_cost' => 'Стоимость доставки',
            'work_begin_time' => 'Начало рабочего дня',
            'work_end_time' => 'Конец рабочего дня',
            'delivery_duration' => 'Время доставки',
            'day1' => 'Пн',
            'day2' => 'Вт',
            'day3' => 'Ср',
            'day4' => 'Чт',
            'day5' => 'Пт',
            'day6' => 'Сб',
            'day7' => 'Вс',
            'img' => 'Картинка',
            'logo' => 'Лого',
            'user_id' => 'Пользователь',
            'balance' => 'Баланс',
            'status' => 'Статус',
            'self_status' => 'Скрыть себя с сайта',
            'vip' => 'Статус VIP',
            'vip_rest' => 'Статус VIP Ресторана',
            'soon_opening' => 'Скоро открытие',
            'procent_deductions' => 'Процент отчисления',
            'date_change' => 'Дата изменения',
            'sms_enabled' => 'Отправлять смс-уведомления о новых заказах',
            'send_email' => 'Отправлять емайл уведомления о заказах на почту',
            'sms_provider' => 'Система отправки смс',
            'allow_bonus' => 'Позволить покупать за баллы',
            'bonus_procent' => 'Процент начисления баллов'
        );
    }

    public function search($domain = false)
    {
        $criteria = new CDbCriteria;

        if ($domain) {
            $partnerIds = Partner::getDomainPartnersIds($domain->id);
            $criteria->addInCondition('id', $partnerIds);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('tname', $this->tname, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('seo_text', $this->seo_text, true);
        $criteria->compare('city_id', $this->city_id, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('email_order', $this->email_order, true);
        $criteria->compare('phone_sms', $this->phone_sms, true);
        $criteria->compare('min_sum', $this->min_sum);
        $criteria->compare('free_delivery_sum', $this->free_delivery_sum, true);
        $criteria->compare('delivery_cost', $this->delivery_cost);
        $criteria->compare('work_begin_time', $this->work_begin_time, true);
        $criteria->compare('work_end_time', $this->work_end_time, true);
        $criteria->compare('delivery_duration', $this->delivery_duration, true);
        $criteria->compare('day1', $this->day1);
        $criteria->compare('day2', $this->day2);
        $criteria->compare('day3', $this->day3);
        $criteria->compare('day4', $this->day4);
        $criteria->compare('day5', $this->day5);
        $criteria->compare('day6', $this->day6);
        $criteria->compare('day7', $this->day7);
        $criteria->compare('img', $this->img, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('balance', $this->balance, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('self_status', $this->self_status, true);
        $criteria->compare('vip', $this->vip, true);
        $criteria->compare('vip_rest', $this->vip_rest, true);
        $criteria->compare('soon_opening', $this->soon_opening, true);
        $criteria->compare('procent_deductions', $this->procent_deductions, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 340,
            ),
        ));
    }

    public function getImage()
    {
        $str = '';
        if (!empty($this->img)) {
            $str = '/upload/partner/small' . $this->img;
        } else {
            $str = '/images/default.jpg';
        }
        return $str;
    }

    public function updatePosition()
    {
        $days = abs(strtotime($this->user->reg_date) - strtotime(date('Y-m-d'))) / 86400;
        if ($days <= 7) {
            $sql3 = "SELECT tbl_partners.*,tbl_users.reg_date,
          (select sum(( SELECT SUM( total_price ) FROM tbl_order_items WHERE order_id = tbl_orders.id )) AS amount
          from tbl_orders WHERE date_sub(curdate(), interval 30 day) <=date and ((status = 'Доставлен'
           AND approved_partner <> '00:00:00') OR status = 'Отменен') AND partners_id = tbl_partners.id group by partners_id ) as total_amount
        FROM tbl_partners,tbl_users WHERE tbl_partners.id=tbl_users.partner_id and ((tbl_partners.position>100 and tbl_partners.position<200) or(tbl_partners.id={$this->id}))
        ORDER BY tbl_users.reg_date desc,position asc ,total_amount desc";
            $partners = Yii::app()->db->createCommand($sql3)->queryAll();
            $position = 101;
            foreach ($partners as $partner) {
                $sql = "update tbl_partners set position={$position} where id={$partner['id']}";
                Yii::app()->db->createCommand($sql)->query();
                $position++;
            }
            return true;
        }
        $sql = "SELECT id,partners_id, date, approved_site, approved_partner, delivered, cancelled, status
                FROM tbl_orders
                WHERE date_sub(curdate(), interval 80 day) <=date and " . " ((status = '" . Order::$DELIVERED . "'  AND approved_partner <> '00:00:00')
                OR status = '" . Order::$CANCELLED . "')
                AND partners_id = " . $this->id . " ";
        $reactions = Yii::app()->db->createCommand($sql)->queryAll();
        $reactions_array = array();
        $sum_reaction_time = 0;
        $time = 0;
        foreach ($reactions as $reaction) {
            //сохраняем дату принятия заказа в формате UTC
            $date_time = strtotime($reaction['date']);
            //Сохраняем дату принятия заказа
            $date = date('Y-m-d', $date_time);
            $def = 0;
            if ($reaction['status'] == Order::$DELIVERED) {
                //Выясняем дату смены статуса
                $approvide_partner_time = strtotime($date . ' ' . $reaction['approved_partner']);
                if ($date_time > $approvide_partner_time) {
                    $approvide_partner_time = $approvide_partner_time + 60 * 60 * 24;
                }
                $def = $approvide_partner_time - $date_time;
            } elseif ($reaction['status'] == Order::$CANCELLED) {
                //Выясняем дату смены статуса
                $cancelled_time = strtotime($date . ' ' . $reaction['cancelled']);
                if ($date_time > $cancelled_time) {
                    $cancelled_time = $cancelled_time + 60 * 60 * 24;
                }
                $def = $cancelled_time - $date_time;
            }
            $reactions_array[] = $def;
            $sum_reaction_time += $def;
        }
        $time = floor((($sum_reaction_time / 60) / (count($reactions_array) ? count($reactions_array) : 1)));


        $time_category = 0;
        if ($time <= 2) {
            $time_category = 1;
        } elseif ($time <= 5) {
            $time_category = 2;
        } elseif ($time <= 10) {
            $time_category = 3;
        } else {
            $time_category = 4;
        }


        $this->position = $time_category * 100 + 501;
        $this->save();
    }

    public static function getWorkDays($id)
    {
        /** @var Partner $model */
        $model = Partner::model()->findByPk($id);
        $workDay = "";
        $workDay .= $model->day1 == 1 ? " Пн " : " -- ";
        $workDay .= $model->day2 == 1 ? " Вт " : " -- ";
        $workDay .= $model->day3 == 1 ? " Ср " : " -- ";
        $workDay .= $model->day4 == 1 ? " Чт " : " -- ";
        $workDay .= $model->day5 == 1 ? " Пт " : " -- ";
        $workDay .= $model->day6 == 1 ? " Сб " : " -- ";
        $workDay .= $model->day7 == 1 ? " Вс " : " -- ";

        return $workDay;
    }

    public function isClosed()
    {
        $result = false;
        $begin = date('Hi', strtotime($this->work_begin_time));
        $end = date('Hi', strtotime($this->work_end_time));
        $now = date('Hi');
        $wday = date('N');
        if($this->{'day'.$wday} == 1)
        {
            if ($begin < $end)//если компания работает днем с 8-00 до 18-00
            {
                if ($begin < $now && $now < $end)//проверяем не находимся ли мы в этом промежутке
                {
                    $result =  false;
                } else {
                    $result =  true;
                }
            } elseif ($begin > $end)//если компания работает через полночь с 18-00 до 8-00
            {
                if ($begin < $now && $now < 2359)//проверяем не находимся ли мы в этом промежутке
                {
                    $result =  false;
                } elseif($end > $now) {
                    $result =  false;
                } else {
                    $result =  true;
                }
            } elseif ($begin == $end) {
                $result =  false;
            }
        } else {
            $result =  true;
        }
        return $result;
    }

    public function howLongWill()
    {
        $diff = strtotime($this->work_begin_time) - strtotime(date("H:i:s", time()));
        if ($diff < 0) {
            $diff = 86400 + $diff;
        }

        $ost = sprintf('%2d:%02d:%02d', $diff / 3600, ($diff % 3600) / 60, $diff % 60);
        $time = explode(':', $ost);
        return $time[0].' ч. '.$time[1].' мин.';
    }

    public function getRestTime()
    {
        $id = 'rest_time' . $this->id;
        $time = Yii::app()->cache->get($id);
        if ($time === false) {
//			$query="select sum(total_price) as sum from tbl_order_items where
//		  exists(select 1 from tbl_orders where tbl_order_items.order_id=id and partners_id= and date > DATE_SUB(NOW(), INTERVAL 2 WEEK) and status<>'' limit 1) ";

            $query = "SELECT sum( total_price ) AS sum
FROM tbl_order_items t1
LEFT JOIN tbl_orders t2 ON t2.id = t1.order_id
WHERE t2.partners_id ={$this->id}
AND t2.date > date_sub( now( ) , INTERVAL 2 week )
AND t2.status <> '" . Order::$CANCELLED . "'";
            $result = Yii::app()->db->createCommand($query)->queryAll();
            $sum = (int)$result[0]['sum'];
            $procent = $sum * $this->procent_deductions / 100;
            if ($procent == 0 || $procent == null) {
                $time = 10000;
            } else {
                $time = floor($this->balance * 14 / ($procent));
            }


            Yii::app()->cache->set($id, $time, 4000);
            //echo $result[0]['sum'];
            //print_r($result);
        }
        echo $time;
        if ($time != 10000) {
            echo " (" . date('d-m-Y', time() + 86400 * $time) . ")";
        }

    }

    public function getDurationTime()
    {
        $duration = "30 minutes";
        switch ($this->delivery_duration) {
            case "30 мин.":
                $duration = "-30 minutes";
                break;
            case "45 мин.":
                $duration = "-45 minutes";
                break;
            case "60 мин.":
                $duration = "-60 minutes";
                break;
            case "90 мин.":
                $duration = "-90 minutes";
                break;
            case "2 часа":
                $duration = "-2 hour";
                break;
            case "3 часа":
                $duration = "-3 hour";
                break;
            case "4 часа":
                $duration = "-4 hour";
                break;
            case "в течении дня":
                $duration = "-16 hour";
                break;
            case "24 часа":
                $duration = "-16 hour";
                break;
        }
        return $duration;
    }

    public static function getWorkDaysString($DaysString)
    {
        $days = "";

        $day = explode(' ', $DaysString);

        if ((substr_count($DaysString, "--") == 2) && ($day[6] == "--") && ($day[7] == "--")) {
            return 'Заказы принимаются по будням';
        }
        if (substr_count($DaysString, "--") == 0) {
            return 'Заказы принимаются без выходных';
        }
        foreach ($day as $key) {
            if (($key !== "--") && ($key !== "")) {
                $days .= $key . ",";
            }
        }
        if ($days == "") {
            return "Заказы не принимаются";
        }

        return 'Заказы принимаются в ' . substr($days, 0, strlen($days) - 1);
    }

    public static function getDeliveryDurationList()
    {
        preg_match('/\((.*)\)/', Partner::model()->tableSchema->columns['delivery_duration']->dbType, $matches);
        foreach (explode(',', $matches[1]) as $value) {
            $value = str_replace("'", null, $value);
            $values[$value] = Yii::t('enumItem', $value);
        }

        return $values;
    }

    public static function PushIos($deviceToken)
    {
        // Put your private key's passphrase here:
        $passphrase = 'dostavka05';

        // Put your alert message here:
        $message = "Новый заказ";
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'aps_production_cert.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit();
        //exit("Failed to connect: $err $errstr" . PHP_EOL);
        //echo 'Connected to APNS' . PHP_EOL;

        $body['aps'] = array(
            'alert' => array(
                'body' => $message,
            ),
            'badge' => 1,
            'sound' => 'oven.wav',
        );

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg)); // @TODO не используется, можно удалить?

        // if (!$result)
        // 	echo 'Message not delivered' . PHP_EOL;
        // else
        // 	echo 'Message successfully delivered' . PHP_EOL;
        // print($payload);
        // Close the connection to the server
        fclose($fp);
    }

    protected function saveDeleted()
    {
        $d = new Deleted;
        $d->item_id = $this->id;
        $d->type = 2;
        $d->date_change = time();
        $d->save(false);
    }

    protected function beforeSave()
    {
        $this->date_change = time();
        $this->use_kassa = 0;  // @TODO Перед тем как удалить строку, запрещающую покупать через Яндекс.Кассу, необходимо протестировать (сделать заказ, изменить статусы, отправка SMS и т.д.) модель Temp_Order, а лучше вообще от нее избавиться и оставить одну модель Order

        return parent::beforeSave();
    }

    protected function beforeDelete()
    {
        if (parent::beforeDelete()) {
            @unlink("upload/partner/logo_partner_" . $this->id . ".png");
            @unlink("upload/partner/logo_smalllogo_partner_" . $this->id . ".png");
            @unlink("upload/partner/partner_" . $this->id . ".png");
            @unlink("upload/partner/smallpartner_" . $this->id . ".png");
            $goods = Goods::model()->findAll("partner_id=" . $this->id);
            foreach ($goods as $good) {
                $good->delete();
            }
            $specs = SpecPartner::model()->findAll("partner_id=" . $this->id);
            foreach ($specs as $spec) {
                $spec->delete();
            }
            foreach ($this->orders as $order) {
                $order->delete();
            }
            $this->user->delete();

            $this->saveDeleted();
            return true;
        }
    }

    protected function afterSave()
    {
        Yii::app()->cache->flush();
        $sql = "UPDATE {{spec_partner}} SET date_change = UNIX_TIMESTAMP() WHERE partner_id = $this->id";
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->execute();
        //$this->date_change = time();
        parent::afterSave();

        return;
    }


    protected function afterDelete()
    {
        Goods::DeleteAllGoods($this->id);
        Order::DeleteAllOrders($this->id);
        Action::model()->deleteAll('partner_id=' . $this->id);
        CartItem::model()->deleteAll('partner_id=' . $this->id);
        Menu::model()->deleteAll('partner_id=' . $this->id);
        Message::model()->deleteAll('partner_id=' . $this->id);
        OrderPartnerDebt::model()->deleteAll('partner_id=' . $this->id);
        Review::model()->deleteAll('partner_id=' . $this->id);
        SpecPartner::model()->deleteAll('partner_id=' . $this->id);
        User::model()->deleteAll('partner_id=' . $this->id);
        Relationpartner::model()->deleteAll('user_id=' . $this->user_id);

        return parent::afterDelete();
    }

    /**
     * Списание средств с личного счета партнера при доставленном заказе
     *
     * Помимо списание средств с личного счета партнера, в этой функции происходит автоматическое создание личного кабинета
     * для клиента, сделавшего заказ, и отправка ему SMS
     *
     * @param $price стоимость заказа
     * @param $order_id ID заказа
     */
    public function orderDelivered($price, $order_id)
    {
        $payment = new Payment_history();
        $payment->sum = $price * ($this->procent_deductions / 100);
        $payment->balance_before = $this->balance;
        $payment->balance_after = $this->balance - $price * ($this->procent_deductions / 100);
        $payment->info = "Списание за заказ <a href='/admin/order/id/{$order_id}/view'>№" . $order_id . '</a>';
        $payment->partner_id = $this->id;
        $payment->order_id = $order_id;
        $payment->author = $this->user->email;
        $date = time();
        $payment->date = $date;
        if (!Payment_history::model()->find("info=:info", array(':info' => $payment->info))) {
            $payment->save();
            $this->balance -= $price * ($this->procent_deductions / 100); //вычитаем в соответствии с его процентом
        }

        if ($this->balance <= 0) {
            $this->status = 0;
        }
        $this->save();
        $order=Order::model()->findByPk($order_id);
        /* @var $order Order */

        if($order->domain_id==3){
            if ($order->user&&!$order->forbonus) {
                UserBonus::getBonusForOrder($order);
                $order->user->save();
            }

            if(!$order->user_id||$order->user->role!=''){
                User::addUserFromOrder($order);
            }else{				
				if($order->user->role==''){

					$sum_one=floor($order->sum*$order->partner->bonus_procent/100);
					$sum=User::getBonus($order->user_id);
					$text="Начислено: ".$sum_one. " баллов. Баланс: ". $sum;
					User::SendUnisenderSms($order->phone,"Dostavka05",$text);
				}
                
            }
        }
    }

    public function GetMoneyForFreeOrder($sum, $order_id)
    {
        $this->balance += $sum;
        $this->save();
        $payment = new Payment_history();
        $payment->sum = $sum;
        $payment->balance_before = $this->balance - $sum;
        $payment->balance_after = $this->balance;
        $payment->info = "Зачисление за бесплатный заказ <a href='/admin/order/id/{$order_id}/view'>№" . $order_id . '</a>';
        $payment->partner_id = $this->id;
        $payment->author = $this->user->email;
        $date = time();
        $payment->date = $date;
        if ($payment->save()) {

        }
    }

    public function getActivePartners()
    {
        $city_id = City::getUserChooseCity();
        $criteria = new CDbCriteria();
        $time = date('H:i:m', time() + 1800);
        $criteria->addCondition('id<>' . $this->id . ' and status = 1 AND self_status = 1 and city_id=' . $city_id . " and work_begin_time<'" . $time . "' and work_end_time<'" . $time . "' ");
        $criteria->order = 'RAND()';
        $criteria->limit = 4;

        $partners = Partner::model()->findAll($criteria);
        return $partners;
    }

    public function getOpenedPartners()
    {
        $city_id = $this->city_id;
        $id = $this->id;
        $time = date('H:i:m');

        $query = "select * from tbl_partners where id<>{$id} and city_id={$city_id}
			  and work_begin_time<'{$time}'  and id in(60,85,46,63) order by rand()";
        $partners = Partner::model()->findAllBySql($query);
        return $partners;
    }

    public function getPartnersForRayon($r_id)
    {
        $query = "select * from tbl_partners as t where exists(select id from tbl_partner_rayon as t2 where t2.rayon_id={$r_id} and t2.partner_id=t.id) order by rand()";
        $partners = Partner::model()->findAllBySql($query);
        return $partners;
    }

    public static function getCountActivePartners()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('status = 1 AND self_status = 1');

        return count(Partner::model()->findAll($criteria));
    }

    public function createUrl()
    {
        return '/restorany/' . $this->tname;
    }

    public static function getDomainPartnersIds($domainId)
    {
        return Yii::app()->db->createCommand()
            ->select('p.id')
            ->from('tbl_city c')
            ->join('tbl_partners p', 'p.city_id = c.id')
            ->where('c.domain_id=:id', array(':id' => $domainId))
            ->queryColumn();
    }

    public function OrdersCount()
    {
        $date_sql = '';
        if (isset($_GET['from'])) {
            $date_sql .= " and date>'" . $_GET['from'] . " 00:00:00' ";
        }
        if (isset($_GET['to'])) {
            $date_sql .= " and date<'" . $_GET['to'] . " 23:59:59' ";
        }
        $result = Yii::app()->db->cache(40000)->createCommand("select count(id) as sum from tbl_orders where status='Доставлен'and partners_id=" . $this->id . $date_sql)->queryAll();
        return $result[0]['sum'];
    }

    public function OrdersSum()
    {
        $date_sql = '';
        if (isset($_GET['from'])) {
            $date_sql .= " and date>'" . $_GET['from'] . " 00:00:00' ";
        }
        if (isset($_GET['to'])) {
            $date_sql .= " and date<'" . $_GET['to'] . " 23:59:59' ";
        }
        $id = "partners_order_sum" . $this->id . $date_sql;
        $sum = Yii::app()->cache->get($id);
        if ($sum === false) {
            $sql = "select id from tbl_orders where status='Доставлен' and partners_id=" . $this->id . $date_sql;
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $sum = 0;
            foreach ($result as $res) {
                $sum += Order::totalPrice($res['id']);
            }
            Yii::app()->cache->set($id, $sum, 40000);
        }
        return $sum;
    }

    public function CancelsCount()
    {
        $date_sql = '';
        if (isset($_GET['from'])) {
            $date_sql .= " and date>'" . $_GET['from'] . " 00:00:00' ";
        }
        if (isset($_GET['to'])) {
            $date_sql .= " and date<'" . $_GET['to'] . " 23:59:59' ";
        }
        $result = Yii::app()->db->cache(40000)->createCommand("select count(id) as sum from tbl_orders where status='Отменен' and partners_id=" . $this->id . $date_sql)->queryAll();
        return $result[0]['sum'];
    }

    public function AverageCheck()
    {
        $date_sql = '';
        if (isset($_GET['from'])) {
            $date_sql .= " and date>'" . $_GET['from'] . " 00:00:00' ";
        }
        if (isset($_GET['to'])) {
            $date_sql .= " and date<'" . $_GET['to'] . " 23:59:59' ";
        }
        $sql = "
                SELECT id, date, approved_site, approved_partner, delivered, cancelled, status
                FROM tbl_orders
                WHERE  ((status = '" . Order::$DELIVERED . "'  AND approved_partner <> '00:00:00')
                OR status = '" . Order::$CANCELLED . "') and partners_id=" . $this->id . $date_sql;
        $reactions = Yii::app()->db->cache(40000)->createCommand($sql)->queryAll();
        $reactions_array = array();
        $sum_reaction_time = 0;
        foreach ($reactions as $reaction) {
            //сохраняем дату принятия заказа в формате UTC
            $date_time = strtotime($reaction['date']);
            //Сохраняем дату принятия заказа
            $date = date('Y-m-d', $date_time);
            $def = 0;
            if ($reaction['status'] == Order::$DELIVERED) {
                //Выясняем дату смены статуса
                $approvide_partner_time = strtotime($date . ' ' . $reaction['approved_partner']);
                if ($date_time > $approvide_partner_time) {
                    $approvide_partner_time = $approvide_partner_time + 60 * 60 * 24;
                }
                $def = $approvide_partner_time - $date_time;
            } elseif ($reaction['status'] == Order::$CANCELLED) {
                //Выясняем дату смены статуса
                $cancelled_time = strtotime($date . ' ' . $reaction['cancelled']);
                if ($date_time > $cancelled_time) {
                    $cancelled_time = $cancelled_time + 60 * 60 * 24;
                }
                $def = $cancelled_time - $date_time;
            }
            $reactions_array[] = $def;
            $sum_reaction_time += $def;
        }
        return floor((($sum_reaction_time / 60) / (count($reactions_array) ? count($reactions_array) : 1)));
    }

    static function CheckPushIOS(){
        $partners=Partner::model()->cache(6000)->findAll("token_ios<>''");
        foreach($partners as $partner){
            //echo $partner->id;
            
            $orders=Order::model()->find("partners_id=".$partner->id." and status='".Order::$APPROVED_SITE."'");
            if($orders){
                if($partner->user->user_token->logged==1){
                    self::PushIos($partner->token_ios);
                }
            }
            
        }
        //Partner::CheckPushIOS();
       // Order::checkNewOrdersMobile();
    }

}
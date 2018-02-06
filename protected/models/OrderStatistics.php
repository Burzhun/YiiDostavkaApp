<?php


class OrderStatistics extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{orders_statistics}}';
    }


    public function rules()
    {

        return array(
            array('domain_id, date, orders_count, orders_sum, new_users, procent, accept_phone, pc, mobile, app, average', 'required'),
            array('procent2','safe')
        );
    }


    public function relations()
    {
        return array();
    }


    public function attributeLabels()
    {
        return array(
            'id'           => 'ID',
            'date'         => 'Дата',
            'orders_count' => 'Колличество заказов',
            'orders_sum'   => 'Сумма заказов',
            'new_users'    => 'Новые пользователи',
            'procent'      => 'Проценты',
            'procent2'     => 'Проценты2',
            'accept_phone' => 'Принято по телефону',
            'pc'           => 'Компьютер',
            'mobile'       => 'Mobile',
            'app'          => 'Приложение',
            'average'      => 'Средний чек',

        );
    }

    static function GetThisDayOrders($domain_id){
        $date=date('Y-m-d');
        $sql = "SELECT  COUNT(DISTINCT `t`.id) AS count,t.date,
                   sum(t.sum) AS sum, floor(sum(t.sum*partners.procent_deductions/100)) as sum2,
                 count(DISTINCT case  when t.order_source<2 then  t.id else null end ) as pc_count,
                 count(DISTINCT case  when t.order_source=2 then  t.id else null end ) as mobile_count,
                 count(DISTINCT case  when t.order_source>2 then  t.id else null end ) as app_count
                FROM `tbl_orders` `t`
                LEFT OUTER JOIN tbl_partners partners ON (partners.id=t.partners_id)
                WHERE FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '%Y-%m-%d')='{$date}' and `t`.status = '" . Order::$DELIVERED ."' and domain_id=".$domain_id."
                ORDER BY `t`.`date` asc";
        $res=Yii::app()->db->createCommand($sql)->queryAll();
        return $res[0];
    }
    static function GetLastDayOrders($domain_id,$n=1){
        $date=date('Y-m-d',time()-86400*$n);
        $sql = "SELECT  COUNT(DISTINCT `t`.id) AS count,t.date,
                   sum(t.sum) AS sum, floor(sum(t.sum*partners.procent_deductions/100)) as sum2,
                 count(DISTINCT case  when t.order_source<2 then  t.id else null end ) as pc_count,
                 count(DISTINCT case  when t.order_source=2 then  t.id else null end ) as mobile_count,
                 count(DISTINCT case  when t.order_source>2 then  t.id else null end ) as app_count
                FROM `tbl_orders` `t`
                LEFT OUTER JOIN tbl_partners partners ON (partners.id=t.partners_id)
                WHERE FROM_UNIXTIME(UNIX_TIMESTAMP(t.date), '%Y-%m-%d')='{$date}' and `t`.status = '" . Order::$DELIVERED ."' and domain_id=".$domain_id."
                ORDER BY `t`.`date` asc";
        $res=Yii::app()->db->createCommand($sql)->queryAll();
        return $res[0];
    }

    public function search($serchCriteria = "")
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('date', $this->date);
        $criteria->compare('orders_count', $this->orders_count);
        $criteria->compare('orders_sum', $this->orders_sum);
        $criteria->compare('new_users', $this->new_users);
        $criteria->compare('procent', $this->procent);
        $criteria->compare('procent2', $this->procent2);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


}
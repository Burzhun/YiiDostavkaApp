<?php

/**
 * Class OrderPartnerDebt
 * @property int id
 * @property int partner_id
 * @property int user_id
 * @property string user_name
 * @property string date
 * @property string address
 * @property int sum
 * @property string phone
 * @property string info
 * @property int paid
 *
 * @property Partner partner
 * @property User user
 */
class Payment_history extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return '{{payment_history}}';
    }


    public function rules()
    {
        return array(
            array('partner_id,date','numerical', 'integerOnly' => true),
            array('info', 'length', 'max' => 255),

            //array('date', 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'),
            array('balance_before','type','type'=>'float'),
            array('balance_after','type','type'=>'float'),
            array('sum','type','type'=>'float'),
            array('id, partner_id,date,info', 'safe'),

        );
    }


    public function relations()
    {
        return array(
        );
    }


    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sum'=>'Сумма',
            'balance_before'=>'Показания до',
            'balance_after'=>'Показания после',
            'author'=>'Автор',
            'info'=>'Описание',
            'date'=>'Дата операции',
        );
    }


    //для админа, вывод всех заказов
    public function search($serchCriteria = array())
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);


    }
    public function sum(){
        $domain=Yii::app()->controller->domain;
        if($domain->alias == 'www.dostavka.az'){
            $sum=$this->sum;
        }else{
            $sum=$this->sum;
        }
        if($this->balance_before<$this->balance_after){
            return '+'.$sum;
        }
        else{
            return '-'.$sum;
        }
    }

    public static function getSum($sum){
        $domain=Yii::app()->domain;
        if($domain->alias == 'www.dostavka.az'){
            return $sum;
        }else{
            return (int)$sum;
        }
    }

    public static function Sum2($date,$domain_id, $operators = false){
        $date_format = '%Y-%m-%d';
        $domain_id=$domain_id ? $domain_id : Yii::app()->session['domain_id']->id;
        if(isset($_GET['period']))
        {
            if($_GET['period'] == "day")
            {
                $date_format = '%Y-%m-%d';
            }elseif($_GET['period'] == 'month')
            {
                $date_format = '%Y-%m';
            }elseif($_GET['period'] == 'year')
            {
                $date_format = '%Y';
            }
        }

        $order_join="";
        $operator_sql="";
        // Оператор
        if ($operators) {
            if($operators == 'all'){
                $admins = User::getAdmins();
                foreach ($admins as $data) {
                    $adminIds[] = '"'.$data->email.'"';
                }
                $operator_sql = " AND tbl_orders.user_id IN (" . implode(',', $adminIds) .")";
            }else{
                $operatorId = User::model()->findByPk($operators)->id;
                $operator_sql = " AND tbl_orders.user_id= '" . $operatorId."'";
            }
            $order_join="left join tbl_orders on tbl_orders.id=tbl_payment_history.order_id";
        }
        $sql="SELECT sum( tbl_payment_history.sum ) as sum FROM tbl_payment_history
              LEFT JOIN tbl_partners ON tbl_partners.id = tbl_payment_history.partner_id
              LEFT JOIN tbl_city ON tbl_city.id = tbl_partners.city_id
              {$order_join}
        WHERE tbl_city.domain_id={$domain_id} and (FROM_UNIXTIME( tbl_payment_history.date, '{$date_format}' ) = '{$date}' AND balance_before > balance_after)".$operator_sql;
        if(!empty($_GET['partner_id']))
        {
            $sql.=" and tbl_payment_history.partner_id = ".$_GET['partner_id'];
        }
        $res=Yii::app()->db->createCommand($sql)->queryAll();
        $count=$res[0]['sum'];
        return $count;
    }
}
<?php

class PayController extends Controller
{

    public function actionIndex()
    {

        /*---------------------------------------------------------------------------*/
        if (isset($_REQUEST['xml']) and isset($_REQUEST['sign'])) // Если не получили параметры xml и sign, то нечего проверять
        {
            // Инициализация переменной для хранения сообщения об ошибке
            $error = '';
            // Декодируем входные параметры
            $xml = base64_decode(str_replace(' ', '+', $_REQUEST['xml']));
            $sign = base64_decode(str_replace(' ', '+', $_REQUEST['sign']));
            // преобразуем входной xml в удобный для использования формат
            $vars = simplexml_load_string($xml);
            if ($vars->order_id)
                // Если поле order_id не заполнено, продолжать нет смысла.
                // Т.к. информацию о заказе не получили и не сможем проверить
                // корректность остальных параметров
                // А также если тип сообщения не result
            {

                /*---Начало секции Debug. В этой секции просто получаем ответ и сохраняем его в файл paytest.txt в корневом каталоге сайта(вообще дебаг типа)*/

                ob_start();
                echo "POST: \n";

                print_r($_POST);

                echo "decoded data: \n";
                print_r($vars);

                echo "xml(md5): \n";
                echo md5($xml);
                echo "response md5: \n";
                echo md5('sdf4564dfg5gd4g46hqf3fs' . $xml . 'sdf4564dfg5gd4g46hqf3fs');
                echo "xml string: \n";
                var_dump($xml);


                $content = ob_get_contents();
                ob_clean();
                file_put_contents('paytest.txt', $content);
                /*---Конец секции Debug*/

                // Получаем информацию о заказе из БД
                $arr = explode('.', $vars->order_id);

                $partner_id = $arr[0];
                $tmp_key = $arr[1];

                $partner = Partner::model()->findByPk($vars->order_id);
                if ($partner) // Если не нашли заказ с указанным номером, то возвращаем ошибку
                {
                    // Загружаем настройки для работы с Pay2Pay
                    $pmconfigs = array(
                        'trans_status_pending' => '',
                        'hidden_key' => 'sdf4564dfg5gd4g46hqf3fs',
                    );
                    // if ($partner->order_status == $pmconfigs['trans_status_pending'])
                    // 	{
                    //$s = md5($pmconfigs['hidden_key'] . $xml . $pmconfigs['hidden_key']);
                    //file_put_contents('debugpay.txt', '0'."\n".$s.' - '.$sign);
                    //if ($sign == $s)

                    if ($partner->tmp_key == $tmp_key) // Если подпись не совпадает, возвращаем ошибку
                    {//file_put_contents('debugpay.txt', '1');
                        /*$currency = $partner->currency_code_iso;
                        // Код валюты заказа преобразуем к формату принятом в Pay2Pay
                        if ($currency == 'RUR')
                            $currency = 'RUB';*/
                        if ($vars->currency == 'RUB') // Нельзя принимать платеж, если валюта платежа и заказа не совпали
                        {
                            /*if ($partner->order_total <= $vars->amount)
                            // Сумма платежа может превышать сумму заказа.
                            // Например, в случае оплаты через системы денежных переводов
                            {*/
                            //file_put_contents('debugpay.txt', '2');
                            if (($vars->status == 'success')/* or ($vars->status == 'fail')*/) // Если статус платежа окончательный, то нужно обновить заказ
                            {
                                // Выбираем какой статус установить для заказа
                                /* $status = $pmconfigs['trans_status_failed'];
                                 if ($vars->status == 'success')
                                 $status = $pmconfigs['trans_status_success'];
                                 // Устанавливаем статус заказа
                                 $partner->status = $status;*/
                                //file_put_contents('debugpay.txt', '3');
                                $partner->balance += $vars->amount;
                                $partner->save(false);
                            }
                            /*}
                            else
                            $error = 'Amount check failed';*/
                        } else
                            $error = 'Currency check failed';
                    } else
                        $error = 'Security check failed';
                    // }
                } else
                    $error = 'Unknown order_id';
            } else
                $error = 'Incorrect order_id';
            // Отвечаем серверу Pay2Pay
            if ($error == '')
                $ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
			 <result>
			 <status>yes</status>
			 <err_msg></err_msg>
			 </result>";
            else
                $ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
			 <result>
			 <status>no</status>
			 <err_msg>$error</err_msg>
			 </result>";
            die($ret);
        }

    }

    /*public function actionForm(){
        $this->renderPartial('_formPay');
    }
*/
    public function actionJson()
    {
        if (isset($_POST['id']) && isset($_POST['sum'])) {
            if (is_numeric($_POST['id']) && is_numeric($_POST['sum'])) {
                if ($partner = Partner::model()->findByPk($_POST['id'])) {
                    $merchant_id = 5956; // Идентификатор магазина в Pay2Pay
                    $secret_key = 'sdf4564dfg5gd4g46hqf3fs'; // Секретный ключ
                    $tmp_key = rand(00000, 55555);
                    $order_id = $_POST['id'] . '.' . $tmp_key; // Номер заказа
                    $amount = $_POST['sum']; // Сумма заказа
                    $currency = 'RUB'; // Валюта заказа
                    $desc = 'Пополнение лицевого счета для "' . $partner->name . '"'; // Описание заказа
                    $success_url = "http://dostavka05.ru/partner/pay/confirm/id" . $_POST['id'] . '?sum=' . $amount;
                    $test_mode = 1; // Тестовый режим
                    // Формируем xml
                    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
					 <request>
					 <version>1.2</version>
					 <merchant_id>$merchant_id</merchant_id>
					 <language>ru</language>
					 <order_id>$order_id</order_id>
					 <amount>$amount</amount>
					 <currency>$currency</currency>
					 <description>$desc</description>
					 <success_url>{$success_url}</success_url>
					 <success" .
                        //"<test_mode>$test_mode</test_mode>".
                        "</request>";
                    // Вычисляем подпись
                    $sign = md5($secret_key . $xml . $secret_key);
                    // Кодируем данные в BASE64
                    $xml_encode = base64_encode($xml);
                    $sign_encode = base64_encode($sign);
                    // Выводим форму инициализации платежа
                    /*echo "<form action=\"https://merchant.pay2pay.com/?page=init\" method=\"post\">
                     <input type=\"hidden\" name=\"xml\" value=\"$xml_encode\"/>
                     <input type=\"hidden\" name=\"sign\" value=\"$sign_encode\"/>
                     <input type=\"submit\"/>
                     </form>";
                    //*/


                    //сохраняем временный ключ в таблицу партнеров
                    $partner->tmp_key = $tmp_key;
                    $partner->save(false);

                    echo json_encode(array('error' => false, 'xml' => $xml_encode, 'sign' => $sign_encode, 'tmp_key' => $tmp_key));
                    Yii::app()->end();
                }
            }
        }

        echo json_encode(array('error' => true));
        Yii::app()->end();


    }

    public function actionSuccess()
    {
        $vars = $this->getDetailPay();
        if ($vars) {
            list($partner_id, $key) = explode('.', $vars->order_id);
            $partner = Partner::model()->findByPk($partner_id);
            if ($partner) {
                $this->render('success', array('vars' => $vars, 'partner' => $partner));
            }
        }
    }

    public function actionError()
    {
        $vars = $this->getDetailPay();
        $this->render('error', array('vars' => $vars));
    }

    public function getDetailPay()
    {
        if (isset($_REQUEST['xml']) and isset($_REQUEST['sign'])) {
            $xml = base64_decode(str_replace(' ', '+', $_REQUEST['xml']));
            $vars = simplexml_load_string($xml);
            return $vars;
        } else
            return false;
    }

    public function actionConfirm($id)
    {
        if (isset($_GET['sum'])) {
            $model = Partner::model()->findByPk($id);
            $payment = new Payment_history();
            $payment->sum = $_GET['sum'];
            $payment->partner_id = $id;
            $payment->info = $model->name . " пополнил баланс на " . $payment->sum .City::getMoneyKod(). " .";
            $date = time();
            $payment->date = $date;
            $payment->save();
        }
    }
}
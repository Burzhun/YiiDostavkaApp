<?php
/*---------------------------------------------------------------------------*/
// Инициализируем параметры платежа
$merchant_id = 5956; // Идентификатор магазина в Pay2Pay
$secret_key = 'sdf4564dfg5gd4g46hqf3fs'; // Секретный ключ
$order_id = '00001'; // Номер заказа
$amount = '100.20'; // Сумма заказа
$currency = 'RUB'; // Валюта заказа
$desc = 'Заказ'; // Описание заказа
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
 <test_mode>$test_mode</test_mode>
 </request>";
// Вычисляем подпись
$sign = md5($secret_key . $xml . $secret_key);
// Кодируем данные в BASE64
$xml_encode = base64_encode($xml);
$sign_encode = base64_encode($sign);
// Выводим форму инициализации платежа
echo "<form action=\"https://merchant.pay2pay.com/?page=init\" method=\"post\">
 <input type=\"hidden\" name=\"xml\" value=\"$xml_encode\"/>
 <input type=\"hidden\" name=\"sign\" value=\"$sign_encode\"/>
 <input type=\"submit\"/>
 </form>";
/*---
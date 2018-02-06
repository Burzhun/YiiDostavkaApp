<?php   include('config.php'); ?>



<!-- Пример в кодировке UTF-8 (обязательно используйте именно эту кодировку для взаимодействия с Яндекс.Кассой)
Внимание! Это только пример. Для того, чтобы он работал, обязательно пропишите в нем shopId и scid, который мы присылаем в письме на ваш контактный e-mail.
Кроме того вам надо реализовать программную часть для CheckOrderURL и AvisoURL.
-->
<form style="display: none;" name="ShopForm" method="POST" action="https://money.yandex.ru/eshop.xml">

<!-- ОБЯЗАТЕЛЬНАНЫЕ ПОЛЯ (все параметры яндекс.кассы регистрозависимые) -->
<input type="hidden" name="shopId" value="<?php echo $configs['shopId'] ?>">
<input type="hidden" name="scid" value="<?php echo $configs['scId'] ?>">

Идентификатор клиента:<br>
<input type="hidden" name="CustomerNumber" size="64" value="dostavka05"><br><br> 
<input type="hidden" name="orderNumber" size="64" value="<?=$_GET['order_id']?>"><br><br> 
Сумма :<?=$_GET['sum']?> руб.<br> 
<input type="hidden" name="sum" size="64" value="<?=$_GET['sum']?>" ><br><br>
<!-- CustomerNumber -- до 64 символов; идентификатор плательщика в ИС Контрагента.
В качестве идентификатора может использоваться номер договора плательщика, логин плательщика и т.п.
Возможна повторная оплата по одному и тому же идентификатору плательщика.

sum -- сумма заказа в рублях.
-->
<!-- необязательные поля (все параметры яндекс.кассы регистрозависимые) -->   


<!-- Внимание! Для тестирования в ДЕМО-среде доступны только два метода оплаты: тестовый Яндекс.Кошелек и Тестовая банковская карта
-->
Способ оплаты:<br><br>
<input name="paymentType" value="PC" type="radio" />Со счета в Яндекс.Деньгах (яндекс кошелек)<br/>
<input name="paymentType" value="AC" type="radio" checked="checked"/>С банковской карты<br/>

<!--
Если подключен метод repeatCardPayment, то в платежную форму можно добавить
<input name="rebillingOn" value="true" type="hidden"/> 
-->

<!--
Ниже перечислены доступные формы оплаты.
Перечисленные методы оплаты могут быть доступны в боевой среде после подписания Договора.
Какие именно методы доступны для вашего Договора, вы можете уточнить у своего персонального менеджера.

AB - Альфа-Клик 
AC - банковская карта
GP - наличные через терминал
MA - MasterPass
MC - мобильная коммерция
PB  -интернет-банк Промсвязьбанка
PC - кошелек Яндекс.Денег
SB - Сбербанк Онлайн
WM - кошелек WebMoney
WQ - Qiwi
QP - Куппи.ру
KV - КупиВкредит

<input name="paymentType" value="GP" type="radio">Оплата по коду через терминал<br>
<input name="paymentType" value="WM" type="radio">Оплата cо счета WebMoney<br>
<input name="paymentType" value="AB" type="radio">Оплата через Альфа-Клик<br>
<input name="paymentType" value="PB" type="radio">Оплата через Промсвязьбанк<br>
<input name="paymentType" value="MA" type="radio">Оплата через MasterPass<br>
<input name="paymentType" value="QW" type="radio">Оплата через Qiwi<br>
<input name="paymentType" value="QP" type="radio">Куппи.ру<br>
<input name="paymentType" value="KV" type="radio">КупиВкредит<br>
-->
<br>
<input type=submit value="Оплатить"><br> 
</form>
<script type="text/javascript">
	document.ShopForm.submit();
</script>
<!-- Тестовый яндекс.кошелек
(прежде чем использовать тестовый яндекс.кошелек, обязательно войдите в https://demomoney.yandex.ru с указанными ниже логином и паролем)

Test-for-yamoney@yandex.ru
Пароль для входа: YAMONEY
Платежный пароль: testforyamoney (не путайте этот пароль с паролем для входа в учетную запись; этот пароль нужен только при платежах)

Логин: test-for-everyday
Пароль: yamoney950
платежный пароль: yamoney900

Ещё тестовые карты
-->

<!-- Тестовая банковская карта

Номер карты: 4444 4444 4444 4448
Действует до: любая дата в будущем
Имя и фамилия владельца: любое имя латиницей, например, IVAN DEMIDOV
Код CVV: 000
Email: свой e-mail

Код CVC: 123
-->

<!--
Пример отвеtime="2011-05-04T20:38:11.000+04:00" code="0" invoiceId="1234567" shopId="13"/>
-->

<!--
EPS и PNG файлы яндекс.кошелька
https://money.yandex.ru/partners/doc.xml?id=522991

EPS и PNG других платежных методов
https://money.yandex.ru/doc.xml?id=526421
-->
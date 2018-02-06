<div style="text-align:center;">
    <? $pqrAmount = 0;


    $imageUrl = $data->image; // Определяем URL изображения товара

    $cartOrder[] = array(

        //"article" => $data->id, // Заполняем в качестве артикула ИД цены оплачиваемого товара

        // "name" => '$data->goods->name', // Заполняем наименование товара
        "name" => $data->name, // Заполняем наименование товара

        "imageUrl" => Yii::app()->request->hostInfo . $imageUrl, // Заполняем URL изображения товара

        "quantity" => 1, // Заполняем количество в заказе
        // "category" => "11", // Заполняем количество в заказе

        "amount" => $data->price); // Подсчитываем сумму по позиции

    $pqrAmount += $data->price * 1; // Подсчитываем итоговую сумму корзины


    $pqrCart = json_encode($cartOrder);

    if (Yii::app()->user->isGuest) {
        $pqrOrderGroup = Yii::app()->session->sessionId . ".GUEST";
    } else {
        $pqrOrderGroup = Yii::app()->session->sessionId . "." . Yii::app()->user->id;
    }




    ?>

    <?


    ?>

    <button
        class="payqr-button"
        data-scenario="buy"
        <? /*data-cart='[{"article":"123123","name":"Хороший товар","quantity":"1","amount":"500.00","imageurl":"http://modastuff.ru/item1.jpg"},{"article":"123123","name":"Очень хороший товар","quantity":"2","amount":"1000.00","imageurl":"http://modastuff.ru/item2.jpg"},{"article":"","name":"","quantity":"1","amount":"500","imageurl":""}]'
*/ ?>
        data-cart='<?= $pqrCart ?>'
        data-firstname-required="required"
        data-lastname-required="notrequired"
        data-phone-required="required"
        data-email-required="notrequired"
        data-orderid="default"
        data-ordergroup="<?= $pqrOrderGroup ?>"
        data-delivery-required="required"
        data-amount="<?= $pqrAmount ?>"
        style="width: 163px; height: 36px;">Купить быстрее
    </button>

    <br>
    <br>
    <br>
</div>
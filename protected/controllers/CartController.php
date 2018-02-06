<?php

class CartController extends Controller
{

    public function actions()
    {

    }

    public function actionIndex()
    {
        $cart = CartItem::model()->findAll(array("condition" => "1=1" . CartItem::getConditionForSelectCartItems()));

        //Проверяем все ли товары от одного паставщика и заодно считаем сумму и заодно проверяем есть ли cartItem.кол==0 => удаляем
        $partnerCount = 0;
        $sum = 0;
        $current_partner = "";
        $have_deleted_item = false;
        foreach ($cart as $c) {
            if ($c->partner_id != $current_partner) {
                $current_partner = $c->partner_id;
                $partnerCount++;
            }
            $sum += $c->price * $c->quality;
            if ($c->quality == 0) {
                $c->delete();
                $have_deleted_item = true;
            }
        }

        //если больше одного поставщика, значит что то не так
        if ($partnerCount > 1) exit();//нада сделать обработчик на данную ошибку, типа хацкер сосни этих сдобных мягких булочек и выпей яду))

        if ($have_deleted_item) {
            $cart = CartItem::model()->findAll(array("condition" => "1=1" . CartItem::getConditionForSelectCartItems()));
        }
        //если один поставщик
        $partner = $partnerCount == 1 ? Partner::model()->findByPk($c->partner_id) : "";

        $this->render('index', array("cart" => $cart, "sum" => $sum, "partner" => $partner));
    }


    public function actionAdd()
    {
        $id = $_POST['product'];
        $count = $_POST['count'];

        $product = Goods::model()->findByPk($id);

        $condition = Yii::app()->user->role == User::USER ? "user_id='" . Yii::app()->user->id . "'" : "session_id='" . Yii::app()->session->sessionId . "'";
        // echo Yii::app()->session->sessionId ;
        CartItem::model()->deleteAll(array('condition' => "partner_id!='" . $product->partner_id . "' AND " . $condition));

        $cartItem = CartItem::model()->find(array("condition" => "goods_id=" . $id . CartItem::getConditionForSelectCartItems()));
        if (!empty($cartItem)) {
            $cartItem->quality += $count;
        } else {
            //если из данного магазина у этого пользователя нет заказанных товаров, то удаляем все другие его товары, ибо нефига!
            $cartItem = new CartItem();
            $cartItem->quality = $count;
            $cartItem->user_id = Yii::app()->user->role == User::USER ? Yii::app()->user->id : 0;
            $cartItem->partner_id = $product->partner_id;
            $cartItem->goods_id = $id;
        }

        // $cartItem->price = $product->getOrigPrice($this->domain);
        $cartItem->price = $product->price;
        $cartItem->date = date('Y-m-d H:i:s');
        $cartItem->session_id = Yii::app()->session->sessionId;
        $cartItem->save();

        if(Yii::app()->request->getParam('type') == 'drink'){

            $cart = CartItem::model()->findAll(array("condition" => "1=1" . CartItem::getConditionForSelectCartItems()));

            $sum = 0;
            foreach ($cart as $c) {
                $sum += $c->quality * $c->price;
            }

            $json['sumCart'] = $sum;
            $json['list'] = $this->renderPartial('_basket_list', array('cart' => $cart), true, false);

            echo CJSON::encode($json);
            Yii::app()->end();
        }
        /*if() проверяем принадлежит ли данному партнеру данный товар
        {*/
        //добавляем в базу данных в карзину колличество данного товара
        //нада бы еще как то вывести эту всю байду
        /*}else
        {
            ничего не делаем, "хакер" и так должен понять что он лох, че ему об этом напоминать
        }
        */
    }

    function actionBasket()
    {
        $partner_id = (int)$_POST['partner'];

        $partner = Partner::model()->findByPk($partner_id);
        $cart = CartItem::model()->findAll(array("condition" => "partner_id=" . $partner_id . CartItem::getConditionForSelectCartItems()));
        $sum = 0;
        foreach ($cart as $c) {
            $sum += $c->quality * $c->price;
        }
        if ($sum == 0) {
            echo false;
        } else {
            echo $this->renderPartial('../supplier/basket', array("cart" => $cart, "sum" => $sum, "partner" => $partner));
        }
    }


    function actionMobileBasket()
    {
        $partner_id = (int)$_POST['partner'];

        $partner = Partner::model()->findByPk($partner_id);
        $cart = CartItem::model()->findAll(array("condition" => "partner_id=" . $partner_id . CartItem::getConditionForSelectCartItems()));
        $count = 0;
        foreach ($cart as $c) {
            $count += $c->quality;
        }
        if ($count == 0) {
            echo false;
        } else {
            echo "<span>" . $count . "</span>";
        }
    }


    function actionPlusProduct()
    {
        $id = (int)$_POST['product'];
        $product = CartItem::model()->find(array('condition' => "goods_id='" . $id . "' " . CartItem::getConditionForSelectCartItems()));

        $product->quality++;
        $product->save();

        $cart = CartItem::model()->findAll(array('condition' => "1=1 " . CartItem::getConditionForSelectCartItems()));
        $sum = 0;
        foreach ($cart as $c) {
            $sum += $c->quality * $c->price;
        }

        $sum_for_free_delivery = -1;
        if ($cart[0]->partner->free_delivery_sum > 0) {
            if ($sum < $cart[0]->partner->free_delivery_sum) {
                $sum_for_free_delivery = $cart[0]->partner->free_delivery_sum - $sum;
            }
        }
        $data = array("count" => $product->quality,
            "sumProduct" => $product->quality * $product->price,
            "sum_for_free_delivery" => $sum_for_free_delivery,
            "sumCart" => $sum,
            "empty" => false
        );

        //$data = array("count"=>1231, "sumProduct"=>12321321, "sum"=>987987);
        //echo json_encode($data);
        echo CJSON::encode($data);
    }

    function actionMinusProduct()
    {
        $id = (int)$_POST['product'];
        $product = CartItem::model()->find(array('condition' => "goods_id='" . $id . "' " . CartItem::getConditionForSelectCartItems()));

        $product->quality = $product->quality == 0 ? 0 : $product->quality - 1;
        $product->save();

        $cart = CartItem::model()->findAll(array('condition' => "1=1 " . CartItem::getConditionForSelectCartItems()));
        $sum = 0;
        $quality = 0;
        foreach ($cart as $c) {
            $sum += $c->quality * $c->price;
            $quality += $c->quality;
        }

        if (count($cart) == 0) {
            $empty = true;
        } elseif ($quality == 0) {
            $empty = true;
        } else {
            $empty = false;
        }
        $sum_for_free_delivery = -1;
        if ($cart[0]->partner->free_delivery_sum > 0) {
            if ($sum < $cart[0]->partner->free_delivery_sum) {
                $sum_for_free_delivery = $cart[0]->partner->free_delivery_sum - $sum;
            }
        }
        $data = array("count" => $product->quality,
            "sumProduct" => $product->quality * $product->price,
            "sumCart" => $sum,
            "sum_for_free_delivery" => $sum_for_free_delivery,
            "empty" => $empty
        );
        //$data = array("count"=>1231, "sumProduct"=>12321321, "sum"=>987987);
        echo CJSON::encode($data);
    }

    function actionEditProduct()
    {
        $id = (int)$_POST['product'];
        $count = (int)$_POST['count'];
        $count = $count < 0 ? 0 : $count;
        $product = CartItem::model()->find(array('condition' => "goods_id='" . $id . "' " . CartItem::getConditionForSelectCartItems()));

        $product->quality = $count;
        $product->save();

        $cart = CartItem::model()->findAll(array('condition' => "1=1 " . CartItem::getConditionForSelectCartItems()));

        $sum = 0;
        $quality = 0;
        foreach ($cart as $c) {
            $sum += $c->quality * $c->price;
            $quality += $c->quality;
        }

        if (count($cart) == 0) {
            $empty = true;
        } elseif ($quality == 0) {
            $empty = true;
        } else {
            $empty = false;
        }

        $data = array("count" => $product->quality, "sumProduct" => $product->quality * $product->price, "sumCart" => $sum, "empty" => $empty);

        echo CJSON::encode($data);
    }

    function actionDeleteProduct()
    {
        $id = (int)$_POST['product'];
        $condition = Yii::app()->user->role != User::USER ? " session_id='" . Yii::app()->session->sessionId . "'" : " user_id='" . Yii::app()->user->id . "'";

        $product = CartItem::model()->find(array('condition' => $condition . " AND goods_id='" . $id . "'"));

        $product->delete();

        $cart = CartItem::model()->findAll(array('condition' => $condition));
        $sum = 0;
        $quality = 0;
        foreach ($cart as $c) {
            $sum += $c->quality * $c->price;
            $quality += $c->quality;
        }

        if (count($cart) == 0) {
            $empty = true;
        } elseif ($quality == 0) {
            $empty = true;
        } else {
            $empty = false;
        }

        $sum_for_free_delivery = -1;
        if ($cart[0]->partner->free_delivery_sum > 0) {
            if ($sum < $cart[0]->partner->free_delivery_sum) {
                $sum_for_free_delivery = $cart[0]->partner->free_delivery_sum - $sum;
            }
        }
        $data = array("count" => $product->quality,
            "sumProduct" => $product->quality * $product->price,
            "sumCart" => $sum,
            "sum_for_free_delivery" => $sum_for_free_delivery,
            "empty" => $empty);
        //$data = array("count"=>1231, "sumProduct"=>12321321, "sumCart"=>$condition."' AND goods_id='".$id."'");
        echo CJSON::encode($data);
    }

    function actionDeleteAllProduct()
    {
        $condition = Yii::app()->user->role != User::USER ? " session_id='" . Yii::app()->session->sessionId . "'" : " user_id='" . Yii::app()->user->id . "'";

        $product = CartItem::model()->find(array('condition' => $condition));

        if ($product->deleteAll()) {
            echo true;
        } else {
            echo false;
        }
    }

    //Так как при заказы у зареганых юзеров сохраняются на user_id, а у нереганных сохраняются на session_id, получается
    //что нужно искать по разным полям в разных случаях
    private function getConditionForSelectCartItems() // @TODO нигед не используется. Нужен ли он?
    {
        if (Yii::app()->user->role == User::USER) {
            $result = " AND user_id='" . Yii::app()->user->id . "'";
        } else {
            $result = " AND session_id='" . Yii::app()->session->sessionId . "'";
        }
        return $result;
    }
}
<? foreach ($model as $goods) { // вывод товаров
    if($goods->price){

        $this->renderPartial('_view_goods', array('goods' => $goods, 'partner' => $partner,'warning'=>$warning));
    }
}
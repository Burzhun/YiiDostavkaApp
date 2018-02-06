<div id="pop-up">
    <div class="pop-header">
        <div id="close-pop-up3"></div>
        <h3>Заказ принят!</h3>
    </div>
    <p class="thanks-order"><?= Config::model()->find("name='order_message'")->value; ?></p>
    <script>
        $(document).ready(function(){
           // alert('345');
            <? $orders_array='';
            foreach($orders_info as $order_info){
                $orders_array.="[".$order_info["id"].",'".$order_info["name"]."','".$order_info["category"]."',".$order_info["price"].",".$order_info["quantity"]."],";
            }?>
            // console.log("<?=$t;?>");
            //alert('<?=substr($orders_array, 0, -1);?>');
            var  orders=[<?=substr($orders_array, 0, -1);?>];
            var id=<?=$order_id;?>;
            var price=<?=$order_price;?>;
            var delivery=<?=$order_delivery;?>;
            addOrder(orders,id,price,delivery);
        });
    </script>
</div>
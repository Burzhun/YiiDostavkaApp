<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<?php // @TODO нужна ли данная страница?
/*$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'order-grid',
   'dataProvider'=>$order_model->search(array('partners_id'=>$model->id)),
    'filter'=>$order_model,
   //'cssFile'=>'css/gridView/partnerOrderGrid.css',
   'rowCssClassExpression'=>'Order::getRowColor($data->status)',
    'emptyText'=>'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
       'summaryText' => '',
   'columns'=>array(
       'id',
       array(
           'class' => 'CLinkColumn',
           'header'=>'Пользователь',
           'labelExpression'=>'$data->user->name',
           'urlExpression'=>'"/admin/user/id/".$data->user->id."/profile/"',
       ),
       array(
           'header'=>'Общая сумма заказа',
           'value'=>'Order::totalPrice($data->id)." <?php echo City::getMoneyKod();?>"',
       ),
       array(
           'header'=>'Колличество товара',
           'value'=>'',
       ),
       'date',
       'city',
       'street',
       'phone',
       array(
           'header'=>'Статус заказа',
           'type'=>'raw',
           'value'=>'ZHtml::enumDropDownList($data, "status", array("id"=>$data->id, "class"=>"statuseditor"))',
           //'name'=>'status',
           'filter'=> ZHtml::enumDropDownList($order_model, 'status', array()),
       ),
       array(
           'class' => 'CLinkColumn',
           'header'=>'Заказ',
           'labelExpression'=>'"→"',
           'urlExpression'=>'"/admin/order/id/".$data->id."/view/"',
       ),
   ),
));
*/
?>

<script>
    $(document).on('change', '.statuseditor', function (event) {
        var p_id = $(this).attr('id');
        var status = $(this).val();
        switch (status) {
            case "<?=Order::$APPROVED_SITE?>":
                status = 1;
                break;
            case "<?=Order::$APPROVED_PARTNER?>":
                status = 2;
                break;
            case "<?=Order::$SENT?>":
                status = 3;
                break;
            case "<?=Order::$DELIVERED?>":
                status = 4;
                break;
            case "<?=Order::$CANCELLED?>":
                status = 5;
                break;
        }
        $.ajax({
            url: "/admin/order/id/" + p_id + "/changeStatus?status=" + status,
            type: "post",
            data: {"product": p_id, "status": status},
            success: function (data) {
                $.fn.yiiGridView.update("order-grid");
            }
        });
    });
</script>
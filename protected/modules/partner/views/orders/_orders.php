Не нужен по идее

<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<?php
/*$this->widget('zii.widgets.grid.CGridView', array(
   'dataProvider'=>$order_model->search(array('partners_id'=>$model->id)),
    'filter'=>$order_model,
   //'cssFile'=>'css/gridView/partnerOrderGrid.css',
   'rowCssClassExpression'=>'Order::getRowColor($data->status)',
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
           'value'=>'$data->status',
           'name'=>'status',
           'filter'=> ZHtml::enumDropDownList($order_model, 'status', array()),
       ),
       array(
           'class' => 'CLinkColumn',
           'header'=>'Заказ',
           'labelExpression'=>'"→"',
           'urlExpression'=>'"/admin/partner/id/".$data->partner->id."/orders/".$data->id',
       ),
   ),
));
*/
?>
<? /** @var PartnerController $this */ ?>
<ul class="nav nav-pills nav-stacked">
    <li <? if (Yii::app()->controller->action->id == "statisticOrders") { ?>class="active"<? } ?>>
        <a href="/admin/partner/id/<?php echo $model->id ?>/statisticOrders">Заказы</a>
    </li>
    <li <? if (Yii::app()->controller->action->id == "statisticGoods") { ?>class="active"<? } ?>>
        <a href="/admin/partner/id/<?php echo $model->id ?>/statisticGoods">Товары</a>
    </li>

    <br><br>
    <li>Период</li>
    <li>
        <select style="width:135px;" id="select_period">
            <option value="1" <? if (Yii::app()->session['period'] == 1) { ?> selected <? } ?>>Текущий месяц</option>
            <option value="2" <? if (Yii::app()->session['period'] == 2) { ?> selected <? } ?>>Прошлый месяц</option>
            <option value="3" <? if (Yii::app()->session['period'] == 3) { ?> selected <? } ?>>3 месяца</option>
            <option value="4" <? if (Yii::app()->session['period'] == 4) { ?> selected <? } ?>>Пол года</option>
            <option value="5" <? if (Yii::app()->session['period'] == 5) { ?> selected <? } ?>>Год</option>
            <option value="6" <? if (Yii::app()->session['period'] == 6) { ?> selected <? } ?>>Весь период</option>
        </select>
    </li>
    <br><br><br>
    <li>Средняя сумма чека</li>
    <?
    $sql = "SELECT COUNT(id) AS count, SUM((SELECT SUM(`total_price`) FROM `tbl_order_items` WHERE `order_id` = `tbl_orders`.id)) AS price FROM tbl_orders WHERE " . $this->bitweenDate() . " AND status = '" . Order::$DELIVERED . "' AND partners_id = " . $model->id;
    $connection = Yii::app()->db;
    $result = $connection->createCommand($sql)->queryAll(); ?>
    <li><?= floor($result[0]['price'] / ($result[0]['count'] ? $result[0]['count'] : 1)) ?> р.</li>
</ul>
<script>
    $("#select_period").change(function () {
        window.open('/admin/partner/id/<?php echo $model->id?>/<?=Yii::app()->controller->action->id;?>?period=' + $(this).val(), '_self');
    });
</script>
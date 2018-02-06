<?
/**
 * @var Partner $model
 * @var array $breadcrumbs
 * @var string $h1
 */
$this->breadcrumbs = array(
    'Админка' => array('/admin'),
    'Заказы' => array('admin'),
    'Графики' => array('graph'),
); ?>

<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>

<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <div style="display: inline-block;vertical-align: top;margin-right: 20px;width: 135px;">
        <? $this->renderPartial('column_nav'); ?>
    </div>
    <div style="display: inline-block">
        <h1>
            Статистика по <?= $model->name; ?>
        </h1>

        <? $countOrders = 0;
        foreach ($result as $r) {
            $countOrders += $r['amount'];
        } ?>
        <? $sum = 0;
        foreach ($resultSum as $r) {
            $sum += $r['amount'];
        }; ?>

        <p>Всего заказов за период: <?= $countOrders; ?></p>

        <p>На сумму: <?= number_format($sum, 0, '.', ' ') . ' ' . City::getMoneyKod(); ?></p>

        <p>Средний чек: <?= floor($sum / ($countOrders ? $countOrders : 1)); ?> р.</p>
        <? /*<p>Новых клиентов: </p>
        <p>Клиентов, сделавших более одного заказа: </p>
        <p>Лучший клиент: </p>*/ ?>


        <? $sql = "
                SELECT id, date, approved_site, approved_partner, delivered, cancelled, status
                FROM tbl_orders
                WHERE " . $this->bitweenDate() . " AND ((status = '" . Order::$DELIVERED . "'  AND approved_partner <> '00:00:00')
                OR status = '" . Order::$CANCELLED . "')
                AND partners_id = " . $model->id;
        $reactions = Yii::app()->db->createCommand($sql)->queryAll();
        $reactions_array = array();
        $sum_reaction_time = 0;
        foreach ($reactions as $reaction) {
            //сохраняем дату принятия заказа в формате UTC
            $date_time = strtotime($reaction['date']);
            //Сохраняем дату принятия заказа
            $date = date('Y-m-d', $date_time);
            $def = 0;
            if ($reaction['status'] == Order::$DELIVERED) {
                //Выясняем дату смены статуса
                $approvide_partner_time = strtotime($date . ' ' . $reaction['approved_partner']);
                if ($date_time > $approvide_partner_time) {
                    $approvide_partner_time = $approvide_partner_time + 60 * 60 * 24;
                }
                $def = $approvide_partner_time - $date_time;
            } elseif ($reaction['status'] == Order::$CANCELLED) {
                //Выясняем дату смены статуса
                $cancelled_time = strtotime($date . ' ' . $reaction['cancelled']);
                if ($date_time > $cancelled_time) {
                    $cancelled_time = $cancelled_time + 60 * 60 * 24;
                }
                $def = $cancelled_time - $date_time;
            }
            $reactions_array[] = $def;
            $sum_reaction_time += $def;
        }
        ?>
        <p>Время реагирования: мин. <?= $reactions_array ? min($reactions_array) : 0 ?> сек.,
            среднее <?= floor((($sum_reaction_time / 60) / (count($reactions_array) ? count($reactions_array) : 1))); ?>
            мин., макс. <?= $reactions_array ? floor(max($reactions_array) / 60) : 0 ?> мин.</p>





        <?
        $source = array('pc' => 0, 'mobile' => 0, 'android' => 0, 'ios' => 0);
        foreach ($result as $res) {
            switch ($res['order_source']) {
                case (Order::$SOURCE_ORDER_DESKTOP):
                    $source['pc'] += 1;
                    break;
                case (Order::$SOURCE_ORDER_MOBILE):
                    $source['mobile'] += 1;
                    break;
                case (Order::$SOURCE_ORDER_ANDROID_APP):
                    $source['android'] += 1;
                    break;
                case (Order::$SOURCE_ORDER_IOS_APP):
                    $source['ios'] += 1;
                    break;
            }
        }
        ?>
        <p>Источники: Сайт - <?= $source['pc'] ?> ,Моб.версия - <?= $source['mobile'] ?> , Android
            - <?= $source['android'] ?> , iOS - <?= $source['ios'] ?></p>






        <?
        $sql = "SELECT goods_id, SUM(goods_id) as sum_orders, SUM(`tbl_order_items`.quantity) as sum_goods
		FROM  `tbl_order_items`
		INNER JOIN tbl_orders ON `tbl_order_items`.order_id = `tbl_orders`.Id
		WHERE " . $this->bitweenDate() . " AND partners_id = " . $model->id . " AND status = '" . Order::$DELIVERED . "'
		GROUP BY goods_id
		ORDER BY `sum_goods` DESC
        LIMIT 3";

        $popular_goods = Yii::app()->db->createCommand($sql)->queryAll(); ?>
        <p>Популярные 3 блюда - <?= Goods::model()->findByPk($popular_goods[0]['goods_id'])->name; ?>
            - <?= $popular_goods[0]['sum_goods']; ?>
            , <?= Goods::model()->cache(10000)->findByPk($popular_goods[1]['goods_id'])->name; ?>
            - <?= $popular_goods[1]['sum_goods']; ?>
            , <?= Goods::model()->cache(10000)->findByPk($popular_goods[2]['goods_id'])->name; ?>
            - <?= $popular_goods[2]['sum_goods']; ?></p>





        <?
        $sql = "SELECT *, (SELECT SUM(total_price) FROM `tbl_order_items` WHERE order_id = `tbl_orders`.id) AS amount
		FROM  `tbl_orders`
		WHERE " . $this->bitweenDate() . " AND partners_id = " . $model->id . "
		GROUP BY date ORDER BY  `date` DESC";
        $detalisation = new CSqlDataProvider($sql, array(
            'totalItemCount' => 1000,
            'pagination' => array(
                'pageSize' => 1000,
            ),
        ));
        ?>
        <h2>Детализация</h2>

        <? $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $detalisation,
            'summaryText' => '',
            'columns' => array(
                array(
                    'name' => 'ID',
                    'value' => '$data["id"]',
                ),
                array(
                    'name' => 'Покупатель',
                    'value' => '$data["customer_name"]',
                ),
                array(
                    'name' => 'Дата',
                    'value' => '$data["date"]',
                ),
                array(
                    'name' => 'Статус',
                    'value' => '$data["status"]',
                ),
                array(
                    'name' => 'Сумма',
                    'value' => '$data["amount"]',
                ),
                array(
                    'name' => 'Источник заказа',
                    'value' => 'Order::getSourceName($data["order_source"])',
                ),
            ),
        )); ?>




        <h1>
            Количество заказов
        </h1>

        <div id="myfirstchart" style="height: 250px; width: 700px;"></div>

        <script>
            new Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'myfirstchart',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                    <? foreach ($result as $data) {?>
                    {day: "<?=$data['day']?>", value: <?=$data['amount']?>},
                    <?  }?>
                ],
                // The name of the data record attribute that contains x-values.
                xkey: 'day',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['value'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Заказы']
            });
        </script>

        <h1>
            Сумма заказов
        </h1>

        <div id="mysecondchart" style="height: 250px; width: 700px;"></div>


    </div>
</div>
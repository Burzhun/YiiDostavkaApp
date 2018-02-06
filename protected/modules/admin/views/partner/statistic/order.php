<?
/** @var Controller $this
 * @var array $result
 * @var array $resultSum
 * @var Partner $model
 * @var array $breadcrumbs
 * @var string $h1
 * @var PartnerController $this
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

<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <div style="display: inline-block;vertical-align: top;margin-right: 20px;width: 135px;">
        <? $this->renderPartial('statistic/column_nav', array('model' => $model)); ?>
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
        $sql = "SELECT goods_id, SUM(goods_id) AS sum_orders, SUM(`tbl_order_items`.quantity) AS sum_goods
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


        <h2>
            Количество заказов
        </h2>

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

        <h2>
            Сумма заказов
        </h2>

        <div id="mysecondchart" style="height: 250px; width: 700px;"></div>

        <script>
            new Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'mysecondchart',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                    <? foreach ($resultSum as $data) {?>
                    {day: "<?=$data['day']?>", value: <?=$data['amount']?>},
                    <?  }?>
                ],
                // The name of the data record attribute that contains x-values.
                xkey: 'day',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['value'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Сумма']
            });
        </script>
        <?

        $id = 'map' . $model->id . Yii::app()->session['period'] . Yii::app()->session['map_city'];
        $array = Yii::app()->cache->get($id);
        if ($array === false) {
            $list = "";
            $all = 0;
            $found = 0;
            $info = "";
            foreach ($orders as $item) {
                $s = "https://geocode-maps.yandex.ru/1.x/?results=2&format=json&geocode=город " . $item['city'] . ", " . $item['street'] . ", дом" . $item['house'];
                $data = json_decode(file_get_contents($s));
                $s = $data->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
                $precision = $data->response->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->precision;
                $a = explode(' ', $s);
                $all++;
                if ($precision == 'street' || $precision == 'exact') {
                    $list .= "[" . $a[1] . ', ' . $a[0] . '],';
                    $found++;
                    $price = $item->sum;
                    $info .= "'Адрес :" . $item['street'] . ' ' . $item['house'] . "</br>Стоимость:" . $price . "<br>Время заказа : " . $item['date'] . "<br>Телефон:" . $item['phone'] . "',";
                }

            }

            $array = array($list, $all, $found, $info);
            Yii::app()->cache->set($id, $array);
        }
        $list = $array[0];
        $all = $array[1];
        $found = $array[2];
        $info = $array[3]; ?>

        <div>
            <select style="width:135px;" id="select_map_city">
                <?php foreach (City::getCityList() as $city) { ?>
                    <option value="<?=$city->id?>" <? if (Yii::app()->session['map_city'] == $city->id) { ?> selected <? } ?>><?=$city->name?></option>
                <? } ?>
            </select>
        </div>

        <script>
            $("#select_map_city").change(function () {
                var t = window.location.href;
                if (t.indexOf('?') == -1) {
                    window.location.href = t + '?map_city=' + $(this).val();
                }
                else {
                    if (t.indexOf('map_city=') > 0) {
                        var n = t.indexOf('map_city=');
                        t = t.replace(t.charAt(n + 9), $(this).val());
                        window.location.href = t;
                    }
                    else {
                        window.location.href = t + '&map_city=' + $(this).val();
                    }

                }
            });
        </script>
        <span>Всего: <?= $all; ?></span>
        <span>Найдено: <?= $found; ?></span>

        <div id="map" style="width: 600px; height: 400px;margin-bottom:200px;"></div>
        <script type="text/javascript">
            //ymaps.ready(init);
            //var myMap;
            var coords = [<?=$city_coords;?>];
            ymaps.ready(function () {
                var myMap = new ymaps.Map('map', {
                        center: [<?=$city_coords;?>],
                        zoom: 8,
                        behaviors: ['default', 'scrollZoom']
                    }, {
                        searchControlProvider: 'yandex#search'
                    }),
                    /**
                     * Создадим кластеризатор, вызвав функцию-конструктор.
                     * Список всех опций доступен в документации.
                     * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Clusterer.xml#constructor-summary
                     */
                    clusterer = new ymaps.Clusterer({
                        /**
                         * Через кластеризатор можно указать только стили кластеров,
                         * стили для меток нужно назначать каждой метке отдельно.
                         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/option.presetStorage.xml
                         */
                        preset: 'islands#invertedVioletClusterIcons',
                        /**
                         * Ставим true, если хотим кластеризовать только точки с одинаковыми координатами.
                         */
                        groupByCoordinates: false,
                        /**
                         * Опции кластеров указываем в кластеризаторе с префиксом "cluster".
                         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/ClusterPlacemark.xml
                         */
                        clusterDisableClickZoom: false,
                        clusterHideIconOnBalloonOpen: false,
                        geoObjectHideIconOnBalloonOpen: false
                    }),
                    /**
                     * Функция возвращает объект, содержащий данные метки.
                     * Поле данных clusterCaption будет отображено в списке геообъектов в балуне кластера.
                     * Поле balloonContentBody - источник данных для контента балуна.
                     * Оба поля поддерживают HTML-разметку.
                     * Список полей данных, которые используют стандартные макеты содержимого иконки метки
                     * и балуна геообъектов, можно посмотреть в документации.
                     * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/GeoObject.xml
                     */
                    getPointData = function (index) {
                        return {
                            balloonContentBody: info[index],
                            clusterCaption: 'метка <strong>' + index + '</strong>'
                        };
                    },
                    /**
                     * Функция возвращает объект, содержащий опции метки.
                     * Все опции, которые поддерживают геообъекты, можно посмотреть в документации.
                     * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/GeoObject.xml
                     */
                    getPointOptions = function () {
                        return {
                            preset: 'islands#violetIcon'
                        };
                    },
                    points = [<?=$list;?>],
                    info = [<?=$info;?>],
                    geoObjects = [];

                /**
                 * Данные передаются вторым параметром в конструктор метки, опции - третьим.
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Placemark.xml#constructor-summary
                 */
                for (var i = 0, len = points.length; i < len; i++) {
                    geoObjects[i] = new ymaps.Placemark(points[i], getPointData(i), getPointOptions());
                }

                /**
                 * Можно менять опции кластеризатора после создания.
                 */
                clusterer.options.set({
                    gridSize: 80
                    //clusterDisableClickZoom: true
                });

                /**
                 * В кластеризатор можно добавить javascript-массив меток (не геоколлекцию) или одну метку.
                 * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/Clusterer.xml#add
                 */
                clusterer.add(geoObjects);
                myMap.geoObjects.add(clusterer);

                var objectState = clusterer.getObjectState(geoObjects[2]);
                if (objectState.isClustered) {
                    // Если метка находится в кластере, выставим ее в качестве активного объекта.
                    // Тогда она будет "выбрана" в открытом балуне кластера.
                    objectState.cluster.state.set('activeObject', geoObjects[2]);
                    clusterer.balloon.open(objectState.cluster);
                } else if (objectState.isShown) {
                    // Если метка не попала в кластер и видна на карте, откроем ее балун.
                    geoObjects[2].balloon.open();
                }
                /**
                 * Спозиционируем карту так, чтобы на ней были видны все объекты.
                 */

                myMap.setBounds(clusterer.getBounds(), {
                    checkZoomRange: true
                });
            });
        </script>

    </div>
</div>
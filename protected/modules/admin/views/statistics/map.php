<?php
?>
<div style="margin-left: 100px;">
    <br>
    <ul class="nav nav-pills nav-stacked" style="">
        <li>Период</li>
        <li>
            <select style="width:135px;" id="select_period">
                <option value="1" <? if (Yii::app()->session['period'] == 1) { ?> selected <? } ?>>Текущий месяц
                </option>
                <option value="2" <? if (Yii::app()->session['period'] == 2) { ?> selected <? } ?>>Прошлый месяц
                </option>
                <option value="3" <? if (Yii::app()->session['period'] == 3) { ?> selected <? } ?>>3 месяца</option>
                <option value="4" <? if (Yii::app()->session['period'] == 4) { ?> selected <? } ?>>Пол года</option>
                <option value="5" <? if (Yii::app()->session['period'] == 5) { ?> selected <? } ?>>Год</option>
                <option value="6" <? if (Yii::app()->session['period'] == 6) { ?> selected <? } ?>>Весь период</option>
            </select>
        </li>
    </ul>
    <script>
        $("#select_period").change(function () {
            window.open('http://<?=$_SERVER['HTTP_HOST'];?>/admin/statistics/<?=Yii::app()->controller->action->id;?>?period=' + $(this).val(), '_self');
        });
    </script>

    <?
    $id = 'map' . Yii::app()->session['period'] . Yii::app()->session['map_city'];
    $array = Yii::app()->cache->get($id);
    if ($array === false) {
        $list = "";
        $all = 0;
        $found = 0;
        $info = "";
        foreach ($orders as $item) {
            $s = "https://geocode-maps.yandex.ru/1.x/?results=2&format=json&geocode=город " . $item['city'] . ", " . urlencode($item['street']) . ", дом" . $item['house'];
            $d=@file_get_contents(trim($s));
            if($d==false){
                continue;
            }
            $data = json_decode($d);
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
    $info = $array[3];
    ?>
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

    <div id="map" style="width: 600px; height: 400px;"></div>
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
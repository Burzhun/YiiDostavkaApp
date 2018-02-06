<? Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile('/css/datepicker.css'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery.datepicker.regional['ru'] = {
            closeText: 'Закрыть',
            prevText: '&#x3c;Пред',
            nextText: 'След&#x3e;',
            currentText: 'Сегодня',
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
                'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
            dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
            dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
            dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            weekHeader: 'Нед',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        jQuery.datepicker.setDefaults(jQuery.datepicker.regional['ru']);
    });


</script>
<br><br>
<div class="well well-bottom">
    <div style="font-size:16px;margin-left: 114px;">
        Выберите город <select id="city_selector">
            <option value="0" <?=isset($_GET['city_id']) ? '' : 'selected';?>>Выберите город</option>
            <?foreach(City::model()->findAll() as $city){?>
                <option value="<?=$city->id;?>" <?=$_GET['city_id']==$city->id ? 'selected' : '';?>><?=$city->name;?></option>
            <?}?>
        </select><br>
        От: <input class="datepicker" id="from" value="<?= $date_from; ?>">
        До:<input class="datepicker" id="to" value="<?= $date_to; ?>">
        <button id="reload" class="btn btn-success">Обновить</button><br><br>
        <script>
            jQuery(document).ready(function(){
                jQuery(".datepicker").datepicker();
                var url = '/admin/statistics/orders_time?';
                jQuery("#reload").click(function () {
                    var city_id=jQuery("#city_selector").val();
                    if(city_id!=0) url+='city_id='+city_id+'&';
                    var date_from = jQuery("#from").val();
                    var date_to = jQuery("#to").val();
                    if (date_from != '') {
                        if (date_to != '') {
                            window.location.href = url + 'date_from=' + date_from + '&date_to=' + date_to;
                        }
                        else {
                            window.location.href = url + 'date_from=' + date_from
                        }
                    }
                    else {
                        if (date_to != '') {
                            window.location.href = url + 'date_to=' + date_to
                        }
                        else {
                            window.location.href = url;
                        }
                    }
                });


                return false;
            });
        </script>

    </div>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['hour','Среднее количество заказов за период', 'Заказы на сегодня'],
                <?for($i=0;$i<24;$i++){?>
                ['<?=$i;?>', <?=number_format($data_av[$i],2);?>,<?=isset($data[$i]) ? $data[$i] : 'null';?>],
                <?}?>
            ]);

            var options = {
                title: 'График заказов',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);



        }
    </script>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>


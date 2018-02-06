<? Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerCssFile('/css/datepicker.css'); ?>
<script type="text/javascript">
    jQuery(function ($) {
        $.datepicker.regional['ru'] = {
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
        $.datepicker.setDefaults($.datepicker.regional['ru']);
    });
    jQuery(window).load(function () {
        jQuery(".datepicker").datepicker();
        jQuery(function () {
            var url = '<?=$period_url;?>';
            jQuery("#reload").click(function () {
                var date_from = jQuery("#from").val();
                var date_to = jQuery("#to").val();
                if (date_from != '') {
                    if (date_to != '') {
                        window.location.href = url + 'from=' + date_from + '&to=' + date_to;
                    }
                    else {
                        window.location.href = url + 'from=' + date_from
                    }
                }
                else {
                    if (date_to != '') {
                        window.location.href = url + 'to=' + date_to
                    }
                    else {
                        window.location.href = url;
                    }
                }
            });
        });
    });

</script>
<style>
    #statistics {
        margin: 40px;
    }

    #statistics .stat {
        margin: 10px;
    }

    #menu {
        margin-left: 50px;
        margin-top: 20px;
    }

    #menu .menu_item {
        display: inline-block;
        color: white;
        background-color: #0088cc;
        padding: 6px;
        border-radius: 7px;
    }

    #menu .<?=$class;?> {
        background-color: white;
        color: black;
    }

    #partner_selector {
        margin-left: 40px;
        margin-top: 15px;
    }
</style>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Platform', 'Users'],
            <? foreach($stat as $key=>$value) {?>
            ['<?=$key;?>', <?=$value;?>],
            <?}?>
        ]);

        var options = {
            title: 'Количество пользователей с каждой платформы <?=$text;?>'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }


</script>

<div id="partner_selector">
    <select id="sel">
        <? $partner_id = isset($_GET['partner_id']) ? $_GET['partner_id'] : 0; ?>
        <option value="0">Выберите партнера</option>
        <? foreach ($partners as $partner) { ?>
            <? if ($partner_id == $partner->id) { ?>
                <option selected value="<?= $partner->id; ?>"><?= $partner->name; ?></option>
            <? } else { ?>
                <option value="<?= $partner->id; ?>"><?= $partner->name; ?></option>
            <? } ?>
        <? } ?>
    </select>
    От: <input class="datepicker" id="from" value="<?= $_GET['from']; ?>">
    До:<input class="datepicker" id="to" value="<?= $_GET['to']; ?>">
    <button id="reload" class="btn btn-success">Обновить</button>
</div>
<div id="statistics">
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</div>
<script>
    var partner_url = '<?=$partner_url;?>';
    var jq = $.noConflict();
    jq(document).ready(function () {
        jq("#sel ").change(function () {
            var id = jq("#sel option:selected").val();
            if (id > 0) {
                partner_url += 'partner_id=' + id
            }
            else {
                partner_url = partner_url.substr(0, partner_url.length - 1);
            }

            window.location.href = partner_url;
        });
    });
</script>
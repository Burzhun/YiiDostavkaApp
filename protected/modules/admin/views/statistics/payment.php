<? Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
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
</script>
<div id="container">
    <br><br>

    <div>Информация по платежам</div>
    <br><br>
    От: <input class="datepicker" id="from" value="<?= $_GET['from']; ?>">
    До:<input class="datepicker" id="to" value="<?= $_GET['to']; ?>">
    <button id="reload" class="btn btn-success">Обновить</button>
    <br><br><br><br>
    <script>

        $(".datepicker").datepicker();
        $(function () {
            var url = '<?=$url_date;?>';
            $("#reload").click(function () {
                var date_from = $("#from").val();
                var date_to = $("#to").val();
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
    </div>
    <script>
        var partner_url = '<?=$url_partner;?>';
        $(document).ready(function () {
            $("#sel ").change(function () {
                var id = $("#sel option:selected").val();
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
    <? if($type==''){?>
        <a class='btn btn-success' href="/admin/statistics/payment2">Показать все начисления</a>
    <?}else{?>
        <a class='btn btn-success' href="/admin/statistics/payment">Показать все платежи</a>
    <?}?>

    <?
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $data,
        'columns' => array(
            array(
                'name' => 'id',
                'htmlOptions' => array('width' => '20px')
            ),
            array(
                'name' => 'Партнер',
                'value' => 'Partner::model()->findByPk($data->partner_id)->name',
                'htmlOptions' => array('width' => '40px'),
            ),
            array(
                'name' => 'date',
                'value' => 'date("j.m.Y H:i", $data["date"])',
                'htmlOptions' => array('width' => '40px'),
            ),
            array(
                'name' => 'sum',
                'value' => '$data->sum()',
                'htmlOptions' => array('width' => '40'),
            ),
            array(
                'name' => 'balance_before',
                'htmlOptions' => array('width' => '40px'),
            ),
            array(
                'name' => 'balance_after',
                'htmlOptions' => array('width' => '40px'),
            ),
            array(
                'name' => 'info',
                'type' => 'html',
                //'value'=>'htmlspecialchars_decode($data->info)'
                'htmlOptions' => array('width' => '40px'),
            ),
            array(
                'name' => 'author',
                'htmlOptions' => array('width' => '40px'),
            ),

        )
    ));
    ?>

    <style>
        .grid-view table.items {
            width: 1200px;;
        }

        .grid-view table.items th, .grid-view table.items td {
            text-align: center;
        }

        #container {
            padding-left: 10px;
        }
    </style>
</div>

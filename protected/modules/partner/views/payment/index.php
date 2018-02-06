<? $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<? Yii::app()->clientScript->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
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
От: <input class="datepicker" id="from" value="<?= $_GET['from']; ?>">
До:<input class="datepicker" id="to" value="<?= $_GET['to']; ?>">
<button id="reload">Обновить</button>
<br><br><br><br>
<script>

    $(".datepicker").datepicker();
    $(function () {
        var url = '/partner/payment?";
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
<?
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $data,
    'columns' => array(
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
            'type' => 'raw',
            'name' => 'info',
            'value' => 'CHtml::link($data["info"], "orders/".$data["id"])',
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

</style>
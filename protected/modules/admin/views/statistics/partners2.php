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
    $(window).ready(function () {
        $(".datepicker").datepicker();
    });

</script>
<div style="padding: 20px">
    <div>
        От: <input class="datepicker" id="from" value="<?= $_GET['from']; ?>">
        <span style="margin:0px 10px">До</span>:<input class="datepicker" id="to" value="<?= $_GET['to']; ?>">
        <button id="reload" class="btn btn-success" style="margin:0px 10px">Обновить</button>
    </div><br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'id' => 'my-grid',
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            'name',
            array(
                'header'=>'Кол-во заказов',
                'value'=>'number_format($data->OrdersCount(),0,"."," ")'
            ),
            array(
                'header'=>'Сумма заказов',
                'value'=>'number_format($data->OrdersSum(),0,"."," ")'
            ),
            array(
                'header'=>'Проценты',
                'value'=>'number_format($data->OrdersSum()*$data->procent_deductions/100,0,"."," ")'
            ),
            array(
                'header'=>'Отказы',
                'value'=>'number_format($data->CancelsCount(),0,"."," ")'
            ),
            array(
                'header'=>'Ср. чек',
                'value'=>'number_format($data->OrdersCount()==0 ? 0 :$data->OrdersSum($date_sql)/$data->OrdersCount(),2,"."," ")'
            ),
            array(
                'header'=>"Ср. время реагирования",
                'value' =>'number_format($data->AverageCheck(),2,"."," ")'
            )
        ),
    ));
    ?>
</div>
<style>
    .items td{
        text-align: right;
    }
    .items tr td:first-child{
        text-align: left;
    }
     .items th.header {
         background-position: center right;
         background-repeat: no-repeat;
         padding-right: 19px !important;
     }

    th.headerSortDown {
        background-image: url('/images/desc.gif');
    }

    th.headerSortUp {
        background-image: url('/images/asc.gif');
    }
</style>
<script>
    $(window).load(function () {
        $("#reload").click(function () {
            var url = "<?=$url_date;?>";
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
    $(document).ready(function () {
        $(".items").tablesorter({
            textExtraction: function(node) {
                return parseInt($(node).text().replace(/\s/g, ''));
            }
        });
    });
</script>

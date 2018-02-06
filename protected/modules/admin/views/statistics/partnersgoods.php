<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
<? Yii::app()->clientScript->registerScriptFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js');
Yii::app()->clientScript->registerCssFile('/css/datepicker.css'); ?>
<script type="text/javascript">
    $(function ($) {

    });
    $(window).load(function () {
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
        $(".datepicker").datepicker();
    });

</script>
<div class="well">
    <ul class="nav nav-pills nav-stacked">
        <li width="150px">
            <a href="/admin/statistics/partners<?= $url; ?>">Заказы</a>
        </li>
        <li class="active" width="150px">
            <a href="/admin/statistics/partnersgoods<?= $url; ?>">Товары</a>
        </li>

        <br><br>
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
        </select><br><br>
        <li>Период</li>
        От: <input class="datepicker" id="from" value="<?= $_GET['from']; ?>">
        До:<input class="datepicker" id="to" value="<?= $_GET['to']; ?>">
        <button id="reload" class="btn btn-success">Обновить</button>

        <br><br><br>
        <li>Средняя сумма чека</li>


    </ul>
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
    </script>
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
</div>
<div class="well" style="width:800px;">

    <div style="display: inline-block">
        <h1>
            Заказываемые товары
        </h1>

        <br><br>

        <? $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'project-grid',
            'dataProvider' => $dataProvider,
            'htmlOptions' => array('class' => ''),
            'summaryText' => '',
            'columns' => array(
                array(
                    'type' => 'raw',
                    'header' => 'Количество купленных     ',
                    'value' => '$data["sum_goods"]',
                ),
                array(
                    'class' => 'CLinkColumn',
                    'header' => 'Товары',
                    'labelExpression' => 'Goods::model()->cache(40000)->findByPk($data["goods_id"])->name ? Goods::model()->cache(40000)->findByPk($data["goods_id"])->name : "--Товар удален--"',
                    'urlExpression' => '"/partner/menu/product/" . $data["goods_id"]',
                    'linkHtmlOptions' => array('target' => '_blank'),
                ),
                array(
                    'type' => 'raw',
                    'header' => 'Изображение',
                    'value' => '"<img style=max-width:150px;max-height:150px; src=/upload/goods/".Goods::model()->cache(40000)->findByPk($data["goods_id"])->img.">"',
                ),
                /*array(
                    'class' => 'CLinkColumn',
                    'header'=>'Название',
                    'labelExpression'=>'$data->name',
                    'urlExpression'=> '"/admin/catalog/index/parent_id/".$data->id',
                ),
                array(
                    'name' => 'actions',
                    'header' => 'Количество подкатегорий',
                    'value' => 'count($data->Sub)',
                    'filter' => false,
                ),
                array(
                    'header' => 'Операции',
                    'class' => 'CButtonColumn',
                    'template'=>'{update}{delete}',
                    'buttons'=>array (
                        'update' => array (
                            'label'=>'Редактировать',//Text label of the button.
                            'imageUrl'=>false,  //Image URL of the button.
                            'options'=>array('style'=>'margin-bottom: 5px;', 'class'=>'btn btn-success')
                        )
                    )
                ),*/
            ),
        )); ?>
        <a href="/partner/statistic/goods?page_size=10000"></a>
    </div>
</div>
<style>
    .datepicker {
        display: block;
        width: 130px;
        margin-left: 10px;
    }

    .well {
        display: inline-block;
        width: 300px;
        vertical-align: top;
    }

    .well ul li {
        width: 150px;
    }
</style>
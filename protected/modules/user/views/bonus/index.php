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
<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="Userbonus"><span>У Вас</span>    <?= User::getBonus($model->id); ?> <span>баллов</span></div>
    <div class="Aboutbonus"><a href="/bonus">Что мне дают баллы?</a></div>
    <div class="blok">
        <?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)); ?>
        <br><br>
        <script>

            $(window).ready(function () {
                $(".datepicker").datepicker();
                $("#update").click(function () {
                    var url = '/user/bonus?';
                    var date_from = $("#date_from").val();
                    var date_to = $("#date_to").val();
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
                $("#reset").click(function () {
                    var url = '/user/bonus?';
                    window.location.href = url;
                });
            });
        </script>
        <div style="padding-left: 10px;font-size: 18px;">
            С <input id="date_from" class="datepicker" type="text" value="<?= $_GET['date_from']; ?>"
                     style="height: 20px;margin: 0 5px;">
            По <input id="date_to" class="datepicker" type="text" value="<?= $_GET['date_to']; ?>"
                      style="height: 20px;margin: 0 5px;">
            <button id="update">Показать</button>
            <button id="reset">Сбросить</button>
        </div>
        <style>
            #update {
                padding: 7px;
                background-color: #14B114;
                border: none;
                color: white;
                border-radius: 7px;
                margin-right: 10px;
                cursor: pointer;
            }

            #reset {
                padding: 7px;
                background-color: #b1100b;
                border: none;
                color: white;
                border-radius: 7px;
                cursor: pointer;
            }
        </style>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'order-grid',
            'dataProvider' => $user_bonus->search(array('user_id' => Yii::app()->user->id)),
            'filter' => $user_bonus,
            'emptyText' => 'Ничего не найдено',
            'summaryText' => '',
            'htmlOptions' => array('class' => 'table_order'),
            'columns' => array(
                array(
                    'header' => 'Дата активности',
                    'value' => 'date("Y.m.d",$data->date)'
                ),
                array(
                    'header' => 'Описание',
                    'value' => '$data->info',
                ),
                array(
                    'header' => 'Количество',
                    'value' => '$data->sum_in_start',
                ),
            ),
        ));
        ?>
    </div>
</div>
<script>
    $(window).ready(function () {
        $(".how_to_get_bonus").click(function () {
            $('#invite_friend_layer').show();
            $("#invite_friend").show();
        });
    });
</script>
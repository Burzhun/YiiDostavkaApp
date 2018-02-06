<?
Yii::app()->clientScript->registerCssFile('/css/datepicker.css'); ?>
<script type="text/javascript">
    $(window).load(function () {
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
    });
</script>
<br><br>
<div class="well well-bottom">
    <div style="font-size:16px;">
        От: <input class="datepicker" id="from" value="<?= $_GET['from']; ?>">
        До:<input class="datepicker" id="to" value="<?= $_GET['to']; ?>">
        <button id="reload" class="btn btn-success">Обновить</button>
        <br><br>
        <script>
            $(window).load(function () {
                jQuery(".datepicker").datepicker();
                var url = '/admin/statistics/users?';
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
        <h1>Информация по пользователям</h1><br>

        <div>
            Общее количество ползователей <?= $users_count; ?>
        </div>
        <div>
            За период зарегистрировались <?= $users_count2; ?>
        </div>
        <div>
            Активных пользователей <?= $active_users; ?>
        </div>
    </div>
    <br><br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(array('nopartner' => '1')),
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'id',
                'filter' => CHtml::textField("User[id]", $model->id, array("class" => "input-mini", 'style' => 'width:50px;')),
            ),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, array("/admin/user/id/".$data->id."/orders/"))',
                'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'model' => $model,
                    'attribute' => 'name',
                    'source' => $this->createUrl('/request/suggestName'),
                    'options' => array(
                        'focus' => "js:function(event, ui) {
						$('#" . CHtml::activeId($model, 'name') . "').val(ui.item.value);
					}",
                    ),
                    'htmlOptions' => array(
                        'style' => 'width:100px;'
                    ),
                ), true),
            ),
            array(
                'name' => 'phone',
                'filter' => CHtml::textField("User[phone]", $model->phone, array("class" => "input-mini", 'style' => 'width:100px;')),
            ),
            array(
                'name' => 'email',
                'filter' => CHtml::textField("User[email]", $model->email, array("class" => "input-mini", 'style' => 'width:100px;')),
            ),
            array(
                'header' => 'Тип',
                'value' => '$data->role=="" ? "user" : $data->role',
                'filter' => CHtml::textField("User[role]", $model->role, array("class" => "input-mini", 'style' => 'width:30px;')),
            ),
            array(
                'name' => 'reg_date',
                'filter' => CHtml::textField("User[reg_date]", $model->reg_date, array("class" => "input-mini", 'style' => 'width:100px;')),
            ),
            array(
                'name' => 'birthdate',
                'filter' => CHtml::textField("User[birthdate]", $model->birthdate, array("class" => "input-mini", 'style' => 'width:100px;')),
            ),
            array(
                'name' => 'pol',
                'filter' => CHtml::textField("User[pol]", $model->pol, array("class" => "input-mini", 'style' => 'width:30px;')),
            ),
            array(
                'name' => 'last_visit',
                'filter' => CHtml::textField("User[last_visit]", $model->last_visit, array("class" => "input-mini", 'style' => 'width:130px;')),
            ),
            array(
                'name' => 'total_order',
                'filter' => CHtml::textField("User[total_order]", $model->total_order, array("class" => "input-mini", 'style' => 'width:100px;')),
            ),
            array(
                'name' => 'bonus',
                'type' => 'raw',
                'value' => '$data->getTotalBonus()',
            ),
            array(
                'header' => 'Оплачено',
                'type' => 'raw',
                'value' => '$data->getGaveTotalMoney()',
            ),
            array(
                'header' => 'Доставлено',
                'value' => '$data->getDeliveredOrdersCount()',
            ),
            array(
                'header' => 'Отмененных заказов',
                'value' => '$data->getCancelledOrdersCount()',
            ),
            array(
                'header' => 'Проценты за заказ',
                'type' => 'raw',
                'value' => '$data->getTotalProcent()',
            ),
        ),
    ));
    ?>
</div>

<style type="text/css">
    .well-bottom {
        padding-left: 25px;
    }
</style>
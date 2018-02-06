<?php /**
 * @var User $model
 * @var array $breadcrumbs
 * @var string $h1
 */ ?>
<?php $this->renderPartial('../layouts/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
<? //$balance = OrderPartnerDebt::getDataForGraphic($model->id);//выборка всех неоплаченных заказов?>

<div class="well">
    <div style="min-width:800px;">
        <div>
            <? echo CHtml::ajaxLink('Список неоплаченных заказов', array('/partner/orderdebt/getOrderDebtList/'), array('update' => '#debtlist')); ?>
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <? /*<a href="/partner/orderdebt/removeOrderDebt/">Снять задолженость</a>*/ ?>
        </div>
        <br><br>

        <div id='debtlist'></div>

        <? $debt = OrderPartnerDebt::getDataForGraphic($model->id); //выборка всех неоплаченных заказов?>
        <? if ($debt) { ?>
            <?
            $arr_keys = array_keys($debt);//берем все ключи-даты
            $date_begin = min($arr_keys);//находим среди них минимальную дату(дату начало)
            $date_end = max($arr_keys);//и максимальную(дату конец)

            $date = $date_begin;
            ?>

            <script type="text/javascript" src="https://www.google.com/jsapi"></script>

            <script type="text/javascript">
                google.load('visualization', '1.1', {packages: ['corechart', 'controls']});
            </script>
            <script type="text/javascript">
                function drawVisualization() {
                    var dashboard = new google.visualization.Dashboard(
                        document.getElementById('dashboard'));

                    var control = new google.visualization.ControlWrapper({
                        'controlType': 'ChartRangeFilter',
                        'containerId': 'control',
                        'options': {
                            // Filter by the date axis.
                            'filterColumnIndex': 0,
                            'ui': {
                                'chartType': 'LineChart',
                                'chartOptions': {
                                    'chartArea': {'width': '100%'},
                                    'hAxis': {'baselineColor': 'none'}
                                },
                                // Display a single series that shows the closing value of the stock.
                                // Thus, this view has two columns: the date (axis) and the stock value (line series).
                                'chartView': {
                                    'columns': [0, 1]
                                },
                                // 1 day in milliseconds = 24 * 60 * 60 * 1000 = 86,400,000
                                'minRangeSize': 86400000
                            }
                        },
                        // Initial range: находим и ставим начало в середину
                        <?/*if(count($arr_keys)>2){?>'state': {'range': {'start': new Date(<?echo date('Y, m, d', strtotime($arr_keys[floor(count($arr_keys)/2)]));?>)}}<?}*/?>
                    });

                    var chart = new google.visualization.ChartWrapper({
                        'chartType': 'AreaChart',
                        'options': {
                            'focusTarget': 'category'
                        },
                        'containerId': 'chart'
                    });

                    var data = new google.visualization.DataTable();
                    data.addColumn('date', 'Date');
                    data.addColumn('number', 'Сумма заказов');

                    <?$i=0; while($date <= $date_end){$i++;//идем от мин даты до макс с интервалом в 1 день?>
                    date = new Date(<?echo date('Y, m-1, d', strtotime($date));?>);
                    data.addRow([date, <?if(in_array($date, $arr_keys)){echo $debt[$date];}else{echo "0";}?>]);

                    <? $date = date('Y-m-d', strtotime($date) + 86400); // где 86400 - количество секунд в одном дне (24*60*60)
                if($i == 800){break;}
                } ?>
                    dashboard.bind(control, chart);
                    dashboard.draw(data);
                }

                google.setOnLoadCallback(drawVisualization);
            </script>

            <h2>Задолженность - <?= OrderPartnerDebt::getDebt($model->id) ?>руб</h2>
            <div id="dashboard">
                <div id="chart" style='width: 915px; height: 300px;'></div>
                <div id="control" style='width: 915px; height: 50px;'></div>
            </div>
        <? }else{ ?>
            <h2>Задолженности нет</h2>
        <? } ?>

        <br>

        <br><br>
    </div>
</div>
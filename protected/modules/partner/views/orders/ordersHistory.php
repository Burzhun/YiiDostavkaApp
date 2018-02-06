<?php /**
 * @var Partner $partner
 * @var CActiveDataProvider $dataProvider
 * @var array $breadcrumbs
 * */ ?>
<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => $breadcrumbs,
));
?>

<div class="h1-box">
    <div class="well">
        <h1>История заказов
            <? if (!empty($_GET['partner_id'])) {
                $partner = Partner::model()->findByPk($_GET['partner_id']);
                echo " - " . $partner->name;
            } else {
                $partner = "";
            }
            ?>
        </h1>
    </div>
</div>
<? /* $this->breadcrumbs=array(
	'Админка'=>array('/partner'),
	'Заказы'=>array('/partner/orders'),
	'История заказов'=>array('/partner/orders/history'),
);*/ ?>
<div class="table12">
    <style>
        .no_table .table {
            width: auto;

            border: 0;
        }

        .no_table table {
            border: 1px solid #ddd;
        }

        .span-18 {
            float: none;
        }

        .pager .next a {
            float: none;
        }

        .pager {
            text-align: left;
        }

        .table12 {
            padding: 10px 0 10px 20px;
        }
    </style>
    <br>
    <a class="btn <? if (isset($_GET['period'])) {
        if ($_GET['period'] == 'day') { ?>btn-primary<? }
    } ?>" href="/partner/orders/history?period=day">Дни</a>
    <a class="btn <? if (isset($_GET['period'])) {
        if ($_GET['period'] == 'month') { ?>btn-primary<? }
    } ?>" href="/partner/orders/history?period=month">Месяцы</a>
    <a class="btn <? if (isset($_GET['period'])) {
        if ($_GET['period'] == 'year') { ?>btn-primary<? }
    } ?>" href="/partner/orders/history?period=year">Года</a>
    <br><br>

    <div class='no_table'>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'summaryText' => '',
            //'cssFile'=>'/css/tableColorRow.css',
            //'rowCssClassExpression'=>'Order::getRowColor($data->status)',
            'htmlOptions' => array('class' => 'table table-bordered table_box'),
            'columns' => array(
                array(
                    'header' => 'Дата',
                    'name' => 'p_date',
                    'value' => '($data["p_date"] != "1970" && $data["p_date"] != "1970-01" && $data["p_date"] != "1970-01-01") ? $data["p_date"] : ""',
                ),
                array(
                    'header' => 'Колличество заказов',
                    'name' => 'count',
                ),
                array(
                    'header' => 'Сумма заказов',
                    'name' => 'sum',
                ),
            ),
        )); ?>
    </div>
</div>

<script>
    $('.relation_partner').change(function () {
        var kalendar = "<?if(isset($_GET['period'])){echo 'period='.$_GET['period'].'&';}else{echo '';}?>";
        window.location = '/partner/orders/history?' + kalendar + "partner_id=" + this.value;
    });
</script>
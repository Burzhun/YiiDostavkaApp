<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Главная' => '/admin', 'Заказы' => '/admin/order', 'История заказов'),
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

        <select name="relation_partner" class="relation_partner">
            <option value="">Выберите партнера</option>
            <? $partners = Partner::model()->findAll(array('order' => 'name'));
            foreach ($partners as $p) { ?>
                <option value="<? echo $p->id ?>" <? if (!empty($_GET['partner_id'])) if ($p->id == $partner->id) {
                    echo "selected";
                } ?>><? echo $p->name ?></option>
            <? } ?>
        </select>
    </div>
</div>

<?php
$this->breadcrumbs = array(
    'Админка' => array('/admin'),
    'Заказы' => array('/admin/order'),
    'История заказов' => array('/admin/order/history'),
);
?>
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
            padding: 10px 0px 10px 20px;
        }
    </style>
    <br>
    <a class="btn <? if (isset($_GET['period'])) {
        if ($_GET['period'] == 'day') { ?>btn-primary<? }
    } ?>"
       href="/admin/historyOrder?period=day<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Дни</a>
    <a class="btn <? if (isset($_GET['period'])) {
        if ($_GET['period'] == 'month') { ?>btn-primary<? }
    } ?>"
       href="/admin/historyOrder?period=month<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Месяцы</a>
    <a class="btn <? if (isset($_GET['period'])) {
        if ($_GET['period'] == 'year') { ?>btn-primary<? }
    } ?>"
       href="/admin/historyOrder?period=year<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Года</a>
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
                array(
                    'header' => 'Проценты',
                    'name' => 'sum2',
                ),
                array(
                    'header' => 'Принято по телефону',
                    'value' => 'Order::getAdminOrdersCount($data["p_date"])',
                ),
            ),
        )); ?>
    </div>
</div>

<script>
    $('.relation_partner').change(function () {
        var kalendar = "<?if(isset($_GET['period'])){echo 'period='.$_GET['period'].'&';}else{echo '';}?>";
        window.location = '/admin/historyOrder?' + kalendar + "partner_id=" + this.value;
    });
</script>
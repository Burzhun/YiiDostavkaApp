<?php $this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => false,
    'separator' => ' / ',
    'links' => array('Главная' => '/admin', 'Информация по платежам'),
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

        <?/* <select name="relation_partner" class="relation_partner">
            <option value="<?=$this->createUrl('', array_merge($_GET, array('partner_id' => '')));?>">Выберите партнера</option>
            <? $partners = Partner::model()->findAll(array('order' => 'name'));
            foreach ($partners as $p) { ?>
                <option value="<?=$this->createUrl('', array_merge($_GET, array('partner_id' => $p->id)));?>" <? if (!empty($_GET['partner_id']) && $_GET['partner_id'] == $p->id){
                    echo "selected";
                }?>><? echo $p->name ?></option>
            <? } ?>
        </select>*/?>

        <select class="relation_admin" style="margin-left:10px;">
            <option value="<?=$this->createUrl('', array_merge($_GET, array('admin_id' => '')));?>">Выберите оператора</option>
            <option value="<?=$this->createUrl('', array_merge($_GET, array('admin_id' => 'all')));?>"
            <? if (!empty($_GET['admin_id']) && $_GET['admin_id'] == 'all'){
                    echo "selected";
                }?>
            >Все операторы</option>
            <? $admins = User::getAdmins();
            foreach ($admins as $admin) { ?>
                <option value="<?=$this->createUrl('', array_merge($_GET, array('admin_id' => $admin->id)));?>" <? if (!empty($_GET['admin_id']) && $_GET['admin_id'] == $admin->id){
                    echo "selected";
                }?>><? echo $admin->name ?></option>
            <? } ?>
        </select>
        <select class="city_selector">
            <option value="<?=$this->createUrl('', array_merge($_GET, array('city_id' => '')));?>" <?=isset($_GET['city_id']) ? '': 'selected';?>>Выберите город</option>
            <?foreach(City::model()->findAll() as $city){?>
            <option value="<?=$this->createUrl('', array_merge($_GET, array('city_id' => $city->id)));?>" <?=$_GET['city_id']==$city->id ? 'selected' : '';?>><?=$city->name;?></option>
            <?}?>
        </select>
    </div>
</div>

<?php
$this->breadcrumbs = array(
    'Админка' => array('/admin'),
    'Заказы' => array('/admin/order'),
    'Стаистика по заказам' => array('/admin/order/history'),
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
    <a class="btn <? if ((isset($_GET['period']) && ($_GET['period'] == 'day')) || !isset($_GET['period'])) { ?>btn-primary<? } ?>"
       href="/admin/statistics/orders?admin_id=<?=$_GET['admin_id'];?>&period=day<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Дни</a>
    <a class="btn <? if (isset($_GET['period']) && $_GET['period'] == 'month') { ?>btn-primary<? } ?>"
       href="/admin/statistics/orders?admin_id=<?=$_GET['admin_id'];?>&period=month<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Месяцы</a>
    <a class="btn <? if (isset($_GET['period']) && $_GET['period'] == 'year') { ?>btn-primary<? } ?>"
       href="/admin/statistics/orders?admin_id=<?=$_GET['admin_id'];?>&period=year<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Года</a>
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
                    'value' => '($data["p_date"] != "1970" && $data["p_date"] != "1970-01" && $data["p_date"] != "1970-01-01") ? Order::FormatDateDay($data["p_date"]) : ""',
                ),
                array(
                    'header' => 'Колличество заказов',
                    'name' => 'count',
                ),
                array(
                    'header' => 'Сумма заказов',
                    'value' => 'round($data["sum1"]*10)/10'
                ),                
                array(
                    'header' => 'Проценты2',
                    'value' => 'Payment_history::Sum2($data["p_date"],0, $_GET["admin_id"])'
                ),
                array(
                    'header' => 'Принято по телефону',
                    'value' => 'Order::getAdminOrdersCount($data["p_date"],0, $_GET["admin_id"])',
                ),
                array(
                    'header' => "Компьютер",
                    'value' => '$data["pc_count"]'
                    //'value' => '$data["pc_count"]'
                ),
                array(
                    'header' => "Mobile",
                    'value' => '$data["mobile_count"]'
                ),
                array(
                    'header' => "Приложение",
                    'value' => '$data["app_count"]'
                ),
                array(
                    'header' => "Средний чек",
                    'value' => 'Order::averageCheck($data["count"], round($data["sum"]*10)/10)'
                ),
            ),
        )); ?>
    </div>
</div>

<script>
   /* $('.relation_partner').change(function () {
        var kalendar = "<?if(isset($_GET['period'])){echo 'period='.$_GET['period'].'&';}else{echo '';}?>";
        window.location = '/admin/statistics/orders?' + kalendar + "partner_id=" + this.value;
    });*/

    $('.relation_admin').change(function () {
        window.location = $(this).val();
    });
    $('.city_selector').change(function () {
       window.location = $(this).val();
    });
     $('.relation_partner').change(function () {
        window.location = $(this).val();
    });
</script>
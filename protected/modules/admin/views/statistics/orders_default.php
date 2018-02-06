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
        <?/*
        <select name="relation_partner" class="relation_partner">
            <option value="<?=$this->createUrl('', array_merge($_GET, array('partner_id' => '')));?>">Выберите партнера</option>
            <? $partners = Partner::model()->findAll(array('order' => 'name'));
            foreach ($partners as $p) { ?>

                <option value="<?=$this->createUrl('', array_merge($_GET, array('partner_id' => $p->id)));?>" <? if (!empty($_GET['partner_id']) && $_GET['partner_id'] == $p->id){
                    echo "selected";
                }?>><? echo $p->name ?></option>
            <? } ?>
        </select>
        */?>
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
       href="/admin/statistics/orders?period=day<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Дни</a>
    <a class="btn <? if (isset($_GET['period']) && $_GET['period'] == 'month') { ?>btn-primary<? } ?>"
       href="/admin/statistics/orders?period=month<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Месяцы</a>
    <a class="btn <? if (isset($_GET['period']) && $_GET['period'] == 'year') { ?>btn-primary<? } ?>"
       href="/admin/statistics/orders?period=year<? if (!empty($_GET['partner_id'])) echo "&partner_id=" . $_GET['partner_id'] ?>">Года</a>
    <br><br>

    <div class='no_table'>
        <?if($named){?>
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'summaryText' => '',
                //'cssFile'=>'/css/tableColorRow.css',
                //'rowCssClassExpression'=>'Order::getRowColor($data->status)',
                'htmlOptions' => array('class' => 'table table-bordered table_box'),
                'columns' => array(
                    array(
                        'header'=>'Дата',
                        'value' =>'Order::FormatDateDay($data->date)'
                    ),
                    'orders_count',
                    'orders_sum',
                    'new_users',
                    'procent',
                    'procent2',
                    'accept_phone',
                    'pc',
                    'mobile',
                    'app',
                    'average',
                ),
            )); ?>
        <?}else{?>
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'summaryText' => '',
                //'cssFile'=>'/css/tableColorRow.css',
                //'rowCssClassExpression'=>'Order::getRowColor($data->status)',
                'htmlOptions' => array('class' => 'table table-bordered table_box'),
                'columns' => array(
                    array(
                        'header'=>'Дата',
                        'value' =>'$data["p_date"]'
                    ),
                    array(
                        'header'=>'Колличество заказов',
                        'value' =>'$data["orders_count"]'
                    ),
                    array(
                        'header'=>'Сумма заказов',
                        'value' =>'$data["orders_sum"]'
                    ),
                    array(
                        'header'=>'Новые пользователи',
                        'value' =>'$data["new_users"]'
                    ),
                    array(
                        'header'=>'Проценты',
                        'value' =>'$data["procent"]'
                    ),
                    array(
                        'header'=>'Проценты2',
                        'value' =>'$data["procent2"]'
                    ),
                    array(
                        'header'=>'Принято по телефону',
                        'value' =>'$data["accept_phone"]'
                    ),
                    array(
                        'header'=>'Компьютер',
                        'value' =>'$data["pc"]'
                    ),
                    array(
                        'header'=>'Mobile',
                        'value' =>'$data["mobile"]'
                    ),
                    array(
                        'header'=>'Приложение',
                        'value' =>'$data["app"]'
                    ),
                    array(
                        'header'=>'Средний чек',
                        'value' =>'$data["average"]'
                    ),
                ),
            )); ?>
        <?}?>
    </div>
</div>

<script>
    /* $('.relation_partner').change(function () {
     var kalendar = "<?if(isset($_GET['period'])){echo 'period='.$_GET['period'].'&';}else{echo '';}?>";
     window.location = '/admin/statistics/orders?' + kalendar + "partner_id=" + this.value;
     });*/

    $('.city_selector').change(function () {
        window.location = $(this).val();
    });
    $('.relation_admin').change(function () {
        window.location = $(this).val();
    });
    $('.relation_partner').change(function () {
        window.location = $(this).val();
    });
</script>
<div class="mainBox shop">

    <div class="OfficeNav">
        <a href="/user/profile"> профиль </a>
        <a href="/user/orders"> заказы</a>
        <a href="/user/address"> адреса </a>
        <a href="/user/bonus" class='shopOpenNavActive'> баллы </a>

    </div>
</div>
<div class="page" id="page" style="text-align: center">
    <div class="Userbonus"><span>У Вас</span>    <?= User::getBonus($model->id); ?> <span>баллов</span></div>
    <div class="how_to_get_bonus">Как получить баллы</div>
    <div class="Aboutbonus"><a href="/bonus">Что мне дают баллы?</a></div>
    <br>

    <div class="blok">
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
            return false;
        });
    });
</script>

<style>
    table {
        background: #F4F0E7 none repeat scroll 0% 0%;
    }

    table td {
        border: 1px solid #DDD;
        padding: 4px;
    }

    .table_order th {
        padding: 10px 14px;
        border: 1px solid #DDD;
        background: #FFF none repeat scroll 0% 0%;
    }

    #page span {
        margin: 5px;
        font-size: 18px;
        margin-bottom: 15px;
        display: block;
        text-align: centers;
    }
</style>


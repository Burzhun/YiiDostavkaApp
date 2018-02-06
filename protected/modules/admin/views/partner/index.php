<div class="h1-box">
    <div class="well">
        <h1>Партнеры</h1>
        <!-- a href="/admin/partner/addpartner">Добавить партнера</a-->
    </div>
</div>
<div class="well well-bottom">
    <button type="button" class="btn btn-<?= Yii::app()->request->cookies['viewPartnerList']->value ?> aktivPartnerBtn">
        <? echo Yii::app()->request->cookies['viewPartnerList']->value == 'primary' ? 'Показаны все' : 'Показаны активные' ?>
    </button>
    <br><br>
    <?php /** @var Partner $model */
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search($this->domain),
        'id' => 'my-grid',
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'ajaxUpdate' => false,
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'id',
                'filter' => CHtml::textField("Partner[id]", $model->id, array("class" => "input-mini", 'style' => 'width:50px;')),
            ),
            array(
                'name'=>'procent_deductions',
                'filter' => CHtml::textField("Partner[procent_deductions]", $model->procent_deductions, array("class" => "input-mini", "style" => "width:50px;")),
            ),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, array("/admin/partner/id/".$data->id."/info/"))',
                'filter' => CHtml::textField("Partner[name]", $model->name, array("class" => "input-mini", "style" => "width:110px;")),
            ),
            array(
                'name' => 'balance',
                'filter' => CHtml::textField("Partner[balance]", $model->balance, array("class" => "input-mini", "style" => "width:50px;")),
            ),
            array(
                'name' => 'Оставшееся количество дней',
                'value' => '$data->getRestTime()',
                'filter' => CHtml::textField("Partner[balance]", $model->balance, array("class" => "input-mini", "style" => "width:50px;")),
            ),
            array(
                'type' => 'raw',
                'header' => 'Партнер активирован',
                'value' => 'CHtml::link("<span class=invisible>$data->status</span>", "#", array("id"=>$data->id, "class"=>$data->status ? "active_partner" : "blocked_partner"))',
            ),
            array(
                'name'=>'position',
                'filter' => CHtml::textField("Partner[position]", $model->position, array("class" => "input-mini", "style" => "width:80px;")),
            ),
            array(
                'header' => 'Рабочее время',
                'value' => 'substr($data->work_begin_time, 0, 5)." - ".substr($data->work_end_time, 0, 5)',
            ),
            array(
                'type' => 'raw',
                'header' => 'Vip',
                'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->vip ? "vip_partner" : "standart_partner"))',
            ),
            array(
                'type' => 'raw',
                'header' => 'Vip REST',
                'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->vip_rest ? "vip_rest_partner" : "standart_rest_partner"))',
            ),
            /* array(
                 'name' => 'min_sum',
                 'filter' => CHtml::textField("Partner[min_sum]", $model->min_sum, array("class" => "input-mini", 'style' => 'width:110px;')),
             ),
             array(
                 'name' => 'delivery_cost',
                 'filter' => CHtml::textField("Partner[delivery_cost]", $model->delivery_cost, array("class" => "input-mini", 'style' => 'width:110px;')),
             ),
             array(
                 'header' => 'Рабочие дни',
                 'value' => 'Partner::getWorkDays($data->id)',
             ),*/


            /*array(
                'header' => 'Время доставки',
                'name' => 'delivery_duration',
                'filter' => ZHtml::enumDropDownList($model, "delivery_duration", array('empty' => '', 'style' => 'width:120px;')),
            ),
            array(
                'header' => 'Операции',
                'class' => 'CButtonColumn',
                'template' => '{delete}',
                'deleteButtonLabel' => 'Удалить',
                'deleteButtonImageUrl' => false,
            ),*/
        ),
    )); ?>
</div>


<script>
    $("td").on('click', '.standart_partner, .vip_partner', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/admin/partner/id/" + p_id + "/changeVip?status=status",
            type: "post",
            //cache:false,
            //data:{"product":p_id, "status":"status"},
            success: function (data) {
                $(local_this).removeClass("vip_partner");
                $(local_this).removeClass("wait_vip_partner");
                $(local_this).removeClass("standart_partner");
                $(local_this).addClass(data);
                //$.fn.yiiGridView.update("my-grid");
            },
            beforeSend: function (data) {
                $(local_this).removeClass("vip_partner");
                $(local_this).removeClass("standart_partner");
                //$(local_this).removeClass("wait_vip_partner");
                $(local_this).addClass("wait_vip_partner");
            }
        });
        //alert(111);
        return false;
    });

    $('.aktivPartnerBtn').on('click', function (event) {
        //var local_this = this;
        //var p_id = $(this).attr('id');

        $.ajax({
            url: "/admin/partner/changeViewPartner",
            type: "post",
            success: function (data) {
                $('.aktivPartnerBtn').removeClass("btn-primary");
                $('.aktivPartnerBtn').removeClass("btn-success");
                $('.aktivPartnerBtn').addClass('btn-' + data);
                if (data == 'primary') {
                    $('.aktivPartnerBtn').text('Показаны все');
                } else {
                    $('.aktivPartnerBtn').text('Показаны активные');
                }
                console.log(data);
                $.fn.yiiGridView.update("my-grid");
            }
        });
        return false;
    });
</script>

<script>
    $("td").on('click', '.standart_rest_partner, .vip_rest_partner', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/admin/partner/id/" + p_id + "/changeVipRest?status=status",
            type: "post",
            //cache:false,
            //data:{"product":p_id, "status":"status"},
            success: function (data) {
                $(local_this).removeClass("vip_rest_partner");
                $(local_this).removeClass("wait_vip_partner");
                $(local_this).removeClass("standart_rest_partner");
                $(local_this).addClass(data);
                //$.fn.yiiGridView.update("my-grid");
            },
            beforeSend: function (data) {
                $(local_this).removeClass("vip_rest_partner");
                $(local_this).removeClass("standart_rest_partner");
                //$(local_this).removeClass("wait_vip_partner");
                $(local_this).addClass("wait_vip_partner");
            }
        });
        //alert(111);
        return false;
    });
    $(window).load(function () {
        $(".items").tablesorter({
            textExtraction: function(node) {
                return parseInt($(node).text().replace(/\s/g, ''));
            }
        });
        $("#my-grid_c4").click()
    });
    $("td").on('click', '.blocked_partner, .active_partner', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/admin/partner/id/" + p_id + "/changePartnerStatus?",
            type: "post",
            //cache:false,
            //data:{"product":p_id, "status":"status"},
            success: function (data) {
                $(local_this).removeClass("vip_partner");
                $(local_this).removeClass("active_partner");
                $(local_this).removeClass("blocked_partner");
                $(local_this).addClass(data);
                //$.fn.yiiGridView.update("my-grid");
            },
            beforeSend: function (data) {
                $(local_this).removeClass("active_partner");
                $(local_this).removeClass("blocked_partner");
                //$(local_this).removeClass("wait_vip_partner");
                $(local_this).addClass("wait_vip_partner");
            }
        });
        //alert(111);
        return false;
    });
</script>
<style>
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
    .blocked_partner{
        display: block;
        width: 16px;
        height: 16px;
        background: url(/iconset/delete.gif);
    }
    .active_partner{
        display: block;
        width: 16px;
        height: 16px;
        background: url(/iconset/check.gif);
    }
    #my-grid_c1{
        max-width: 63px;
    }
</style>
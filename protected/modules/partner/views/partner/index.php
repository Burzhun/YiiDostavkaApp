<div class="h1-box">
    <div class="well">
        <h1>Партнеры</h1>
    </div>
</div>
<div class="well well-bottom">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
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
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name, array("/admin/partner/id/".$data->id."/info/"))',
                'filter' => CHtml::textField("Partner[name]", $model->name, array("class" => "input-mini", "style" => "width:110px;")),
            ),
            array(
                'type' => 'raw',
                'header' => 'Vip',
                'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->vip ? "vip_partner" : "standart_partner"))',
            ),
            array(
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
            ),
            array(
                'header' => 'Рабочее время',
                'value' => 'substr($data->work_begin_time, 0, 5)." - ".substr($data->work_end_time, 0, 5)',
            ),
            array(
                'name' => 'balance',
                'value' => 'OrderPartnerDebt::getDebt($data->id)',
                'filter' => CHtml::textField("Partner[balance]", $model->name, array("class" => "input-mini", "style" => "width:50px;")),
            ),
            array(
                'header' => 'Время доставки',
                'name' => 'delivery_duration',
                'filter' => ZHtml::enumDropDownList($model, "delivery_duration", array('empty' => '', 'style' => 'width:120px;')),
            ),
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
            success: function (data) {
                $(local_this).removeClass("vip_partner");
                $(local_this).removeClass("wait_vip_partner");
                $(local_this).removeClass("standart_partner");
                $(local_this).addClass(data);
            },
            beforeSend: function (data) {
                $(local_this).removeClass("vip_partner");
                $(local_this).removeClass("standart_partner");
                $(local_this).addClass("wait_vip_partner");
            }
        });
        return false;
    });
</script>
<div class="h1-box">
    <div class="well">
        <h1>Пользователи</h1>
    </div>
</div>
<div class="well well-bottom">
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
                'name' => 'reg_date',
                'filter' => CHtml::textField("User[reg_date]", $model->reg_date, array("class" => "input-mini", 'style' => 'width:100px;')),
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
                'header' => 'Бонус2',
                'value' => '$data->getTotalBonus()'
            ),
            array(
                'header' => 'Оплачено',
                'type' => 'raw',
                'value' => '$data->getGaveTotalMoney()',
            ),
        ),
    ));
    ?>
</div>
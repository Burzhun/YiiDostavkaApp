<div class="well">

    <div>
        <button class="btn-success" onclick="$('.form').slideToggle();">Добавить прину отмены</button>
        <div class="form" style="display: none"><br>
            <input type="text" style="min-width: 400px;">
            <button class="btn-primary" style="vertical-align: top; height: 28px; margin-left: 6px; padding: 0px 11px;">Ok</button>
        </div>
    </div><br>
    <? $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProvider,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered', 'style' => 'float:left;'),
        'summaryText' => '',
        'columns' => array(
            'id',
            array(
                'class' => 'editable.EditableColumn',
                'header' => 'Изменить текст',
                'name' => 'name',
                'value' => $data['name'],
                //'headerHtmlOptions' => array('style' => 'width: 100px'),
                'editable' => array(
                    'title' => 'Изменить',
                    'type' => 'text',
                    'model' => $data,
                    'attribute' => 'price',
                    //'source'  => array(1 => 'Да', 0 => 'Нет'),
                    'url' => $this->createUrl('default/UpdateAjaxReason'),
                ),
                'filter' => false,
            )
        ),
    ));
    ?>
</div>
<style>
    .editable-input input{
        min-width: 500px;
    }
    .well{
        padding: 20px;
    }
</style>
<script>
    $(document).ready(function(){
        $(".form button").click(function(){
            var text=$(".form input").val();
            window.location.href="/admin/default/cancel_reasons?text="+text;
        });
    });
</script>
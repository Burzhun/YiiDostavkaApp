<div class="h1-box">
    <div class="well">
        <h1>Баннеры</h1>
    </div>
</div>
<div class="well well-bottom">
    <a class="btn btn-primary" href="/admin/banners/create">Добавить баннер</a>
    <br><br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'id' => 'my-grid',
        'filter' => $model,
        'emptyText' => 'Ничего не найдено',
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            'id',
            'text',
            array(
                'type' => 'raw',
                'header' => 'Изображение',
                'value' => 'CHtml::image("/themes/mobile/img/banners/".$data->image,"",array("width"=>"250px"))',
            ),
            array(
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
            ),
        ),
    ));
    ?>
</div>
<script>
    $(document).ready(function () {
        $('a.delete').click(function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            window.location.href = url;
            return false;
        });
    });
</script>
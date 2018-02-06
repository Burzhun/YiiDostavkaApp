<div class="h1-box">
    <div class="well">
        <h1>Комментарии</h1>
    </div>
</div>

<div class="well well-bottom">
    <div>
        <a class="btn btn-primary" href="/admin/post/id/0/create">Добавить пост</a>
    </div>
    <br>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'filter' => $model,
        'htmlOptions' => array('class' => 'table table-bordered'),
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'name',
                'filter' => CHtml::textField("CommentInPost[name]", $model->name, array("class" => "input-mini", "style" => "width:110px;")),
            ),
            'date',
            'text',
            array(
                'type' => 'raw',
                'header' => 'Статус',
                'value' => 'CHtml::link("", "#", array("id"=>$data->id, "class"=>$data->publ ? "publ_comment" : "not_publ_comment"))',
            ),
        ),
    ));
    ?>
</div>


<script>
    $("td").on('click', '.not_publ_comment, .publ_comment', function (event) {
        var local_this = this;
        var p_id = $(this).attr('id');
        $.ajax({
            url: "/admin/comment/id/" + p_id + "/changeStatus?status=status",
            type: "post",
            success: function (data) {
                $(local_this).removeClass("publ_comment");
                $(local_this).removeClass("wait_publ");
                $(local_this).removeClass("not_publ_comment");
                $(local_this).addClass(data);
            },
            beforeSend: function (data) {
                $(local_this).removeClass("publ_comment");
                $(local_this).removeClass("not_publ_comment");
                $(local_this).addClass("wait_publ");
            }
        });
        return false;
    });
</script>
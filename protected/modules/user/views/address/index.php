<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="Userbonus"><span>У Вас</span>    <?= User::getBonus($model->id); ?> <span>баллов</span></div>
    <div class="Aboutbonus"><a href="/bonus">Что мне дают баллы?</a></div>
    <div class="blok">
        <?php $this->renderPartial('../default/header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>
        <br><br>
        <a class="btn btn-primary" href="/user/address/create">Добавить адрес</a>
        <br><br>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $address_model->search(array('user_id' => Yii::app()->user->id)),
            //'filter'=>$address_model,
            'emptyText' => 'Ничего не найдено',
            'htmlOptions' => array('class' => 'table table_add table-bordered'),
            'summaryText' => '',
            'columns' => array(
                array(
                    'header' => 'Город',
                    'value' => '$data->city->name',
                ),
                'street',
                'house',
                'podezd',
                'storey',
                'number',
                array(
                    'header' => 'Операции',
                    'class' => 'CButtonColumn',
                    'template' => '{update}{delete}',
                    'updateButtonImageUrl' => '/iconset/' . 'edit.gif',
                    'updateButtonUrl' => '"/user/address/update/".$data->id',
                    'deleteButtonImageUrl' => '/iconset/' . 'delete.gif',
                    'deleteButtonUrl' => '"/user/address/delete/".$data->id',
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
        });
    });
</script>